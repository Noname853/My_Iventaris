'use client'

import { useState } from 'react'
import { signIn } from 'next-auth/react'
import { useRouter } from 'next/navigation'
import Link from 'next/link'

export default function LoginPage() {
  const router = useRouter()
  const [loading, setLoading] = useState(false)
  const [error, setError] = useState('')

  async function handleSubmit(e: React.FormEvent<HTMLFormElement>) {
    e.preventDefault()
    setLoading(true)
    setError('')
    const formData = new FormData(e.currentTarget)

    const result = await signIn('credentials', {
      email: formData.get('email'),
      password: formData.get('password'),
      redirect: false,
    })

    if (result?.error) {
      setError('Email atau password salah')
      setLoading(false)
    } else {
      router.push('/dashboard')
    }
  }

  return (
    <div className="glass-card w-full max-w-sm p-8">
      <h1 className="mb-1 text-2xl font-bold text-white">Masuk</h1>
      <p className="mb-6 text-sm text-neutral-400">Silakan login untuk melanjutkan</p>

      <form onSubmit={handleSubmit} className="space-y-4">
        <div>
          <label className="mb-1.5 block text-sm text-neutral-300">Email</label>
          <input
            name="email"
            type="email"
            required
            placeholder="admin@tkj.com"
            className="w-full rounded-lg border border-neutral-700 bg-white/[0.03] px-3 py-2.5 text-sm text-white placeholder-neutral-600 outline-none transition focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30"
          />
        </div>
        <div>
          <label className="mb-1.5 block text-sm text-neutral-300">Password</label>
          <input
            name="password"
            type="password"
            required
            placeholder="••••••••"
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
          {loading ? 'Memuat...' : 'Masuk'}
        </button>
      </form>

      <p className="mt-4 text-center text-sm text-neutral-500">
        Belum punya akun?{' '}
        <Link href="/register" className="text-blue-400 hover:text-blue-300">
          Daftar
        </Link>
      </p>
    </div>
  )
}
