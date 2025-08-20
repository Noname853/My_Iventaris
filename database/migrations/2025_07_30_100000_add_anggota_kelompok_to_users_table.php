<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'anggota_kelompok')) {
                $table->json('anggota_kelompok')->nullable()->after('kelompok');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'anggota_kelompok')) {
                $table->dropColumn('anggota_kelompok');
            }
        });
    }
};
