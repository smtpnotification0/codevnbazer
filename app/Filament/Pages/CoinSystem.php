<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Illuminate\Support\Facades\DB;
use App\Models\Setting;
use App\Models\User;

class CoinSystem extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    
    protected static ?string $navigationLabel = 'Coin System';
    
    protected static ?string $navigationGroup = 'Products';
    
    protected static ?int $navigationSort = 10;
    
    protected static string $view = 'filament.pages.coin-system';

    public ?array $data = [];

    public function mount(): void
    {
        $coinToTaka = Setting::get('coin_to_taka') ?? '7';
        // If old format exists (1000=7), extract just the taka amount
        if (strpos($coinToTaka, '=') !== false) {
            [, $takaAmount] = explode('=', $coinToTaka);
            $coinToTaka = $takaAmount;
        }
        $this->form->fill([
            'taka_amount' => (float) $coinToTaka,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Coin to Taka Rate')
                    ->description('Set how much Taka (৳) users will get for 1000 coins. Example: Enter 7 means 1000 coins = 7৳')
                    ->schema([
                        TextInput::make('taka_amount')
                            ->label('Taka Amount (for 1000 coins)')
                            ->placeholder('7')
                            ->numeric()
                            ->required()
                            ->step(0.01)
                            ->minValue(0.01)
                            ->suffix('৳')
                            ->helperText('Enter the amount in Taka. Example: 7 means 1000 coins = 7৳'),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();
        
        $takaAmount = (float) $data['taka_amount'];
        
        if ($takaAmount <= 0) {
            \Filament\Notifications\Notification::make()
                ->title('Invalid amount')
                ->body('Taka amount must be greater than 0')
                ->danger()
                ->send();
            return;
        }

        // Save as just the taka amount (we always use 1000 coins as base)
        Setting::updateOrCreate(
            [
                'group' => 'coin',
                'name' => 'coin_to_taka'
            ],
            ['payload' => json_encode((string) $takaAmount)]
        );

        \Filament\Notifications\Notification::make()
            ->title('Settings saved')
            ->body("Rate updated: 1000 coins = {$takaAmount}৳")
            ->success()
            ->send();
    }

    public function getUsersWithCoinsProperty()
    {
        return User::where('coins', '>', 0)
            ->orderBy('coins', 'desc')
            ->limit(50)
            ->get(['id', 'name', 'email', 'coins', 'balance']);
    }
}

