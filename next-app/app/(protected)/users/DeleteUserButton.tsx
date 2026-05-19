'use client'

import { useRouter } from 'next/navigation'
import { useState } from 'react'

export function DeleteUserButton({ id }: { id: number }) {
  const router = useRouter()
  const [loading, setLoading] = useState(false)

  async function handleDelete() {
    if (!confirm('Hapus user ini?')) return
    setLoading(true)
    const res = await fetch(`/api/users/${id}`, { method: 'DELETE' })
    if (res.ok) {
      router.refresh()
    } else {
      const data = await res.json()
      alert(data.error ?? 'Gagal menghapus')
      setLoading(false)
    }
  }

  return (
    <button
      onClick={handleDelete}
      disabled={loading}
      className="text-xs text-red-400 hover:text-red-300 disabled:opacity-50"
    >
      {loading ? '...' : 'Hapus'}
    </button>
  )
}
