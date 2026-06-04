<?php

namespace App\Filament\Resources;

use Filament\Tables;
use App\Models\Voucher;
use Filament\Forms\Form;
use App\Constants\Status;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TagsInput;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\VoucherResource\Pages;

class VoucherResource extends Resource
{
    protected static ?string $model = Voucher::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static ?int $navigationSort = 10;

    protected static ?string $navigationGroup = 'Products';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Select::make('variation_id')
                            ->relationship('variation', titleAttribute: 'title', modifyQueryUsing: fn(Builder $query) => $query->whereHas('product', function (Builder $query) {
                                $query->where('type', Status::VOUCHER);
                            }))
                            ->searchable()
                            ->preload()
                            ->required(),
                        TagsInput::make('code')
                            ->splitKeys(['Tab', ','])
                            ->required()
                            ->placeholder('Code'),
                        Toggle::make('status')
                            ->label('Available')
                            ->required()         
                            ->default(true),
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
                TextColumn::make('variation.product.title')
                    ->label('Product')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('variation.title')
                    ->label('Variation')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('code')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                ToggleColumn::make('status')
                    ->label('Available')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('order_id')
                    ->label('Order Id')
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
                SelectFilter::make('variation')
                    ->relationship('variation', titleAttribute: 'title', modifyQueryUsing: fn(Builder $query) => $query->whereHas('product', function (Builder $query) {
                        $query->where('type', Status::VOUCHER);
                    }))
                    ->searchable()
                    ->preload(),
                SelectFilter::make('status')
                    ->options([
                        Status::AVAILABLE => 'Available',
                        Status::SOLD      => 'Sold',
                    ]),
            ], layout: FiltersLayout::AboveContentCollapsible)
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
            //
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('variation.product');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListVouchers::route('/'),
            'create' => Pages\CreateVoucher::route('/create'),
            'edit'   => Pages\EditVoucher::route('/{record}/edit'),
        ];
    }
}