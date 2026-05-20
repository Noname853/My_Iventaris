import { auth } from '@/lib/auth'
import { prisma } from '@/lib/prisma'
import { redirect } from 'next/navigation'
import { GlassCard } from '@/components/shared/GlassCard'
import { ProfilForm } from './ProfilForm'

export default async function ProfilPage() {
  const session = await auth()
  if (!session) redirect('/login')

  const user = await prisma.user.findUnique({
    where: { id: parseInt(session.user.id) },
    select: { name: true, email: true, kelas: true, role: true, kelompok: true, anggotaKelompok: true },
  })
  if (!user) redirect('/login')

  const anggota: string[] = user.anggotaKelompok ? JSON.parse(user.anggotaKelompok) : []

  return (
    <div className="mx-auto max-w-lg space-y-6">
      <div>
        <h1 className="text-2xl font-bold text-white">Profil</h1>
        <p className="text-sm text-neutral-400">Informasi akun dan kelompok kamu</p>
      </div>

      <GlassCard className="p-5">
        <h2 className="mb-4 text-sm font-semibold text-neutral-300">Informasi Akun</h2>
        <div className="space-y-3">
          <div className="flex justify-between text-sm">
            <span className="text-neutral-500">Nama</span>
            <span className="text-white">{user.name}</span>
          </div>
          <div className="flex justify-between text-sm">
            <span className="text-neutral-500">Email</span>
            <span className="text-white">{user.email}</span>
          </div>
          {user.kelas && (
            <div className="flex justify-between text-sm">
              <span className="text-neutral-500">Kelas</span>
              <span className="text-white">{user.kelas}</span>
            </div>
          )}
          <div className="flex justify-between text-sm">
            <span className="text-neutral-500">Role</span>
            <span className="text-white capitalize">{user.role}</span>
          </div>
        </div>
      </GlassCard>

      <ProfilForm kelompok={user.kelompok} anggota={anggota} />
    </div>
  )
}
