<?php

namespace App\Filament\Resources\HomepageSections;

use App\Filament\Resources\HomepageSections\Pages\EditHomepageSection;
use App\Filament\Resources\HomepageSections\Pages\ListHomepageSections;
use App\Filament\Resources\HomepageSections\Schemas\HomepageSectionForm;
use App\Filament\Resources\HomepageSections\Tables\HomepageSectionsTable;
use App\Models\HomepageSection;
use BackedEnum;
use Filament\Resources\Pages\PageRegistration;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class HomepageSectionResource extends Resource
{
    protected static ?string $model = HomepageSection::class;

    protected static string|BackedEnum|null $navigationIcon =
        Heroicon::OutlinedRectangleGroup;

    protected static string|UnitEnum|null $navigationGroup = 'Website';

    protected static ?string $navigationLabel = 'Homepage Sections';

    protected static ?string $modelLabel = 'Homepage Section';

    protected static ?string $pluralModelLabel = 'Homepage Sections';

    protected static ?int $navigationSort = 10;

    /**
     * Configure the section-specific editing form.
     */
    public static function form(Schema $schema): Schema
    {
        return HomepageSectionForm::configure($schema);
    }

    /**
     * Configure the fixed homepage-section listing.
     */
    public static function table(Table $table): Table
    {
        return HomepageSectionsTable::configure($table);
    }

    /**
     * Register only list and edit routes.
     *
     * Homepage section creation is deliberately unavailable.
     *
     * @return array<string, PageRegistration>
     */
    public static function getPages(): array
    {
        return [
            'index' => ListHomepageSections::route('/'),
            'edit' => EditHomepageSection::route('/{record}/edit'),
        ];
    }
}
