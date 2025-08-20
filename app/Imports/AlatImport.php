<?php

namespace App\Imports;

use App\Models\Alat;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class AlatImport implements ToModel, WithHeadingRow
{
    
    protected $importedCount = 0;
    protected $updatedCount = 0;
    
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Skip if kode is empty
        if (empty($row['kode'])) {
            return null;
        }

        // Convert dates
        $tanggalEos = null;
        if (!empty($row['tanggal_eos']) && $row['tanggal_eos'] !== '-') {
            try {
                $tanggalEos = Carbon::parse($row['tanggal_eos'])->format('Y-m-d');
            } catch (\Exception $e) {
                $tanggalEos = null;
            }
        }

        $tanggalEol = null;
        if (!empty($row['tanggal_eol']) && $row['tanggal_eol'] !== '-') {
            try {
                $tanggalEol = Carbon::parse($row['tanggal_eol'])->format('Y-m-d');
            } catch (\Exception $e) {
                $tanggalEol = null;
            }
        }

        // Check if alat already exists by kode
        $existingAlat = Alat::where('kode', strtoupper(trim($row['kode'])))->first();
        
        if ($existingAlat) {
            // Update existing alat
            $existingAlat->update([
                'nama' => $row['nama'] ?? $existingAlat->nama,
                'kategori' => $row['kategori'] ?? $existingAlat->kategori,
                'stok' => is_numeric($row['stok']) ? (int)$row['stok'] : $existingAlat->stok,
                'lokasi' => $row['lokasi'] ?? $existingAlat->lokasi,
                'deskripsi' => $row['deskripsi'] ?? null,
                'tanggal_eos' => $tanggalEos,
                'tanggal_eol' => $tanggalEol,
                'keterangan_eos' => $row['keterangan_eos'] ?? null,
                'keterangan_eol' => $row['keterangan_eol'] ?? null,
            ]);
            $this->updatedCount++;
            return null;
        } else {
            // Create new alat
            $this->importedCount++;
            return new Alat([
                'kode' => strtoupper(trim($row['kode'])),
                'nama' => $row['nama'],
                'kategori' => $row['kategori'],
                'stok' => is_numeric($row['stok']) ? (int)$row['stok'] : 0,
                'lokasi' => $row['lokasi'],
                'deskripsi' => $row['deskripsi'] ?? null,
                'tanggal_eos' => $tanggalEos,
                'tanggal_eol' => $tanggalEol,
                'keterangan_eos' => $row['keterangan_eos'] ?? null,
                'keterangan_eol' => $row['keterangan_eol'] ?? null,
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'kode' => ['required', 'string', 'max:50'],
            'nama' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'string', 'max:100'],
            'stok' => ['required', 'integer', 'min:0'],
            'lokasi' => ['required', 'string', 'max:100'],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
            'tanggal_eos' => ['nullable', 'date_format:Y-m-d'],
            'tanggal_eol' => ['nullable', 'date_format:Y-m-d'],
            'keterangan_eos' => ['nullable', 'string', 'max:500'],
            'keterangan_eol' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function customValidationMessages()
    {
        return [
            'kode.required' => 'Kode alat wajib diisi.',
            'kode.unique' => 'Kode alat sudah ada.',
            'nama.required' => 'Nama alat wajib diisi.',
            'kategori.required' => 'Kategori alat wajib diisi.',
            'stok.required' => 'Stok alat wajib diisi.',
            'stok.integer' => 'Stok harus berupa angka.',
            'stok.min' => 'Stok minimal 0.',
            'lokasi.required' => 'Lokasi alat wajib diisi.',
            'tanggal_eos.date_format' => 'Format tanggal EOS tidak valid (gunakan YYYY-MM-DD).',
            'tanggal_eol.date_format' => 'Format tanggal EOL tidak valid (gunakan YYYY-MM-DD).',
        ];
    }

    public function getImportedCount(): int
    {
        return $this->importedCount;
    }

    public function getUpdatedCount(): int
    {
        return $this->updatedCount;
    }
}
