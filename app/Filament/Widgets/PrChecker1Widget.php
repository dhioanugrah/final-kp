<?php

namespace App\Filament\Widgets;

use App\Models\Pr;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class PrChecker1Widget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getTableHeading(): string
    {
        return 'PR untuk Persetujuan Checker 1';
    }

    // ✅ Menggunakan getTableQuery() untuk menghindari error
    protected function getTableQuery(): Builder
    {
        return Pr::query()
            ->where(function ($query) {
                $query->whereNull('checker_1_status')
                      ->orWhere('checker_1_status', '!=', 'disetujui');
            })
            ->where('direktur_status', '!=', 'disetujui');
    }

    // ✅ Menggunakan getTableColumns()
    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('no_pr')->label('No PR')->sortable(),
            Tables\Columns\TextColumn::make('created_at')->label('Tanggal Pengajuan')->date(),
            Tables\Columns\BadgeColumn::make('checker_1_status')
                ->label('Status Checker 1')
                ->colors([
                    'pending' => 'gray',
                    'disetujui' => 'success',
                    'ditolak' => 'danger',
                ]),
        ];
    }

    // ✅ Menggunakan canView() agar hanya checker 1, admin, dan superadmin yang bisa melihat tabel
    public static function canView(): bool
    {
        return Auth::user()->hasRole(['checker_1', 'admin', 'superadmin']);
    }
}
