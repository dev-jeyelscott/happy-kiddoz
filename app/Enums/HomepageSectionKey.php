<?php

namespace App\Enums;

/**
 * Identifies the fixed sections that compose the public homepage.
 *
 * Section metadata is kept here so the seeder, public navigation,
 * controller, and Filament resource can share one canonical definition.
 */
enum HomepageSectionKey: string
{
    case Hero = 'hero';
    case Categories = 'categories';
    case About = 'about';
    case Advantages = 'advantages';
    case Reviews = 'reviews';
    case Faq = 'faq';
    case Contact = 'contact';

    /**
     * Return the public HTML anchor for this section.
     *
     * The hero has no anchor because it is displayed at the top of the page
     * and is not represented by a standard header navigation item.
     */
    public function anchor(): ?string
    {
        return match ($this) {
            self::Hero => null,
            self::Categories => 'shop',
            self::About => 'about',
            self::Advantages => 'why-us',
            self::Reviews => 'reviews',
            self::Faq => 'faq',
            self::Contact => 'contact',
        };
    }

    /**
     * Return the label used when the section appears in public navigation.
     */
    public function navigationLabel(): ?string
    {
        return match ($this) {
            self::Hero => null,
            self::Categories => 'Shop',
            self::About => 'About',
            self::Advantages => 'Why Us',
            self::Reviews => 'Reviews',
            self::Faq => 'FAQ',
            self::Contact => 'Contact',
        };
    }

    /**
     * Return the approved default homepage position.
     */
    public function defaultSortOrder(): int
    {
        return match ($this) {
            self::Hero => 10,
            self::Categories => 20,
            self::About => 30,
            self::Advantages => 40,
            self::Reviews => 50,
            self::Faq => 60,
            self::Contact => 70,
        };
    }

    /**
     * Determine whether the section is visible by default.
     *
     * FAQ remains hidden until approved FAQ content is available.
     */
    public function defaultVisibility(): bool
    {
        return $this !== self::Faq;
    }

    /**
     * Determine whether the section is required for the initial launch.
     */
    public function isRequiredForLaunch(): bool
    {
        return $this !== self::Faq;
    }

    /**
     * Return the primitive values for validation and database operations.
     *
     * @return list<string>
     */
    public static function values(): array
    {
        return array_map(
            static fn (self $key): string => $key->value,
            self::cases(),
        );
    }
}
