import { auth } from '@/lib/auth'
import { prisma } from '@/lib/prisma'
import { NextRequest, NextResponse } from 'next/server'
import bcrypt from 'bcryptjs'

export async function PUT(req: NextRequest, { params }: { params: Promise<{ id: string }> }) {
  const session = await auth()
  if (!session || session.user.role !== 'admin')
    return NextResponse.json({ error: 'Forbidden' }, { status: 403 })

  const { id } = await params
  try {
    const body = await req.json()
    const { name, email, role, kelas, kelompok, password } = body

    const data: Record<string, unknown> = { name, email, role, kelas, kelompok }
    if (password) data.password = await bcrypt.hash(password, 10)

    const user = await prisma.user.update({
      where: { id: parseInt(id) },
      data,
      select: { id: true, name: true, email: true, role: true, kelas: true },
    })
    return NextResponse.json(user)
  } catch {
    return NextResponse.json({ error: 'Server error' }, { status: 500 })
  }
}

export async function DELETE(_req: NextRequest, { params }: { params: Promise<{ id: string }> }) {
  const session = await auth()
  if (!session || session.user.role !== 'admin')
    return NextResponse.json({ error: 'Forbidden' }, { status: 403 })

  const { id } = await params
  if (parseInt(id) === parseInt(session.user.id))
    return NextResponse.json({ error: 'Tidak bisa menghapus akun sendiri' }, { status: 400 })

  try {
    const aktif = await prisma.peminjaman.count({
      where: {
        userId: parseInt(id),
        status: { in: ['menunggu_verifikasi', 'dipinjam'] },
      },
    })

    if (aktif > 0)
      return NextResponse.json(
        { error: `User masih memiliki ${aktif} peminjaman aktif. Selesaikan atau batalkan dulu sebelum menghapus.` },
        { status: 400 }
      )

    const userId = parseInt(id)

    await prisma.$transaction(async (tx) => {
      // Putus referensi verifiedBy / returnedBy / cancelledBy ke user ini
      await tx.peminjaman.updateMany({ where: { verifiedBy: userId }, data: { verifiedBy: null } })
      await tx.peminjaman.updateMany({ where: { returnedBy: userId }, data: { returnedBy: null } })
      await tx.peminjaman.updateMany({ where: { cancelledBy: userId }, data: { cancelledBy: null } })

      // Hapus detail peminjaman milik user ini
      await tx.peminjamanDetail.deleteMany({ where: { peminjaman: { userId } } })

      // Hapus semua peminjaman milik user ini
      await tx.peminjaman.deleteMany({ where: { userId } })

      // Baru hapus user
      await tx.user.delete({ where: { id: userId } })
    })

    return NextResponse.json({ success: true })
  } catch {
    return NextResponse.json({ error: 'Gagal menghapus user' }, { status: 500 })
  }
}
