<?php

use App\Enums\HomepageSectionKey;
use App\Models\HomepageSection;
use App\Models\SiteSetting;
use Database\Seeders\HomepageSectionSeeder;
use Database\Seeders\SiteSettingSeeder;

it('seeds every canonical homepage section in the approved order', function () {
    $this->seed(HomepageSectionSeeder::class);

    $sections = HomepageSection::query()
        ->ordered()
        ->get();

    expect($sections)
        ->toHaveCount(count(HomepageSectionKey::cases()))
        ->and(
            $sections
                ->map(
                    static fn (HomepageSection $section): HomepageSectionKey => $section->key,
                )
                ->all(),
        )
        ->toBe(HomepageSectionKey::cases());
});

it('can run the homepage section seeder repeatedly without duplicates', function () {
    $this->seed(HomepageSectionSeeder::class);
    $this->seed(HomepageSectionSeeder::class);

    $this->assertDatabaseCount(
        'homepage_sections',
        count(HomepageSectionKey::cases()),
    );
});

it('seeds all eight approved product categories in their required order', function () {
    $this->seed(HomepageSectionSeeder::class);

    $categories = HomepageSection::query()
        ->where('key', HomepageSectionKey::Categories->value)
        ->firstOrFail();

    expect($categories->content['items'])
        ->toHaveCount(8)
        ->and(
            collect($categories->content['items'])
                ->pluck('title')
                ->all(),
        )
        ->toBe([
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

it('keeps approved reviews visible and faq hidden until faq content exists', function () {
    $this->seed(HomepageSectionSeeder::class);

    $reviews = HomepageSection::query()
        ->where('key', HomepageSectionKey::Reviews->value)
        ->firstOrFail();

    $faq = HomepageSection::query()
        ->where('key', HomepageSectionKey::Faq->value)
        ->firstOrFail();

    expect($reviews->is_visible)
        ->toBeTrue()
        ->and($reviews->content['items'])
        ->toHaveCount(4)
        ->and($faq->is_visible)
        ->toBeFalse()
        ->and($faq->content['items'])
        ->toBe([]);
});

it('seeds the approved marketplace social and contact settings', function () {
    $this->seed(SiteSettingSeeder::class);

    expect(SiteSetting::value('brand_name'))
        ->toBe('Happy Kiddos')
        ->and(SiteSetting::value('primary_shop_url'))
        ->toBe('https://shopee.ph/bugs_2wan')
        ->and(SiteSetting::value('shopee_url'))
        ->toBe('https://shopee.ph/bugs_2wan')
        ->and(SiteSetting::value('lazada_url'))
        ->toBe('https://www.lazada.com.ph/tag/happy-kiddo/')
        ->and(SiteSetting::value('facebook_url'))
        ->toBe('https://www.facebook.com/happykiddosph/')
        ->and(SiteSetting::value('instagram_url'))
        ->toBe('https://www.instagram.com/happykiddosph/?hl=en')
        ->and(SiteSetting::value('contact_email'))
        ->toBe('happykiddosph@gmail.com')
        ->and(SiteSetting::value('contact_address'))
        ->toBe('Valenzuela City, Metro Manila, Philippines');
});

it('can run the site setting seeder repeatedly without duplicate keys', function () {
    $this->seed(SiteSettingSeeder::class);
    $this->seed(SiteSettingSeeder::class);

    expect(
        SiteSetting::query()
            ->select('key')
            ->groupBy('key')
            ->havingRaw('COUNT(*) > 1')
            ->exists(),
    )->toBeFalse();
});

it('seeds the hero using the canonical admin-compatible content contract', function (): void {
    $this->seed(HomepageSectionSeeder::class);

    $hero = HomepageSection::query()
        ->where('key', HomepageSectionKey::Hero->value)
        ->firstOrFail();

    expect(array_keys($hero->content))->toBe([
        'eyebrow',
        'headline',
        'description',
        'image_path',
        'image_alt',
        'rating_value',
        'rating_statement',
    ]);
});
