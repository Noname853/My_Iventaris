<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Order by latest
        $query->orderBy('created_at', 'desc');

        $users = $query->paginate(10);

        // Statistics for admin
        $stats = [
            'total' => User::count(),
            'admin' => User::where('role', 'admin')->count(),
            'siswa' => User::where('role', 'siswa')->count(),
            'verified' => User::whereNotNull('email_verified_at')->count(),
        ];

        return view('users.index', compact('users', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validationRules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|in:admin,siswa',
            'password' => 'required|string|min:8|confirmed',
        ];
        
        // Add kelas validation for siswa role
        if ($request->role === 'siswa') {
            $validationRules['kelas'] = 'required|string|max:50';
        }
        
        $request->validate($validationRules);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ];
        
        // Add kelas for siswa role
        if ($request->role === 'siswa') {
            $userData['kelas'] = $request->kelas;
        }

        // Set email verification if checked
        if ($request->has('email_verified')) {
            $userData['email_verified_at'] = now();
        }

        $user = User::create($userData);

        return redirect()->route('users.index')
            ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        // Load relationships for statistics
        $user->load('peminjamans.peminjamanDetails.alat');
        
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        // Load relationships for statistics
        $user->load('peminjamans');
        
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validationRules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'role' => 'required|in:admin,siswa',
            'password' => 'nullable|string|min:8|confirmed',
        ];
        
        // Add kelas validation for siswa role
        if ($request->role === 'siswa') {
            $validationRules['kelas'] = 'required|string|max:50';
        }
        
        $request->validate($validationRules);

        // Prevent user from changing their own role
        if ($user->id == Auth::id() && $request->role != $user->role) {
            return redirect()->back()
                ->with('error', 'Anda tidak dapat mengubah role akun Anda sendiri.');
        }

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];
        
        // Add kelas for siswa role
        if ($request->role === 'siswa') {
            $userData['kelas'] = $request->kelas;
        } else {
            // Clear kelas if role is not siswa
            $userData['kelas'] = null;
        }

        // Update password if provided
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        // Handle email verification status
        if ($request->has('email_verified')) {
            if (!$user->email_verified_at) {
                $userData['email_verified_at'] = now();
            }
        } else {
            $userData['email_verified_at'] = null;
        }

        $user->update($userData);

        // If user updated their own password, logout for security
        if ($user->id == Auth::id() && $request->filled('password')) {
            Auth::logout();
            return redirect()->route('login')
                ->with('success', 'Profil berhasil diperbarui. Silakan login kembali dengan password baru.');
        }

        return redirect()->route('users.show', $user)
            ->with('success', 'Pengguna berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Prevent user from deleting their own account
        if ($user->id == Auth::id()) {
            return redirect()->back()
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // Check if user has active borrowings
        $activeBorrowings = $user->peminjamans()
            ->whereIn('status', ['menunggu_verifikasi', 'dipinjam'])
            ->count();

        if ($activeBorrowings > 0) {
            return redirect()->back()
                ->with('error', 'Tidak dapat menghapus pengguna yang memiliki peminjaman aktif.');
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', "Pengguna {$userName} berhasil dihapus.");
    }

    /**
     * Verify user email manually
     */
    public function verifyEmail(User $user)
    {
        if ($user->email_verified_at) {
            return redirect()->back()
                ->with('error', 'Email pengguna sudah terverifikasi.');
        }

        $user->update([
            'email_verified_at' => now()
        ]);

        return redirect()->back()
            ->with('success', 'Email pengguna berhasil diverifikasi.');
    }

    /**
     * Reset user password
     */
    public function resetPassword(User $user)
    {
        // Generate random password
        $newPassword = $this->generateRandomPassword();
        
        $user->update([
            'password' => Hash::make($newPassword)
        ]);

        // In a real application, you would send this password via email
        // For now, we'll just show it in the session
        return redirect()->back()
            ->with('success', "Password berhasil direset. Password baru: {$newPassword}");
    }

    /**
     * Generate random password
     */
    private function generateRandomPassword($length = 12)
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*';
        $password = '';
        
        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[rand(0, strlen($chars) - 1)];
        }
        
        return $password;
    }

    /**
     * Get user statistics
     */
    public function statistics()
    {
        $stats = [
            'total_users' => User::count(),
            'total_admins' => User::where('role', 'admin')->count(),
            'total_students' => User::where('role', 'siswa')->count(),
            'verified_users' => User::whereNotNull('email_verified_at')->count(),
            'unverified_users' => User::whereNull('email_verified_at')->count(),
            'recent_users' => User::where('created_at', '>=', now()->subDays(30))->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Bulk actions for users
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:verify_email,delete',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        $userIds = $request->user_ids;
        
        // Prevent action on current user
        if (in_array(Auth::id(), $userIds)) {
            return redirect()->back()
                ->with('error', 'Anda tidak dapat melakukan aksi pada akun Anda sendiri.');
        }

        switch ($request->action) {
            case 'verify_email':
                User::whereIn('id', $userIds)
                    ->whereNull('email_verified_at')
                    ->update(['email_verified_at' => now()]);
                
                return redirect()->back()
                    ->with('success', 'Email pengguna terpilih berhasil diverifikasi.');
                
            case 'delete':
                // Check for active borrowings
                $usersWithActiveBorrowings = User::whereIn('id', $userIds)
                    ->whereHas('peminjamans', function($query) {
                        $query->whereIn('status', ['menunggu_verifikasi', 'dipinjam']);
                    })
                    ->pluck('name')
                    ->toArray();
                
                if (!empty($usersWithActiveBorrowings)) {
                    return redirect()->back()
                        ->with('error', 'Tidak dapat menghapus pengguna dengan peminjaman aktif: ' . implode(', ', $usersWithActiveBorrowings));
                }
                
                $deletedCount = User::whereIn('id', $userIds)->count();
                User::whereIn('id', $userIds)->delete();
                
                return redirect()->back()
                    ->with('success', "{$deletedCount} pengguna berhasil dihapus.");
        }

        return redirect()->back()
            ->with('error', 'Aksi tidak valid.');
    }
}