<?php

namespace App\Filament\Resources;

use App\Constants\OrderStatus;
use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use App\Models\User;
use App\Models\Transaction;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\HtmlString;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    public static function getNavigationBadge(): ?string
    {
        $processing = Order::where('status', 'processing')->count();
        $auto       = Order::where('status', 'auto-processing')->count();
        return "🟨 P: {$processing} | 🟦 A: {$auto}";
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            // প্রথম সেকশন: অর্ডার স্ট্যাটাস এবং বেসিক ইনফো
            Section::make('Order Summary')
                ->columns(2)
                ->schema([
                    Hidden::make('id'),
                    Hidden::make('product_id'),
                    Hidden::make('variation_id'),

                    Select::make('status')
                        ->label('Order Status')
                        ->options(OrderStatus::options())
                        ->required(),

                    Placeholder::make('product_info')
                        ->label('Product & Variation')
                        ->content(function (?Order $record) {
                            if (!$record) return 'New Order';
                            $record->loadMissing(['product', 'variation']);
                            return "ORD-{$record->id} | " . ($record->product->title ?? 'N/A') . " | " . ($record->variation->title ?? 'N/A');
                        }),

                    // Transaction Details: কলাপসিবল বক্স
                    Section::make('Transaction Details')
                        ->collapsible()
                        ->collapsed() 
                        ->columnSpanFull()
                        ->schema([
                            Placeholder::make('transaction_info')
                                ->label('')
                                ->content(function (?Order $record) {
                                    $transaction = Transaction::where('order_id', $record?->id)->first();
                                    if (!$transaction) return 'No transaction found.';
                                    
                                    return new HtmlString("
                                        <div class='space-y-1 text-sm'>
                                            <p><b>Method:</b> <span class='text-success-600'>{$transaction->method}</span></p>
                                            <p><b>TRX ID:</b> <span class='text-primary-600'>{$transaction->transaction_id}</span></p>
                                            <p><b>Amount:</b> {$transaction->amount} TK</p>
                                            <p><b>Paid At:</b> {$transaction->time_paid}</p>
                                            <p><b>Gmail:</b> {$transaction->user_gmail}</p>
                                        </div>
                                    ");
                                }),
                        ]),

                    KeyValue::make('account_info_original')
                        ->label('Original Account Info')
                        ->columnSpanFull(),

                    Placeholder::make('player_id_copy')
                        ->label('Player ID (Double Click to Copy)')
                        ->content(fn (?Order $record) =>
                            self::jsonValue($record?->account_info_original, 'player_id') ?? 'N/A'
                        )
                        ->extraAttributes(fn (?Order $record) => [
                            'class' => 'cursor-pointer bg-blue-50 border-2 border-dashed border-blue-400 p-3 rounded-lg text-center font-bold text-blue-700',
                            'ondblclick' => "navigator.clipboard.writeText('" . self::jsonValue($record?->account_info_original, 'player_id') . "');",
                        ])
                        ->columnSpanFull(),
                ]),

            // দ্বিতীয় সেকশন: Account & Delivery Info (কলাপসিবল বক্স)
            Section::make('Account & Delivery Info')
                ->collapsible()
                ->collapsed()
                ->schema([
                    // ইউজার আইডি বা কাস্টমার সিলেক্ট করার অপশন
                    Select::make('user_id')
                        ->label('Select Customer')
                        ->relationship('user', 'name') // User মডেলের সাথে রিলেশন
                        ->searchable()
                        ->preload()
                        ->required(),

                    TextInput::make('order_id_to')
                        ->label('External Order ID')
                        ->placeholder('TRX-XXXXXX'),

                    KeyValue::make('account_info')
                        ->label('Editable Account Info (Data Entry)')
                        ->columnSpanFull(),

                    KeyValue::make('account_info_to')
                        ->label('TopUp To Of Account Info')
                        ->columnSpanFull(),

                    Textarea::make('delivery_message')
                        ->label('Delivery Message')
                        ->rows(3)
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Order::with(['user', 'product', 'variation']))
            ->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->description(fn ($record) => "ID: {$record->user_id}"),
                
                Tables\Columns\TextColumn::make('transaction.method')
                    ->label('Method')
                    ->badge()
                    ->color('success'),

                Tables\Columns\TextColumn::make('transaction.transaction_id')
                    ->label('TRX ID')
                    ->searchable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('product.title')
                    ->label('Product')
                    ->description(fn ($record) => $record->variation?->title ?? '—'),

                Tables\Columns\TextColumn::make('amount')->label('Price')->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'pending'         => 'warning',
                        'processing'      => 'info',
                        'auto-processing' => 'primary',
                        'completed'       => 'success',
                        'cancel'          => 'danger',
                        default           => 'gray',
                    }),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(), // সিঙ্গেল ডিলিট অপশন
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    DeleteBulkAction::make(), // একসাথে অনেকগুলো সিলেক্ট করে ডিলিট করার অপশন
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrder::route('/'),
            'edit'  => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    protected static function jsonValue($data, string $key): mixed
    {
        if (is_string($data)) {
            $data = json_decode($data, true);
        }
        return is_array($data) ? ($data[$key] ?? null) : null;
    }
}