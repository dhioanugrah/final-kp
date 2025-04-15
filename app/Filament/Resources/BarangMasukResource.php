<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BarangMasukResource\Pages;
use App\Models\PenerimaanBarang;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action as FormAction;

class BarangMasukResource extends Resource
{
    protected static ?string $model = PenerimaanBarang::class;

    protected static ?string $navigationIcon = 'heroicon-s-currency-dollar';
    protected static ?string $navigationLabel = 'Barang Diterima';
    protected static ?string $pluralLabel = 'Barang Diterima';

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();

        if (!$user) {
            return false;
        }

        // Ambil role user dari tabel roles
        $userRole = \DB::table('roles')
            ->join('model_has_roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('model_has_roles.model_id', $user->id)
            ->value('roles.name');

        return in_array($userRole, ['superadmin', 'admin','warehouse']);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('prDetail.pr.no_pr')
                    ->label('No PR')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('prDetail.kode_barang')
                    ->label('Kode Barang')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('prDetail.barang.nama_barang')
                    ->label('Nama Barang')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('prDetail.barang.merk')
                    ->label('Merk')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('prDetail.barang.ukuran')
                    ->label('Ukuran')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('prDetail.barang.part_number')
                    ->label('Part Number')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('vendor')
                    ->label('Vendor')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('jumlah_diterima')->label('Jumlah Diterima'),

                TextColumn::make('tanggal_diterima')
                    ->label('Tanggal Diterima')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                Filter::make('tanggal_diterima')
                    ->form([
                        DatePicker::make('from')->label('Dari Tanggal'),
                        DatePicker::make('to')->label('Sampai Tanggal'),

                        // Tombol refresh manual
                        Actions::make([
                            FormAction::make('refreshPage')
                                ->label('Terapkan Tanggal')
                                ->color('gray')
                                ->button()
                                ->extraAttributes([
                                    'x-data' => '{}',
                                    'x-on:click' => 'window.location.reload()',
                                ]),
                        ]),
                    ])
                    ->indicateUsing(function (array $data) {
                        return $data['from'] || $data['to']
                            ? 'Tanggal dipilih'
                            : null;
                    })
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn ($q) => $q->where('tanggal_diterima', '>=', $data['from']))
                            ->when($data['to'], fn ($q) => $q->where('tanggal_diterima', '<=', $data['to']));
                    }),
            ])
            ->defaultSort('tanggal_diterima', 'desc');
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBarangMasuk::route('/'),
        ];
    }
}
