<?php

namespace Database\Seeders;

use App\Enums\HomepageSectionKey;
use App\Models\HomepageSection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class HomepageSectionSeeder extends Seeder
{
    /**
     * Seed every canonical homepage section exactly once.
     */
    public function run(): void
    {
        foreach (HomepageSectionKey::cases() as $key) {
            HomepageSection::query()->updateOrCreate(
                ['key' => $key->value],
                [
                    'content' => $this->contentFor($key),
                    'sort_order' => $key->defaultSortOrder(),
                    'is_visible' => $this->visibilityFor($key),
                ],
            );
        }
    }

    /**
     * Resolve the canonical content payload for a homepage section.
     *
     * @return array<string, mixed>
     */
    private function contentFor(HomepageSectionKey $key): array
    {
        return match ($key) {
            HomepageSectionKey::Hero => $this->heroContent(),
            HomepageSectionKey::Categories => $this->categoriesContent(),
            HomepageSectionKey::About => $this->aboutContent(),
            HomepageSectionKey::Advantages => $this->advantagesContent(),
            HomepageSectionKey::Reviews => $this->reviewsContent(),
            HomepageSectionKey::Faq => $this->faqContent(),
            HomepageSectionKey::Contact => $this->contactContent(),
        };
    }

    /**
     * Return the canonical hero content.
     *
     * @return array<string, mixed>
     */
    private function heroContent(): array
    {
        return [
            'eyebrow' => 'Premium Montessori furniture and materials for curious little minds',
            'headline' => 'Beautiful spaces where little ones grow.',
            'description' => 'Thoughtfully designed Montessori furniture and materials that inspire independence, creativity, and joyful learning for homes, schools, and learning centers.',
            'image_path' => $this->publishImage('hero.webp'),
            'image_alt' => 'Montessori classroom with child-sized wooden tables, chairs, shelves, and learning materials',
            'rating_value' => 4.9,
            'rating_statement' => 'Loved by many Filipino families and school owners since 2016!',
        ];
    }

    /**
     * Return the canonical category-section content.
     *
     * @return array<string, mixed>
     */
    private function categoriesContent(): array
    {
        return [
            'eyebrow' => 'Shop by Category',
            'heading' => 'Everything for a purposeful play space',
            'items' => [
                $this->category(
                    title: 'Tables & Chairs',
                    description: 'Right-sized seating for focused little learners.',
                    image: 'categories/tables-and-chairs.webp',
                    imageAlt: 'Child-sized wooden table and chairs',
                    sortOrder: 10,
                ),
                $this->category(
                    title: 'Bookshelves',
                    description: 'Front-facing shelves that invite independent reading.',
                    image: 'categories/bookshelves.webp',
                    imageAlt: "Front-facing wooden children's bookshelves",
                    sortOrder: 20,
                ),
                $this->category(
                    title: 'Toy Organizers',
                    description: 'Tidy, reachable storage that teaches order.',
                    image: 'categories/toy-organizers.webp',
                    imageAlt: 'Child-accessible wooden toy organizer',
                    sortOrder: 30,
                ),
                $this->category(
                    title: 'Learning Towers',
                    description: 'Safe kitchen helpers for hands-on discovery.',
                    image: 'categories/learning-towers.webp',
                    imageAlt: 'Wooden learning tower for kitchen activities',
                    sortOrder: 40,
                ),
                $this->category(
                    title: 'Play Gyms',
                    description: 'Indoor climbers that build strength and courage.',
                    image: 'categories/play-gyms.webp',
                    imageAlt: 'Indoor wooden climbing gym',
                    sortOrder: 50,
                ),
                $this->category(
                    title: 'Montessori Toys',
                    description: 'Natural, purposeful play for growing minds.',
                    image: 'categories/montessori-toys.webp',
                    imageAlt: 'Natural wooden Montessori toys',
                    sortOrder: 60,
                ),
                $this->category(
                    title: 'STEM Toys',
                    description: 'Gears, builds and puzzles that spark curiosity.',
                    image: 'categories/stem-toys.webp',
                    imageAlt: 'Colorful STEM gears and activity toys',
                    sortOrder: 70,
                ),
                $this->category(
                    title: 'Storage and Trays',
                    description: 'Beautiful order for shelves and activity work.',
                    image: 'categories/storage-and-trays.webp',
                    imageAlt: 'Wooden storage trays on open shelving',
                    sortOrder: 80,
                ),
            ],
        ];
    }

    /**
     * Create one category item using the approved content schema.
     *
     * @return array<string, mixed>
     */
    private function category(
        string $title,
        string $description,
        string $image,
        string $imageAlt,
        int $sortOrder,
    ): array {
        return [
            'title' => $title,
            'description' => $description,
            'image_path' => $this->publishImage($image),
            'image_alt' => $imageAlt,
            'external_url' => null,
            'sort_order' => $sortOrder,
        ];
    }

    /**
     * Return the canonical About section content.
     *
     * @return array<string, mixed>
     */
    private function aboutContent(): array
    {
        return [
            'eyebrow' => 'About Us',
            'heading' => 'Happy Kiddoz was founded in 2016, inspired by a personal experience as a parent.',
            'paragraphs' => [
                'When our first child was born, we searched for child-sized bookshelves, toy organizers, and tables and chairs, but they were difficult to find, expensive to customize, and inconvenient to transport. As a former preschool teacher, we saw an opportunity to create furniture based on the Montessori philosophy: safe, child-sized, functional, and designed to encourage independence.',
                'Over the years, our two children became our biggest product testers and inspiration. Their everyday use helped us refine every design to better meet the needs of growing families and classrooms.',
                'Today, our mission is simple: to make quality, affordable Montessori furniture and educational materials accessible to every Filipino home, preschool, and learning center. We continue to grow toward becoming a trusted one-stop destination for Montessori-inspired learning spaces.',
            ],
            'image_path' => $this->publishImage('about.webp'),
            'image_alt' => 'Child using Montessori furniture and toys in a prepared learning space',
        ];
    }

    /**
     * Return the canonical advantages-section content.
     *
     * @return array<string, mixed>
     */
    private function advantagesContent(): array
    {
        return [
            'eyebrow' => 'Why Parents and Schools Choose Us',
            'heading' => 'The Happy Kiddoz Advantage',
            'items' => [
                [
                    'icon' => 'sparkles',
                    'title' => 'Pioneer in Montessori Furniture',
                    'description' => 'The first to offer Montessori-inspired furniture in the Philippine market.',
                    'sort_order' => 10,
                ],
                [
                    'icon' => 'leaf',
                    'title' => 'Natural & non-toxic',
                    'description' => 'Solid, sustainably sourced wood with child-safe, non-toxic finishes and smooth, rounded edges.',
                    'sort_order' => 20,
                ],
                [
                    'icon' => 'blocks',
                    'title' => 'Montessori-aligned',
                    'description' => 'Designed to be child-sized and accessible, encouraging independence and focused play.',
                    'sort_order' => 30,
                ],
                [
                    'icon' => 'shield',
                    'title' => 'Built to last',
                    'description' => 'Sturdy construction tested for busy homes and classrooms, made to grow with your child.',
                    'sort_order' => 40,
                ],
                [
                    'icon' => 'truck',
                    'title' => 'Nationwide delivery',
                    'description' => 'Fast, careful shipping across the Philippines via Shopee, Lazada, and approved couriers.',
                    'sort_order' => 50,
                ],
            ],
        ];
    }

    /**
     * Return the review-section structure.
     *
     * Reviews remain empty and hidden until their wording, source,
     * customer identity, and display permission are approved.
     *
     * @return array<string, mixed>
     */
    private function reviewsContent(): array
    {
        return [
            'eyebrow' => 'Real Reviews From Real People',
            'heading' => 'Over 20,000 positive reviews!',
            'items' => [],
        ];
    }

    /**
     * Return the optional FAQ-section structure.
     *
     * @return array<string, mixed>
     */
    private function faqContent(): array
    {
        return [
            'eyebrow' => 'Frequently Asked Questions',
            'heading' => 'How can we help?',
            'items' => [],
        ];
    }

    /**
     * Return the canonical contact CTA content.
     *
     * Contact details and destination URLs come from global site settings.
     *
     * @return array<string, mixed>
     */
    private function contactContent(): array
    {
        return [
            'heading' => "Let's build a happier space for your kiddos",
            'description' => "Shopping for home, furnishing a classroom, or planning a learning center? Reach out—we'd love to help you choose the right pieces.",
            'links_panel_heading' => 'Shop and follow us',
        ];
    }

    /**
     * Resolve initial visibility using content-approval requirements.
     *
     * FAQ and reviews remain hidden because their approved repeated content
     * is not yet available. All other launch sections use the enum default.
     */
    private function visibilityFor(HomepageSectionKey $key): bool
    {
        return match ($key) {
            HomepageSectionKey::Reviews,
            HomepageSectionKey::Faq => false,
            default => $key->defaultVisibility(),
        };
    }

    /**
     * Publish an approved homepage image to the public storage disk.
     *
     * Missing source assets are expected during development while client
     * approval is pending, so a missing file produces a null database path.
     */
    private function publishImage(string $relativePath): ?string
    {
        $sourcePath = database_path(
            'seeders/assets/homepage/'.$relativePath,
        );

        if (! File::exists($sourcePath)) {
            $this->command?->warn(
                "Pending homepage asset was not seeded: {$relativePath}",
            );

            return null;
        }

        $destinationPath = 'homepage/'.$relativePath;

        $written = Storage::disk('public')->put(
            $destinationPath,
            File::get($sourcePath),
            'public',
        );

        if (! $written) {
            throw new RuntimeException(
                "Unable to publish homepage asset: {$relativePath}",
            );
        }

        return $destinationPath;
    }
}
