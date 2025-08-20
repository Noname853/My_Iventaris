<?php
namespace App\Exports;

use App\Models\Alat;

class AlatExport
{
    public function collection()
    {
        return Alat::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Kode',
            'Nama',
            'Kategori',
            'Stok',
            'Lokasi',
            'Deskripsi',
            'Tanggal EOS',
            'Tanggal EOL',
            'Keterangan EOS',
            'Keterangan EOL',
            'Dibuat pada',
            'Diperbarui pada',
        ];
    }

    public function map($alat): array
    {
        return [
            $alat->id,
            $alat->kode,
            $alat->nama,
            $alat->kategori,
            $alat->stok,
            $alat->lokasi,
            $alat->deskripsi,
            $alat->tanggal_eos ? $alat->tanggal_eos->format('Y-m-d') : '-',
            $alat->tanggal_eol ? $alat->tanggal_eol->format('Y-m-d') : '-',
            $alat->keterangan_eos,
            $alat->keterangan_eol,
            $alat->created_at->format('Y-m-d H:i:s'),
            $alat->updated_at->format('Y-m-d H:i:s'),
        ];
    }
    
    public function toArray()
    {
        $data = [];
        $data[] = $this->headings();
        
        foreach ($this->collection() as $alat) {
            $data[] = $this->map($alat);
        }
        
        return $data;
    }
}

