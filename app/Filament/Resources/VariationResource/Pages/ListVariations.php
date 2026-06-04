<?php

namespace App\Filament\Resources\VariationResource\Pages;

use Filament\Actions;
use App\Constants\Status;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\VariationResource;
use Illuminate\Database\Eloquent\Builder;

class ListVariations extends ListRecords
{
    protected static string $resource = VariationResource::class;

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
            'topup' => Tab::make()->query(fn (Builder $query) => $query->whereHas('product', function(Builder $query){
                $query->where('type', Status::TOPUP);
            })),
            'ingame' => Tab::make()->label('In Game')->query(fn (Builder $query) => $query->whereHas('product', function(Builder $query){
                $query->where('type', Status::INGAME);
            })),
            'voucher' => Tab::make()->query(fn (Builder $query) => $query->whereHas('product', function(Builder $query){
                $query->where('type', Status::VOUCHER);
            })),
        ];
    }
}
