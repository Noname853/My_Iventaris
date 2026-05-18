<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Exports\AlatExport;
use App\Exports\AlatTemplateExport;
use App\Imports\AlatImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class AlatController extends Controller
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
        $query = Alat::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('kode', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        // Filter by location
        if ($request->filled('lokasi')) {
            $query->where('lokasi', $request->lokasi);
        }

        // Filter by status
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'tersedia':
                    $query->where('stok', '>', 0);
                    break;
                case 'habis':
                    $query->where('stok', 0);
                    break;
                case 'stok_rendah':
                    $query->where('stok', '>', 0)->where('stok', '<=', 5);
                    break;
            }
        }

        // Order by name
        $query->orderBy('nama');

        $alats = $query->paginate(12);

        // Get filter options
        $kategoris = Alat::distinct()->pluck('kategori')->filter()->sort();
        $lokasis = Alat::distinct()->pluck('lokasi')->filter()->sort();

        // Statistics for admin
        $stats = null;
        if (Auth::user()->role === 'admin') {
            $stats = [
                'total' => Alat::count(),
                'tersedia' => Alat::where('stok', '>', 0)->count(),
                'stok_rendah' => Alat::where('stok', '>', 0)->where('stok', '<=', 5)->count(),
                'habis' => Alat::where('stok', 0)->count(),
                'eos_warning' => Alat::eosExpiring(30)->count(),
                'eol_warning' => Alat::eolExpiring(90)->count(),
                'eos_expired' => Alat::eosExpired()->count(),
                'eol_expired' => Alat::eolExpired()->count(),
            ];
        }

        return view('alat.index', compact('alats', 'kategoris', 'lokasis', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Alat::class);
        
        // Get existing categories and locations for suggestions
        $kategoris = Alat::distinct()->pluck('kategori')->filter()->sort();
        $lokasis = Alat::distinct()->pluck('lokasi')->filter()->sort();
        
        return view('alat.create', compact('kategoris', 'lokasis'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Alat::class);
        
        $request->validate([
            'kode' => 'required|string|max:50|unique:alats',
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'stok' => 'required|integer|min:0',
            'lokasi' => 'required|string|max:100',
            'deskripsi' => 'nullable|string|max:1000',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tanggal_eos' => 'nullable|date|after:today',
            'tanggal_eol' => 'nullable|date|after:today|after_or_equal:tanggal_eos',
            'keterangan_eos' => 'nullable|string|max:500',
            'keterangan_eol' => 'nullable|string|max:500',
        ]);

        // Handle photo upload
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $fotoName = time() . '_' . $foto->getClientOriginalName();
            $fotoPath = $foto->storeAs('alat_photos', $fotoName, 'public');
        }

        $alat = Alat::create([
            'kode' => strtoupper($request->kode),
            'nama' => $request->nama,
            'kategori' => $request->kategori,
            'stok' => $request->stok,
            'lokasi' => $request->lokasi,
            'deskripsi' => $request->deskripsi,
            'foto' => $fotoPath,
            'tanggal_eos' => $request->tanggal_eos,
            'tanggal_eol' => $request->tanggal_eol,
            'keterangan_eos' => $request->keterangan_eos,
            'keterangan_eol' => $request->keterangan_eol,
        ]);

        return redirect()->route('alat.show', $alat)
            ->with('success', 'Alat berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Alat $alat)
    {
        // Load recent borrowings for this tool
        $recentBorrowings = $alat->peminjamans()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Check if current user has pending borrowing for this tool
        $hasPendingBorrowing = false;
        if (Auth::user()->role === 'siswa') {
            $hasPendingBorrowing = $alat->peminjamans()
                ->where('user_id', Auth::id())
                ->whereIn('status', ['menunggu_verifikasi', 'dipinjam'])
                ->exists();
        }

        return view('alat.show', compact('alat', 'recentBorrowings', 'hasPendingBorrowing'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Alat $alat)
    {
        $this->authorize('update', $alat);
        
        // Get existing categories and locations for suggestions
        $kategoris = Alat::distinct()->pluck('kategori')->filter()->sort();
        $lokasis = Alat::distinct()->pluck('lokasi')->filter()->sort();
        
        // Check for active borrowings count
        $activeBorrowings = $alat->peminjamans()
            ->whereIn('status', ['menunggu_verifikasi', 'dipinjam'])
            ->count();
        
        return view('alat.edit', compact('alat', 'kategoris', 'lokasis', 'activeBorrowings'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Alat $alat)
    {
        $this->authorize('update', $alat);
        
        $request->validate([
            'kode' => 'required|string|max:50|unique:alats,kode,' . $alat->id,
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'stok' => 'required|integer|min:0',
            'lokasi' => 'required|string|max:100',
            'deskripsi' => 'nullable|string|max:1000',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tanggal_eos' => 'nullable|date',
            'tanggal_eol' => 'nullable|date|after_or_equal:tanggal_eos',
            'keterangan_eos' => 'nullable|string|max:500',
            'keterangan_eol' => 'nullable|string|max:500',
        ]);

        // Check if reducing stock below borrowed amount
        $borrowedAmount = $alat->peminjamans()
            ->whereIn('status', ['menunggu_verifikasi', 'dipinjam'])
            ->sum('jumlah');

        if ($request->stok < $borrowedAmount) {
            return redirect()->back()
                ->with('error', "Stok tidak dapat dikurangi di bawah {$borrowedAmount} karena sedang dipinjam.")
                ->withInput();
        }

        // Handle photo upload
        $updateData = [
            'kode' => strtoupper($request->kode),
            'nama' => $request->nama,
            'kategori' => $request->kategori,
            'stok' => $request->stok,
            'lokasi' => $request->lokasi,
            'deskripsi' => $request->deskripsi,
            'tanggal_eos' => $request->tanggal_eos,
            'tanggal_eol' => $request->tanggal_eol,
            'keterangan_eos' => $request->keterangan_eos,
            'keterangan_eol' => $request->keterangan_eol,
        ];

        if ($request->hasFile('foto')) {
            // Delete old photo if exists
            if ($alat->foto && \Storage::disk('public')->exists($alat->foto)) {
                \Storage::disk('public')->delete($alat->foto);
            }
            
            // Upload new photo
            $foto = $request->file('foto');
            $fotoName = time() . '_' . $foto->getClientOriginalName();
            $fotoPath = $foto->storeAs('alat_photos', $fotoName, 'public');
            $updateData['foto'] = $fotoPath;
        }

        $alat->update($updateData);

        return redirect()->route('alat.show', $alat)
            ->with('success', 'Alat berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Alat $alat)
    {
        $this->authorize('delete', $alat);
        
        // Check if tool has active borrowings
        $activeBorrowings = $alat->peminjamans()
            ->whereIn('status', ['menunggu_verifikasi', 'dipinjam'])
            ->count();

        if ($activeBorrowings > 0) {
            return redirect()->back()
                ->with('error', 'Tidak dapat menghapus alat yang sedang dipinjam.');
        }

        $alatNama = $alat->nama;
        $alat->delete();

        return redirect()->route('alat.index')
            ->with('success', "Alat {$alatNama} berhasil dihapus.");
    }

    /**
     * Update stock of the tool
     */
    public function updateStock(Request $request, Alat $alat)
    {
        $this->authorize('update', $alat);
        
        $request->validate([
            'stok' => 'required|integer|min:0',
            'action' => 'required|in:set,add,subtract'
        ]);

        $currentStock = $alat->stok;
        $newStock = $currentStock;

        switch ($request->action) {
            case 'set':
                $newStock = $request->stok;
                break;
            case 'add':
                $newStock = $currentStock + $request->stok;
                break;
            case 'subtract':
                $newStock = max(0, $currentStock - $request->stok);
                break;
        }

        // Check if reducing stock below borrowed amount
        $borrowedAmount = $alat->peminjamans()
            ->whereIn('status', ['menunggu_verifikasi', 'dipinjam'])
            ->sum('jumlah');

        if ($newStock < $borrowedAmount) {
            return redirect()->back()
                ->with('error', "Stok tidak dapat dikurangi di bawah {$borrowedAmount} karena sedang dipinjam.");
        }

        $alat->update(['stok' => $newStock]);

        $message = "Stok berhasil diperbarui dari {$currentStock} menjadi {$newStock}.";
        
        return redirect()->back()
            ->with('success', $message);
    }

    /**
     * Generate tool code based on category
     */
    public function generateCode(Request $request)
    {
        $request->validate([
            'kategori' => 'required|string|max:100'
        ]);

        $kategori = $request->kategori;
        $prefix = strtoupper(substr($kategori, 0, 3));
        
        // Find the next number for this category
        $lastCode = Alat::where('kode', 'like', $prefix . '%')
            ->orderBy('kode', 'desc')
            ->first();

        $nextNumber = 1;
        if ($lastCode) {
            $lastNumber = (int) substr($lastCode->kode, 3);
            $nextNumber = $lastNumber + 1;
        }

        $code = $prefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        return response()->json(['code' => $code]);
    }

    /**
     * Get tool details for AJAX
     */
    public function getDetails(Alat $alat)
    {
        return response()->json([
            'id' => $alat->id,
            'kode' => $alat->kode,
            'nama' => $alat->nama,
            'kategori' => $alat->kategori,
            'stok' => $alat->stok,
            'lokasi' => $alat->lokasi,
            'deskripsi' => $alat->deskripsi,
            'status' => $alat->stok > 0 ? 'Tersedia' : 'Habis',
            'borrowed_count' => $alat->peminjamans()
                ->whereIn('status', ['menunggu_verifikasi', 'dipinjam'])
                ->sum('jumlah'),
            'available_stock' => max(0, $alat->stok - $alat->peminjamans()
                ->whereIn('status', ['menunggu_verifikasi', 'dipinjam'])
                ->sum('jumlah'))
        ]);
    }

    /**
     * Display iOS 16 themed detail view
     */
    public function showIOS16(Alat $alat)
    {
        // Load recent borrowings for this tool
        $recentBorrowings = $alat->peminjamans()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Check if current user has pending borrowing for this tool
        $hasPendingBorrowing = false;
        if (Auth::user()->role === 'siswa') {
            $hasPendingBorrowing = $alat->peminjamans()
                ->where('user_id', Auth::id())
                ->whereIn('status', ['menunggu_verifikasi', 'dipinjam'])
                ->exists();
        }

        return view('alat.show-ios16', compact('alat', 'recentBorrowings', 'hasPendingBorrowing'));
    }

    /**
     * Search tools for borrowing
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        $alats = Alat::where('stok', '>', 0)
            ->where(function($q) use ($query) {
                $q->where('nama', 'like', "%{$query}%")
                  ->orWhere('kode', 'like', "%{$query}%")
                  ->orWhere('kategori', 'like', "%{$query}%");
            })
            ->orderBy('nama')
            ->take(10)
            ->get(['id', 'kode', 'nama', 'kategori', 'stok', 'lokasi']);

        return response()->json($alats);
    }

    /**
     * Export alat data to Excel
     */
    public function export()
    {
        // Only admin can export
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $filename = 'data_alat_' . date('Y-m-d_H-i-s') . '.csv';
        
        $export = new AlatExport;
        $data = $export->toArray();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            foreach ($data as $row) {
                fputcsv($file, $row, ';'); // Use semicolon as delimiter
            }
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Import alat data from CSV
     */
    public function import(Request $request)
    {
        // Only admin can import
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120' // Max 5MB
        ]);

        try {
            DB::beginTransaction();
            
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $importedCount = 0;
            $updatedCount = 0;
            $errorCount = 0;
            
            if (in_array($extension, ['csv'])) {
                // Handle CSV manually
                $handle = fopen($file->getPathname(), 'r');
                $header = null;
                $rowIndex = 0;
                
                // Detect delimiter
                $firstLine = fgets($handle);
                $delimiter = strpos($firstLine, ';') !== false ? ';' : ',';
                rewind($handle);
                
                while (($row = fgetcsv($handle, 0, $delimiter)) !== FALSE) {
                    if ($rowIndex === 0) {
                        // Clean BOM from first column header if exists
                        $row[0] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $row[0]);
                        // Normalize headers: lowercase and trim
                        $header = array_map(function($h) { return strtolower(trim($h)); }, $row);
                        $rowIndex++;
                        continue;
                    }
                    
                    if (count($row) < count($header)) {
                        $errorCount++;
                        continue;
                    }
                    
                    // Map data to associative array
                    $data = array_combine($header, $row);
                    
                    if (empty($data['kode'])) {
                        $errorCount++;
                        continue;
                    }
                    
                    // Process the row
                    try {
                        $this->processImportRow($data, $importedCount, $updatedCount);
                    } catch (\Exception $e) {
                        $errorCount++;
                        continue;
                    }
                    
                    $rowIndex++;
                }
                
                fclose($handle);
            } else {
                // Try to use Excel import if available
                $import = new AlatImport;
                Excel::import($import, $request->file('file'));
                $importedCount = $import->getImportedCount();
                $updatedCount = $import->getUpdatedCount();
            }
            
            DB::commit();

            $message = "Import berhasil! ";
            $message .= "Data baru: {$importedCount}, ";
            $message .= "Data diperbarui: {$updatedCount}";
            
            if ($errorCount > 0) {
                $message .= ", Error: {$errorCount} baris diabaikan";
            }

            return redirect()->route('alat.index')
                ->with('success', $message);
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat import: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    private function processImportRow($data, &$importedCount, &$updatedCount)
    {
        // Convert dates
        $tanggalEos = null;
        if (!empty($data['tanggal_eos']) && $data['tanggal_eos'] !== '-') {
            try {
                $tanggalEos = \Carbon\Carbon::parse($data['tanggal_eos'])->format('Y-m-d');
            } catch (\Exception $e) {
                $tanggalEos = null;
            }
        }

        $tanggalEol = null;
        if (!empty($data['tanggal_eol']) && $data['tanggal_eol'] !== '-') {
            try {
                $tanggalEol = \Carbon\Carbon::parse($data['tanggal_eol'])->format('Y-m-d');
            } catch (\Exception $e) {
                $tanggalEol = null;
            }
        }

        // Check if alat already exists by kode
        $existingAlat = Alat::where('kode', strtoupper(trim($data['kode'])))->first();
        
        if ($existingAlat) {
            // Update existing alat
            $existingAlat->update([
                'nama' => $data['nama'] ?? $existingAlat->nama,
                'kategori' => $data['kategori'] ?? $existingAlat->kategori,
                'stok' => is_numeric($data['stok']) ? (int)$data['stok'] : $existingAlat->stok,
                'lokasi' => $data['lokasi'] ?? $existingAlat->lokasi,
                'deskripsi' => $data['deskripsi'] ?? null,
                'tanggal_eos' => $tanggalEos,
                'tanggal_eol' => $tanggalEol,
                'keterangan_eos' => $data['keterangan_eos'] ?? null,
                'keterangan_eol' => $data['keterangan_eol'] ?? null,
            ]);
            $updatedCount++;
        } else {
            // Create new alat
            Alat::create([
                'kode' => strtoupper(trim($data['kode'])),
                'nama' => $data['nama'],
                'kategori' => $data['kategori'],
                'stok' => is_numeric($data['stok']) ? (int)$data['stok'] : 0,
                'lokasi' => $data['lokasi'],
                'deskripsi' => $data['deskripsi'] ?? null,
                'tanggal_eos' => $tanggalEos,
                'tanggal_eol' => $tanggalEol,
                'keterangan_eos' => $data['keterangan_eos'] ?? null,
                'keterangan_eol' => $data['keterangan_eol'] ?? null,
            ]);
            $importedCount++;
        }
    }

    /**
     * Download template CSV for import
     */
    public function downloadTemplate()
    {
        // Only admin can download template
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $filename = 'template_import_alat.csv';
        
        $template = new AlatTemplateExport;
        $data = $template->toArray();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            foreach ($data as $row) {
                fputcsv($file, $row, ';'); // Use semicolon as delimiter
            }
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
