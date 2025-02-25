<?php

namespace App\Filament\Widgets;

use App\Models\Pr;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables;
use Filament\Tables\Table;

class BarangTableWidget extends BaseWidget
{
    protected static ?int $sort = 1; // Urutan widget di dashboard

    protected function getTableHeading(): string
    {
        return 'PR on going';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Pr::where('direktur_status', '!=', 'disetujui')
                    ->whereHas('prDetails', function ($query) {
                        $query->whereRaw(
                            '(SELECT COALESCE(SUM(jumlah_diterima), 0) FROM penerimaan_barang WHERE penerimaan_barang.pr_detail_id = pr_details.id) < pr_details.jumlah_diajukan'
                        );
                    })
            )
            ->columns([
                Tables\Columns\TextColumn::make('no_pr')->label('No PR')->sortable(),
            ])
            ->defaultSort('id', 'desc')
            ->striped();
    }
}

