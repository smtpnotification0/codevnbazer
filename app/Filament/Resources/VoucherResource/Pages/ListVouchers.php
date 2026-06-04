<?php

namespace App\Filament\Resources\VoucherResource\Pages;

use Filament\Actions;
use App\Constants\Status;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\VoucherResource;

class ListVouchers extends ListRecords
{
    protected static string $resource = VoucherResource::class;

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
            'available' => Tab::make()->query(fn ($query) => $query->where('status', Status::AVAILABLE)),
            'sold' => Tab::make()->query(fn ($query) => $query->where('status', Status::SOLD)),
        ];
    }
}
