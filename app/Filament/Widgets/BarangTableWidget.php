<?php

namespace App\Filament\Widgets;

use App\Models\Pr;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class BarangTableWidget extends BaseWidget
{
    protected static ?int $sort = 1; // Urutan widget di dashboard

    protected function getTableHeading(): string
    {
        return 'PR Sedang Diproses';
    }

    protected function getTableQuery(): Builder
    {
        return Pr::query()
            ->where('direktur_status', 'disetujui') // Hanya yang sudah disetujui Direktur
            ->whereHas('prDetails', function ($query) {
                $query->whereRaw(
                    '(SELECT COALESCE(SUM(jumlah_diterima), 0) FROM penerimaan_barang WHERE penerimaan_barang.pr_detail_id = pr_details.id) < pr_details.jumlah_diajukan'
                );
            }); // Masih dalam proses penerimaan
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('no_pr')
                ->label('No PR')
                ->sortable()
                ->url(fn ($record) => \App\Filament\Resources\PrResource::getUrl('cek-pengajuan', ['record' => $record->id])), // ini link ke detail PR

            Tables\Columns\TextColumn::make('created_at')
                ->label('Tanggal Pengajuan')
                ->date(),
        ];
    }

    protected function getTableActions(): array
{
    return [
        Tables\Actions\Action::make('Detail')
            ->label('Lihat Detail')
            ->icon('heroicon-o-eye')
            ->url(fn ($record) => \App\Filament\Resources\PrResource::getUrl('cek-pengajuan', ['record' => $record->id])),
    ];
}


    // ✅ Gunakan canView() untuk menyembunyikan tabel jika user tidak memiliki role yang diizinkan
    public static function canView(): bool
    {
        return true;
    }
}
