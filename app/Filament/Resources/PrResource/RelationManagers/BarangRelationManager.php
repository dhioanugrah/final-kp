<?php

namespace App\Filament\Resources\PrResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use App\Models\PrDetail;
use Filament\Forms;
use Filament\Forms\Components\TextInput;

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
                Tables\Actions\Action::make('Edit')
                ->icon('heroicon-o-pencil')
                ->form([
                    TextInput::make('jumlah_diajukan_sebelumnya')
                        ->label('Jumlah Diajukan Sebelumnya')
                        ->default(fn (Model $record) => $record->jumlah_diajukan)
                        ->disabled(), // Biar nggak bisa diedit

                    TextInput::make('jumlah_diajukan')
                        ->label('Ubah Jumlah Diajukan')
                        ->numeric()
                        ->minValue(1)
                        ->required(),
                ])
                ->action(function (Model $record, array $data) {
                    $record->update([
                        'jumlah_diajukan' => $data['jumlah_diajukan'],
                    ]);

                    Notification::make()
                        ->title('Jumlah Diajukan berhasil diubah!')
                        ->success()
                        ->send();
                })
                ->hidden(fn () => !auth()->user()->hasRole(['warehouse','admin','superadmin']))
                ->disabled(fn (Model $record) => \DB::table('prs')
                ->where('id', $record->pr_id) // Sesuaikan dengan foreign key yang menghubungkan `pr_details` dengan `prs`
                ->value('direktur_status') === 'disetujui'),



                Tables\Actions\Action::make('Hapus')
                ->icon('heroicon-o-trash')
                ->requiresConfirmation()
                ->modalHeading('Konfirmasi Hapus')
                ->modalButton('Ya, Hapus')
                ->modalWidth('sm')
                ->action(function (Model $record) {
                    PrDetail::where('kode_barang', $record->kode_barang)
                        ->delete();

                    Notification::make()
                        ->title('Barang berhasil dihapus!')
                        ->success()
                        ->send();
                })
                ->hidden(fn () => !auth()->user()->hasRole(['warehouse','admin','superadmin']))
                ->disabled(fn (Model $record) => \DB::table('prs')
                    ->where('id', $record->pr_id) // Sesuaikan dengan foreign key yang menghubungkan `pr_details` dengan `prs`
                    ->value('direktur_status') === 'disetujui'),
            ]);
    }
}

