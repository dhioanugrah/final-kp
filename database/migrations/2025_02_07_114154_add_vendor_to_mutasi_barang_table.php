<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('mutasi_barang', function (Blueprint $table) {
            $table->string('vendor')->nullable()->after('jumlah'); // Menambahkan kolom vendor
        });
    }

    public function down(): void
    {
        Schema::table('mutasi_barang', function (Blueprint $table) {
            $table->dropColumn('vendor');
        });
    }
};
