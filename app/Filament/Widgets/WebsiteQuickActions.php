<?php

namespace App\Filament\Widgets;

use App\Filament\Pages\ManageSiteSettings;
use App\Filament\Resources\HomepageSections\HomepageSectionResource;
use Filament\Widgets\Widget;

class WebsiteQuickActions extends Widget
{
    /**
     * Render the custom quick-actions Blade view.
     */
    protected string $view =
        'filament.widgets.website-quick-actions';

    /**
     * Display this widget after the homepage status cards.
     */
    protected static ?int $sort = 2;

    /**
     * Use the complete dashboard width.
     */
    protected int|string|array $columnSpan = 'full';

    /**
     * Return the homepage-section listing URL.
     */
    public function homepageSectionsUrl(): string
    {
        return HomepageSectionResource::getUrl('index');
    }

    /**
     * Return the typed site-settings page URL.
     */
    public function siteSettingsUrl(): string
    {
        return ManageSiteSettings::getUrl();
    }

    /**
     * Return the public homepage URL.
     */
    public function publicHomepageUrl(): string
    {
        return route('home');
    }
}
