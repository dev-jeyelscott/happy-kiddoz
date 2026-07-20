<?php

use App\Enums\HomepageSectionKey;
use App\Models\HomepageSection;
use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    HomepageSection::query()->create([
        'key' => HomepageSectionKey::Contact->value,
        'content' => [
            'heading' => "Let's build a happier space for your kiddos",
            'description' => 'Reach out and let us help you choose the right pieces.',
            'links_panel_heading' => 'Shop and follow us',
        ],
        'sort_order' => HomepageSectionKey::Contact->defaultSortOrder(),
        'is_visible' => true,
    ]);

    $settings = [
        'brand_name' => [
            'value' => 'Happy Kiddos',
            'group' => 'brand',
        ],
        'logo_path' => [
            'value' => null,
            'group' => 'brand',
        ],
        'shopee_url' => [
            'value' => 'https://shopee.ph/happy-kiddos',
            'group' => 'marketplace',
        ],
        'lazada_url' => [
            'value' => 'https://www.lazada.com.ph/shop/happy-kiddos',
            'group' => 'marketplace',
        ],
        'facebook_url' => [
            'value' => 'https://www.facebook.com/happy-kiddos',
            'group' => 'social',
        ],
        'instagram_url' => [
            'value' => 'https://www.instagram.com/happy-kiddos',
            'group' => 'social',
        ],
        'contact_email' => [
            'value' => 'hello@happykiddoz.test',
            'group' => 'contact',
        ],
        'contact_phone' => [
            'value' => '+63 999 123 4567',
            'group' => 'contact',
        ],
        'contact_viber' => [
            'value' => '0917 123 4567',
            'group' => 'contact',
        ],
        'contact_address' => [
            'value' => 'Valenzuela City, Metro Manila',
            'group' => 'contact',
        ],
        'contact_map_url' => [
            'value' => 'https://maps.google.com/?q=Valenzuela+City',
            'group' => 'contact',
        ],
        'privacy_policy_url' => [
            'value' => 'https://example.com/privacy',
            'group' => 'legal',
        ],
        'terms_url' => [
            'value' => 'https://example.com/terms',
            'group' => 'legal',
        ],
        'copyright_text' => [
            'value' => 'All rights reserved.',
            'group' => 'legal',
        ],
        'meta_title' => [
            'value' => 'Happy Kiddos',
            'group' => 'seo',
        ],
        'meta_description' => [
            'value' => 'Happy Kiddos homepage.',
            'group' => 'seo',
        ],
    ];

    foreach ($settings as $key => $attributes) {
        SiteSetting::query()->create([
            'key' => $key,
            ...$attributes,
        ]);
    }
});

it('renders the configured contact call to action', function (): void {
    $response = $this->get(route('home'));

    $response
        ->assertOk()
        ->assertSee("Let's build a happier space for your kiddos")
        ->assertSee('Reach out and let us help you choose the right pieces.')
        ->assertSee('Shop and follow us')
        ->assertSee('hello@happykiddoz.test')
        ->assertSee('+63 999 123 4567')
        ->assertSee('0917 123 4567 (Viber)')
        ->assertSee('Valenzuela City, Metro Manila');
});

it('renders safe email telephone and external links', function (): void {
    $response = $this->get(route('home'));

    $response
        ->assertOk()
        ->assertSee(
            'href="mailto:hello@happykiddoz.test"',
            false,
        )
        ->assertSee(
            'href="tel:+639991234567"',
            false,
        )
        ->assertSee(
            'href="tel:09171234567"',
            false,
        )
        ->assertSee(
            'href="https://shopee.ph/happy-kiddos"',
            false,
        )
        ->assertSee(
            'rel="noopener noreferrer"',
            false,
        );
});

it('renders the footer using global settings', function (): void {
    $response = $this->get(route('home'));

    $response
        ->assertOk()
        ->assertSee('Happy Kiddos')
        ->assertSee('© '.now()->year.' Happy Kiddos. All rights reserved.')
        ->assertSee('Privacy Policy')
        ->assertSee('Terms of Service')
        ->assertSee(
            'href="https://example.com/privacy"',
            false,
        )
        ->assertSee(
            'href="https://example.com/terms"',
            false,
        );
});

it('does not render the contact section when it is hidden', function (): void {
    HomepageSection::query()
        ->where('key', HomepageSectionKey::Contact->value)
        ->update(['is_visible' => false]);

    $this->get(route('home'))
        ->assertOk()
        ->assertDontSee("Let's build a happier space for your kiddos")
        ->assertDontSee('id="contact"', false);
});

it('does not render malformed external URLs', function (): void {
    SiteSetting::query()
        ->where('key', 'shopee_url')
        ->update(['value' => 'javascript:alert(1)']);

    SiteSetting::query()
        ->where('key', 'privacy_policy_url')
        ->update(['value' => 'not-a-url']);

    $this->get(route('home'))
        ->assertOk()
        ->assertDontSee('javascript:alert(1)', false)
        ->assertDontSee('not-a-url', false)
        ->assertDontSee('Privacy Policy');
});
