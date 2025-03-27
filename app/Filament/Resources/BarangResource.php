<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BarangResource\Pages;
use App\Filament\Resources\BarangResource\RelationManagers;
use App\Models\Barang;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BarangResource extends Resource
{
    protected static ?string $model = Barang::class;

    protected static ?string $navigationIcon = 'heroicon-s-archive-box';
    protected static ?string $navigationLabel = 'Stok Barang';
    protected static ?string $pluralLabel = 'Stok Barang';

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

        return in_array($userRole, ['superadmin','admin', 'warehouse', 'checker_1', 'checker_2', 'direktur', 'purchase']);
    }



    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('kode_barang')
                ->label('Kode Barang')
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(50),

            Forms\Components\TextInput::make('nama_barang')
                ->label('Nama Barang')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('merk')
                ->label('Merk')
                ->maxLength(100),

            Forms\Components\TextInput::make('ukuran')
                ->label('Ukuran')
                ->maxLength(50),

            Forms\Components\TextInput::make('part_number')
                ->label('Part Number')
                ->maxLength(50),

            Forms\Components\TextInput::make('satuan')
                ->label('Satuan')
                ->required()
                ->maxLength(20),

            Forms\Components\TextInput::make('stok')
                ->label('Stok')
                ->numeric()
                ->minValue(0)
                ->required()
                ->default(0),

            Forms\Components\TextInput::make('min_stok')
                ->label('Minimal Stok')
                ->numeric()
                ->minValue(0)
                ->required()
                ->default(5),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_barang')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('nama_barang')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('merk')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('ukuran')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('part_number')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('satuan'),
                Tables\Columns\TextColumn::make('stok')
                    ->sortable()
                    ->label('Stok')
                    ->badge()
                    ->color(fn ($record) => $record->stok <= $record->min_stok ? 'danger' : 'success')
                    ->formatStateUsing(fn ($record) => $record->stok <= $record->min_stok ? "{$record->stok} ⚠️" : $record->stok),

                Tables\Columns\TextColumn::make('min_stok')
                    ->label('Minimal Stok')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->headerActions([
                Tables\Actions\Action::make('Export PDF')
                    ->label('Print PDF')
                    ->url(fn () => route('barang.stok.pdf'))
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-printer'),
            ])
            ->defaultSort(fn ($query) =>
                $query->orderByRaw('(stok <= min_stok) DESC')->orderBy('stok', 'asc') // Menampilkan stok di bawah min_stok di atas
            );
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBarangs::route('/'),
            'create' => Pages\CreateBarang::route('/create'),
            'edit' => Pages\EditBarang::route('/{record}/edit'),
        ];
    }
}
