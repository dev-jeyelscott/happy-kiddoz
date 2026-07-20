<?php

use App\Filament\Widgets\WebsiteStatusOverview;
use App\Models\HomepageSection;
use App\Models\SiteSetting;
use App\Models\User;
use Database\Seeders\HomepageSectionSeeder;
use Database\Seeders\SiteSettingSeeder;
use Livewire\Livewire;

beforeEach(function (): void {
    /*
     * Match the email-based Filament panel authorization rule.
     */
    config()->set(
        'admin.seed_user.email',
        'admin@happy-kiddoz.test',
    );

    $this->actingAs(
        User::factory()->create([
            'email' => 'admin@happy-kiddoz.test',
        ]),
    );

    /*
     * Seed only the data needed by the admin dashboard.
     */
    $this->seed([
        SiteSettingSeeder::class,
        HomepageSectionSeeder::class,
    ]);
});

it('renders the branded admin dashboard and quick actions', function (): void {
    $this->get('/admin')
        ->assertOk()
        ->assertSee('Happy Kiddoz')
        ->assertSee('Homepage Status')
        ->assertSee('Quick actions')
        ->assertSee('Edit homepage sections')
        ->assertSee('Manage site settings')
        ->assertSee('View public website');
});

it('reports a ready homepage when required sections are visible', function (): void {
    Livewire::test(WebsiteStatusOverview::class)
        ->assertOk()
        ->assertSee('Homepage readiness')
        ->assertSee('Ready')
        ->assertSee('All required homepage sections are visible');
});

it('reports a hidden required section', function (): void {
    HomepageSection::query()
        ->where('key', 'hero')
        ->update([
            'is_visible' => false,
        ]);

    Livewire::test(WebsiteStatusOverview::class)
        ->assertOk()
        ->assertSee('Needs attention')
        ->assertSee('1 required section is missing or hidden');
});

it('reports missing marketplace and social links', function (): void {
    SiteSetting::query()
        ->whereIn('key', [
            'primary_shop_url',
            'shopee_url',
            'lazada_url',
            'facebook_url',
            'instagram_url',
        ])
        ->update([
            'value' => null,
        ]);

    Livewire::test(WebsiteStatusOverview::class)
        ->assertOk()
        ->assertSee('Store and social links')
        ->assertSee('5 missing')
        ->assertSee('Complete the missing URLs in Site Settings');
});
