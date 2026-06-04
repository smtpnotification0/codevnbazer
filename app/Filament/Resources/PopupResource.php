<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Popup;
use Filament\Forms\Form;
use App\Constants\Status;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use App\Filament\Resources\PopupResource\Pages;
use Filament\Forms\Components\FileUpload;

class PopupResource extends Resource
{
    protected static ?string $model = Popup::class;

    protected static ?string $navigationIcon = 'heroicon-o-bell-alert';

    protected static ?int $navigationSort = 12;

    protected static ?string $navigationGroup = 'Frontend';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('url')
                            ->label('URL')
                            ->maxLength(255),
                        TextInput::make('button_text')
                            ->label('Button Name')
                            ->maxLength(255),
                        RichEditor::make('content')->maxLength(5000)->columnSpanFull(),
                    ])->columnSpan(2)->columns(2),
                Section::make('Meta')
                    ->schema([
                        FileUpload::make('image_url')
                            ->directory('popup')
                            ->required(),
                        Select::make('type')
                            ->options([
                                Status::ONCE => 'First Visit',
                                Status::DAILY => 'Daily'
                            ])->required()->default(Status::ACTIVE),
                        // Select::make('status')
                        //     ->options([
                        //         Status::ACTIVE => 'Active',
                        //         Status::INACTIVE => 'Inactive'
                        //     ])->required()->default(Status::ACTIVE),
                        Toggle::make('status')->required()
                    ])->columnSpan(1),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('url')
                    ->label('URL')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('button_text')
                    ->label('Button Name')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('type')
                    ->sortable()
                    ->searchable()
                    ->toggleable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        Status::ONCE => 'success',
                        Status::DAILY => 'danger'
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        Status::ONCE => 'First Visit',
                        Status::DAILY => 'Daily'
                    }),
                ToggleColumn::make('status')
                    ->sortable()
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
                SelectFilter::make('type')
                    ->options([
                        Status::ONCE => 'Once',
                        Status::DAILY => 'Daily'
                    ]),
                SelectFilter::make('status')
                    ->options([
                        Status::ACTIVE => 'Active',
                        Status::INACTIVE => 'Inactive'
                    ])
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPopups::route('/'),
            'create' => Pages\CreatePopup::route('/create'),
            'edit' => Pages\EditPopup::route('/{record}/edit'),
        ];
    }
}
