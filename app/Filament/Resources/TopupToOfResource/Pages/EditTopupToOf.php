<?php

namespace App\Filament\Resources\TopupToOfResource\Pages;

use App\Filament\Resources\TopupToOfResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTopupToOf extends EditRecord
{
    protected static string $resource = TopupToOfResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
