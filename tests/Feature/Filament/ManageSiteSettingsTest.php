<?php

use App\Filament\Pages\ManageSiteSettings;
use App\Models\SiteSetting;
use App\Models\User;
use Database\Seeders\SiteSettingSeeder;
use Livewire\Livewire;

beforeEach(function (): void {
    config()->set('admin.seed_user.email', 'admin@example.test');

    $this->admin = User::factory()->create([
        'email' => 'admin@example.test',
    ]);

    $this->seed(SiteSettingSeeder::class);
});

it('requires authentication to access site settings', function (): void {
    $this->get(ManageSiteSettings::getUrl(panel: 'admin'))
        ->assertRedirect();
});

it('allows the configured administrator to access site settings', function (): void {
    $this->actingAs($this->admin)
        ->get(ManageSiteSettings::getUrl(panel: 'admin'))
        ->assertOk();
});

it('loads canonical settings into the form', function (): void {
    $this->actingAs($this->admin);

    Livewire::test(ManageSiteSettings::class)
        ->assertSchemaStateSet([
            'brand_name' => 'Happy Kiddoz',
            'meta_title' => 'Happy Kiddoz | Montessori Furniture and Learning Materials',
        ]);
});

it('updates canonical site settings', function (): void {
    $this->actingAs($this->admin);

    Livewire::test(ManageSiteSettings::class)
        ->fillForm([
            'brand_name' => 'Happy Kiddoz Philippines',
            'brand_tagline' => 'Thoughtful spaces for growing minds',
            'facebook_url' => 'https://www.facebook.com/happykiddoz',
            'contact_email' => 'hello@example.test',
            'meta_title' => 'Happy Kiddoz Philippines',
            'meta_description' => 'Montessori furniture and learning materials for Filipino families.',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect(SiteSetting::value('brand_name'))
        ->toBe('Happy Kiddoz Philippines')
        ->and(SiteSetting::value('facebook_url'))
        ->toBe('https://www.facebook.com/happykiddoz')
        ->and(SiteSetting::value('contact_email'))
        ->toBe('hello@example.test');
});

it('stores blank optional links as null', function (): void {
    SiteSetting::query()
        ->where('key', 'facebook_url')
        ->update(['value' => 'https://www.facebook.com/happykiddoz']);

    $this->actingAs($this->admin);

    Livewire::test(ManageSiteSettings::class)
        ->fillForm([
            'facebook_url' => '',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect(SiteSetting::value('facebook_url'))->toBeNull();
});

it('rejects external links that do not use https', function (): void {
    $this->actingAs($this->admin);

    Livewire::test(ManageSiteSettings::class)
        ->fillForm([
            'facebook_url' => 'http://example.com/happy-kiddoz',
        ])
        ->call('save')
        ->assertHasFormErrors([
            'facebook_url' => 'url',
        ]);
});

it('does not expose the removed generic resource route', function (): void {
    $this->actingAs($this->admin)
        ->get('/admin/site-settings')
        ->assertNotFound();
});
