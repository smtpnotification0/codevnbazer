<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TopupToOfResource\Pages;
use App\Models\TopupToOf;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;

class TopupToOfResource extends Resource
{
    protected static ?string $model = TopupToOf::class;

    // ১. নেভিগেশন সেটিংস (ইমেজ অনুযায়ী Products গ্রুপের ভেতরে নিতে)
    protected static ?string $navigationIcon = 'heroicon-o-adjustments-horizontal';
    protected static ?string $navigationGroup = 'Products'; // এখানে আপনার গ্রুপের নাম দিলেন
    protected static ?string $navigationLabel = 'Topup Settings'; // মেনুতে যে নাম দেখাবে

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // ব্যালেন্স এবং স্ট্যাটাস সেকশন
                Section::make('General Settings')
                    ->description('Manage topup status and balance detection.')
                    ->schema([
                        Toggle::make('status')
                            ->label('System Active Status')
                            ->onColor('success')
                            ->offColor('danger')
                            ->required(),

                        TextInput::make('balance_detect')
                            ->label('Balance Detect Amount')
                            ->numeric()
                            ->prefix('৳') // অথবা $ চিহ্ন দিতে পারেন
                            ->required(),
                    ])->columns(2),

                // প্লেয়ার আইডি সেকশন
                Section::make('Player ID Boxes')
                    ->description('Define labels for player ID input fields.')
                    ->schema([
                        TextInput::make('player_id_1')->label('Player ID 1 Label'),
                        TextInput::make('player_id_2')->label('Player ID 2 Label'),
                        TextInput::make('player_id_3')->label('Player ID 3 Label'),
                        TextInput::make('player_id_4')->label('Player ID 4 Label'),
                        TextInput::make('player_id_5')->label('Player ID 5 Label'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // টেবিল লিস্ট থেকেই অন/অফ করার সুবিধা
                ToggleColumn::make('status')
                    ->label('Active'),

                TextColumn::make('balance_detect')
                    ->label('Balance')
                    ->sortable()
                    ->money('BDT'), // আপনার কারেন্সি কোড

                TextColumn::make('player_id_1')
                    ->label('ID 1')
                    ->searchable(),
                
                TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListTopupToOfs::route('/'),
            'create' => Pages\CreateTopupToOf::route('/create'),
            'edit' => Pages\EditTopupToOf::route('/{record}/edit'),
        ];
    }
}