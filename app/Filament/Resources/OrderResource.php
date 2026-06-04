<?php

namespace App\Filament\Resources;

use App\Constants\OrderStatus;
use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
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
use Filament\Tables\Actions\Action as TableAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationLabel = 'Orders';
    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        $processing = Order::where('status', OrderStatus::PROCESSING)->count();
        $auto       = Order::where('status', OrderStatus::AUTOPROCESSING)->count();
        return ($processing + $auto) > 0 ? (string)($processing + $auto) : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([

            // ── TOP SUMMARY CARD ──────────────────────────────────────
            Section::make()
                ->schema([
                    Placeholder::make('order_header')
                        ->label('')
                        ->content(function (?Order $record) {
                            if (!$record) return new HtmlString('<p class="text-gray-400">New Order</p>');
                            $record->loadMissing(['product', 'variation', 'user']);

                            $statusColors = [
                                OrderStatus::PENDING        => 'bg-yellow-100 text-yellow-700',
                                OrderStatus::PROCESSING     => 'bg-blue-100 text-blue-700',
                                OrderStatus::AUTOPROCESSING => 'bg-indigo-100 text-indigo-700',
                                OrderStatus::COMPLETED      => 'bg-green-100 text-green-700',
                                OrderStatus::CANCEL         => 'bg-red-100 text-red-700',
                            ];
                            $statusClass = $statusColors[$record->status] ?? 'bg-gray-100 text-gray-700';

                            return new HtmlString("
                                <div class='flex flex-wrap items-center gap-4 py-1'>
                                    <div class='flex items-center gap-2'>
                                        <span class='text-2xl font-black text-gray-800 dark:text-white'>#{$record->id}</span>
                                        <span class='px-3 py-1 rounded-full text-xs font-bold {$statusClass} uppercase tracking-wide'>{$record->status}</span>
                                    </div>
                                    <div class='flex-1 flex flex-wrap gap-6 text-sm text-gray-500'>
                                        <span>👤 <b class='text-gray-700'>" . ($record->user->name ?? 'N/A') . "</b></span>
                                        <span>📦 <b class='text-gray-700'>" . ($record->product->title ?? 'N/A') . "</b></span>
                                        <span>🎁 <b class='text-gray-700'>" . ($record->variation->title ?? 'N/A') . "</b></span>
                                        <span>💰 <b class='text-green-600'>{$record->amount} TK</b></span>
                                        <span>🕐 <b class='text-gray-700'>" . ($record->created_at?->format('d M Y, h:i A') ?? '') . "</b></span>
                                    </div>
                                </div>
                            ");
                        })
                        ->columnSpanFull(),
                ])
                ->columnSpanFull(),

            // ── STATUS + CUSTOMER ──────────────────────────────────────
            Section::make('🎯 Order Control')
                ->description('Update order status and assign customer')
                ->icon('heroicon-o-cog-6-tooth')
                ->columns(2)
                ->schema([
                    Hidden::make('id'),
                    Hidden::make('product_id'),
                    Hidden::make('variation_id'),

                    Select::make('status')
                        ->label('Order Status')
                        ->options(OrderStatus::options())
                        ->required()
                        ->native(false)
                        ->suffixIcon('heroicon-o-tag'),

                    Select::make('user_id')
                        ->label('Customer')
                        ->relationship('user', 'name')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->suffixIcon('heroicon-o-user'),

                    TextInput::make('order_id_to')
                        ->label('External Order ID')
                        ->placeholder('e.g. ORD-123456')
                        ->prefixIcon('heroicon-o-identification')
                        ->columnSpanFull(),
                ]),

            // ── TRANSACTION INFO ───────────────────────────────────────
            Section::make('💳 Transaction Details')
                ->icon('heroicon-o-credit-card')
                ->collapsible()
                ->collapsed()
                ->schema([
                    Placeholder::make('transaction_info')
                        ->label('')
                        ->content(function (?Order $record) {
                            $transaction = Transaction::where('order_id', $record?->id)->first();
                            if (!$transaction) {
                                return new HtmlString("<div class='text-center py-4 text-gray-400'>⚠️ No transaction found for this order.</div>");
                            }

                            $methodBadge = "<span class='px-2 py-0.5 bg-green-100 text-green-700 rounded-full text-xs font-bold'>{$transaction->method}</span>";
                            $trxBadge    = "<span class='font-mono text-xs bg-gray-100 px-2 py-0.5 rounded'>{$transaction->transaction_id}</span>";

                            return new HtmlString("
                                <div class='grid grid-cols-2 md:grid-cols-3 gap-4 text-sm'>
                                    <div class='bg-white dark:bg-gray-800 rounded-xl border p-3 shadow-sm'>
                                        <p class='text-xs text-gray-400 mb-1'>Payment Method</p>
                                        <p class='font-semibold'>{$methodBadge}</p>
                                    </div>
                                    <div class='bg-white dark:bg-gray-800 rounded-xl border p-3 shadow-sm'>
                                        <p class='text-xs text-gray-400 mb-1'>Transaction ID</p>
                                        <p class='font-semibold'>{$trxBadge}</p>
                                    </div>
                                    <div class='bg-white dark:bg-gray-800 rounded-xl border p-3 shadow-sm'>
                                        <p class='text-xs text-gray-400 mb-1'>Amount Paid</p>
                                        <p class='font-bold text-green-600 text-base'>{$transaction->amount} TK</p>
                                    </div>
                                    <div class='bg-white dark:bg-gray-800 rounded-xl border p-3 shadow-sm'>
                                        <p class='text-xs text-gray-400 mb-1'>Paid At</p>
                                        <p class='font-semibold'>{$transaction->time_paid}</p>
                                    </div>
                                    <div class='bg-white dark:bg-gray-800 rounded-xl border p-3 shadow-sm col-span-2'>
                                        <p class='text-xs text-gray-400 mb-1'>Customer Email</p>
                                        <p class='font-semibold'>{$transaction->user_gmail}</p>
                                    </div>
                                </div>
                            ");
                        })
                        ->columnSpanFull(),
                ]),

            // ── ACCOUNT INFO ───────────────────────────────────────────
            Section::make('🎮 Account Info')
                ->icon('heroicon-o-user-circle')
                ->collapsible()
                ->columns(1)
                ->schema([
                    Placeholder::make('player_id_display')
                        ->label('Player ID (Double-click to copy)')
                        ->content(fn (?Order $record) =>
                            new HtmlString("
                                <div
                                    class='cursor-pointer select-all text-center py-3 px-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border-2 border-dashed border-blue-400 rounded-xl font-bold text-blue-700 dark:text-blue-300 text-lg tracking-widest hover:bg-blue-100 transition'
                                    ondblclick=\"navigator.clipboard.writeText('" . (self::jsonValue($record?->account_info_original, 'player_id') ?? '') . "'); this.innerText='✅ Copied!'; setTimeout(()=>this.innerText='" . (self::jsonValue($record?->account_info_original, 'player_id') ?? 'N/A') . "',1500);\"
                                    title='Double-click to copy'
                                >
                                    " . (self::jsonValue($record?->account_info_original, 'player_id') ?? 'N/A') . "
                                </div>
                            ")
                        )
                        ->columnSpanFull(),

                    KeyValue::make('account_info_original')
                        ->label('Original Account Info (Read-only view)')
                        ->columnSpanFull(),

                    KeyValue::make('account_info')
                        ->label('Editable Account Info')
                        ->columnSpanFull(),

                    KeyValue::make('account_info_to')
                        ->label('TopUp Delivery Account Info')
                        ->columnSpanFull(),
                ]),

            // ── DELIVERY MESSAGE ───────────────────────────────────────
            Section::make('📝 Delivery Note')
                ->icon('heroicon-o-chat-bubble-left-ellipsis')
                ->collapsible()
                ->collapsed()
                ->schema([
                    Textarea::make('delivery_message')
                        ->label('Delivery Message / Admin Note')
                        ->rows(4)
                        ->placeholder('Add a delivery note or internal message...')
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Order::query()->with(['user', 'product', 'variation', 'transaction']))
            ->defaultSort('id', 'desc')
            ->striped()
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Order')
                    ->formatStateUsing(fn ($state) => "#{$state}")
                    ->sortable()
                    ->weight('bold')
                    ->color('primary'),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable()
                    ->icon('heroicon-o-user')
                    ->description(fn ($record) => $record->user?->email ?? ''),

                Tables\Columns\TextColumn::make('transaction.method')
                    ->label('Payment')
                    ->badge()
                    ->color(fn ($state) => match (strtolower($state ?? '')) {
                        'wallet'     => 'success',
                        'uddoktapay' => 'info',
                        default      => 'gray',
                    })
                    ->icon('heroicon-o-credit-card'),

                Tables\Columns\TextColumn::make('transaction.transaction_id')
                    ->label('TRX ID')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Copied!')
                    ->fontFamily('mono')
                    ->color('gray')
                    ->limit(20),

                Tables\Columns\TextColumn::make('product.title')
                    ->label('Product')
                    ->searchable()
                    ->description(fn ($record) => $record->variation?->title ?? '—')
                    ->icon('heroicon-o-cube'),

                Tables\Columns\TextColumn::make('amount')
                    ->label('Amount')
                    ->sortable()
                    ->money('BDT')
                    ->color('success')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->icon(fn ($state) => match ($state) {
                        OrderStatus::PENDING        => 'heroicon-o-clock',
                        OrderStatus::PROCESSING     => 'heroicon-o-arrow-path',
                        OrderStatus::AUTOPROCESSING => 'heroicon-o-bolt',
                        OrderStatus::COMPLETED      => 'heroicon-o-check-circle',
                        OrderStatus::CANCEL         => 'heroicon-o-x-circle',
                        default                     => 'heroicon-o-question-mark-circle',
                    })
                    ->color(fn ($state) => match ($state) {
                        OrderStatus::PENDING        => 'warning',
                        OrderStatus::PROCESSING     => 'info',
                        OrderStatus::AUTOPROCESSING => 'primary',
                        OrderStatus::COMPLETED      => 'success',
                        OrderStatus::CANCEL         => 'danger',
                        default                     => 'gray',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('d M Y, h:i A')
                    ->sortable()
                    ->since()
                    ->color('gray'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Filter by Status')
                    ->options(OrderStatus::options())
                    ->native(false),

                Filter::make('today')
                    ->label('Today\'s Orders')
                    ->query(fn (Builder $query) => $query->whereDate('created_at', today()))
                    ->toggle(),
            ])
            ->actions([
                EditAction::make()
                    ->icon('heroicon-o-pencil-square')
                    ->color('primary'),

                TableAction::make('mark_complete')
                    ->label('Complete')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Order $record) => !in_array($record->status, [
                        OrderStatus::COMPLETED,
                        OrderStatus::CANCEL,
                    ]))
                    ->action(fn (Order $record) => $record->update(['status' => OrderStatus::COMPLETED])),

                DeleteAction::make()
                    ->icon('heroicon-o-trash'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('bulk_complete')
                        ->label('Mark as Completed')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each->update(['status' => OrderStatus::COMPLETED])),

                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateIcon('heroicon-o-shopping-cart')
            ->emptyStateHeading('No Orders Yet')
            ->emptyStateDescription('Orders will appear here once customers start purchasing.');
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