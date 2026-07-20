<?php

use App\Enums\HomepageSectionKey;
use App\Models\HomepageSection;
use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Blade;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    SiteSetting::query()->create([
        'key' => 'brand_name',
        'value' => 'Happy Kiddos',
        'group' => 'brand',
    ]);

    SiteSetting::query()->create([
        'key' => 'primary_shop_url',
        'value' => null,
        'group' => 'marketplace',
    ]);
});

it('renders navigation only for visible homepage sections', function (): void {
    HomepageSection::factory()
        ->forKey(HomepageSectionKey::Categories)
        ->visible()
        ->create();

    HomepageSection::factory()
        ->forKey(HomepageSectionKey::About)
        ->hidden()
        ->create();

    HomepageSection::factory()
        ->forKey(HomepageSectionKey::Faq)
        ->visible()
        ->withContent(['items' => []])
        ->create();

    $html = Blade::render('<x-public-site.header />');

    expect($html)
        ->toContain(route('home').'#shop')
        ->not->toContain(route('home').'#about')
        ->not->toContain(route('home').'#faq');
});

it('renders the faq link only when visible faq content exists', function (): void {
    HomepageSection::factory()
        ->forKey(HomepageSectionKey::Faq)
        ->visible()
        ->withContent([
            'items' => [
                [
                    'question' => 'Do you deliver nationwide?',
                    'answer' => 'Yes, approved delivery options are available.',
                    'is_visible' => true,
                ],
            ],
        ])
        ->create();

    $html = Blade::render('<x-public-site.header />');

    expect($html)
        ->toContain(route('home').'#faq')
        ->toContain('FAQ');
});

it('renders shop now only for a valid https url', function (): void {
    SiteSetting::query()
        ->where('key', 'primary_shop_url')
        ->update([
            'value' => 'http://example.com/shop',
        ]);

    $invalidHtml = Blade::render('<x-public-site.header />');

    expect($invalidHtml)
        ->not->toContain('Shop Now');

    SiteSetting::query()
        ->where('key', 'primary_shop_url')
        ->update([
            'value' => 'https://example.com/shop',
        ]);

    $validHtml = Blade::render('<x-public-site.header />');

    expect($validHtml)
        ->toContain('https://example.com/shop')
        ->toContain('Shop Now')
        ->toContain('rel="noopener noreferrer"');
});

it('renders the accessible public page shell', function (): void {
    $this->withoutVite();

    $response = $this->get(route('home'));

    $response
        ->assertOk()
        ->assertSee('Skip to main content')
        ->assertSee('href="#main-content"', false)
        ->assertSee('id="main-content"', false)
        ->assertSee('bg-brand-canvas', false);
});
