<?php

namespace App\Filament\Widgets;

use App\Models\Pr;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class PrSudahDisetujuiWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected function getTableHeading(): string
    {
        return 'PR Sudah Disetujui Direktur';
    }

    protected function getTableQuery(): Builder
    {
        return Pr::query()
            ->where('direktur_status', 'disetujui') // ✅ Hanya yang sudah disetujui Direktur
            ->where(function ($query) {
                $query->where('checker_1_status', 'disetujui')
                      ->orWhere('checker_2_status', 'disetujui');
            });
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('no_pr')->label('No PR')->sortable(),
            Tables\Columns\TextColumn::make('created_at')->label('Tanggal Pengajuan')->date(),
        ];
    }

    // ✅ Gunakan `canView()` agar hanya role tertentu yang bisa melihat widget ini
    public static function canView(): bool
    {
        return Auth::user()->hasRole(['purchase', 'admin', 'superadmin']);
    }
}
