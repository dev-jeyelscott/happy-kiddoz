<?php

namespace App\Models;

use App\Enums\HomepageSectionKey;
use Database\Factories\HomepageSectionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use LogicException;

/**
 * Represents one fixed section of the public homepage.
 *
 * @property int $id
 * @property HomepageSectionKey $key
 * @property array<string, mixed> $content
 * @property int $sort_order
 * @property bool $is_visible
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable([
    'key',
    'content',
    'sort_order',
    'is_visible',
])]
class HomepageSection extends Model
{
    /** @use HasFactory<HomepageSectionFactory> */
    use HasFactory;

    /**
     * Register model-level homepage section invariants.
     */
    protected static function booted(): void
    {
        static::updating(function (self $section): void {
            if ($section->isDirty('key')) {
                throw new LogicException(
                    'A persisted homepage section key cannot be changed.',
                );
            }
        });
    }

    /**
     * Define the model's attribute casts.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'key' => HomepageSectionKey::class,
            'content' => 'array',
            'sort_order' => 'integer',
            'is_visible' => 'boolean',
        ];
    }

    /**
     * Restrict the query to sections enabled for public rendering.
     *
     * @param  Builder<HomepageSection>  $query
     */
    #[Scope]
    protected function visible(Builder $query): void
    {
        $query->where('is_visible', true);
    }

    /**
     * Order sections according to the configured homepage position.
     *
     * The record ID provides deterministic ordering if two sections
     * temporarily have the same sort order.
     *
     * @param  Builder<HomepageSection>  $query
     */
    #[Scope]
    protected function ordered(Builder $query): void
    {
        $query
            ->orderBy('sort_order')
            ->orderBy('id');
    }
}
