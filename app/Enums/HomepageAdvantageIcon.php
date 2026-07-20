<?php

namespace App\Enums;

/**
 * Identifies the approved decorative icons available to advantage cards.
 *
 * Only these identifiers may be stored through Filament and rendered on the
 * public homepage. Arbitrary SVG or HTML supplied through the CMS is rejected.
 */
enum HomepageAdvantageIcon: string
{
    case Sparkles = 'sparkles';
    case Leaf = 'leaf';
    case Blocks = 'blocks';
    case Shield = 'shield';
    case Truck = 'truck';

    /**
     * Return the administrator-facing label for this icon.
     */
    public function label(): string
    {
        return match ($this) {
            self::Sparkles => 'Pioneer / Sparkles',
            self::Leaf => 'Natural / Leaf',
            self::Blocks => 'Montessori / Building Blocks',
            self::Shield => 'Built to Last / Shield',
            self::Truck => 'Nationwide Delivery / Truck',
        };
    }

    /**
     * Return icon options formatted for a Filament select field.
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        $options = [];

        foreach (self::cases() as $icon) {
            $options[$icon->value] = $icon->label();
        }

        return $options;
    }

    /**
     * Return the primitive icon identifiers used for validation and tests.
     *
     * @return list<string>
     */
    public static function values(): array
    {
        return array_map(
            static fn (self $icon): string => $icon->value,
            self::cases(),
        );
    }
}
