'use client'

import { useState } from 'react'
import { useRouter } from 'next/navigation'
import Link from 'next/link'

export default function RegisterPage() {
  const router = useRouter()
  const [loading, setLoading] = useState(false)
  const [error, setError] = useState('')

  async function handleSubmit(e: React.FormEvent<HTMLFormElement>) {
    e.preventDefault()
    setLoading(true)
    setError('')
    const formData = new FormData(e.currentTarget)

    const res = await fetch('/api/register', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        name: formData.get('name'),
        email: formData.get('email'),
        password: formData.get('password'),
        kelas: formData.get('kelas'),
      }),
    })

    const data = await res.json()
    if (!res.ok) {
      setError(data.error ?? 'Gagal mendaftar')
      setLoading(false)
    } else {
      router.push('/login?registered=1')
    }
  }

  return (
    <div className="glass-card w-full max-w-sm p-8">
      <h1 className="mb-1 text-2xl font-bold text-white">Daftar</h1>
      <p className="mb-6 text-sm text-neutral-400">Buat akun siswa baru</p>

      <form onSubmit={handleSubmit} className="space-y-4">
        <div>
          <label className="mb-1.5 block text-sm text-neutral-300">Nama Lengkap</label>
          <input
            name="name"
            required
            placeholder="Budi Santoso"
            className="w-full rounded-lg border border-neutral-700 bg-white/[0.03] px-3 py-2.5 text-sm text-white placeholder-neutral-600 outline-none transition focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30"
          />
        </div>
        <div>
          <label className="mb-1.5 block text-sm text-neutral-300">Email</label>
          <input
            name="email"
            type="email"
            required
            placeholder="budi@tkj.com"
            className="w-full rounded-lg border border-neutral-700 bg-white/[0.03] px-3 py-2.5 text-sm text-white placeholder-neutral-600 outline-none transition focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30"
          />
        </div>
        <div>
          <label className="mb-1.5 block text-sm text-neutral-300">Kelas</label>
          <input
            name="kelas"
            placeholder="XII TKJ 1"
            className="w-full rounded-lg border border-neutral-700 bg-white/[0.03] px-3 py-2.5 text-sm text-white placeholder-neutral-600 outline-none transition focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30"
          />
        </div>
        <div>
          <label className="mb-1.5 block text-sm text-neutral-300">Password</label>
          <input
            name="password"
            type="password"
            required
            minLength={6}
            placeholder="Minimal 6 karakter"
            className="w-full rounded-lg border border-neutral-700 bg-white/[0.03] px-3 py-2.5 text-sm text-white placeholder-neutral-600 outline-none transition focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30"
          />
        </div>

        {error && (
          <p className="rounded-lg border border-red-500/20 bg-red-500/10 px-3 py-2 text-sm text-red-400">
            {error}
          </p>
        )}

        <button
          type="submit"
          disabled={loading}
          className="w-full rounded-lg bg-gradient-to-r from-blue-600 to-purple-600 py-2.5 text-sm font-semibold text-white transition hover:from-blue-500 hover:to-purple-500 disabled:opacity-60"
        >
          {loading ? 'Mendaftar...' : 'Daftar Sekarang'}
        </button>
      </form>

      <p className="mt-4 text-center text-sm text-neutral-500">
        Sudah punya akun?{' '}
        <Link href="/login" className="text-blue-400 hover:text-blue-300">
          Masuk
        </Link>
      </p>
    </div>
  )
}
