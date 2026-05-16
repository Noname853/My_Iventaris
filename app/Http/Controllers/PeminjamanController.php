<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\PeminjamanDetail;
use App\Models\Alat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Peminjaman::with(['user', 'peminjamanDetails.alat']);

        // Filter by user role
        if (Auth::user()->role === 'siswa') {
            $query->where('user_id', Auth::id());
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhereHas('peminjamanDetails.alat', function($alatQuery) use ($search) {
                    $alatQuery->where('nama', 'like', "%{$search}%")
                             ->orWhere('kode', 'like', "%{$search}%");
                })
                ->orWhere('keperluan', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_pinjam', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal_pinjam', '<=', $request->tanggal_selesai);
        }

        // Order by latest
        $query->orderBy('created_at', 'desc');

        $peminjamans = $query->paginate(10);

        // Statistics for admin
        $stats = null;
        if (Auth::user()->role === 'admin') {
            $stats = [
                'total' => Peminjaman::count(),
                'menunggu_verifikasi' => Peminjaman::where('status', 'menunggu_verifikasi')->count(),
                'dipinjam' => Peminjaman::where('status', 'dipinjam')->count(),
                'dikembalikan' => Peminjaman::where('status', 'dikembalikan')->count(),
                'dibatalkan' => Peminjaman::where('status', 'dibatalkan')->count(),
            ];
        }

        return view('peminjaman.index', compact('peminjamans', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Only students can create borrowing requests
        if (Auth::user()->role !== 'siswa') {
            return redirect()->route('peminjaman.index')
                ->with('error', 'Hanya siswa yang dapat mengajukan peminjaman.');
        }

        $alats = Alat::all()->filter(function($alat) {
            return $alat->stok_tersedia > 0;
        })->sortBy('nama')->values();

        $selectedAlat = null;
        if ($request->filled('alat_id')) {
            $selectedAlat = Alat::find($request->alat_id);
        }

        return view('peminjaman.create', compact('alats', 'selectedAlat'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Only students can create borrowing requests
        if (Auth::user()->role !== 'siswa') {
            return redirect()->route('peminjaman.index')
                ->with('error', 'Hanya siswa yang dapat mengajukan peminjaman.');
        }

        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.alat_id' => 'required|exists:alats,id',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.keterangan' => 'nullable|string|max:255',
            'tanggal_pinjam' => 'required|date_format:Y-m-d\TH:i|after_or_equal:now',
            'tanggal_selesai' => 'required|date_format:Y-m-d\TH:i|after:tanggal_pinjam',
            'keperluan' => 'required|string|max:500',
            'catatan' => 'nullable|string|max:1000',
        ], [
            'tanggal_pinjam.after_or_equal' => 'Sesuaikan waktu dan Tanggal Anda(user)',
        ]);

        DB::beginTransaction();
        try {
            // Calculate total items
            $totalItems = array_sum(array_column($request->items, 'jumlah'));

            // Check stock availability for all items
            foreach ($request->items as $item) {
                $alat = Alat::findOrFail($item['alat_id']);
                
                // Check if user already has pending borrowing for this tool
                $existingBorrowing = PeminjamanDetail::whereHas('peminjaman', function($query) {
                    $query->where('user_id', Auth::id())
                          ->whereIn('status', ['menunggu_verifikasi', 'dipinjam']);
                })->where('alat_id', $item['alat_id'])->exists();

                if ($existingBorrowing) {
                    throw new \Exception("Anda sudah memiliki peminjaman aktif untuk alat: {$alat->nama}");
                }

                // Check available stock
                $borrowedAmount = $alat->peminjamanDetails()
                    ->whereHas('peminjaman', function($query) {
                        $query->whereIn('status', ['menunggu_verifikasi', 'dipinjam']);
                    })
                    ->sum('jumlah');
                
                $availableStock = $alat->stok - $borrowedAmount;

                if ($item['jumlah'] > $availableStock) {
                    throw new \Exception("Stok tidak mencukupi untuk {$alat->nama}. Stok tersedia: {$availableStock}");
                }
            }

            // Create peminjaman
            $tanggal_batas_kembali = $request->tanggal_selesai;
            $lama_pinjam = (strtotime($request->tanggal_selesai) - strtotime($request->tanggal_pinjam)) / 3600; // jam
            $peminjaman = Peminjaman::create([
                'user_id' => Auth::id(),
                'total_items' => $totalItems,
                'tanggal_pinjam' => $request->tanggal_pinjam,
                'keperluan' => $request->keperluan,
                'catatan' => $request->catatan,
                'status' => 'menunggu_verifikasi',
                'tanggal_batas_kembali' => $tanggal_batas_kembali,
                'lama_pinjam' => $lama_pinjam,
            ]);

            // Create peminjaman details
            foreach ($request->items as $item) {
                PeminjamanDetail::create([
                    'peminjaman_id' => $peminjaman->id,
                    'alat_id' => $item['alat_id'],
                    'jumlah' => $item['jumlah'],
                    'keterangan' => $item['keterangan'] ?? null,
                ]);
            }

            DB::commit();

            return redirect()->route('peminjaman.show', $peminjaman)
                ->with('success', 'Peminjaman berhasil diajukan. Menunggu verifikasi admin.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Peminjaman $peminjaman)
    {
        // Check authorization
        if (Auth::user()->role === 'siswa' && $peminjaman->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke peminjaman ini.');
        }

        $peminjaman->load(['user', 'peminjamanDetails.alat']);

        return view('peminjaman.show', compact('peminjaman'));
    }

    /**
     * Verify borrowing request (Admin only)
     */
    public function verify(Peminjaman $peminjaman)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Hanya admin yang dapat memverifikasi peminjaman.');
        }

        if ($peminjaman->status !== 'menunggu_verifikasi') {
            return redirect()->back()
                ->with('error', 'Peminjaman sudah diverifikasi atau dibatalkan.');
        }

        // Check available stock again for all items
        foreach ($peminjaman->peminjamanDetails as $detail) {
            $alat = $detail->alat;
            $borrowedAmount = $alat->peminjamanDetails()
                ->whereHas('peminjaman', function($query) use ($peminjaman) {
                    $query->whereIn('status', ['menunggu_verifikasi', 'dipinjam'])
                          ->where('id', '!=', $peminjaman->id);
                })
                ->sum('jumlah');
            
            $availableStock = $alat->stok - $borrowedAmount;

            if ($detail->jumlah > $availableStock) {
                return redirect()->back()
                    ->with('error', "Stok tidak mencukupi untuk verifikasi {$alat->nama}. Stok tersedia: {$availableStock}");
            }
        }

        $peminjaman->update([
            'status' => 'dipinjam',
            'tanggal_verifikasi' => now(),
            'verified_by' => Auth::id(),
        ]);

        return redirect()->back()
            ->with('success', 'Peminjaman berhasil diverifikasi.');
    }

    /**
     * Return borrowed item (Admin only)
     */
    public function return(Request $request, Peminjaman $peminjaman)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Hanya admin yang dapat memproses pengembalian.');
        }

        if ($peminjaman->status !== 'dipinjam') {
            return redirect()->back()
                ->with('error', 'Peminjaman belum diverifikasi atau sudah dikembalikan.');
        }

        $request->validate([
            'catatan_pengembalian' => 'nullable|string|max:1000',
        ]);

        $peminjaman->update([
            'status' => 'dikembalikan',
            'tanggal_kembali' => now(),
            'returned_by' => Auth::id(),
            'catatan_pengembalian' => $request->catatan_pengembalian,
        ]);

        return redirect()->back()
            ->with('success', 'Pengembalian berhasil diproses.');
    }

    /**
     * Cancel borrowing request
     */
    public function cancel(Request $request, Peminjaman $peminjaman)
    {
        // Check authorization
        if (Auth::user()->role === 'siswa' && $peminjaman->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk membatalkan peminjaman ini.');
        }

        if (!in_array($peminjaman->status, ['menunggu_verifikasi', 'dipinjam'])) {
            return redirect()->back()
                ->with('error', 'Peminjaman tidak dapat dibatalkan.');
        }

        $request->validate([
            'alasan_pembatalan' => 'required|string|max:500',
        ]);

        $peminjaman->update([
            'status' => 'dibatalkan',
            'tanggal_batal' => now(),
            'cancelled_by' => Auth::id(),
            'alasan_pembatalan' => $request->alasan_pembatalan,
        ]);

        return redirect()->back()
            ->with('success', 'Peminjaman berhasil dibatalkan.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Peminjaman $peminjaman)
    {
        // Check authorization
        if (Auth::user()->role === 'siswa' && $peminjaman->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus peminjaman ini.');
        }

        // Only allow deletion if status allows it
        if (!in_array($peminjaman->status, ['menunggu_verifikasi', 'dibatalkan', 'dikembalikan'])) {
            return redirect()->back()
                ->with('error', 'Peminjaman tidak dapat dihapus.');
        }

        $peminjaman->delete();

        return redirect()->route('peminjaman.index')
            ->with('success', 'Peminjaman berhasil dihapus.');
    }

    /**
     * Export borrowings to CSV
     */
    public function export(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Hanya admin yang dapat mengekspor data.');
        }

        $query = Peminjaman::with(['user', 'peminjamanDetails.alat']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_pinjam', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal_pinjam', '<=', $request->tanggal_selesai);
        }

        $peminjamans = $query->orderBy('created_at', 'desc')->get();

        $filename = 'peminjaman_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($peminjamans) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // CSV headers
            fputcsv($file, [
                'ID Peminjaman',
                'Peminjam',
                'Email',
                'Kelas',
                'Alat',
                'Kode Alat',
                'Jumlah',
                'Keterangan Item',
                'Total Items',
                'Tanggal Pinjam',
                'Keperluan',
                'Status',
                'Tanggal Verifikasi',
                'Tanggal Kembali',
                'Tanggal Dibuat'
            ]);
            
            // CSV data
            foreach ($peminjamans as $peminjaman) {
                foreach ($peminjaman->peminjamanDetails as $detail) {
                    fputcsv($file, [
                        $peminjaman->id,
                        $peminjaman->user->name,
                        $peminjaman->user->email,
                        $peminjaman->user->kelas ?? '-',
                        $detail->alat->nama,
                        $detail->alat->kode,
                        $detail->jumlah,
                        $detail->keterangan ?? '-',
                        $peminjaman->total_items,
                        $peminjaman->tanggal_pinjam,
                        $peminjaman->keperluan,
                        ucfirst(str_replace('_', ' ', $peminjaman->status)),
                        $peminjaman->tanggal_verifikasi,
                        $peminjaman->tanggal_kembali,
                        $peminjaman->created_at->format('Y-m-d H:i:s')
                    ]);
                }
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
