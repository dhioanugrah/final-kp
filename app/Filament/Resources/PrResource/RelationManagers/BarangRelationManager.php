<?php

namespace App\Filament\Resources\PrResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use App\Models\PrDetail;
use Filament\Notifications\Notification;


class BarangRelationManager extends RelationManager
{
    protected static string $relationship = 'prDetails';

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_barang')
                    ->label('Kode Barang'),
                Tables\Columns\TextColumn::make('barang.nama_barang')
                    ->label('Nama Barang'),
                Tables\Columns\TextColumn::make('barang.merk')
                    ->label('Merk'),
                Tables\Columns\TextColumn::make('barang.ukuran')
                    ->label('Ukuran'),
                Tables\Columns\TextColumn::make('barang.part_number')
                    ->label('Part Number'),
                Tables\Columns\TextColumn::make('jumlah_diajukan')
                    ->label('Jumlah Diajukan'),
            ])

            ->actions([


                // ✅ Aksi Hapus Barang
                Tables\Actions\Action::make('Hapus Barang')
                    ->icon('heroicon-o-trash')
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi Hapus')
                    ->modalButton('Ya, Hapus')
                    ->modalWidth('sm')
                    ->action(function (Model $record) {
                        PrDetail::where('kode_barang', $record->kode_barang)
                            ->where('kode_barang', $record->kode_barang)
                            ->delete();

                        Notification::make()
                            ->title('Barang berhasil dihapus!')
                            ->success()
                            ->send();
                    }),
            ]);
    }
}

