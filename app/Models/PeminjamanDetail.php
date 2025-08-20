<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeminjamanDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'peminjaman_id',
        'alat_id',
        'jumlah',
        'keterangan',
    ];

    /**
     * Get the peminjaman that owns the detail.
     */
    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }

    /**
     * Get the alat for this detail.
     */
    public function alat()
    {
        return $this->belongsTo(Alat::class);
    }
}