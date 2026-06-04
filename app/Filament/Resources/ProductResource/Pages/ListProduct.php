<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Constants\Status;
use Filament\Resources\Components\Tab;
use App\Filament\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProduct extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    
    public function getTabs(): array
    {
        return [
            null => Tab::make('All'),
            'topup' => Tab::make()->query(fn ($query) => $query->where('type', Status::TOPUP)),
            'ingame' => Tab::make()->label('In Game')->query(fn ($query) => $query->where('type', Status::INGAME)),
            'voucher' => Tab::make()->query(fn ($query) => $query->where('type', Status::VOUCHER)),
            'subscription' => Tab::make()->query(fn ($query) => $query->where('type', Status::SUBSCRIPTION)),
        ];
    }
}
