<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VideoResource\Pages;
use App\Models\Video;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class VideoResource extends Resource
{
    protected static ?string $model = Video::class;

    protected static ?string $navigationIcon = 'heroicon-o-play-circle'; // Icon Play
    protected static ?string $navigationGroup = 'Content Management'; // Grup baru biar rapi
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Informasi Utama')
                            ->schema([
                                // Pilih Topik Bencana
                                Forms\Components\Select::make('topic_id')
                                    ->label('Topik Bencana')
                                    ->relationship('topic', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Forms\Components\TextInput::make('title')
                                    ->label('Judul Video')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),

                                Forms\Components\TextInput::make('slug')
                                    ->required()
                                    ->readOnly()
                                    ->maxLength(255),
                                
                                Forms\Components\Textarea::make('description')
                                    ->label('Deskripsi Video')
                                    ->rows(3)
                                    ->columnSpanFull(),
                            ]),
                    ])->columnSpan(2),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Meta Data')
                            ->schema([
                                // Input Youtube ID
                                Forms\Components\TextInput::make('youtube_id')
                                    ->label('YouTube ID')
                                    ->placeholder('cth: dQw4w9WgXcQ')
                                    ->helperText('Hanya masukkan kode unik di akhir URL YouTube. Contoh: https://youtube.com/watch?v=<b>dQw4w9WgXcQ</b>')
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('duration')
                                    ->label('Durasi (Menit)')
                                    ->numeric()
                                    ->default(0)
                                    ->suffix('menit'),

                                Forms\Components\Toggle::make('is_featured')
                                    ->label('Highlight Video Ini?')
                                    ->helperText('Video akan muncul di bagian rekomendasi utama.')
                                    ->default(false)
                                    ->onColor('success'),
                            ]),
                    ])->columnSpan(1),
            ])->columns(3); // Layout 3 kolom (2 kiri, 1 kanan)
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->limit(50)
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('topic.name')
                    ->label('Topik')
                    ->badge()
                    ->color('gray')
                    ->sortable(),

                // Menampilkan Youtube ID dengan fitur Copy
                Tables\Columns\TextColumn::make('youtube_id')
                    ->label('YT ID')
                    ->fontFamily('mono')
                    ->copyable() 
                    ->copyMessage('ID Youtube disalin!')
                    ->color('primary'),

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
                
                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Status Featured'),
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
            'index' => Pages\ListVideos::route('/'),
            'create' => Pages\CreateVideo::route('/create'),
            'edit' => Pages\EditVideo::route('/{record}/edit'),
        ];
    }
}