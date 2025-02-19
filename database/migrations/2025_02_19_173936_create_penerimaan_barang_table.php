<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('penerimaan_barang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pr_detail_id')->constrained('pr_details')->onDelete('cascade'); // Hubungkan ke pr_details
            $table->integer('jumlah_diterima');
            $table->string('vendor');
            $table->date('tanggal_diterima');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penerimaan_barang');
    }
};
