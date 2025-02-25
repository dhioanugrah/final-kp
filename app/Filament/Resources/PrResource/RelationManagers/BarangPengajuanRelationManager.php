<?php

namespace App\Filament\Resources\PrResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use App\Models\PrDetail;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;

class BarangPengajuanRelationManager extends RelationManager
{
    protected static string $relationship = 'prDetails';
    protected static ?string $title = 'Cek Pengajuan'; // Ubah judul tab
    public static function getRelationName(): string
    {
        return 'prDetails';
    }


    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('kode_barang')->label('Kode Barang'),
                TextColumn::make('barang.nama_barang')->label('Nama Barang'),
                TextColumn::make('barang.merk')->label('Merk'),
                TextColumn::make('barang.ukuran')->label('Ukuran'),
                TextColumn::make('barang.part_number')->label('Part Number'),
                TextColumn::make('jumlah_diajukan')->label('Jumlah Diajukan'),

                // ✅ Menampilkan jumlah total barang yang sudah diterima
                TextColumn::make('jumlah_diterima')
                    ->label('Jumlah Diterima')
                    ->getStateUsing(fn (Model $record) => $record->penerimaan()->sum('jumlah_diterima')),

                // ✅ Menampilkan jumlah sisa = jumlah_diajukan - total jumlah_diterima dari tabel penerimaan_barang
                TextColumn::make('jumlah_sisa')
                    ->label('Jumlah Sisa')
                    ->getStateUsing(fn (Model $record) => $record->jumlah_diajukan - $record->penerimaan()->sum('jumlah_diterima')),
            ])
            ->actions([
                Action::make('Terima Barang')
                    ->icon('heroicon-o-check')
                    ->requiresConfirmation()
                    ->modalHeading('Konfirmasi Terima Barang')
                    ->modalButton('Ya, Terima Barang')
                    ->modalWidth('sm')
                    ->form([
                        TextInput::make('vendor')
                            ->label('Vendor')
                            ->required(),

                        TextInput::make('jumlah_diterima')
                            ->label('Jumlah Diterima')
                            ->numeric()
                            ->minValue(1)
                            ->required(),

                        DatePicker::make('tanggal_diterima')
                            ->label('Tanggal Diterima')
                            ->default(now())
                            ->required(),
                    ])
                    ->action(function (Model $record, array $data) {
                        $jumlahSisa = $record->jumlah_diajukan - $record->penerimaan()->sum('jumlah_diterima');

                        if ($jumlahSisa <= 0) {
                            Notification::make()
                                ->title('Semua barang sudah diterima!')
                                ->danger()
                                ->send();
                            return;
                        }

                        \App\Models\PenerimaanBarang::create([
                            'pr_detail_id' => $record->id,
                            'vendor' => $data['vendor'],
                            'jumlah_diterima' => $data['jumlah_diterima'],
                            'tanggal_diterima' => $data['tanggal_diterima'],
                        ]);

                        $barang = \App\Models\Barang::where('kode_barang', $record->kode_barang)->first();
                        if ($barang) {
                            $barang->increment('stok', $data['jumlah_diterima']);
                        }

                        Notification::make()
                            ->title('Barang berhasil diterima!')
                            ->success()
                            ->send();
                    })
                    ->disabled(fn (Model $record) => ($record->jumlah_diajukan - $record->penerimaan()->sum('jumlah_diterima')) <= 0)
                    ->hidden(fn () => !auth()->user()->hasRole(['warehouse','admin','superadmin'])), // ✅ Sembunyikan jika bukan warehouse

                Action::make('Detail')
                    ->icon('heroicon-o-information-circle')
                    ->modalHeading('Detail Penerimaan Barang')
                    ->modalWidth('lg')
                    ->modalSubmitAction(false)
                    ->modalCancelAction(false)
                    ->modalContent(fn (Model $record) => view('filament.modals.detail_barang', [
                        'penerimaan' => $record->penerimaan
                    ])),
            ]);
    }
}
