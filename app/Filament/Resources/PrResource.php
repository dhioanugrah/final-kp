<?php

    namespace App\Filament\Resources;

    use App\Filament\Resources\PrResource\Pages;
    use App\Models\Pr;
    use Filament\Forms;
    use Filament\Forms\Form;
    use Filament\Resources\Resource;
    use Filament\Tables;
    use Filament\Forms\Components\Section;
    use Filament\Forms\Components\Grid;
    use Filament\Forms\Components\TextEntry;
    use Filament\Forms\Components\Group;
    use App\Filament\Resources\PrResource\RelationManagers\BarangRelationManager;
    use App\Filament\Resources\PrResource\RelationManagers\BarangPengajuanRelationManager;
    use Filament\Tables\Table;

    class PrResource extends Resource
    {

        protected static ?string $navigationIcon = 'heroicon-s-archive-box';
        protected static ?string $pluralLabel = 'Process Request'; // Nama di sidebar dan halaman
        protected static ?string $navigationLabel = 'Process Request'; // Nama di sidebar
        protected static ?string $model = Pr::class;

        public static function shouldRegisterNavigation(): bool
        {
            $user = auth()->user();

            if (!$user) {
                return false;
            }

            $userRole = \DB::table('roles')
                ->join('model_has_roles', 'roles.id', '=', 'model_has_roles.role_id')
                ->where('model_has_roles.model_id', $user->id)
                ->value('roles.name');

            return in_array($userRole, ['superadmin','admin', 'checker_1', 'checker_2', 'direktur', 'warehouse', 'purchase']);
        }



        public static function getRelations(): array
        {
            return [
                BarangRelationManager::class, // Pastikan RelationManager sudah didaftarkan
                BarangPengajuanRelationManager::class,

            ];
        }

        public static function form(Form $form): Form
        {
            return $form->schema([
                Forms\Components\TextInput::make('no_pr')
                ->label('No PR')
                ->disabled() // Supaya user tidak bisa edit/input
                ->dehydrated(false) // Supaya tidak dikirim ke backend saat submit
                ->default(fn () => \App\Models\Pr::generateNoPr()), // Generate otomatis
                Forms\Components\DatePicker::make('tanggal_diajukan')
                    ->required(),
                Forms\Components\TextInput::make('required_for')
                    ->required(),
                Forms\Components\TextInput::make('request_by')
                    ->required(),
            ]);
        }

        public static function table(Table $table): Table
        {
            return $table
                ->columns([



                    Tables\Columns\TextColumn::make('status_penerimaan')
                    ->label('Status Penerimaan')
                    ->getStateUsing(fn ($record) => $record->status_penerimaan)
                    ->sortable(),







                    Tables\Columns\TextColumn::make('no_pr')->sortable()->searchable(),
                    Tables\Columns\TextColumn::make('tanggal_diajukan')->sortable(),
                    Tables\Columns\TextColumn::make('required_for')->sortable(),
                    Tables\Columns\TextColumn::make('request_by')->sortable(),
                    Tables\Columns\BadgeColumn::make('checker_1_status')
                        ->label('Checker 1')
                        ->formatStateUsing(fn ($record) => $record->checker_1_status)
                        ->color(fn ($record) => $record->checker_1_status === 'pending' ? 'danger' : 'success'),

                    Tables\Columns\BadgeColumn::make('checker_2_status')
                        ->label('Checker 2')
                        ->formatStateUsing(fn ($record) => $record->checker_2_status)
                        ->color(fn ($record) => $record->checker_2_status === 'pending' ? 'danger' : 'success'),

                    Tables\Columns\BadgeColumn::make('direktur_status')
                        ->label('Direktur')
                        ->formatStateUsing(fn ($record) => $record->direktur_status)
                        ->color(fn ($record) => $record->direktur_status === 'pending' ? 'danger' : 'success'),
                ])

                ->actions([

                    Tables\Actions\Action::make('approval_checker_1')
                    ->label('approved')
                    ->hidden(fn () => !auth()->user()->hasRole('checker_1'))
                    ->requiresConfirmation() // Tambahkan konfirmasi
                    ->modalHeading('Konfirmasi Approval')
                    ->modalDescription('Apakah kamu yakin ingin menyetujui PR ini?')
                    ->modalButton('Ya, Setujui')
                    ->action(function ($record) {
                        $record->update(['checker_1_status' => 'disetujui']);
                    })
                    ->color('success'),

                    Tables\Actions\Action::make('approval_checker_2')
                    ->label('approved')
                    ->hidden(fn () => !auth()->user()->hasRole('checker_2'))
                    ->requiresConfirmation() // Tambahkan konfirmasi
                    ->modalHeading('Konfirmasi Approval')
                    ->modalDescription('Apakah kamu yakin ingin menyetujui PR ini?')
                    ->modalButton('Ya, Setujui')
                    ->action(function ($record) {
                        $record->update(['checker_2_status' => 'disetujui']);
                    })
                    ->color('success'),

                    Tables\Actions\Action::make('approval_direktur')
                    ->label('approved')
                    ->hidden(fn ($record) => !auth()->user()->hasRole('direktur') ||
                    ($record->checker_1_status !== 'disetujui' && $record->checker_2_status !== 'disetujui'))
                 // Direktur hanya bisa approve jika checker sudah setuju
                    ->requiresConfirmation() // Tambahkan konfirmasi
                    ->modalHeading('Konfirmasi Approval Direktur')
                    ->modalDescription('Apakah kamu yakin ingin menyetujui PR ini sebagai Direktur?')
                    ->modalButton('Ya, Setujui')
                    ->action(function ($record) {
                        $record->update(['direktur_status' => 'disetujui']);
                    })
                    ->color('success'),



                    Tables\Actions\Action::make('Tambah Barang')
                    ->icon('heroicon-o-plus-circle')
                    ->modalHeading('Tambah Barang ke PR')
                    ->modalButton('Simpan')
                    ->modalWidth('md')
                    ->hidden(fn () => !auth()->user()->hasRole(['warehouse','admin','superadmin'])) // ❗ Hanya tampil jika user role 'warehouse'
                    ->disabled(fn (Pr $record) => $record->direktur_status === 'disetujui') // ✅ Cek langsung di model `Pr`
                    ->action(function (Pr $record, $data) { // ✅ Gunakan Pr, bukan Model
                        \App\Models\PrDetail::create([
                            'pr_id' => $record->id, // Pastikan ini sesuai dengan relasi yang benar
                            'kode_barang' => $data['kode_barang'],
                            'jumlah_diajukan' => $data['jumlah_diajukan'],
                        ]);

                        // ✅ Notifikasi sukses!
                        \Filament\Notifications\Notification::make()
                            ->title('Barang berhasil ditambahkan!')
                            ->success()
                            ->send();
                    })
                    ->form([
                        Forms\Components\Select::make('kode_barang')
                            ->label('Kode Barang')
                            ->options(\App\Models\Barang::all()->pluck('kode_barang', 'kode_barang'))
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $barang = \App\Models\Barang::where('kode_barang', $state)->first();
                                    if ($barang) {
                                        $set('barang_info', "{$barang->nama_barang} | {$barang->merk} | {$barang->ukuran} | {$barang->part_number}");
                                    } else {
                                        $set('barang_info', 'Data barang tidak ditemukan');
                                    }
                                }
                            }),

                        Forms\Components\TextInput::make('barang_info')
                            ->label('Nama Barang | Merk | Ukuran | Part Number')
                            ->disabled()
                            ->live(),

                        Forms\Components\TextInput::make('jumlah_diajukan')
                            ->label('Jumlah Diajukan')
                            ->numeric()
                            ->required(),
                        ]),


                        Tables\Actions\Action::make('print')
                        ->label('Print PDF')
                        ->icon('heroicon-o-printer')
                        ->url(fn ($record) => url("/pr/{$record->id}/print"))
                        ->openUrlInNewTab(),



                Tables\Actions\Action::make('Detail')
                ->icon('heroicon-o-eye')
                ->button()
                ->color('primary')
                ->url(fn ($record) => PrResource::getUrl('cek-pengajuan', ['record' => $record->id]))
                ]);
        }

        public static function getPages(): array
        {
            return [
                'index' => Pages\ListPrs::route('/'),
                'create' => Pages\CreatePr::route('/create'),
                'edit' => Pages\EditPr::route('/{record}/edit'),
                'cek-pengajuan' => Pages\CekPengajuan::route('/{record}/cek-pengajuan'),
                'cek-detail' => Pages\CekDetail::route('/{record}/cek-detail'),

            ];
        }

    }
