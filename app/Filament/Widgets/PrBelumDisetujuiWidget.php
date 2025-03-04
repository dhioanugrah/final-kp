<?php

namespace App\Filament\Widgets;

use App\Models\Pr;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class PrBelumDisetujuiWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getTableHeading(): string
    {
        return 'PR Belum Disetujui Direktur';
    }

    protected function getTableQuery(): Builder
    {
        return Pr::query()
            ->where(function ($query) {
                $query->where('checker_1_status', 'disetujui')
                      ->orWhere('checker_2_status', 'disetujui');
            })
            ->where('direktur_status', '!=', 'disetujui');
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('no_pr')->label('No PR')->sortable(),
            Tables\Columns\TextColumn::make('checker_1_status')->label('Checker 1')->sortable(),
            Tables\Columns\TextColumn::make('checker_2_status')->label('Checker 2')->sortable(),
            Tables\Columns\TextColumn::make('direktur_status')->label('Status Direktur')->sortable(),
        ];
    }

    // ✅ Membatasi hanya Direktur, Admin, dan Superadmin yang bisa melihat
    public static function canView(): bool
    {
        return Auth::user()->hasRole(['direktur', 'admin', 'superadmin']);
    }
}
