<?php

namespace App\Http\Controllers\PublicSite;

use App\Enums\HomepageAdvantageIcon;
use App\Enums\HomepageSectionKey;
use App\Http\Controllers\Controller;
use App\Models\HomepageSection;
use App\Models\SiteSetting;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class HomeController extends Controller
{
    /**
     * Render the public homepage with the currently implemented sections.
     */
    public function index(): View
    {
        $settings = SiteSetting::keyValueMap();

        $heroSection = HomepageSection::query()
            ->visible()
            ->where('key', HomepageSectionKey::Hero->value)
            ->first();

        $heroContent = is_array($heroSection?->content)
            ? $heroSection->content
            : [];

        $categoriesSection = HomepageSection::query()
            ->visible()
            ->where('key', HomepageSectionKey::Categories->value)
            ->first();

        $categoriesContent = is_array($categoriesSection?->content)
            ? $categoriesSection->content
            : [];

        $aboutSection = HomepageSection::query()
            ->visible()
            ->where('key', HomepageSectionKey::About->value)
            ->first();

        $aboutContent = is_array($aboutSection?->content)
            ? $aboutSection->content
            : [];

        $advantagesSection = HomepageSection::query()
            ->visible()
            ->where('key', HomepageSectionKey::Advantages->value)
            ->first();

        $advantagesContent = is_array($advantagesSection?->content)
            ? $advantagesSection->content
            : [];

        $faqSection = HomepageSection::query()
            ->visible()
            ->where('key', HomepageSectionKey::Faq->value)
            ->first();

        $faqContent = is_array($faqSection?->content)
            ? $faqSection->content
            : [];

        $reviewsSection = HomepageSection::query()
            ->visible()
            ->where('key', HomepageSectionKey::Reviews->value)
            ->first();

        $reviewsContent = is_array($reviewsSection?->content)
            ? $reviewsSection->content
            : [];

        return view('pages.home', [
            'heroSection' => $heroSection,

            'heroImageUrl' => $this->publicStorageUrl(
                $heroContent['image_path'] ?? null,
            ),

            'heroLinks' => $this->heroLinks($settings),

            'categoriesSection' => $categoriesSection,

            'categoryItems' => $this->categoryItems(
                $categoriesContent['items'] ?? [],
            ),

            'aboutSection' => $aboutSection,

            'aboutImageUrl' => $this->publicStorageUrl(
                $aboutContent['image_path'] ?? null,
            ),

            'aboutParagraphs' => $this->aboutParagraphs(
                $aboutContent['paragraphs'] ?? [],
            ),

            'advantagesSection' => $advantagesSection,

            'advantageItems' => $this->advantageItems(
                $advantagesContent['items'] ?? [],
            ),

            'reviewsSection' => $reviewsSection,

            'reviewItems' => $this->reviewItems(
                $reviewsContent['items'] ?? [],
            ),

            'faqSection' => $faqSection,

            'faqItems' => $this->faqItems(
                $faqContent['items'] ?? [],
            ),

            'metaTitle' => $this->settingOrFallback(
                settings: $settings,
                key: 'meta_title',
                fallback: 'Happy Kiddoz | Montessori Furniture and Learning Materials',
            ),

            'metaDescription' => $this->settingOrFallback(
                settings: $settings,
                key: 'meta_description',
                fallback: 'Thoughtfully designed Montessori furniture and learning materials for Filipino homes, schools, and learning centers.',
            ),
        ]);
    }

    /**
     * Normalize category content for safe and predictable public rendering.
     *
     * @return list<array{
     *     title: string,
     *     description: string,
     *     image_url: string|null,
     *     image_alt: string,
     *     external_url: string|null,
     *     sort_order: int
     * }>
     */
    private function categoryItems(mixed $items): array
    {
        if (! is_array($items)) {
            return [];
        }

        $categories = [];

        foreach ($items as $item) {
            if (! is_array($item)) {
                continue;
            }

            $title = trim((string) ($item['title'] ?? ''));

            /*
             * Invalid or incomplete category records should not create
             * blank public cards.
             */
            if ($title === '') {
                continue;
            }

            $sortOrder = filter_var(
                $item['sort_order'] ?? null,
                FILTER_VALIDATE_INT,
            );

            $categories[] = [
                'title' => $title,
                'description' => trim(
                    (string) ($item['description'] ?? ''),
                ),
                'image_url' => $this->publicStorageUrl(
                    $item['image_path'] ?? null,
                ),
                'image_alt' => trim(
                    (string) ($item['image_alt'] ?? ''),
                ),
                'external_url' => $this->validatedHttpsUrl(
                    $item['external_url'] ?? null,
                ),
                'sort_order' => $sortOrder !== false
                    ? $sortOrder
                    : (count($categories) + 1) * 10,
            ];
        }

        /*
         * The JSON array order must not be treated as the source of truth.
         * Explicit sort_order values preserve the approved design order.
         */
        usort(
            $categories,
            static fn (array $left, array $right): int => $left['sort_order']
                <=> $right['sort_order'],
        );

        return $categories;
    }

    /**
     * Normalize About section paragraphs for predictable public rendering.
     *
     * Empty and malformed values are removed so they do not create blank
     * paragraph spacing on the public homepage.
     *
     * @return list<string>
     */
    private function aboutParagraphs(mixed $paragraphs): array
    {
        if (! is_array($paragraphs)) {
            return [];
        }

        $normalizedParagraphs = array_map(
            static fn (mixed $paragraph): string => trim((string) $paragraph),
            $paragraphs,
        );

        return array_values(
            array_filter(
                $normalizedParagraphs,
                static fn (string $paragraph): bool => $paragraph !== '',
            ),
        );
    }

    /**
     * Normalize advantage cards for safe and predictable public rendering.
     *
     * Unknown icon identifiers and incomplete cards are excluded. This
     * prevents arbitrary CMS-controlled HTML or SVG from reaching the view.
     *
     * @return list<array{
     *     icon: HomepageAdvantageIcon,
     *     title: string,
     *     description: string,
     *     sort_order: int
     * }>
     */
    private function advantageItems(mixed $items): array
    {
        if (! is_array($items)) {
            return [];
        }

        $advantages = [];

        foreach ($items as $item) {
            if (! is_array($item)) {
                continue;
            }

            $icon = HomepageAdvantageIcon::tryFrom(
                trim((string) ($item['icon'] ?? '')),
            );

            $title = trim((string) ($item['title'] ?? ''));
            $description = trim((string) ($item['description'] ?? ''));

            /*
             * A card must have an approved icon, title, and description.
             * Invalid CMS values should not generate incomplete UI elements.
             */
            if (
                $icon === null
                || $title === ''
                || $description === ''
            ) {
                continue;
            }

            $sortOrder = filter_var(
                $item['sort_order'] ?? null,
                FILTER_VALIDATE_INT,
            );

            $advantages[] = [
                'icon' => $icon,
                'title' => $title,
                'description' => $description,
                'sort_order' => $sortOrder !== false
                    ? $sortOrder
                    : (count($advantages) + 1) * 10,
            ];
        }

        /*
         * Preserve the approved card sequence independently of the JSON
         * array's current physical ordering.
         */
        usort(
            $advantages,
            static fn (array $left, array $right): int => $left['sort_order']
                <=> $right['sort_order'],
        );

        return $advantages;
    }

    /**
     * Normalize FAQ entries for safe and predictable public rendering.
     *
     * Empty, malformed, and explicitly hidden entries are removed so the
     * homepage and its navigation never expose an incomplete FAQ section.
     *
     * @return list<array{
     *     question: string,
     *     answer: string
     * }>
     */
    private function faqItems(mixed $items): array
    {
        if (! is_array($items)) {
            return [];
        }

        $faqItems = [];

        foreach ($items as $item) {
            if (! is_array($item)) {
                continue;
            }

            /*
             * Support an optional per-item visibility flag without requiring it
             * in the current Filament schema. Missing visibility defaults to true.
             */
            $isVisible = filter_var(
                $item['is_visible'] ?? true,
                FILTER_VALIDATE_BOOL,
                FILTER_NULL_ON_FAILURE,
            );

            if ($isVisible !== true) {
                continue;
            }

            $question = trim((string) ($item['question'] ?? ''));
            $answer = trim((string) ($item['answer'] ?? ''));

            /*
             * Incomplete entries must not produce empty disclosure controls or
             * cause the FAQ navigation anchor to appear.
             */
            if ($question === '' || $answer === '') {
                continue;
            }

            $faqItems[] = [
                'question' => $question,
                'answer' => $answer,
            ];
        }

        /*
         * Preserve the administrator-controlled repeater sequence because FAQ
         * order is editorial rather than a fixed canonical product order.
         */
        return $faqItems;
    }

    /**
     * Normalize customer reviews for safe and predictable public rendering.
     *
     * Incomplete reviews and invalid ratings are excluded. The customer handle
     * takes precedence over the customer name when both values are available.
     *
     * @return list<array{
     *     customer_identifier: string,
     *     avatar_initial: string,
     *     rating: int,
     *     quote: string
     * }>
     */
    private function reviewItems(mixed $items): array
    {
        if (! is_array($items)) {
            return [];
        }

        $reviews = [];

        /*
         * Enforce the same upper limit as the Filament form even when data is
         * written outside the administration panel.
         */
        foreach (array_slice($items, 0, 6) as $item) {
            if (! is_array($item)) {
                continue;
            }

            $customerName = trim(
                (string) ($item['customer_name'] ?? ''),
            );

            $customerHandle = trim(
                (string) ($item['customer_handle'] ?? ''),
            );

            $customerIdentifier = $customerHandle !== ''
                ? $customerHandle
                : $customerName;

            $quote = trim((string) ($item['quote'] ?? ''));

            $rating = filter_var(
                $item['rating'] ?? null,
                FILTER_VALIDATE_INT,
                [
                    'options' => [
                        'min_range' => 1,
                        'max_range' => 5,
                    ],
                ],
            );

            /*
             * A public review must have an identifier, quotation, and valid
             * one-to-five-star rating.
             */
            if (
                $customerIdentifier === ''
                || $quote === ''
                || $rating === false
            ) {
                continue;
            }

            $avatarSource = trim(
                (string) ($item['avatar_initial'] ?? ''),
            );

            if ($avatarSource === '') {
                $avatarSource = $customerIdentifier;
            }

            $reviews[] = [
                'customer_identifier' => $customerIdentifier,
                'avatar_initial' => Str::lower(
                    Str::substr($avatarSource, 0, 1),
                ),
                'rating' => $rating,
                'quote' => $quote,
            ];
        }

        return $reviews;
    }

    /**
     * Build the configured external marketplace and social links.
     *
     * Empty, malformed, and non-HTTPS URLs are excluded so the public
     * homepage never renders dead or unsafe external links.
     *
     * @param  array<string, string|null>  $settings
     * @return list<array{key: string, label: string, url: string}>
     */
    private function heroLinks(array $settings): array
    {
        $definitions = [
            [
                'setting' => 'shopee_url',
                'key' => 'shopee',
                'label' => 'Shopee',
            ],
            [
                'setting' => 'lazada_url',
                'key' => 'lazada',
                'label' => 'Lazada',
            ],
            [
                'setting' => 'facebook_url',
                'key' => 'facebook',
                'label' => 'Facebook',
            ],
            [
                'setting' => 'instagram_url',
                'key' => 'instagram',
                'label' => 'Instagram',
            ],
        ];

        $links = [];

        foreach ($definitions as $definition) {
            $url = $this->validatedHttpsUrl(
                $settings[$definition['setting']] ?? null,
            );

            if ($url === null) {
                continue;
            }

            $links[] = [
                'key' => $definition['key'],
                'label' => $definition['label'],
                'url' => $url,
            ];
        }

        return $links;
    }

    /**
     * Validate an external destination and return HTTPS URLs only.
     */
    private function validatedHttpsUrl(mixed $value): ?string
    {
        if (! is_string($value)) {
            return null;
        }

        $url = trim($value);

        if (
            $url === ''
            || filter_var($url, FILTER_VALIDATE_URL) === false
        ) {
            return null;
        }

        $parts = parse_url($url);

        if (
            ! is_array($parts)
            || strtolower((string) ($parts['scheme'] ?? '')) !== 'https'
            || blank($parts['host'] ?? null)
        ) {
            return null;
        }

        return $url;
    }

    /**
     * Convert a public-disk-relative image path into a browser URL.
     *
     * Absolute paths and traversal-style paths are rejected.
     */
    private function publicStorageUrl(mixed $value): ?string
    {
        if (! is_string($value)) {
            return null;
        }

        $path = ltrim(
            str_replace('\\', '/', trim($value)),
            '/',
        );

        if (
            $path === ''
            || in_array('..', explode('/', $path), true)
        ) {
            return null;
        }

        return Storage::disk('public')->url($path);
    }

    /**
     * Read a text setting while providing a safe non-empty fallback.
     *
     * @param  array<string, string|null>  $settings
     */
    private function settingOrFallback(
        array $settings,
        string $key,
        string $fallback,
    ): string {
        $value = trim((string) ($settings[$key] ?? ''));

        return $value !== '' ? $value : $fallback;
    }
}
