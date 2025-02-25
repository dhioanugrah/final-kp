<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MutasiBarangResource\Pages;
use App\Models\MutasiBarang;
use App\Models\Barang;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter; // Import Filter

class MutasiBarangResource extends Resource
{
    protected static ?string $model = MutasiBarang::class;

    protected static ?string $navigationIcon = 'heroicon-s-currency-dollar';

    protected static ?string $pluralLabel = 'Mutasi Barang Keluar';
    protected static ?string $navigationLabel = 'Barang Keluar';

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

        return in_array($userRole, ['superadmin','warehouse' ]);
    }


    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
        ->schema([
            Forms\Components\Select::make('kode_barang')
            ->label('Kode Barang')
            ->searchable()
            ->options(fn () => Barang::pluck('kode_barang', 'kode_barang'))
            ->required()
            ->reactive()
            ->afterStateUpdated(function ($state, callable $set) {
                if ($state) {
                    $barang = Barang::where('kode_barang', $state)->first();

                    if ($barang) {
                        $set('barang_info', "{$barang->nama_barang} | {$barang->merk} | {$barang->ukuran} | {$barang->part_number}");
                        $set('max_stok', $barang->stok);
                    } else {
                        $set('barang_info', 'Barang tidak ditemukan');
                        $set('max_stok', 0);
                    }
                } else {
                    $set('barang_info', '');
                    $set('max_stok', 0);
                }
            })
            ,

            Forms\Components\TextInput::make('barang_info')
                ->label('Nama Barang | Merk | Ukuran | Part Number')

                ->disabled()
                ->live(),

            Forms\Components\Hidden::make('max_stok'),

            Forms\Components\DatePicker::make('tanggal')
                ->required(),

            Forms\Components\TextInput::make('jumlah')
                ->label('Jumlah')
                ->required()
                ->numeric()
                ->minValue(1)
                ->step(1)
                ->rule(function (callable $get) {
                    $stok = $get('max_stok') ?? 0;
                    return "max:$stok";
                }, 'Jumlah keluar tidak boleh lebih dari stok tersedia'),

            Forms\Components\TextInput::make('pengguna')
                ->label('Pengguna')
                ->required()
                ->maxLength(255),

            Forms\Components\Textarea::make('keterangan')
                ->label('Keterangan')
                ->nullable(),

            Forms\Components\Hidden::make('jenis')->default('output'),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('barang.nama_barang')->label('Nama Barang')->sortable()->searchable(),
                TextColumn::make('barang.merk')->label('Merk')->sortable()->searchable(),
                TextColumn::make('barang.ukuran')->label('Ukuran')->sortable()->searchable(),
                TextColumn::make('barang.part_number')->label('Part Number')->sortable()->searchable(),
                TextColumn::make('tanggal')->sortable(),
                TextColumn::make('jumlah')->sortable(),
                TextColumn::make('pengguna')->label('Pengguna')->sortable()->searchable(),
                TextColumn::make('keterangan')->limit(50),
            ])
            ->filters([
                Filter::make('tanggal') // Filter untuk tanggal
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('from')->label('Dari Tanggal'),
                        \Filament\Forms\Components\DatePicker::make('to')->label('Sampai Tanggal'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn ($q) => $q->where('tanggal', '>=', $data['from']))
                            ->when($data['to'], fn ($q) => $q->where('tanggal', '<=', $data['to']));
                    }),
            ])
            ->defaultSort('tanggal', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMutasiBarangs::route('/'),
            'create' => Pages\CreateMutasiBarang::route('/create'),
        ];
    }
}
