<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('User Information')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        // ✅ এখানে Radio button user_type ফিল্ড
                        Forms\Components\Radio::make('user_type')
                            ->label('User Type')
                            ->options([
                                'admin' => 'Admin',
                                'user' => 'User',
                                'reseller' => 'Reseller',
                            ])
                            ->inline() // এক লাইনে দেখাবে
                            ->default('user')
                            ->required()
                            ->descriptions([
                                'admin' => 'Full control of the system',
                                'user' => 'Standard user account',
                                'reseller' => 'Can resell and manage clients',
                            ]),

                        TextInput::make('email')
                            ->required()
                            ->maxLength(255)
                            ->email(),

                        TextInput::make('phone')
                            ->maxLength(255),

                        TextInput::make('balance')
                            ->required()
                            ->maxLength(16)
                            ->default(0),

                        TextInput::make('coins')
                            ->required()
                            ->maxLength(16)
                            ->default(0),

                        TextInput::make('password')
                            ->password()
                            ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->required(fn (string $operation): bool => $operation === 'create'),

                        Toggle::make('status')
                            ->label('Active')
                            ->required()
                            ->default(1),

                        Toggle::make('is_reseller')
                            ->label('Reseller')
                            ->required()
                            ->default(0),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable()->toggleable(),
                TextColumn::make('name')->sortable()->searchable()->toggleable(),
                TextColumn::make('email')->sortable()->searchable()->toggleable(),
                TextColumn::make('phone')->sortable()->searchable()->toggleable(),
                TextInputColumn::make('balance')->sortable()->toggleable(),
                TextInputColumn::make('coins')->sortable()->toggleable(),
                Tables\Columns\BadgeColumn::make('user_type')
                    ->label('Role')
                    ->colors([
                        'info' => 'admin',
                        'success' => 'user',
                        'warning' => 'reseller',
                    ])
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                ToggleColumn::make('status')->label('Active')->sortable()->toggleable(),
                ToggleColumn::make('is_reseller')->label('Reseller')->sortable()->toggleable(),
                TextColumn::make('created_at')->label('Date')->date()->sortable()->toggleable(),
            ])
            ->filters([
                SelectFilter::make('user_type')
                    ->options([
                        'user' => 'User',
                        'admin' => 'Admin',
                        'reseller' => 'Reseller',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->hidden(fn ($record): bool => $record->user_type === 'admin'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}