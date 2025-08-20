<?php

namespace App\Exports;

class AlatTemplateExport
{
    public function collection()
    {
        return [
            [
                'ALT001',
                'Laptop ASUS VivoBook',
                'Laptop',
                5,
                'Lab Komputer 1',
                'Laptop untuk pembelajaran programming',
                '2026-12-31',
                '2028-12-31',
                'End of Service setelah 3 tahun',
                'End of Life setelah 5 tahun'
            ],
            [
                'NET001',
                'Switch Cisco 24 Port',
                'Networking',
                2,
                'Server Room',
                'Switch untuk jaringan lab',
                '',
                '2027-06-30',
                '',
                'Akan diganti dengan model terbaru'
            ]
        ];
    }

    public function headings(): array
    {
        return [
            'kode',
            'nama',
            'kategori',
            'stok',
            'lokasi',
            'deskripsi',
            'tanggal_eos',
            'tanggal_eol',
            'keterangan_eos',
            'keterangan_eol'
        ];
    }
    
    public function toArray()
    {
        $data = [];
        $data[] = $this->headings();
        
        foreach ($this->collection() as $row) {
            $data[] = $row;
        }
        
        return $data;
    }
}
