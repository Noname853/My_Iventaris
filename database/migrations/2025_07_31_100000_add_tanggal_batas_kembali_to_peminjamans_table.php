<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            if (!Schema::hasColumn('peminjamans', 'tanggal_batas_kembali')) {
                $table->date('tanggal_batas_kembali')->nullable()->after('tanggal_pinjam');
            }
            if (!Schema::hasColumn('peminjamans', 'lama_pinjam')) {
                $table->integer('lama_pinjam')->nullable()->after('tanggal_batas_kembali');
            }
        });
    }

    public function down()
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            if (Schema::hasColumn('peminjamans', 'tanggal_batas_kembali')) {
                $table->dropColumn('tanggal_batas_kembali');
            }
            if (Schema::hasColumn('peminjamans', 'lama_pinjam')) {
                $table->dropColumn('lama_pinjam');
            }
        });
    }
};
