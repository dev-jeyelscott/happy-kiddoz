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

        return view('pages.home', [
            'heroSection' => $heroSection,
            'heroImageUrl' => $this->publicStorageUrl(
                $heroContent['image_path'] ?? null,
            ),
            'heroLinks' => $this->heroLinks($settings),
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
