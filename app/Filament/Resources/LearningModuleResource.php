<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LearningModuleResource\Pages;
use App\Models\LearningModule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class LearningModuleResource extends Resource
{
    protected static ?string $model = LearningModule::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open'; // Icon Buku
    protected static ?string $navigationGroup = 'Content Management';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // --- KOLOM KIRI (INFORMASI UTAMA) ---
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Detail Modul')
                            ->schema([
                                Forms\Components\Select::make('topic_id')
                                    ->label('Topik Bencana')
                                    ->relationship('topic', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Forms\Components\TextInput::make('title')
                                    ->label('Judul Modul')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),

                                Forms\Components\TextInput::make('slug')
                                    ->required()
                                    ->readOnly()
                                    ->maxLength(255),

                                Forms\Components\Textarea::make('description')
                                    ->label('Ringkasan Isi')
                                    ->rows(5)
                                    ->columnSpanFull(),
                            ]),
                    ])->columnSpan(2),

                // --- KOLOM KANAN (UPLOAD & STATUS) ---
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('File & Media')
                            ->schema([
                                // Upload Cover Gambar
                                Forms\Components\FileUpload::make('cover_image')
                                    ->label('Cover Buku')
                                    ->image() // Hanya gambar
                                    ->directory('module-covers')
                                    ->maxSize(2048), // Max 2MB

                                // Upload File PDF
                                Forms\Components\FileUpload::make('file_path')
                                    ->label('File Dokumen (PDF)')
                                    ->acceptedFileTypes(['application/pdf']) // Wajib PDF
                                    ->directory('learning-modules')
                                    ->required()
                                    ->maxSize(20480) // Max 20MB
                                    ->downloadable() // Admin bisa download lagi buat ngecek
                                    ->helperText('Format PDF. Maksimal 20MB.'),

                                Forms\Components\Toggle::make('is_featured')
                                    ->label('Rekomendasi Utama')
                                    ->default(false)
                                    ->onColor('success'),
                            ]),
                    ])->columnSpan(1),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Tampilkan Cover Kecil
                Tables\Columns\ImageColumn::make('cover_image')
                    ->label('Cover')
                    ->height(60),

                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Modul')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->limit(40),

                Tables\Columns\TextColumn::make('topic.name')
                    ->label('Topik')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                // Indikator File Ada/Tidak
                Tables\Columns\IconColumn::make('file_path')
                    ->label('PDF')
                    ->boolean()
                    ->trueIcon('heroicon-o-document-check')
                    ->falseIcon('heroicon-o-x-circle')
                    ->color(fn ($state) => $state ? 'success' : 'danger'),

                Tables\Columns\IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('topic')
                    ->relationship('topic', 'name')
                    ->label('Filter Topik'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                // Tombol Download langsung di Tabel
                Tables\Actions\Action::make('download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn (LearningModule $record) => \Illuminate\Support\Facades\Storage::url($record->file_path))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLearningModules::route('/'),
            'create' => Pages\CreateLearningModule::route('/create'),
            'edit' => Pages\EditLearningModule::route('/{record}/edit'),
        ];
    }
}