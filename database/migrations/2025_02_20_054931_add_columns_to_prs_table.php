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
            $table->string('no_pr')->unique()->after('id');
            $table->date('tanggal_diajukan')->nullable()->after('no_pr');
            $table->string('required_for')->nullable()->after('tanggal_diajukan');
            $table->string('request_by')->nullable()->after('required_for');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prs', function (Blueprint $table) {
            $table->dropColumn(['no_pr', 'tanggal_diajukan', 'required_for', 'request_by']);
        });
    }
};
