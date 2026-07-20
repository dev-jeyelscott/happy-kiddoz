<?php

namespace App\Filament\Widgets;

use App\Enums\HomepageSectionKey;
use App\Models\HomepageSection;
use App\Models\SiteSetting;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class WebsiteStatusOverview extends StatsOverviewWidget
{
    /**
     * External links that should be configured before the public launch.
     *
     * @var list<string>
     */
    private const EXTERNAL_LINK_KEYS = [
        'primary_shop_url',
        'shopee_url',
        'lazada_url',
        'facebook_url',
        'instagram_url',
    ];

    /**
     * Position this widget before the quick-actions widget.
     */
    protected static ?int $sort = 1;

    /**
     * Use the complete dashboard width for the status cards.
     */
    protected int|string|array $columnSpan = 'full';

    /**
     * Load immediately because these checks use only two small queries.
     */
    protected static bool $isLazy = false;

    /**
     * Disable polling because website configuration changes infrequently.
     */
    protected ?string $pollingInterval = null;

    /**
     * Dashboard heading displayed above the status cards.
     */
    protected ?string $heading = 'Homepage Status';

    /**
     * Explain what the status cards represent.
     */
    protected ?string $description =
        'Launch readiness and configuration health for the public homepage.';

    /**
     * Build the dashboard status cards.
     *
     * @return array<int, Stat>
     */
    protected function getStats(): array
    {
        /** @var array<string, bool> $sectionStates */
        $sectionStates = HomepageSection::query()
            ->get(['key', 'is_visible'])
            ->mapWithKeys(
                static fn (HomepageSection $section): array => [
                    $section->key->value => $section->is_visible,
                ],
            )
            ->all();

        $requiredSectionsMissing =
            $this->requiredSectionsMissing($sectionStates);

        $visibleSectionCount = count(
            array_filter(
                $sectionStates,
                static fn (bool $isVisible): bool => $isVisible,
            ),
        );

        $totalSectionCount = count(HomepageSectionKey::cases());

        $missingExternalLinks = $this->missingExternalLinks();

        return [
            Stat::make(
                'Homepage readiness',
                $requiredSectionsMissing === 0
                    ? 'Ready'
                    : 'Needs attention',
            )
                ->description(
                    $this->requiredSectionDescription(
                        $requiredSectionsMissing,
                    ),
                )
                ->descriptionIcon(
                    $requiredSectionsMissing === 0
                        ? 'heroicon-o-check-circle'
                        : 'heroicon-o-exclamation-triangle',
                )
                ->color(
                    $requiredSectionsMissing === 0
                        ? 'success'
                        : 'warning',
                ),

            Stat::make(
                'Visible sections',
                "{$visibleSectionCount} / {$totalSectionCount}",
            )
                ->description(
                    $visibleSectionCount === $totalSectionCount
                        ? 'Every section is currently visible'
                        : 'Hidden sections are excluded from the homepage',
                )
                ->descriptionIcon('heroicon-o-eye')
                ->color('primary'),

            Stat::make(
                'Store and social links',
                $missingExternalLinks === 0
                    ? 'Complete'
                    : "{$missingExternalLinks} missing",
            )
                ->description(
                    $missingExternalLinks === 0
                        ? 'All marketplace and social links are configured'
                        : 'Complete the missing URLs in Site Settings',
                )
                ->descriptionIcon(
                    $missingExternalLinks === 0
                        ? 'heroicon-o-link'
                        : 'heroicon-o-link-slash',
                )
                ->color(
                    $missingExternalLinks === 0
                        ? 'success'
                        : 'warning',
                ),
        ];
    }

    /**
     * Count required homepage sections that are missing or hidden.
     *
     * @param  array<string, bool>  $sectionStates
     */
    private function requiredSectionsMissing(
        array $sectionStates,
    ): int {
        $missingCount = 0;

        foreach (HomepageSectionKey::cases() as $sectionKey) {
            if (! $sectionKey->isRequiredForLaunch()) {
                continue;
            }

            if (($sectionStates[$sectionKey->value] ?? false) !== true) {
                $missingCount++;
            }
        }

        return $missingCount;
    }

    /**
     * Count empty marketplace and social settings.
     */
    private function missingExternalLinks(): int
    {
        /** @var array<string, string|null> $storedLinks */
        $storedLinks = SiteSetting::query()
            ->whereIn('key', self::EXTERNAL_LINK_KEYS)
            ->pluck('value', 'key')
            ->all();

        $missingCount = 0;

        foreach (self::EXTERNAL_LINK_KEYS as $settingKey) {
            $value = $storedLinks[$settingKey] ?? null;

            if (! is_string($value) || trim($value) === '') {
                $missingCount++;
            }
        }

        return $missingCount;
    }

    /**
     * Build grammatically correct required-section status text.
     */
    private function requiredSectionDescription(
        int $missingCount,
    ): string {
        return match ($missingCount) {
            0 => 'All required homepage sections are visible',
            1 => '1 required section is missing or hidden',
            default => "{$missingCount} required sections are missing or hidden",
        };
    }
}
