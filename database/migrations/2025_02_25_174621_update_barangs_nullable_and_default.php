<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            $table->string('merk')->nullable()->change();
            $table->string('ukuran')->nullable()->change();
            $table->string('part_number')->nullable()->change();
            $table->string('satuan')->nullable()->change();
            $table->integer('stok')->default(0)->change();
        });
    }

    public function down(): void
    {
        Schema::table('barangs', function (Blueprint $table) {
            $table->string('merk')->nullable(false)->change();
            $table->string('ukuran')->nullable(false)->change();
            $table->string('part_number')->nullable(false)->change();
            $table->string('satuan')->nullable(false)->change();
            $table->integer('stok')->default(null)->change();
        });
    }
};
