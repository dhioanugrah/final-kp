<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration {
    public function up(): void
    {
        // Update password admin
        DB::table('users')
            ->where('email', 'admin@hit.com')
            ->update([
                'password' => Hash::make('hidrolik2025'),
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        // Rollback ke password lama (opsional, bisa dihapus kalau tidak perlu)
        DB::table('users')
            ->where('email', 'admin@hit.com')
            ->update([
                'password' => Hash::make('Minimal8'),
                'updated_at' => now(),
            ]);
    }
};
