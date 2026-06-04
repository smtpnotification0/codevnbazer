<?php

namespace App\Filament\Resources\OrderResource\Pages;

use Filament\Resources\Components\Tab;
use App\Constants\Status;
use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrder extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            
        ];
    }
    
    public function getTabs(): array
    {
        $data = [
            null         => Tab::make('All'),
            'complete'  => Tab::make()->query(fn($query) => $query->where('status', Status::COMPLETE)),
            'processing' => Tab::make()->query(fn($query) => $query->where('status', Status::PROCESSING)),
            'cancel'  => Tab::make()->query(fn($query) => $query->where('status', Status::CANCEL)),
        ];

       if (gs()->enable_auto_topup) {
            $data['auto-processing'] = Tab::make()->query(fn($query) => $query->where('status', Status::AUTOPROCESSING));
       }

        return $data;
    }
}
