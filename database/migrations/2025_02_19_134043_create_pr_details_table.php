<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('pr_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pr_id')->constrained('prs')->onDelete('cascade');
            $table->string('kode_barang');
            $table->integer('jumlah_diajukan');
            $table->timestamps();

            // Jika kode_barang merupakan foreign key
            $table->foreign('kode_barang')->references('kode_barang')->on('barangs')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pr_details');
    }
};
