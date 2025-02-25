<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('prs', function (Blueprint $table) {
            $table->enum('checker_1_status', ['pending', 'disetujui', 'ditolak'])->default('pending')->after('request_by');
            $table->enum('checker_2_status', ['pending', 'disetujui', 'ditolak'])->default('pending')->after('checker_1_status');
            $table->enum('direktur_status', ['pending', 'disetujui', 'ditolak'])->default('pending')->after('checker_2_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prs', function (Blueprint $table) {
            $table->dropColumn(['checker_1_status', 'checker_2_status', 'direktur_status']);
        });
    }
};
