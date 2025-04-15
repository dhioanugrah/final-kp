<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-s-user';

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

        return in_array($userRole, ['superadmin','admin','direktur' ]);
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label('Name')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('email')
                ->label('Email')
                ->required()
                ->email()
                ->unique(ignoreRecord: true), // Supaya nggak error saat update

                Forms\Components\TextInput::make('raw_password')
                ->label('Password Saat Ini')
                ->disabled()
                ->dehydrated(false),

                Forms\Components\TextInput::make('new_password')
                ->label('Password Baru')
                ->password()
                ->maxLength(255)
                ->dehydrated(false) // supaya field ini gak langsung dikirim ke DB
                ->default(null)
                ->live(onBlur: true)
                ->extraAttributes(['autocomplete' => 'new-password'])
                ->afterStateUpdated(function ($state, callable $set) {
                    if (!empty($state)) {
                        $set('password', bcrypt($state));
                        $set('raw_password', $state); // 👈 ini menyimpan password asli ke kolom
                    }
                })
                ->required(fn (string $context): bool => $context === 'create'),




                Forms\Components\Hidden::make('password'),
                Forms\Components\Hidden::make('raw_password'),



        ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->query(
                User::whereDoesntHave('roles', function ($query) {
                    $query->where('name', 'superadmin');
                })
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('email')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('raw_password')
                ->label('Password')
                ->copyable() // Optional: biar bisa dicopy\

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
