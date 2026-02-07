<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TopicResource\Pages;
use App\Models\Topic;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class TopicResource extends Resource
{
    protected static ?string $model = Topic::class;

    protected static ?string $navigationIcon = 'heroicon-o-hashtag'; // Ganti icon biar beda
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?int $navigationSort = 2; // Urutan kedua setelah category

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Detail Topik')
                    ->description('Kelola topik bencana (misal: Gempa Bumi, Banjir) dan hubungkan dengan kategori.')
                    ->schema([
                        // Select Relation: Mengambil data dari tabel Categories
                        Forms\Components\Select::make('category_id')
                            ->label('Kategori Induk')
                            ->relationship('category', 'name') // Ambil relasi 'category', tampilkan kolom 'name'
                            ->searchable() // Biar bisa diketik cari
                            ->preload() // Load data di awal biar cepet
                            ->required(),

                        Forms\Components\TextInput::make('name')
                            ->label('Nama Topik')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->readOnly()
                            ->maxLength(255),

                        // Fitur Upload Gambar
                        Forms\Components\FileUpload::make('thumbnail')
                            ->label('Thumbnail (Cover)')
                            ->image() // Pastikan cuma boleh gambar
                            ->directory('topic-thumbnails') // Folder simpan di storage/app/public/topic-thumbnails
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi Singkat')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Tampilkan Gambar Kecil di Tabel
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->label('Cover')
                    ->circular(), 
                
                Tables\Columns\TextColumn::make('name')
                    ->label('Topik')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                // Menampilkan nama kategori dengan Badge warna-warni
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Filter Samping: Biar bisa sortir berdasarkan Kategori
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name')
                    ->label('Filter Kategori'),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTopics::route('/'),
            'create' => Pages\CreateTopic::route('/create'),
            'edit' => Pages\EditTopic::route('/{record}/edit'),
        ];
    }
}