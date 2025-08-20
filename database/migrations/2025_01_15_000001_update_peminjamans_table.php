<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            // Check if qty column exists before renaming
            if (Schema::hasColumn('peminjamans', 'qty')) {
                $table->renameColumn('qty', 'jumlah');
            }
            
            // Update status enum to include 'dibatalkan'
            $table->enum('status', ['menunggu_verifikasi', 'dipinjam', 'dikembalikan', 'dibatalkan'])
                  ->default('menunggu_verifikasi')
                  ->change();
            
            // Change tanggal_kembali to datetime
            $table->datetime('tanggal_kembali')->nullable()->change();
            
            // Add new fields
            $table->datetime('tanggal_verifikasi')->nullable()->after('tanggal_kembali');
            $table->datetime('tanggal_batal')->nullable()->after('tanggal_verifikasi');
            $table->text('keperluan')->nullable()->after('tanggal_batal');
            $table->text('catatan')->nullable()->after('keperluan');
            $table->text('catatan_pengembalian')->nullable()->after('catatan');
            $table->text('alasan_pembatalan')->nullable()->after('catatan_pengembalian');
            
            // Add foreign keys for tracking who performed actions
            $table->foreignId('verified_by')->nullable()->constrained('users')->after('alasan_pembatalan');
            $table->foreignId('returned_by')->nullable()->constrained('users')->after('verified_by');
            $table->foreignId('cancelled_by')->nullable()->constrained('users')->after('returned_by');
            
            // Drop keterangan column if it exists
            if (Schema::hasColumn('peminjamans', 'keterangan')) {
                $table->dropColumn('keterangan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            // Reverse the changes - check if jumlah column exists before renaming
            if (Schema::hasColumn('peminjamans', 'jumlah')) {
                $table->renameColumn('jumlah', 'qty');
            }
            
            $table->enum('status', ['menunggu_verifikasi', 'dipinjam', 'dikembalikan', 'ditolak'])
                  ->default('menunggu_verifikasi')
                  ->change();
            
            $table->date('tanggal_kembali')->nullable()->change();
            
            // Drop new fields safely
            if (Schema::hasColumn('peminjamans', 'verified_by')) {
                $table->dropForeign(['verified_by']);
            }
            if (Schema::hasColumn('peminjamans', 'returned_by')) {
                $table->dropForeign(['returned_by']);
            }
            if (Schema::hasColumn('peminjamans', 'cancelled_by')) {
                $table->dropForeign(['cancelled_by']);
            }
            
            $columnsToCheck = [
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
            
            foreach ($columnsToCheck as $column) {
                if (Schema::hasColumn('peminjamans', $column)) {
                    $table->dropColumn($column);
                }
            }
            
            // Add back keterangan if it doesn't exist
            if (!Schema::hasColumn('peminjamans', 'keterangan')) {
                $table->text('keterangan')->nullable();
            }
        });
    }
};