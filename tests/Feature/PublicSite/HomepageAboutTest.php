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

it('renders the approved about content after the category section', function (): void {
    $response = $this->get(route('home'));

    $response
        ->assertOk()
        ->assertSeeInOrder([
            'id="shop"',
            'id="about"',
        ], escape: false)
        ->assertSee('aria-labelledby="homepage-about-heading"', escape: false)
        ->assertSee('About Us')
        ->assertSee(
            'Happy Kiddoz was founded in 2016, inspired by a personal experience as a parent.',
        )
        ->assertSeeInOrder([
            'When our first child was born',
            'Over the years, our two children became',
            'Today, our mission is simple',
        ])
        ->assertSee('loading="lazy"', escape: false)
        ->assertSee('width="1200"', escape: false)
        ->assertSee('height="1000"', escape: false);

    expect(
        substr_count($response->getContent(), 'data-about-paragraph'),
    )->toBe(3);
});

it('does not render the about section when it is hidden', function (): void {
    HomepageSection::query()
        ->where('key', HomepageSectionKey::About->value)
        ->update([
            'is_visible' => false,
        ]);

    $this->get(route('home'))
        ->assertOk()
        ->assertDontSee('id="about"', escape: false)
        ->assertDontSee(
            'Happy Kiddoz was founded in 2016, inspired by a personal experience as a parent.',
        );
});

it('removes empty about paragraphs before rendering', function (): void {
    $about = HomepageSection::query()
        ->where('key', HomepageSectionKey::About->value)
        ->firstOrFail();

    $content = $about->content;
    $content['paragraphs'] = [
        'First approved story paragraph.',
        '   ',
        null,
        'Second approved story paragraph.',
    ];

    $about->update([
        'content' => $content,
    ]);

    $response = $this->get(route('home'));

    $response
        ->assertOk()
        ->assertSeeInOrder([
            'First approved story paragraph.',
            'Second approved story paragraph.',
        ]);

    expect(
        substr_count($response->getContent(), 'data-about-paragraph'),
    )->toBe(2);
});

it('escapes about content supplied through the cms', function (): void {
    $about = HomepageSection::query()
        ->where('key', HomepageSectionKey::About->value)
        ->firstOrFail();

    $content = $about->content;
    $content['heading'] = '<script>alert("about-heading")</script>';
    $content['paragraphs'] = [
        '<img src=x onerror=alert("about-paragraph")>',
    ];

    $about->update([
        'content' => $content,
    ]);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('&lt;script&gt;', escape: false)
        ->assertSee('&lt;img', escape: false)
        ->assertDontSee(
            '<script>alert("about-heading")</script>',
            escape: false,
        )
        ->assertDontSee(
            '<img src=x onerror=alert("about-paragraph")>',
            escape: false,
        );
});
