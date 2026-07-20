<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class SiteSettingSeeder extends Seeder
{
    /**
     * Seed the canonical Happy Kiddos global settings.
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
                'value' => 'Happy Kiddos',
                'group' => 'brand',
            ],
            'brand_tagline' => [
                'value' => 'Premium Montessori furniture and materials for curious little minds',
                'group' => 'brand',
            ],
            'logo_path' => [
                'value' => $this->seedBrandAsset(
                    sourceRelativePath: 'images/brand/happy-kiddos-logo.webp',
                    targetPath: 'site/brand/happy-kiddos-logo.webp',
                ),
                'group' => 'brand',
            ],
            'favicon_path' => [
                'value' => $this->seedBrandAsset(
                    sourceRelativePath: 'images/brand/favicon.webp',
                    targetPath: 'site/brand/favicon.webp',
                ),
                'group' => 'brand',
            ],

            // Marketplace links
            'primary_shop_url' => [
                'value' => 'https://shopee.ph/bugs_2wan',
                'group' => 'marketplace',
            ],
            'shopee_url' => [
                'value' => 'https://shopee.ph/bugs_2wan',
                'group' => 'marketplace',
            ],
            'lazada_url' => [
                'value' => 'https://www.lazada.com.ph/tag/happy-kiddo/',
                'group' => 'marketplace',
            ],

            // Social links
            'facebook_url' => [
                'value' => 'https://www.facebook.com/happykiddosph/',
                'group' => 'social',
            ],
            'instagram_url' => [
                'value' => 'https://www.instagram.com/happykiddosph/?hl=en',
                'group' => 'social',
            ],

            // Contact information
            'contact_email' => [
                'value' => 'happykiddosph@gmail.com',
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
                'value' => 'Valenzuela City, Metro Manila, Philippines',
                'group' => 'contact',
            ],
            'contact_map_url' => [
                'value' => null,
                'group' => 'contact',
            ],

            // SEO
            'meta_title' => [
                'value' => 'Happy Kiddos | Montessori Furniture and Learning Materials',
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
     * Copy a tracked default brand asset to Laravel's public storage disk.
     *
     * The stored setting remains a disk-relative path so Filament uploads
     * and future public rendering use the same path format.
     */
    private function seedBrandAsset(
        string $sourceRelativePath,
        string $targetPath,
    ): ?string {
        $sourcePath = public_path($sourceRelativePath);

        if (! File::exists($sourcePath)) {
            return null;
        }

        Storage::disk('public')->put(
            $targetPath,
            File::get($sourcePath),
        );

        return $targetPath;
    }
}
