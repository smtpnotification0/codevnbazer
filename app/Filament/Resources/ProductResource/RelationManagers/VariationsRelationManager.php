<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Constants\TopupProvider;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Filters\QueryBuilder\Constraints\NumberConstraint;

class VariationsRelationManager extends RelationManager
{
    protected static string $relationship = 'variations';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
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
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('id')
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
            ])
            ->filters([
                QueryBuilder::make()
                    ->constraints([
                        NumberConstraint::make('price'),
                        NumberConstraint::make('stock'),
                    ]),
            ], layout: FiltersLayout::AboveContentCollapsible)
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Bulk Action to increase Price Only
                    Tables\Actions\BulkAction::make('increasePrice')
                        ->label('Increase Price')
                        ->icon('heroicon-o-arrow-up')
                        ->color('success')
                        ->form([
                            TextInput::make('amount')
                                ->label('Increase by (Amount)')
                                ->numeric()
                                ->required()
                                ->default(1),
                        ])
                        ->action(function (array $data, $records) {
                            $amount = (float) $data['amount'];
                            
                            foreach ($records as $record) {
                                $record->price += $amount;
                                if ($record->price < 0) {
                                    $record->price = 0;
                                }
                                $record->save();
                            }
                        })
                        ->requiresConfirmation(),

                    // Bulk Action to decrease Price Only
                    Tables\Actions\BulkAction::make('decreasePrice')
                        ->label('Decrease Price')
                        ->icon('heroicon-o-arrow-down')
                        ->color('danger')
                        ->form([
                            TextInput::make('amount')
                                ->label('Decrease by (Amount)')
                                ->numeric()
                                ->required()
                                ->default(1),
                        ])
                        ->action(function (array $data, $records) {
                            $amount = (float) $data['amount'];
                            
                            foreach ($records as $record) {
                                $record->price -= $amount;
                                if ($record->price < 0) {
                                    $record->price = 0;
                                }
                                $record->save();
                            }
                        })
                        ->requiresConfirmation(),

                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->deferLoading()
            ->paginated([10, 25, 50, 100, 200, 500, 1000]);
    }
}