<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Alat extends Model
{
    protected $table = 'alats';
    
    protected $fillable = [
        'kode',
        'nama',
        'kategori',
        'stok',
        'lokasi',
        'deskripsi',
        'foto',
        'tanggal_eos',
        'tanggal_eol',
        'keterangan_eos',
        'keterangan_eol'
    ];
    
    protected $casts = [
        'stok' => 'integer',
        'tanggal_eos' => 'date',
        'tanggal_eol' => 'date'
    ];
    
    public function peminjamanDetails(): HasMany
    {
        return $this->hasMany(PeminjamanDetail::class);
    }
    
    public function peminjamans()
    {
        return $this->belongsToMany(Peminjaman::class, 'peminjaman_details')
                    ->withPivot('jumlah', 'keterangan')
                    ->withTimestamps();
    }
    
    public function getStokTersediaAttribute()
    {
        $dipinjam = $this->peminjamanDetails()
            ->whereHas('peminjaman', function($query) {
                $query->whereIn('status', ['menunggu_verifikasi', 'dipinjam']);
            })
            ->sum('jumlah');
            
        return $this->stok - $dipinjam;
    }
    
    public function getStatusStokAttribute()
    {
        if ($this->stok == 0) {
            return 'habis';
        } elseif ($this->stok <= 5) {
            return 'rendah';
        } else {
            return 'tersedia';
        }
    }
    
    public function scopeAvailable($query)
    {
        return $query->where('stok', '>', 0);
    }
    
    public function scopeLowStock($query, $threshold = 5)
    {
        return $query->where('stok', '>', 0)->where('stok', '<=', $threshold);
    }
    
    // EOS/EOL Related Methods
    public function getStatusEosAttribute()
    {
        if (!$this->tanggal_eos) {
            return 'no_eos';
        }
        
        $now = Carbon::now();
        $eosDate = Carbon::parse($this->tanggal_eos);
        $daysToEos = $now->diffInDays($eosDate, false);
        
        if ($daysToEos < 0) {
            return 'eos_expired'; // Sudah melewati EOS
        } elseif ($daysToEos <= 30) {
            return 'eos_warning'; // Dalam 30 hari
        } elseif ($daysToEos <= 90) {
            return 'eos_notice'; // Dalam 90 hari
        } else {
            return 'eos_normal'; // Masih lama
        }
    }
    
    public function getStatusEolAttribute()
    {
        if (!$this->tanggal_eol) {
            return 'no_eol';
        }
        
        $now = Carbon::now();
        $eolDate = Carbon::parse($this->tanggal_eol);
        $daysToEol = $now->diffInDays($eolDate, false);
        
        if ($daysToEol < 0) {
            return 'eol_expired'; // Sudah melewati EOL
        } elseif ($daysToEol <= 30) {
            return 'eol_critical'; // Dalam 30 hari
        } elseif ($daysToEol <= 90) {
            return 'eol_warning'; // Dalam 90 hari
        } elseif ($daysToEol <= 180) {
            return 'eol_notice'; // Dalam 180 hari
        } else {
            return 'eol_normal'; // Masih lama
        }
    }
    
    public function getStatusEosTextAttribute()
    {
        $statusTexts = [
            'no_eos' => 'Tidak ada EOS',
            'eos_normal' => 'EOS Normal',
            'eos_notice' => 'EOS dalam 3 bulan',
            'eos_warning' => 'EOS dalam 30 hari',
            'eos_expired' => 'EOS Berakhir'
        ];
        
        return $statusTexts[$this->status_eos] ?? 'Unknown';
    }
    
    public function getStatusEolTextAttribute()
    {
        $statusTexts = [
            'no_eol' => 'Tidak ada EOL',
            'eol_normal' => 'EOL Normal',
            'eol_notice' => 'EOL dalam 6 bulan',
            'eol_warning' => 'EOL dalam 3 bulan',
            'eol_critical' => 'EOL dalam 30 hari',
            'eol_expired' => 'EOL Berakhir'
        ];
        
        return $statusTexts[$this->status_eol] ?? 'Unknown';
    }
    
    public function getStatusEosBadgeAttribute()
    {
        $badges = [
            'no_eos' => 'secondary',
            'eos_normal' => 'success',
            'eos_notice' => 'info',
            'eos_warning' => 'warning',
            'eos_expired' => 'danger'
        ];
        
        return $badges[$this->status_eos] ?? 'secondary';
    }
    
    public function getStatusEolBadgeAttribute()
    {
        $badges = [
            'no_eol' => 'secondary',
            'eol_normal' => 'success',
            'eol_notice' => 'info',
            'eol_warning' => 'warning',
            'eol_critical' => 'danger',
            'eol_expired' => 'dark'
        ];
        
        return $badges[$this->status_eol] ?? 'secondary';
    }
    
    public function getDaysToEosAttribute()
    {
        if (!$this->tanggal_eos) {
            return null;
        }
        
        return Carbon::now()->diffInDays(Carbon::parse($this->tanggal_eos), false);
    }
    
    public function getDaysToEolAttribute()
    {
        if (!$this->tanggal_eol) {
            return null;
        }
        
        return Carbon::now()->diffInDays(Carbon::parse($this->tanggal_eol), false);
    }
    
    // Scopes for filtering by EOS/EOL status
    public function scopeEosExpiring($query, $days = 30)
    {
        return $query->whereNotNull('tanggal_eos')
                    ->whereDate('tanggal_eos', '<=', Carbon::now()->addDays($days));
    }
    
    public function scopeEolExpiring($query, $days = 90)
    {
        return $query->whereNotNull('tanggal_eol')
                    ->whereDate('tanggal_eol', '<=', Carbon::now()->addDays($days));
    }
    
    public function scopeEosExpired($query)
    {
        return $query->whereNotNull('tanggal_eos')
                    ->whereDate('tanggal_eos', '<', Carbon::now());
    }
    
    public function scopeEolExpired($query)
    {
        return $query->whereNotNull('tanggal_eol')
                    ->whereDate('tanggal_eol', '<', Carbon::now());
    }
    
    public function getIsEosExpiredAttribute()
    {
        return $this->status_eos === 'eos_expired';
    }
    
    public function getIsEolExpiredAttribute()
    {
        return $this->status_eol === 'eol_expired';
    }
    
    public function getIsEosWarningAttribute()
    {
        return in_array($this->status_eos, ['eos_warning', 'eos_expired']);
    }
    
    public function getIsEolWarningAttribute()
    {
        return in_array($this->status_eol, ['eol_critical', 'eol_warning', 'eol_expired']);
    }
}
