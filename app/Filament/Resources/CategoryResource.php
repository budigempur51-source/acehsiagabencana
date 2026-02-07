<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Manajemen Konten';
    protected static ?string $navigationLabel = 'Kategori Bencana';
    protected static ?int $navigationSort = 1;

    /**
     * Menampilkan Badge jumlah data di Sidebar
     */
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // SECTION KIRI: Informasi Utama
                Forms\Components\Section::make('Informasi Kategori')
                    ->description('Data dasar kategori bencana.')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Kategori')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $operation, $state, $set) => 
                                $operation === 'create' ? $set('slug', Str::slug($state)) : null
                            ),

                        Forms\Components\TextInput::make('slug')
                            ->label('URL Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->disabled()
                            ->dehydrated()
                            ->helperText('Otomatis dibuat dari nama kategori.'),

                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi Singkat')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpan(2), // Ambil 2/3 layar

                // SECTION KANAN: Atribut Tambahan
                Forms\Components\Section::make('Atribut & Status')
                    ->schema([
                        Forms\Components\FileUpload::make('icon')
                            ->label('Ikon / Ilustrasi')
                            ->image()
                            ->directory('categories')
                            ->imageEditor()
                            ->columnSpanFull(),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktifkan Kategori')
                            ->default(true)
                            ->helperText('Jika non-aktif, kategori ini tidak akan muncul di halaman depan.')
                            ->onColor('success')
                            ->offColor('danger'),
                    ])
                    ->columnSpan(1), // Ambil 1/3 layar
            ])
            ->columns(3); // Total grid 3 kolom
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('icon')
                    ->label('Ikon')
                    ->circular()
                    ->defaultImageUrl(url('/images/placeholder.png')), // Fallback image jika perlu

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Kategori')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (Category $record): string => Str::limit($record->description, 30)),

                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->color('gray')
                    ->icon('heroicon-m-link')
                    ->toggleable(isToggledHiddenByDefault: true),

                // Menghitung jumlah topik dalam kategori ini
                Tables\Columns\TextColumn::make('topics_count')
                    ->counts('topics')
                    ->label('Jml. Topik')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Status Aktif')
                    ->onColor('success')
                    ->offColor('danger')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Filter Status')
                    ->placeholder('Semua Kategori')
                    ->trueLabel('Hanya yang Aktif')
                    ->falseLabel('Hanya yang Non-Aktif'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            // Nanti kita bisa tambah RelationManager di sini (misal: Topik)
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}