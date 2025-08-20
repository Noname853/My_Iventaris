<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Peminjaman extends Model
{
    protected $table = 'peminjamans';
    
    protected $fillable = [
        'user_id',
        'total_items',
        'status',
        'tanggal_pinjam',
        'tanggal_batas_kembali',
        'lama_pinjam',
        'tanggal_kembali',
        'tanggal_verifikasi',
        'tanggal_batal',
        'keperluan',
        'catatan',
        'catatan_pengembalian',
        'alasan_pembatalan',
        'verified_by',
        'returned_by',
        'cancelled_by'
    ];
    
    protected $casts = [
        'total_items' => 'integer',
        'tanggal_pinjam' => 'date',
        'tanggal_kembali' => 'datetime',
        'tanggal_verifikasi' => 'datetime',
        'tanggal_batal' => 'datetime'
    ];
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function peminjamanDetails()
    {
        return $this->hasMany(PeminjamanDetail::class);
    }
    
    public function alats()
    {
        return $this->belongsToMany(Alat::class, 'peminjaman_details')
                    ->withPivot('jumlah', 'keterangan')
                    ->withTimestamps();
    }
    
    public function scopeAktif($query)
    {
        return $query->where('status', 'dipinjam');
    }
    
    public function scopeMenungguVerifikasi($query)
    {
        return $query->where('status', 'menunggu_verifikasi');
    }
    
    public function scopeSelesai($query)
    {
        return $query->where('status', 'dikembalikan');
    }
    
    public function scopeDibatalkan($query)
    {
        return $query->where('status', 'dibatalkan');
    }
    
    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
    
    public function returnedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'returned_by');
    }
    
    public function cancelledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }
    
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'menunggu_verifikasi' => 'warning',
            'dipinjam' => 'primary',
            'dikembalikan' => 'success',
            'dibatalkan' => 'danger'
        ];
        
        return $badges[$this->status] ?? 'secondary';
    }
    
    public function getStatusTextAttribute()
    {
        $texts = [
            'menunggu_verifikasi' => 'Menunggu Verifikasi',
            'dipinjam' => 'Dipinjam',
            'dikembalikan' => 'Dikembalikan',
            'dibatalkan' => 'Dibatalkan'
        ];
        
        return $texts[$this->status] ?? 'Unknown';
    }
    
    public function getDurasiPeminjamanAttribute()
    {
        if ($this->tanggal_kembali) {
            return $this->tanggal_pinjam->diffInDays($this->tanggal_kembali);
        }
        
        if ($this->status === 'dipinjam') {
            return $this->tanggal_pinjam->diffInDays(now());
        }
        
        return null;
    }
}
