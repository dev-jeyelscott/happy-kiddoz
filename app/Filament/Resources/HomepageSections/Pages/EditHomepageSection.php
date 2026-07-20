<?php

namespace App\Filament\Resources\HomepageSections\Pages;

use App\Filament\Resources\HomepageSections\HomepageSectionResource;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Str;

class EditHomepageSection extends EditRecord
{
    protected static string $resource = HomepageSectionResource::class;

    /**
     * Display the canonical section name in the page title.
     */
    public function getTitle(): string
    {
        $key = $this->record->key;

        $value = $key instanceof BackedEnum
            ? (string) $key->value
            : (string) $key;

        return 'Edit '.Str::headline($value);
    }

    /**
     * Expose only the public-homepage shortcut.
     *
     * Delete and replicate actions are intentionally excluded.
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
