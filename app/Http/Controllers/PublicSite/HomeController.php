<?php

namespace App\Http\Controllers\PublicSite;

use App\Enums\HomepageSectionKey;
use App\Http\Controllers\Controller;
use App\Models\HomepageSection;
use App\Models\SiteSetting;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;

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
