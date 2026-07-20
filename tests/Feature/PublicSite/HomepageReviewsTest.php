<?php

use App\Enums\HomepageSectionKey;
use App\Models\HomepageSection;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders complete visible reviews in their configured order', function (): void {
    HomepageSection::factory()
        ->forKey(HomepageSectionKey::Reviews)
        ->visible()
        ->withContent([
            'eyebrow' => 'Real Reviews From Real People',
            'heading' => 'Over 20,000 positive reviews!',
            'items' => [
                [
                    'customer_name' => 'buyer_one',
                    'customer_handle' => null,
                    'rating' => 5,
                    'avatar_initial' => 'b',
                    'quote' => 'The first approved customer review.',
                ],
                [
                    'customer_name' => 'Buyer Two',
                    'customer_handle' => 'buyer_two',
                    'rating' => 4,
                    'avatar_initial' => '',
                    'quote' => 'The second approved customer review.',
                ],
            ],
        ])
        ->create();

    $response = $this->get(route('home'));

    $response
        ->assertOk()
        ->assertSee('id="reviews"', false)
        ->assertSee(
            'href="'.route('home').'#reviews"',
            false,
        )
        ->assertSeeText('Real Reviews From Real People')
        ->assertSeeText('Over 20,000 positive reviews!')
        ->assertSeeText('buyer_one')
        ->assertSeeText('buyer_two')
        ->assertSee(
            'aria-label="5 out of 5 stars"',
            false,
        )
        ->assertSee(
            'aria-label="4 out of 5 stars"',
            false,
        )
        ->assertSeeInOrder([
            'The first approved customer review.',
            'The second approved customer review.',
        ]);
});

it('does not render reviews or their navigation link when the section is hidden', function (): void {
    HomepageSection::factory()
        ->forKey(HomepageSectionKey::Reviews)
        ->hidden()
        ->withContent([
            'eyebrow' => 'Hidden Reviews',
            'heading' => 'This should not render',
            'items' => [
                [
                    'customer_name' => 'hidden_customer',
                    'customer_handle' => null,
                    'rating' => 5,
                    'avatar_initial' => 'h',
                    'quote' => 'Hidden customer review.',
                ],
            ],
        ])
        ->create();

    $response = $this->get(route('home'));

    $response
        ->assertOk()
        ->assertDontSee('id="reviews"', false)
        ->assertDontSee('#reviews', false)
        ->assertDontSeeText('Hidden customer review.');
});

it('does not create an empty section or broken link from incomplete review content', function (): void {
    HomepageSection::factory()
        ->forKey(HomepageSectionKey::Reviews)
        ->visible()
        ->withContent([
            'eyebrow' => 'Reviews',
            'heading' => 'Customer Reviews',
            'items' => [
                [
                    'customer_name' => 'incomplete_customer',
                    'customer_handle' => null,
                    'rating' => 8,
                    'avatar_initial' => 'i',
                    'quote' => '',
                ],
            ],
        ])
        ->create();

    $response = $this->get(route('home'));

    $response
        ->assertOk()
        ->assertDontSee('id="reviews"', false)
        ->assertDontSee('#reviews', false)
        ->assertDontSeeText('incomplete_customer');
});

it('escapes review content supplied through the cms', function (): void {
    $maliciousQuote = '<script>alert("xss")</script>';

    HomepageSection::factory()
        ->forKey(HomepageSectionKey::Reviews)
        ->visible()
        ->withContent([
            'eyebrow' => 'Reviews',
            'heading' => 'Customer Reviews',
            'items' => [
                [
                    'customer_name' => 'safe_customer',
                    'customer_handle' => null,
                    'rating' => 5,
                    'avatar_initial' => 's',
                    'quote' => $maliciousQuote,
                ],
            ],
        ])
        ->create();

    $response = $this->get(route('home'));

    $response
        ->assertOk()
        ->assertSee(e($maliciousQuote), false)
        ->assertDontSee($maliciousQuote, false);
});
