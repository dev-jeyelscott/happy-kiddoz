<?php

use App\Filament\Resources\HomepageSections\HomepageSectionResource;
use App\Filament\Resources\HomepageSections\Pages\EditHomepageSection;
use App\Filament\Resources\HomepageSections\Pages\ListHomepageSections;
use App\Models\HomepageSection;
use App\Models\User;
use Database\Seeders\HomepageSectionSeeder;
use Livewire\Livewire;

beforeEach(function (): void {
    $this->actingAs(User::factory()->create());

    $this->seed(HomepageSectionSeeder::class);
});

it('loads the homepage section list', function (): void {
    $sections = HomepageSection::query()
        ->orderBy('sort_order')
        ->get();

    Livewire::test(ListHomepageSections::class)
        ->assertOk()
        ->assertCanSeeTableRecords($sections, inOrder: true);
});

it('registers only list and edit resource pages', function (): void {
    $pages = HomepageSectionResource::getPages();

    expect($pages)
        ->toHaveKeys(['index', 'edit'])
        ->not->toHaveKey('create');
});

it('allows an administrator to update homepage content', function (): void {
    $hero = HomepageSection::query()
        ->where('key', 'hero')
        ->firstOrFail();

    $content = [
        ...$hero->content,
        'eyebrow' => 'Updated by Happy Kiddoz',
    ];

    Livewire::test(EditHomepageSection::class, [
        'record' => $hero->getRouteKey(),
    ])
        ->fillForm([
            'is_visible' => false,
            'content' => $content,
        ])
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertNotified();

    $hero->refresh();

    expect($hero->is_visible)
        ->toBeFalse()
        ->and($hero->content['eyebrow'])
        ->toBe('Updated by Happy Kiddoz');
});

it('does not allow the section key to be changed', function (): void {
    $hero = HomepageSection::query()
        ->where('key', 'hero')
        ->firstOrFail();

    Livewire::test(EditHomepageSection::class, [
        'record' => $hero->getRouteKey(),
    ])
        ->fillForm([
            'key' => 'faq',
            'content' => $hero->content,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $hero->refresh();

    $key = $hero->key instanceof BackedEnum
        ? $hero->key->value
        : $hero->key;

    expect($key)->toBe('hero');
});

it('forbids creating and deleting fixed sections', function (): void {
    $user = User::factory()->create();

    $hero = HomepageSection::query()
        ->where('key', 'hero')
        ->firstOrFail();

    expect($user->can('create', HomepageSection::class))
        ->toBeFalse()
        ->and($user->can('delete', $hero))
        ->toBeFalse();
});

it('updates categories using the canonical content field names', function (): void {
    $categories = HomepageSection::query()
        ->where('key', 'categories')
        ->firstOrFail();

    $content = $categories->content;
    $content['items'][0]['description'] =
        'Updated category description.';
    $content['items'][0]['external_url'] =
        'https://shopee.ph/happy-kiddoz-tables';

    Livewire::test(EditHomepageSection::class, [
        'record' => $categories->getRouteKey(),
    ])
        ->fillForm([
            'is_visible' => true,
            'content' => $content,
        ])
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertNotified();

    $categories->refresh();

    expect($categories->content['items'][0])
        ->toHaveKeys([
            'title',
            'description',
            'image_path',
            'image_alt',
            'external_url',
            'sort_order',
        ])
        ->and($categories->content['items'][0]['description'])
        ->toBe('Updated category description.')
        ->and($categories->content['items'][0]['external_url'])
        ->toBe('https://shopee.ph/happy-kiddoz-tables');
});

it('updates about content using the canonical image path field', function (): void {
    $about = HomepageSection::query()
        ->where('key', 'about')
        ->firstOrFail();

    $content = $about->content;
    $content['paragraphs'][0] = 'Updated About story paragraph.';

    Livewire::test(EditHomepageSection::class, [
        'record' => $about->getRouteKey(),
    ])
        ->fillForm([
            'is_visible' => true,
            'content' => $content,
        ])
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertNotified();

    $about->refresh();

    expect($about->content)
        ->toHaveKeys([
            'eyebrow',
            'heading',
            'paragraphs',
            'image_path',
            'image_alt',
        ])
        ->not->toHaveKey('image')
        ->and($about->content['paragraphs'][0])
        ->toBe('Updated About story paragraph.')
        ->and($about->content['image_path'])
        ->not->toBeNull();
});
