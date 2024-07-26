<?php

namespace Modules\Blog\Filament\Resources;

use Modules\Blog\Filament\Resources\PostResource\Pages;
use Modules\Blog\Filament\Resources\PostResource\RelationManagers;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Blog';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make('Post Info')->schema([
                        Forms\Components\TextInput::make('title')
                            ->autofocus()
                            ->live(onBlur: true)
                            ->unique()
                            ->placeholder('Enter Post Title')
                            ->required()
                            ->afterStateUpdated(function (string $operation, $state, Forms\Set $set) {
                                if ($operation !== 'create') {
                                    return;
                                }
                                $set('slug', Str::slug($state));
                            }),

                        Forms\Components\TextInput::make('slug')
                            ->disabled()
                            ->dehydrated()
                            ->required()
                            ->unique(Post::class, 'slug', ignoreRecord: true),

                        Forms\Components\RichEditor::make('content')
                            ->required()
                            ->columnSpanFull(),
                    ])
                ]),
                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make('Relations')->schema([

                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->required()
                            ->native(false)
                            ->searchable()
                            ->options(fn () => \App\Models\User::pluck('name', 'id')->toArray()),

                        Forms\Components\Select::make('categories')
                            ->relationship('categories', 'title')
                            ->required()
                            ->multiple()
                            ->native(false)
                            ->searchable()
                            ->options(fn () => \App\Models\Category::pluck('title', 'id')->toArray()),

                        // Forms\Components\Select::make('categories')
                        //     ->multiple()
                        //     ->relationship('categories', 'title'),
                        Forms\Components\TextInput::make('meta_description')
                            ->maxLength(255),
                        Forms\Components\Toggle::make('is_published')
                            ->required(),
                        Forms\Components\DateTimePicker::make('published_at'),
                    ])
                ]),
                Forms\Components\Section::make()->schema([

                    Forms\Components\SpatieMediaLibraryFileUpload::make('image')
                        ->image()
                        ->imageEditor()
                        ->collection('posts')
                        ->rules(['file', 'mimes:jpeg,png', 'max:1024'])
                        ->afterStateUpdated(function ($state, $component) {
                            if ($state) {
                                \Log::info('Image uploaded: ' . $state);
                            }
                        })
                        ->optimize('webp'),

                    Forms\Components\SpatieMediaLibraryFileUpload::make('gallery')
                        ->image()
                        ->imageEditor()
                        ->collection('posts_gallery')
                        ->multiple()
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('image')
                    ->collection('posts'),
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('categories.title')->searchable()->badge(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_published')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Tables\Filters\SelectFilter::make('categories')
                //     ->multiple()
                //     ->relationship('categories', 'title'),

                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('Published')
                    ->boolean()
                    ->trueLabel('Published')
                    ->falseLabel('Draft')
                    ->native(false),

                // Tables\Filters\SelectFilter::make('category')
                //     ->relationship('category', 'title')
                //     ->label('Category')
                //     ->options(fn () => \App\Models\Category::pluck('title', 'id')->toArray()),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'view' => Pages\ViewPost::route('/{record}'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
