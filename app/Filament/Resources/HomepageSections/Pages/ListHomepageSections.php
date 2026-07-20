<?php

namespace App\Filament\Resources\HomepageSections\Pages;

use App\Filament\Resources\HomepageSections\HomepageSectionResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;

class ListHomepageSections extends ListRecords
{
    protected static string $resource = HomepageSectionResource::class;

    /**
     * Provide a direct link to the public homepage.
     *
     * @return array<Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            Action::make('viewHomepage')
                ->label('View Homepage')
                ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                ->url(url('/'))
                ->openUrlInNewTab(),
        ];
    }
}
