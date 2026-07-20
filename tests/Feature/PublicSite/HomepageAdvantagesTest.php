<?php

use App\Enums\HomepageAdvantageIcon;
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

it('renders the approved advantages after the about section', function (): void {
    $response = $this->get(route('home'));

    $response
        ->assertOk()
        ->assertSeeInOrder([
            'id="about"',
            'id="why-us"',
        ], escape: false)
        ->assertSee(
            'aria-labelledby="homepage-advantages-heading"',
            escape: false,
        )
        ->assertSee('Why Parents and Schools Choose Us')
        ->assertSee('The Happy Kiddoz Advantage')
        ->assertSeeInOrder([
            'Pioneer in Montessori Furniture',
            'Natural &amp; non-toxic',
            'Montessori-aligned',
            'Built to last',
            'Nationwide delivery',
        ], escape: false)
        ->assertSee(
            'data-advantage-icon="sparkles"',
            escape: false,
        )
        ->assertSee(
            'data-advantage-icon="leaf"',
            escape: false,
        )
        ->assertSee(
            'data-advantage-icon="blocks"',
            escape: false,
        )
        ->assertSee(
            'data-advantage-icon="shield"',
            escape: false,
        )
        ->assertSee(
            'data-advantage-icon="truck"',
            escape: false,
        );

    expect(
        substr_count($response->getContent(), 'data-advantage-card'),
    )->toBe(5);
});

it('does not render the advantages when the section is hidden', function (): void {
    HomepageSection::query()
        ->where('key', HomepageSectionKey::Advantages->value)
        ->update([
            'is_visible' => false,
        ]);

    $this->get(route('home'))
        ->assertOk()
        ->assertDontSee('id="why-us"', escape: false)
        ->assertDontSee('The Happy Kiddoz Advantage');
});

it('renders advantage cards using their configured sort order', function (): void {
    $advantages = HomepageSection::query()
        ->where('key', HomepageSectionKey::Advantages->value)
        ->firstOrFail();

    $content = $advantages->content;
    $content['items'] = [
        [
            'icon' => HomepageAdvantageIcon::Truck->value,
            'title' => 'Second approved advantage',
            'description' => 'This card should render second.',
            'sort_order' => 20,
        ],
        [
            'icon' => HomepageAdvantageIcon::Sparkles->value,
            'title' => 'First approved advantage',
            'description' => 'This card should render first.',
            'sort_order' => 10,
        ],
    ];

    $advantages->update([
        'content' => $content,
    ]);

    $this->get(route('home'))
        ->assertOk()
        ->assertSeeInOrder([
            'First approved advantage',
            'Second approved advantage',
        ]);
});

it('rejects malformed and unsupported advantage records', function (): void {
    $advantages = HomepageSection::query()
        ->where('key', HomepageSectionKey::Advantages->value)
        ->firstOrFail();

    $content = $advantages->content;
    $content['items'] = [
        [
            'icon' => HomepageAdvantageIcon::Shield->value,
            'title' => 'Valid advantage',
            'description' => 'This valid card should be rendered.',
            'sort_order' => 10,
        ],
        [
            'icon' => '<svg onload="alert(1)">',
            'title' => 'Unsafe icon record',
            'description' => 'This card must not be rendered.',
            'sort_order' => 20,
        ],
        [
            'icon' => HomepageAdvantageIcon::Leaf->value,
            'title' => '',
            'description' => 'This incomplete card must not be rendered.',
            'sort_order' => 30,
        ],
    ];

    $advantages->update([
        'content' => $content,
    ]);

    $response = $this->get(route('home'));

    $response
        ->assertOk()
        ->assertSee('Valid advantage')
        ->assertDontSee('Unsafe icon record')
        ->assertDontSee(
            '<svg onload="alert(1)">',
            escape: false,
        );

    expect(
        substr_count($response->getContent(), 'data-advantage-card'),
    )->toBe(1);
});

it('escapes advantage content supplied through the cms', function (): void {
    $advantages = HomepageSection::query()
        ->where('key', HomepageSectionKey::Advantages->value)
        ->firstOrFail();

    $content = $advantages->content;
    $content['heading'] = '<script>alert("heading")</script>';
    $content['items'] = [
        [
            'icon' => HomepageAdvantageIcon::Sparkles->value,
            'title' => '<img src=x onerror=alert("title")>',
            'description' => '<script>alert("description")</script>',
            'sort_order' => 10,
        ],
    ];

    $advantages->update([
        'content' => $content,
    ]);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('&lt;script&gt;', escape: false)
        ->assertSee('&lt;img', escape: false)
        ->assertDontSee(
            '<script>alert("heading")</script>',
            escape: false,
        )
        ->assertDontSee(
            '<img src=x onerror=alert("title")>',
            escape: false,
        )
        ->assertDontSee(
            '<script>alert("description")</script>',
            escape: false,
        );
});

it('seeds only supported advantage icon identifiers', function (): void {
    $advantages = HomepageSection::query()
        ->where('key', HomepageSectionKey::Advantages->value)
        ->firstOrFail();

    $icons = collect($advantages->content['items'])
        ->pluck('icon')
        ->all();

    expect($icons)->toBe([
        HomepageAdvantageIcon::Sparkles->value,
        HomepageAdvantageIcon::Leaf->value,
        HomepageAdvantageIcon::Blocks->value,
        HomepageAdvantageIcon::Shield->value,
        HomepageAdvantageIcon::Truck->value,
    ]);
});
