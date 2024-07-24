<?php

namespace Modules\Blog\Filament\Resources;

use App\Models\Post;
use Modules\Blog\Filament\Resources\PostResource\Pages;
use Modules\Blog\Filament\Resources\PostResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Illuminate\Support\Str;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->autofocus()
                    ->live(onBlur: true)
                    ->unique()
                    ->placeholder('Enter product name')
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
                    ->label('Content')
                    ->minLength(3)
                    ->required(),
                Forms\Components\TextInput::make('meta_description')
                    ->label('Meta Description')
                    ->nullable(),
                Forms\Components\Checkbox::make('is_published')
                    ->label('Is Published')
                    ->default(false),
                Forms\Components\DateTimePicker::make('published_at')
                    ->label('Published At')
                    ->nullable(),

                Forms\Components\Hidden::make('user_id')
                    ->dehydrateStateUsing(function ($state) {
                        return auth()->id();
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->primary()
                    ->searchable()
                    ->sortable(),

                Tables\Columns\CheckboxColumn::make('is_published')
                    ->label('Published')
                    ->sortable(),

                Tables\Columns\TextColumn::make('published_at')
                    ->label('Published At')
                    ->sortable(),

                Tables\Columns\TextColumn::make('user_id')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}

