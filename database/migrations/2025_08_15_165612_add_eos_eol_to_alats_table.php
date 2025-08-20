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
        Schema::table('alats', function (Blueprint $table) {
            $table->date('tanggal_eos')->nullable()->after('foto')->comment('End of Service date');
            $table->date('tanggal_eol')->nullable()->after('tanggal_eos')->comment('End of Life date');
            $table->text('keterangan_eos')->nullable()->after('tanggal_eol')->comment('EOS description/notes');
            $table->text('keterangan_eol')->nullable()->after('keterangan_eos')->comment('EOL description/notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alats', function (Blueprint $table) {
            $table->dropColumn(['tanggal_eos', 'tanggal_eol', 'keterangan_eos', 'keterangan_eol']);
        });
    }
};
