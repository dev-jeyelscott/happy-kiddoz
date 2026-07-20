<?php

namespace App\View\Components\PublicSite;

use App\Enums\HomepageSectionKey;
use App\Models\HomepageSection;
use App\Models\SiteSetting;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\Component;

final class Header extends Component
{
    /**
     * Render the public header using canonical homepage sections
     * and typed global site settings.
     */
    public function render(): View
    {
        $settings = SiteSetting::keyValueMap();

        return view('components.public.header', [
            'brandName' => $this->brandName(
                $settings['brand_name'] ?? null,
            ),
            'logoUrl' => $this->publicStorageUrl(
                $settings['logo_path'] ?? null,
            ),
            'navigationLinks' => $this->navigationLinks(),
            'primaryShopUrl' => $this->validatedHttpsUrl(
                $settings['primary_shop_url'] ?? null,
            ),
        ]);
    }

    /**
     * Build navigation links from publicly visible homepage sections.
     *
     * @return list<array{label: string, url: string}>
     */
    private function navigationLinks(): array
    {
        $sections = HomepageSection::query()
            ->visible()
            ->ordered()
            ->get(['key', 'content']);

        $links = [];

        foreach ($sections as $section) {
            if (! $this->shouldRenderNavigationLink($section)) {
                continue;
            }

            $anchor = $section->key->anchor();
            $label = $section->key->navigationLabel();

            if ($anchor === null || $label === null) {
                continue;
            }

            $links[] = [
                'label' => $label,
                'url' => route('home').'#'.$anchor,
            ];
        }

        return $links;
    }

    /**
     * Determine whether a visible section should be listed in navigation.
     */
    private function shouldRenderNavigationLink(
        HomepageSection $section,
    ): bool {
        if ($section->key === HomepageSectionKey::Hero) {
            return false;
        }

        if (! $this->hasHomepageComponent($section->key)) {
            return false;
        }

        if ($section->key === HomepageSectionKey::Faq) {
            return $this->faqHasVisibleContent($section);
        }

        return $section->key->anchor() !== null
            && $section->key->navigationLabel() !== null;
    }

    /**
     * Determine whether the public Blade component for a section exists.
     *
     * This prevents the header from linking to sections that have not yet
     * been implemented during the incremental homepage refactor.
     */
    private function hasHomepageComponent(
        HomepageSectionKey $key,
    ): bool {
        return ViewFacade::exists(
            'components.home.'.$key->value,
        );
    }

    /**
     * Determine whether the FAQ contains at least one complete visible item.
     */
    private function faqHasVisibleContent(
        HomepageSection $section,
    ): bool {
        $items = $section->content['items'] ?? [];

        if (! is_array($items)) {
            return false;
        }

        foreach ($items as $item) {
            if (! is_array($item)) {
                continue;
            }

            if (($item['is_visible'] ?? true) === false) {
                continue;
            }

            if (
                filled($item['question'] ?? null)
                && filled($item['answer'] ?? null)
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Resolve the displayed brand name with an application fallback.
     */
    private function brandName(?string $brandName): string
    {
        $brandName = trim((string) $brandName);

        return $brandName !== ''
            ? $brandName
            : config('app.name', 'Happy Kiddoz');
    }

    /**
     * Convert a public-disk-relative path into a browser URL.
     *
     * Invalid or traversal-style paths are rejected.
     */
    private function publicStorageUrl(?string $path): ?string
    {
        if (! is_string($path)) {
            return null;
        }

        $path = ltrim(
            str_replace('\\', '/', trim($path)),
            '/',
        );

        if ($path === '') {
            return null;
        }

        if (in_array('..', explode('/', $path), true)) {
            return null;
        }

        return Storage::disk('public')->url($path);
    }

    /**
     * Return only complete HTTPS external URLs.
     */
    private function validatedHttpsUrl(?string $url): ?string
    {
        if (! is_string($url)) {
            return null;
        }

        $url = trim($url);

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
}
