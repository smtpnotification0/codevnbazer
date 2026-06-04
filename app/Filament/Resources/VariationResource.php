<?php

namespace App\Filament\Resources;

use App\Constants\TopupProvider;
use App\Filament\Resources\VariationResource\Pages;
use App\Filament\Resources\VariationResource\RelationManagers\VouchersRelationManager;
use App\Models\Variation;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class VariationResource extends Resource
{
    protected static ?string $model = Variation::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?int $navigationSort = 9;

    protected static ?string $navigationGroup = 'Products';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Select::make('product_id')
                            ->relationship('product', 'title')
                            ->searchable()
                            ->preload()
                            ->required(),

                        TextInput::make('title')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('buy_rate')
                            ->label('Buy Rate')
                            ->required()
                            ->numeric()
                            ->inputMode('decimal'),

                        TextInput::make('price')
                            ->required()
                            ->numeric()
                            ->inputMode('decimal'),

                        TextInput::make('stock')
                            ->required()
                            ->integer()
                            ->inputMode('decimal'),

                        Toggle::make('automatic')
                            ->label('Auto TopUP')
                            ->visible(fn(): bool => gs()->enable_auto_topup)
                            ->live()
                            ->columnSpanFull(),

                        Select::make('provider')
                            ->label('Auto Topup Provider')
                            ->visible(fn(): bool => gs()->enable_auto_topup)
                            ->options(TopupProvider::OPTIONS)
                            ->required()
                            ->live()
                            ->hidden(fn(Get $get): bool => !$get('automatic')),

                        Select::make('provider_product_id')
                            ->label('Provider Product')
                            ->visible(fn(): bool => gs()->enable_auto_topup)
                            ->options(TopupProvider::PRODUCTVARIATIONS)
                            ->required()
                            ->hidden(fn(Get $get): bool => !$get('automatic') || !$get('provider')),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('product.title')
                    ->label('Product')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('title')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('buy_rate')
                    ->label('Buy Rate')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('price')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('stock')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Date')
                    ->date()
                    ->sortable()
                    ->toggleable()
                    ->toggledHiddenByDefault(),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                SelectFilter::make('product')
                    ->relationship('product', 'title')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->deferLoading()
            ->paginated([10, 25, 50, 100, 200, 500, 1000]);
    }

    public static function getRelations(): array
    {
        return [
            VouchersRelationManager::class,
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('product');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListVariations::route('/'),
            'create' => Pages\CreateVariation::route('/create'),
            'edit'   => Pages\EditVariation::route('/{record}/edit'),
        ];
    }
}