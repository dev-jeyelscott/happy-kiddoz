<?php

use App\Enums\HomepageSectionKey;
use App\Models\HomepageSection;
use Illuminate\Database\QueryException;
use LogicException;

it('casts homepage section attributes to their expected types', function () {
    $section = HomepageSection::factory()
        ->forKey(HomepageSectionKey::About)
        ->withContent([
            'heading' => 'About Happy Kiddoz',
            'paragraphs' => [
                'First paragraph.',
                'Second paragraph.',
            ],
        ])
        ->create();

    $section->refresh();

    expect($section->key)->toBe(HomepageSectionKey::About)
        ->and($section->content)->toBeArray()
        ->and($section->content['heading'])->toBe('About Happy Kiddoz')
        ->and($section->sort_order)->toBeInt()
        ->and($section->is_visible)->toBeBool();
});

it('returns only publicly visible homepage sections', function () {
    HomepageSection::factory()
        ->forKey(HomepageSectionKey::Hero)
        ->create();

    HomepageSection::factory()
        ->forKey(HomepageSectionKey::Faq)
        ->hidden()
        ->create();

    $sections = HomepageSection::query()
        ->visible()
        ->ordered()
        ->get();

    expect($sections)->toHaveCount(1)
        ->and($sections->first()?->key)->toBe(HomepageSectionKey::Hero);
});

it('returns homepage sections in configured order', function () {
    HomepageSection::factory()
        ->forKey(HomepageSectionKey::Reviews)
        ->create();

    HomepageSection::factory()
        ->forKey(HomepageSectionKey::Hero)
        ->create();

    HomepageSection::factory()
        ->forKey(HomepageSectionKey::Categories)
        ->create();

    $keys = HomepageSection::query()
        ->ordered()
        ->get()
        ->map(
            static fn (HomepageSection $section): HomepageSectionKey => $section->key,
        )
        ->all();

    expect($keys)->toBe([
        HomepageSectionKey::Hero,
        HomepageSectionKey::Categories,
        HomepageSectionKey::Reviews,
    ]);
});

it('does not allow duplicate homepage section keys', function () {
    HomepageSection::factory()
        ->forKey(HomepageSectionKey::Hero)
        ->create();

    expect(
        fn () => HomepageSection::factory()
            ->forKey(HomepageSectionKey::Hero)
            ->create(),
    )->toThrow(QueryException::class);
});

it('does not allow a persisted homepage section key to change', function () {
    $section = HomepageSection::factory()
        ->forKey(HomepageSectionKey::Hero)
        ->create();

    $section->key = HomepageSectionKey::Contact;

    expect(fn () => $section->save())
        ->toThrow(
            LogicException::class,
            'A persisted homepage section key cannot be changed.',
        );
});

it('uses the enum defaults when creating a section through its factory', function () {
    $faq = HomepageSection::factory()
        ->forKey(HomepageSectionKey::Faq)
        ->create();

    expect($faq->sort_order)->toBe(60)
        ->and($faq->is_visible)->toBeFalse();
});
