<?php

use App\Enums\HomepageSectionKey;
use App\Models\HomepageSection;
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

it('renders the approved category content in its configured order', function (): void {
    $response = $this->get(route('home'));

    $response
        ->assertOk()
        ->assertSee('id="shop"', escape: false)
        ->assertSee('Shop by Category')
        ->assertSee('Everything for a purposeful play space')
        ->assertSeeInOrder([
            'Tables & Chairs',
            'Bookshelves',
            'Toy Organizers',
            'Learning Towers',
            'Play Gyms',
            'Montessori Toys',
            'STEM Toys',
            'Storage and Trays',
        ])
        ->assertSee('loading="lazy"', escape: false)
        ->assertSee('width="800"', escape: false)
        ->assertSee('height="600"', escape: false);

    expect(
        substr_count($response->getContent(), 'data-category-card'),
    )->toBe(8);
});

it('orders category cards by their explicit sort order', function (): void {
    $section = HomepageSection::query()
        ->where('key', HomepageSectionKey::Categories->value)
        ->firstOrFail();

    $content = $section->content;
    $content['items'] = array_reverse($content['items']);

    $section->update([
        'content' => $content,
    ]);

    $this->get(route('home'))
        ->assertOk()
        ->assertSeeInOrder([
            'Tables & Chairs',
            'Bookshelves',
            'Toy Organizers',
            'Learning Towers',
            'Play Gyms',
            'Montessori Toys',
            'STEM Toys',
            'Storage and Trays',
        ]);
});

it('links only categories with an approved https destination', function (): void {
    $section = HomepageSection::query()
        ->where('key', HomepageSectionKey::Categories->value)
        ->firstOrFail();

    $content = $section->content;
    $content['items'][0]['external_url'] =
        'https://shopee.ph/happy-kiddos-tables-and-chairs';

    $section->update([
        'content' => $content,
    ]);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee(
            'href="https://shopee.ph/happy-kiddos-tables-and-chairs"',
            escape: false,
        )
        ->assertSee('target="_blank"', escape: false)
        ->assertSee(
            'rel="noopener noreferrer"',
            escape: false,
        );
});

it('rejects non-https category destinations', function (): void {
    $section = HomepageSection::query()
        ->where('key', HomepageSectionKey::Categories->value)
        ->firstOrFail();

    $content = $section->content;
    $content['items'][0]['external_url'] =
        'http://example.test/not-secure';

    $section->update([
        'content' => $content,
    ]);

    $this->get(route('home'))
        ->assertOk()
        ->assertDontSee(
            'http://example.test/not-secure',
            escape: false,
        );
});

it('does not render categories when the section is hidden', function (): void {
    HomepageSection::query()
        ->where('key', HomepageSectionKey::Categories->value)
        ->update([
            'is_visible' => false,
        ]);

    $this->get(route('home'))
        ->assertOk()
        ->assertDontSee('Everything for a purposeful play space')
        ->assertDontSee('id="shop"', escape: false);
});

it('escapes category content supplied through the cms', function (): void {
    $section = HomepageSection::query()
        ->where('key', HomepageSectionKey::Categories->value)
        ->firstOrFail();

    $content = $section->content;
    $content['items'][0]['title'] = '<script>alert("xss")</script>';

    $section->update([
        'content' => $content,
    ]);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('&lt;script&gt;', escape: false)
        ->assertDontSee('<script>alert("xss")</script>', escape: false);
});
