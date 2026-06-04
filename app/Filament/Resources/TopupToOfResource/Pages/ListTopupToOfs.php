<?php

namespace App\Filament\Resources\TopupToOfResource\Pages;

use App\Filament\Resources\TopupToOfResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTopupToOfs extends ListRecords
{
    protected static string $resource = TopupToOfResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
