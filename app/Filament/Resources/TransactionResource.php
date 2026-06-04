<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Toggle;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card'; // আইকন পরিবর্তন করা হয়েছে

    protected static ?string $navigationLabel = 'Transactions';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Transaction Details')
                    ->schema([
                        TextInput::make('user_gmail')
                            ->email()
                            ->label('User Email'),
                        TextInput::make('method')
                            ->required()
                            ->maxLength(50),
                        TextInput::make('transaction_id')
                            ->required()
                            ->label('Transaction ID')
                            ->maxLength(100),
                        TextInput::make('amount')
                            ->numeric()
                            ->prefix('$') // আপনার কারেন্সি অনুযায়ী পরিবর্তন করতে পারেন
                            ->required(),
                        TextInput::make('page')
                            ->maxLength(255),
                        TextInput::make('order_id')
                            ->numeric()
                            ->label('Order ID'),
                        DateTimePicker::make('time_paid')
                            ->label('Payment Time'),
                        Toggle::make('unpaid')
                            ->label('Is Unpaid?')
                            ->default(true),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->sortable(),
                TextColumn::make('user_gmail')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('method')
                    ->badge()
                    ->color('info'),
                TextColumn::make('transaction_id')
                    ->label('TXN ID')
                    ->copyable() // ক্লিক করলে আইডি কপি হবে
                    ->searchable(),
                TextColumn::make('amount')
                    ->money('USD') // আপনার প্রয়োজন অনুযায়ী কারেন্সি পরিবর্তন করুন
                    ->sortable(),
                IconColumn::make('unpaid')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-x-circle')
                    ->falseIcon('heroicon-o-check-circle')
                    ->trueColor('danger')
                    ->falseColor('success'),
                TextColumn::make('time_paid')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('unpaid')
                    ->label('Payment Status')
                    ->placeholder('All Transactions')
                    ->trueLabel('Unpaid')
                    ->falseLabel('Paid'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(), // ভিউ অ্যাকশন যুক্ত করা হয়েছে
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}