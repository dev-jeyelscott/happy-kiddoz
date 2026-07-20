<?php

use App\Enums\HomepageSectionKey;

it('defines the approved homepage section keys in canonical order', function () {
    expect(HomepageSectionKey::values())->toBe([
        'hero',
        'categories',
        'about',
        'advantages',
        'reviews',
        'faq',
        'contact',
    ]);
});

it('provides the approved navigation anchors', function () {
    expect(HomepageSectionKey::Hero->anchor())->toBeNull()
        ->and(HomepageSectionKey::Categories->anchor())->toBe('shop')
        ->and(HomepageSectionKey::About->anchor())->toBe('about')
        ->and(HomepageSectionKey::Advantages->anchor())->toBe('why-us')
        ->and(HomepageSectionKey::Reviews->anchor())->toBe('reviews')
        ->and(HomepageSectionKey::Faq->anchor())->toBe('faq')
        ->and(HomepageSectionKey::Contact->anchor())->toBe('contact');
});

it('provides the approved default section order', function () {
    expect(HomepageSectionKey::Hero->defaultSortOrder())->toBe(10)
        ->and(HomepageSectionKey::Categories->defaultSortOrder())->toBe(20)
        ->and(HomepageSectionKey::About->defaultSortOrder())->toBe(30)
        ->and(HomepageSectionKey::Advantages->defaultSortOrder())->toBe(40)
        ->and(HomepageSectionKey::Reviews->defaultSortOrder())->toBe(50)
        ->and(HomepageSectionKey::Faq->defaultSortOrder())->toBe(60)
        ->and(HomepageSectionKey::Contact->defaultSortOrder())->toBe(70);
});

it('keeps the faq hidden and optional by default', function () {
    expect(HomepageSectionKey::Faq->defaultVisibility())->toBeFalse()
        ->and(HomepageSectionKey::Faq->isRequiredForLaunch())->toBeFalse();
});

it('makes all other homepage sections visible and required by default', function (
    HomepageSectionKey $key,
) {
    expect($key->defaultVisibility())->toBeTrue()
        ->and($key->isRequiredForLaunch())->toBeTrue();
})->with([
    HomepageSectionKey::Hero,
    HomepageSectionKey::Categories,
    HomepageSectionKey::About,
    HomepageSectionKey::Advantages,
    HomepageSectionKey::Reviews,
    HomepageSectionKey::Contact,
]);
