'use client'

import { useState, useEffect } from 'react'
import { useRouter } from 'next/navigation'
import { GlassCard } from '@/components/shared/GlassCard'
import { StockBadge } from '@/components/shared/StockBadge'
import { Plus, Trash2, ArrowLeft, Search } from 'lucide-react'
import Link from 'next/link'

interface AlatOption {
  id: number
  kode: string
  nama: string
  kategori: string
  stok: number
  stokTersedia: number
}

interface Item {
  alatId: number
  alat: AlatOption | null
  jumlah: number
  keterangan: string
}

export default function BuatPeminjamanPage() {
  const router = useRouter()
  const [items, setItems] = useState<Item[]>([{ alatId: 0, alat: null, jumlah: 1, keterangan: '' }])
  const [keperluan, setKeperluan] = useState('')
  const [tanggalBatas, setTanggalBatas] = useState('')
  const [catatan, setCatatan] = useState('')
  const [loading, setLoading] = useState(false)
  const [error, setError] = useState('')
  const [searchResults, setSearchResults] = useState<AlatOption[]>([])
  const [searchIdx, setSearchIdx] = useState<number | null>(null)
  const [searchQuery, setSearchQuery] = useState('')

  useEffect(() => {
    if (!searchQuery) { setSearchResults([]); return }
    const t = setTimeout(async () => {
      const res = await fetch(`/api/alat?search=${encodeURIComponent(searchQuery)}&limit=10`)
      const data = await res.json()
      setSearchResults(data.data ?? [])
    }, 300)
    return () => clearTimeout(t)
  }, [searchQuery])

  function selectAlat(idx: number, alat: AlatOption) {
    setItems((prev) => prev.map((item, i) => (i === idx ? { ...item, alatId: alat.id, alat } : item)))
    setSearchIdx(null)
    setSearchQuery('')
  }

  function addItem() {
    setItems((prev) => [...prev, { alatId: 0, alat: null, jumlah: 1, keterangan: '' }])
  }

  function removeItem(idx: number) {
    setItems((prev) => prev.filter((_, i) => i !== idx))
  }

  async function handleSubmit(e: React.FormEvent) {
    e.preventDefault()
    setError('')
    if (!keperluan) { setError('Keperluan wajib diisi'); return }
    if (items.some((i) => !i.alatId)) { setError('Semua alat harus dipilih'); return }

    setLoading(true)
    const res = await fetch('/api/peminjaman', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        keperluan,
        tanggalBatasKembali: tanggalBatas || null,
        catatan: catatan || null,
        items: items.map((i) => ({ alatId: i.alatId, jumlah: i.jumlah, keterangan: i.keterangan || null })),
      }),
    })
    const data = await res.json()
    if (!res.ok) {
      setError(data.error ?? 'Gagal membuat peminjaman')
      setLoading(false)
    } else {
      router.push(`/peminjaman/${data.id}`)
    }
  }

  const inputClass =
    'w-full rounded-lg border border-neutral-700 bg-white/[0.03] px-3 py-2.5 text-sm text-white placeholder-neutral-600 outline-none transition focus:border-blue-500 focus:ring-1 focus:ring-blue-500/30'

  return (
    <div className="space-y-6">
      <div className="flex items-center gap-3">
        <Link href="/peminjaman" className="rounded-lg border border-neutral-800 p-2 text-neutral-400 hover:text-white">
          <ArrowLeft className="h-4 w-4" />
        </Link>
        <div>
          <h1 className="text-2xl font-bold text-white">Buat Peminjaman</h1>
          <p className="text-sm text-neutral-400">Ajukan permintaan peminjaman alat</p>
        </div>
      </div>

      <form onSubmit={handleSubmit}>
        <div className="grid gap-6 lg:grid-cols-3">
          <div className="lg:col-span-2 space-y-4">
            {/* Items */}
            <GlassCard className="p-5">
              <h2 className="mb-4 text-sm font-semibold text-neutral-300">Alat yang Dipinjam</h2>
              <div className="space-y-3">
                {items.map((item, idx) => (
                  <div key={idx} className="rounded-lg border border-neutral-800 p-3">
                    {/* Alat search */}
                    <div className="relative mb-2">
                      {item.alat ? (
                        <div className="flex items-center justify-between rounded-lg bg-white/[0.03] px-3 py-2">
                          <div>
                            <p className="text-sm font-medium text-white">{item.alat.nama}</p>
                            <p className="text-xs text-neutral-500">{item.alat.kode}</p>
                          </div>
                          <div className="flex items-center gap-2">
                            <StockBadge stok={item.alat.stok} stokTersedia={item.alat.stokTersedia} />
                            <button
                              type="button"
                              onClick={() => setItems((prev) => prev.map((it, i) => i === idx ? { ...it, alatId: 0, alat: null } : it))}
                              className="text-neutral-500 hover:text-white"
                            >
                              ×
                            </button>
                          </div>
                        </div>
                      ) : (
                        <div>
                          <div className="relative">
                            <Search className="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-neutral-500" />
                            <input
                              placeholder="Cari alat..."
                              value={searchIdx === idx ? searchQuery : ''}
                              onFocus={() => setSearchIdx(idx)}
                              onChange={(e) => setSearchQuery(e.target.value)}
                              className={`${inputClass} pl-9`}
                            />
                          </div>
                          {searchIdx === idx && searchResults.length > 0 && (
                            <div className="absolute z-10 mt-1 w-full max-w-md rounded-lg border border-neutral-700 bg-neutral-900 shadow-xl">
                              {searchResults.map((a) => (
                                <button
                                  key={a.id}
                                  type="button"
                                  onClick={() => selectAlat(idx, a)}
                                  className="flex w-full items-center justify-between px-3 py-2 text-left text-sm transition hover:bg-white/[0.05]"
                                >
                                  <div>
                                    <p className="text-white">{a.nama}</p>
                                    <p className="text-xs text-neutral-500">{a.kode}</p>
                                  </div>
                                  <StockBadge stok={a.stok} stokTersedia={a.stokTersedia} />
                                </button>
                              ))}
                            </div>
                          )}
                        </div>
                      )}
                    </div>
                    <div className="flex items-center gap-2">
                      <div className="w-24">
                        <label className="mb-1 block text-xs text-neutral-500">Jumlah</label>
                        <input
                          type="number"
                          min="1"
                          max={item.alat?.stokTersedia ?? 99}
                          value={item.jumlah}
                          onChange={(e) => setItems((prev) => prev.map((it, i) => i === idx ? { ...it, jumlah: parseInt(e.target.value) || 1 } : it))}
                          className={inputClass}
                        />
                      </div>
                      <div className="flex-1">
                        <label className="mb-1 block text-xs text-neutral-500">Keterangan</label>
                        <input
                          placeholder="Opsional"
                          value={item.keterangan}
                          onChange={(e) => setItems((prev) => prev.map((it, i) => i === idx ? { ...it, keterangan: e.target.value } : it))}
                          className={inputClass}
                        />
                      </div>
                      {items.length > 1 && (
                        <button
                          type="button"
                          onClick={() => removeItem(idx)}
                          className="mt-5 rounded-lg p-2 text-red-400 hover:bg-red-500/10"
                        >
                          <Trash2 className="h-4 w-4" />
                        </button>
                      )}
                    </div>
                  </div>
                ))}
              </div>
              <button
                type="button"
                onClick={addItem}
                className="mt-3 flex items-center gap-2 text-sm text-blue-400 hover:text-blue-300"
              >
                <Plus className="h-4 w-4" />
                Tambah Alat
              </button>
            </GlassCard>
          </div>

          <div className="space-y-4">
            <GlassCard className="p-5">
              <h2 className="mb-4 text-sm font-semibold text-neutral-300">Informasi Peminjaman</h2>
              <div className="space-y-3">
                <div>
                  <label className="mb-1.5 block text-sm text-neutral-300">Keperluan *</label>
                  <input
                    value={keperluan}
                    onChange={(e) => setKeperluan(e.target.value)}
                    placeholder="Praktikum jaringan..."
                    className={inputClass}
                  />
                </div>
                <div>
                  <label className="mb-1.5 block text-sm text-neutral-300">Batas Kembali</label>
                  <input
                    type="date"
                    value={tanggalBatas}
                    onChange={(e) => setTanggalBatas(e.target.value)}
                    className={inputClass}
                  />
                </div>
                <div>
                  <label className="mb-1.5 block text-sm text-neutral-300">Catatan</label>
                  <textarea
                    rows={3}
                    value={catatan}
                    onChange={(e) => setCatatan(e.target.value)}
                    placeholder="Catatan tambahan..."
                    className={inputClass}
                  />
                </div>
              </div>
            </GlassCard>

            {error && (
              <p className="rounded-lg border border-red-500/20 bg-red-500/10 px-3 py-2 text-sm text-red-400">{error}</p>
            )}

            <button
              type="submit"
              disabled={loading}
              className="w-full rounded-lg bg-gradient-to-r from-blue-600 to-purple-600 py-2.5 text-sm font-semibold text-white transition hover:from-blue-500 hover:to-purple-500 disabled:opacity-60"
            >
              {loading ? 'Mengajukan...' : 'Ajukan Peminjaman'}
            </button>
          </div>
        </div>
      </form>
    </div>
  )
}
