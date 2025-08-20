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
            // Check if columns exist before dropping them
            if (Schema::hasColumn('peminjamans', 'alat_id')) {
                // Drop foreign key constraint first
                $table->dropForeign(['alat_id']);
                $table->dropColumn('alat_id');
            }
            
            if (Schema::hasColumn('peminjamans', 'jumlah')) {
                $table->dropColumn('jumlah');
            }
            
            // Add total_items field to track total number of items borrowed
            if (!Schema::hasColumn('peminjamans', 'total_items')) {
                $table->integer('total_items')->default(0)->after('user_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            // Add back the columns if they don't exist
            if (!Schema::hasColumn('peminjamans', 'alat_id')) {
                $table->foreignId('alat_id')->constrained()->onDelete('cascade')->after('user_id');
            }
            
            if (!Schema::hasColumn('peminjamans', 'jumlah')) {
                $table->integer('jumlah')->after('alat_id');
            }
            
            // Remove total_items if it exists
            if (Schema::hasColumn('peminjamans', 'total_items')) {
                $table->dropColumn('total_items');
            }
        });
    }
};