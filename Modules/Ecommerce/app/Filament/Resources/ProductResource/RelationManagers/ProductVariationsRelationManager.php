<?php

namespace Modules\Ecommerce\Filament\Resources\ProductResource\RelationManagers;

use App\Models\ProductVariation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProductVariationsRelationManager extends RelationManager
{
    protected static string $relationship = 'productVariations';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->autofocus()
                    ->live(onBlur: true)
                    ->unique(ProductVariation::class, 'title', ignoreRecord: true)
                    ->placeholder('Enter variation title')
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
                    ->unique(ProductVariation::class, 'slug', ignoreRecord: true),

                Forms\Components\TextInput::make('type')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('price')
                    ->required()
                    ->numeric(),

                Forms\Components\TextInput::make('sku')
                    ->label('SKU')
                    ->required()
                    ->unique(ProductVariation::class, 'sku', ignoreRecord: true)
                    ->maxLength(255)
                    ->placeholder('Enter SKU')
                    ,

                Forms\Components\Select::make('product_id')
                    ->label('Product')
                    ->relationship('parent', 'title'),

                Forms\Components\SpatieMediaLibraryFileUpload::make('image')
                    ->image()
                    ->imageEditor()
                    ->collection('products')
                    ->rules(['file', 'mimes:jpeg,png', 'max:1024'])
                    ->afterStateUpdated(function ($state, $component) {
                        if ($state) {
                            Log::info('Image uploaded: ' . $state);
                        }
                    })
                    ->optimize('webp'),

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->reorderable('order')
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('price'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
