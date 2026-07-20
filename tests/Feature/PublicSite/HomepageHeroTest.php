<?php

use App\Enums\HomepageSectionKey;
use App\Models\HomepageSection;
use App\Models\SiteSetting;
use Database\Seeders\HomepageSectionSeeder;
use Database\Seeders\SiteSettingSeeder;
use Illuminate\Support\Facades\Storage;

beforeEach(function (): void {
    Storage::fake('public');

    $this->seed([
        SiteSettingSeeder::class,
        HomepageSectionSeeder::class,
    ]);
});

it('renders the approved Happy Kiddos hero content', function (): void {
    $response = $this->get(route('home'));

    $response
        ->assertOk()
        ->assertSee(
            'Premium Montessori furniture and materials for curious little minds',
        )
        ->assertSee('Beautiful spaces where little ones grow.')
        ->assertSee(
            'Thoughtfully designed Montessori furniture and materials',
        )
        ->assertSee('Loved by many Filipino families and school owners since 2016!')
        ->assertSee('loading="eager"', escape: false)
        ->assertSee('fetchpriority="high"', escape: false)
        ->assertSee('width="948"', escape: false)
        ->assertSee('height="948"', escape: false)
        ->assertDontSee('Fine Dining Restaurant')
        ->assertDontSee('href="#"', escape: false);

    expect(
        substr_count($response->getContent(), '<h1'),
    )->toBe(1);
});

it('renders only configured https marketplace and social links', function (): void {
    $links = [
        'shopee_url' => 'https://shopee.ph/happy-kiddos',
        'lazada_url' => 'https://www.lazada.com.ph/shop/happy-kiddos',
        'facebook_url' => 'https://www.facebook.com/happykiddoz',
        'instagram_url' => 'https://www.instagram.com/happykiddoz',
    ];

    foreach ($links as $key => $value) {
        SiteSetting::query()
            ->where('key', $key)
            ->update(['value' => $value]);
    }

    $response = $this->get(route('home'));

    $response
        ->assertOk()
        ->assertSee('Shopee')
        ->assertSee('Lazada')
        ->assertSee('Facebook')
        ->assertSee('Instagram')
        ->assertSee(
            'href="https://shopee.ph/happy-kiddos"',
            escape: false,
        )
        ->assertSee(
            'href="https://www.lazada.com.ph/shop/happy-kiddos"',
            escape: false,
        )
        ->assertSee(
            'target="_blank"',
            escape: false,
        )
        ->assertSee(
            'rel="noopener noreferrer"',
            escape: false,
        );
});

it('rejects non-https hero links', function (): void {
    SiteSetting::query()
        ->where('key', 'shopee_url')
        ->update([
            'value' => 'http://example.test/not-secure',
        ]);

    $this->get(route('home'))
        ->assertOk()
        ->assertDontSee(
            'http://example.test/not-secure',
            escape: false,
        );
});

it('does not render the hero when the section is hidden', function (): void {
    HomepageSection::query()
        ->where('key', HomepageSectionKey::Hero->value)
        ->update([
            'is_visible' => false,
        ]);

    $this->get(route('home'))
        ->assertOk()
        ->assertDontSee('Beautiful spaces where little ones grow.');
});
