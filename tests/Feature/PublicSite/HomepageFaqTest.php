<?php

use App\Enums\HomepageSectionKey;
use App\Models\HomepageSection;

it('renders a visible and populated FAQ section with its navigation link', function () {
    HomepageSection::factory()
        ->forKey(HomepageSectionKey::Faq)
        ->visible()
        ->withContent([
            'eyebrow' => 'Frequently Asked Questions',
            'heading' => 'How can we help?',
            'items' => [
                [
                    'question' => 'Do you deliver nationwide?',
                    'answer' => 'Yes. Available delivery options depend on the product and destination.',
                ],
                [
                    'question' => 'Can schools place an order?',
                    'answer' => 'Schools may contact the Happy Kiddoz team for product assistance.',
                ],
            ],
        ])
        ->create();

    $response = $this->get(route('home'));

    $response
        ->assertOk()
        ->assertSee('href="'.route('home').'#faq"', false)
        ->assertSee('id="faq"', false)
        ->assertSee('name="homepage-faq"', false)
        ->assertSee('<summary', false)
        ->assertSeeText('How can we help?')
        ->assertSeeText('Do you deliver nationwide?')
        ->assertSeeText('Can schools place an order?');
});

it('does not render a hidden FAQ section or its navigation link', function () {
    HomepageSection::factory()
        ->forKey(HomepageSectionKey::Faq)
        ->hidden()
        ->withContent([
            'eyebrow' => 'Frequently Asked Questions',
            'heading' => 'How can we help?',
            'items' => [
                [
                    'question' => 'Do you deliver nationwide?',
                    'answer' => 'Yes.',
                ],
            ],
        ])
        ->create();

    $response = $this->get(route('home'));

    $response
        ->assertOk()
        ->assertDontSee('href="'.route('home').'#faq"', false)
        ->assertDontSee('id="faq"', false)
        ->assertDontSeeText('Do you deliver nationwide?');
});

it('does not render an enabled FAQ section without complete content', function () {
    HomepageSection::factory()
        ->forKey(HomepageSectionKey::Faq)
        ->visible()
        ->withContent([
            'eyebrow' => 'Frequently Asked Questions',
            'heading' => 'How can we help?',
            'items' => [
                [
                    'question' => 'Incomplete question',
                    'answer' => '',
                ],
                [
                    'question' => '',
                    'answer' => 'Incomplete answer',
                ],
            ],
        ])
        ->create();

    $response = $this->get(route('home'));

    $response
        ->assertOk()
        ->assertDontSee('href="'.route('home').'#faq"', false)
        ->assertDontSee('id="faq"', false)
        ->assertDontSeeText('Incomplete question')
        ->assertDontSeeText('Incomplete answer');
});

it('filters hidden and malformed FAQ items', function () {
    HomepageSection::factory()
        ->forKey(HomepageSectionKey::Faq)
        ->visible()
        ->withContent([
            'eyebrow' => 'Frequently Asked Questions',
            'heading' => 'How can we help?',
            'items' => [
                [
                    'question' => 'Visible question',
                    'answer' => 'Visible answer',
                ],
                [
                    'question' => 'Hidden question',
                    'answer' => 'Hidden answer',
                    'is_visible' => false,
                ],
                [
                    'question' => 'Missing answer',
                ],
                'invalid FAQ item',
            ],
        ])
        ->create();

    $response = $this->get(route('home'));

    $response
        ->assertOk()
        ->assertSeeText('Visible question')
        ->assertSeeText('Visible answer')
        ->assertDontSeeText('Hidden question')
        ->assertDontSeeText('Hidden answer')
        ->assertDontSeeText('Missing answer');
});

it('escapes HTML stored in FAQ content', function () {
    HomepageSection::factory()
        ->forKey(HomepageSectionKey::Faq)
        ->visible()
        ->withContent([
            'eyebrow' => 'Frequently Asked Questions',
            'heading' => 'How can we help?',
            'items' => [
                [
                    'question' => '<strong>Unsafe question</strong>',
                    'answer' => '<script>alert("unsafe")</script>',
                ],
            ],
        ])
        ->create();

    $response = $this->get(route('home'));

    $response
        ->assertOk()
        ->assertDontSee('<strong>Unsafe question</strong>', false)
        ->assertDontSee('<script>alert("unsafe")</script>', false)
        ->assertSee('&lt;strong&gt;Unsafe question&lt;/strong&gt;', false)
        ->assertSee(
            '&lt;script&gt;alert(&quot;unsafe&quot;)&lt;/script&gt;',
            false,
        );
});
