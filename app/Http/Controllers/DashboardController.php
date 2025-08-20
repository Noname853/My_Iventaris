<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Helpers\EosEolNotificationHelper;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            return $this->adminDashboard();
        } else {
            return $this->siswaDashboard();
        }
    }

    private function adminDashboard()
    {
        // Statistics
        $totalAlat = Alat::count();
        $alatTersedia = Alat::where('stok', '>', 0)->count();
        $alatHabis = Alat::where('stok', 0)->count();
        $alatStokRendahCount = Alat::where('stok', '>', 0)->where('stok', '<=', 5)->count();
        
        $totalPeminjaman = Peminjaman::count();
        $peminjamanAktif = Peminjaman::where('status', 'dipinjam')->count();
        $peminjamanMenunggu = Peminjaman::where('status', 'menunggu_verifikasi')->count();
        $peminjamanHariIni = Peminjaman::whereDate('created_at', today())->count();
        
        $totalUsers = User::count();
        $totalSiswa = User::where('role', 'siswa')->count();
        $totalAdmin = User::where('role', 'admin')->count();
        $usersVerified = User::whereNotNull('email_verified_at')->count();
        
        // Recent activities
        $recentPeminjaman = Peminjaman::with(['user', 'peminjamanDetails.alat'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        $peminjamanMenungguVerifikasi = Peminjaman::with(['user', 'peminjamanDetails.alat'])
            ->where('status', 'menunggu_verifikasi')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
            
        // Chart data for last 7 days
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $chartData[] = [
                'date' => $date->format('Y-m-d'),
                'label' => $date->format('d M'),
                'peminjaman' => Peminjaman::whereDate('created_at', $date)->count(),
                'pengembalian' => Peminjaman::whereDate('tanggal_kembali', $date)->count(),
            ];
        }
        
        // Top borrowed items
        $topAlat = Alat::withCount(['peminjamans' => function($query) {
                $query->where('status', '!=', 'dibatalkan');
            }])
            ->orderBy('peminjamans_count', 'desc')
            ->limit(5)
            ->get();
            
        // Low stock items
        $alatStokRendahList = Alat::where('stok', '>', 0)
            ->where('stok', '<=', 5)
            ->orderBy('stok', 'asc')
            ->get();
            
        // EOS/EOL Statistics and notifications
        $eosEolStats = EosEolNotificationHelper::getStatistics();
        $eosEolNotifications = EosEolNotificationHelper::getNotifications();
        $criticalAlats = EosEolNotificationHelper::getToolsNeedingAttention();
        
        // Additional EOS/EOL statistics for cards
        $eosExpired = $eosEolStats['eos_expired'];
        $eolExpired = $eosEolStats['eol_expired'];
        $eosWarning = $eosEolStats['eos_warning_30'];
        $eolWarning = $eosEolStats['eol_warning_90'];
            
        $menungguVerifikasi = $peminjamanMenunggu;
        $totalUser = $totalUsers; // Alias untuk konsistensi dengan view
        $peminjamanTerbaru = $recentPeminjaman; // Alias untuk konsistensi dengan view
        $alatStokRendah = $alatStokRendahList; // Alias untuk konsistensi dengan view - menggunakan collection bukan count
        
        return view('dashboard.admin', compact(
            'totalAlat', 'alatTersedia', 'alatHabis', 'alatStokRendahCount',
            'totalPeminjaman', 'peminjamanAktif', 'peminjamanMenunggu', 'peminjamanHariIni',
            'totalUsers', 'totalSiswa', 'totalAdmin', 'usersVerified',
            'recentPeminjaman', 'peminjamanMenungguVerifikasi', 'chartData', 'topAlat', 'alatStokRendah',
            'menungguVerifikasi', 'totalUser', 'peminjamanTerbaru',
            'eosEolStats', 'eosEolNotifications', 'criticalAlats',
            'eosExpired', 'eolExpired', 'eosWarning', 'eolWarning'
        ));
    }

    private function siswaDashboard()
    {
        $user = Auth::user();
        
        // Student's borrowing statistics
        $totalPeminjaman = Peminjaman::where('user_id', $user->id)->count();
        $peminjamanAktif = Peminjaman::where('user_id', $user->id)
            ->where('status', 'dipinjam')
            ->count();
        $peminjamanMenunggu = Peminjaman::where('user_id', $user->id)
            ->where('status', 'menunggu_verifikasi')
            ->count();
        $peminjamanSelesai = Peminjaman::where('user_id', $user->id)
            ->where('status', 'dikembalikan')
            ->count();
            
        // Recent borrowings
        $recentPeminjaman = Peminjaman::with('peminjamanDetails.alat')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        // Active borrowings
        $peminjamanAktifList = Peminjaman::with('peminjamanDetails.alat')
            ->where('user_id', $user->id)
            ->whereIn('status', ['menunggu_verifikasi', 'dipinjam'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Available tools count
        $alatTersedia = Alat::where('stok', '>', 0)->count();
        
        $menungguVerifikasi = $peminjamanMenunggu;
        
        return view('dashboard.siswa', compact(
            'totalPeminjaman', 'peminjamanAktif', 'peminjamanMenunggu', 'peminjamanSelesai',
            'recentPeminjaman', 'peminjamanAktifList', 'alatTersedia', 'menungguVerifikasi'
        ));
    }

    public function laporan(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Hanya admin yang dapat mengakses laporan.');
        }
        
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->toDateString());
        $status = $request->get('status');
        
        $query = Peminjaman::with(['user', 'peminjamanDetails.alat'])
            ->whereBetween('created_at', [$startDate, $endDate]);
            
        if ($status) {
            $query->where('status', $status);
        }
        
        $peminjamans = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Get statistics from all data (not just current page)
        $statsQuery = Peminjaman::whereBetween('created_at', [$startDate, $endDate]);
        if ($status) {
            $statsQuery->where('status', $status);
        }
        
        $totalPeminjaman = $statsQuery->count();
        $peminjamanMenunggu = $statsQuery->where('status', 'menunggu_verifikasi')->count();
        $peminjamanDipinjam = $statsQuery->where('status', 'dipinjam')->count();
        $peminjamanDikembalikan = $statsQuery->where('status', 'dikembalikan')->count();
        $peminjamanDibatalkan = $statsQuery->where('status', 'dibatalkan')->count();
        
        $menungguVerifikasi = $peminjamanMenunggu;
        $dipinjam = $peminjamanDipinjam;
        $dikembalikan = $peminjamanDikembalikan;
        
        return view('dashboard.laporan', compact(
            'peminjamans', 'startDate', 'endDate', 'status', 'totalPeminjaman',
            'peminjamanMenunggu', 'peminjamanDipinjam', 'peminjamanDikembalikan', 'peminjamanDibatalkan',
            'menungguVerifikasi', 'dipinjam', 'dikembalikan'
        ));
    }
    
    /**
     * Get dashboard statistics for AJAX requests
     */
    public function getStats()
    {
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            return response()->json([
                'alat' => [
                    'total' => Alat::count(),
                    'tersedia' => Alat::where('stok', '>', 0)->count(),
                    'habis' => Alat::where('stok', 0)->count(),
                    'stok_rendah' => Alat::where('stok', '>', 0)->where('stok', '<=', 5)->count(),
                ],
                'peminjaman' => [
                    'total' => Peminjaman::count(),
                    'aktif' => Peminjaman::where('status', 'dipinjam')->count(),
                    'menunggu' => Peminjaman::where('status', 'menunggu_verifikasi')->count(),
                    'hari_ini' => Peminjaman::whereDate('created_at', today())->count(),
                ],
                'users' => [
                    'total' => User::count(),
                    'siswa' => User::where('role', 'siswa')->count(),
                    'admin' => User::where('role', 'admin')->count(),
                    'verified' => User::whereNotNull('email_verified_at')->count(),
                ]
            ]);
        } else {
            return response()->json([
                'peminjaman' => [
                    'total' => Peminjaman::where('user_id', $user->id)->count(),
                    'aktif' => Peminjaman::where('user_id', $user->id)->where('status', 'dipinjam')->count(),
                    'menunggu' => Peminjaman::where('user_id', $user->id)->where('status', 'menunggu_verifikasi')->count(),
                    'selesai' => Peminjaman::where('user_id', $user->id)->where('status', 'dikembalikan')->count(),
                ],
                'alat_tersedia' => Alat::where('stok', '>', 0)->count()
            ]);
        }
    }
    
    /**
     * Get chart data for dashboard
     */
    public function getChartData(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }
        
        $days = $request->get('days', 7);
        $chartData = [];
        
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $chartData[] = [
                'date' => $date->format('Y-m-d'),
                'label' => $date->format('d M'),
                'peminjaman' => Peminjaman::whereDate('created_at', $date)->count(),
                'pengembalian' => Peminjaman::whereDate('tanggal_kembali', $date)->count(),
            ];
        }
        
        return response()->json($chartData);
    }
}
