<?php

namespace App\Filament\Widgets;

use App\Models\Pr;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class PrChecker2Widget extends BaseWidget
{
    protected static ?int $sort = 2; // Urutan widget di dashboard

    protected function getTableHeading(): string
    {
        return 'PR untuk Persetujuan Checker 2';
    }

    // ✅ Query untuk menampilkan PR yang belum disetujui oleh Checker 2
    protected function getTableQuery(): Builder
    {
        return Pr::query()
            ->where(function ($query) {
                $query->whereNull('checker_2_status') // Jika null, berarti belum ada keputusan
                      ->orWhere('checker_2_status', '!=', 'disetujui');
            })
            ->where('direktur_status', '!=', 'disetujui'); // Hilang setelah Direktur menyetujui
    }

    // ✅ Kolom untuk ditampilkan di tabel
    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('no_pr')->label('No PR')->sortable(),
            Tables\Columns\TextColumn::make('created_at')->label('Tanggal Pengajuan')->date(),
            Tables\Columns\BadgeColumn::make('checker_2_status')
                ->label('Status Checker 2')
                ->colors([
                    'pending' => 'gray',
                    'disetujui' => 'success',
                    'ditolak' => 'danger',
                ]),
        ];
    }

    // ✅ Gunakan `canView()` agar hanya Checker 2, Admin, dan Superadmin yang bisa melihat tabel
    public static function canView(): bool
    {
        return Auth::user()->hasRole(['checker_2', 'admin', 'superadmin']);
    }
}
