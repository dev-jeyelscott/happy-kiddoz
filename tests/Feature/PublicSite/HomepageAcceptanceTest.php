<?php

use App\Models\SiteSetting;
use Database\Seeders\HomepageSectionSeeder;
use Database\Seeders\SiteSettingSeeder;
use Illuminate\Support\Facades\Storage;

beforeEach(function (): void {
    /*
     * Homepage and branding seeders publish images to the public disk.
     * Fake the disk so tests never write generated files into local storage.
     */
    Storage::fake('public');

    $this->seed([
        SiteSettingSeeder::class,
        HomepageSectionSeeder::class,
    ]);
});

it('renders the configured homepage metadata without duplicating the brand name', function (): void {
    SiteSetting::query()
        ->where('key', 'brand_name')
        ->update([
            'value' => 'Happy Kiddos',
        ]);

    SiteSetting::query()
        ->where('key', 'meta_title')
        ->update([
            'value' => 'Happy Kiddos | Montessori Furniture Philippines',
        ]);

    SiteSetting::query()
        ->where('key', 'meta_description')
        ->update([
            'value' => 'Montessori furniture and learning materials for Filipino families.',
        ]);

    $response = $this->get(route('home'));

    $response
        ->assertOk()
        ->assertSee(
            '<title>Happy Kiddos | Montessori Furniture Philippines</title>',
            escape: false,
        )
        ->assertSee(
            '<meta name="description" content="Montessori furniture and learning materials for Filipino families.">',
            escape: false,
        )
        ->assertDontSee(
            'Happy Kiddos | Montessori Furniture Philippines | Happy Kiddos',
        );

    expect(
        substr_count(
            $response->getContent(),
            '<title>Happy Kiddos | Montessori Furniture Philippines</title>',
        ),
    )->toBe(1);
});

it('renders visible homepage sections in the approved composition order', function (): void {
    $this->get(route('home'))
        ->assertOk()
        ->assertSeeInOrder([
            'homepage-hero-heading',
            'homepage-categories-heading',
            'homepage-about-heading',
            'homepage-advantages-heading',
            'homepage-reviews-heading',
            'homepage-contact-heading',
        ], escape: false);
});

it('does not render the faq between reviews and contact while faq is hidden', function (): void {
    $response = $this->get(route('home'));

    $response
        ->assertOk()
        ->assertDontSee('id="faq"', escape: false)
        ->assertDontSee('href="http://', escape: false)
        ->assertDontSee('href="#"', escape: false);
});
