<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class KelompokController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        $anggota = $user->anggota_kelompok ? json_decode($user->anggota_kelompok, true) : [];
        $anggota[] = $request->name;
        $user->anggota_kelompok = json_encode($anggota);
        $user->save();

        return redirect()->back()->with('success', 'Anggota baru berhasil ditambahkan ke kelompok.');
    }
}
