<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class SiteSettingSeeder extends Seeder
{
    /**
     * Seed the canonical Happy Kiddoz global settings.
     *
     * Unconfirmed URLs, contact details, and assets remain null so the
     * public website can omit them instead of rendering broken links.
     */
    public function run(): void
    {
        foreach ($this->settings() as $key => $attributes) {
            SiteSetting::query()->updateOrCreate(
                ['key' => $key],
                $attributes,
            );
        }
    }

    /**
     * Return the canonical global settings grouped by administration area.
     *
     * @return array<string, array{value: string|null, group: string}>
     */
    private function settings(): array
    {
        return [
            // Branding
            'brand_name' => [
                'value' => 'Happy Kiddoz',
                'group' => 'brand',
            ],
            'brand_tagline' => [
                'value' => 'Premium Montessori furniture and materials for curious little minds',
                'group' => 'brand',
            ],
            'logo_path' => [
                'value' => $this->publicAssetPath(
                    'images/brand/happy-kiddoz-logo.webp',
                ),
                'group' => 'brand',
            ],
            'favicon_path' => [
                'value' => $this->publicAssetPath(
                    'images/brand/favicon.webp',
                ),
                'group' => 'brand',
            ],

            // Marketplace and social links
            'primary_shop_url' => [
                'value' => null,
                'group' => 'marketplace',
            ],
            'shopee_url' => [
                'value' => null,
                'group' => 'marketplace',
            ],
            'lazada_url' => [
                'value' => null,
                'group' => 'marketplace',
            ],
            'facebook_url' => [
                'value' => null,
                'group' => 'social',
            ],
            'instagram_url' => [
                'value' => null,
                'group' => 'social',
            ],

            // Contact information
            'contact_email' => [
                'value' => null,
                'group' => 'contact',
            ],
            'contact_phone' => [
                'value' => null,
                'group' => 'contact',
            ],
            'contact_viber' => [
                'value' => null,
                'group' => 'contact',
            ],
            'contact_address' => [
                'value' => null,
                'group' => 'contact',
            ],
            'contact_map_url' => [
                'value' => null,
                'group' => 'contact',
            ],

            // SEO
            'meta_title' => [
                'value' => 'Happy Kiddoz | Montessori Furniture and Learning Materials',
                'group' => 'seo',
            ],
            'meta_description' => [
                'value' => 'Thoughtfully designed Montessori furniture and learning materials for Filipino homes, schools, and learning centers.',
                'group' => 'seo',
            ],

            // Legal and footer
            'privacy_policy_url' => [
                'value' => null,
                'group' => 'legal',
            ],
            'terms_url' => [
                'value' => null,
                'group' => 'legal',
            ],
            'copyright_text' => [
                'value' => 'All rights reserved.',
                'group' => 'legal',
            ],
        ];
    }

    /**
     * Return a public asset path only when the approved file exists.
     */
    private function publicAssetPath(string $relativePath): ?string
    {
        return File::exists(public_path($relativePath))
            ? $relativePath
            : null;
    }
}
