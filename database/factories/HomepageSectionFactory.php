<?php

namespace Database\Factories;

use App\Enums\HomepageSectionKey;
use App\Models\HomepageSection;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<HomepageSection>
 */
class HomepageSectionFactory extends Factory
{
    /**
     * The model represented by this factory.
     *
     * @var class-string<HomepageSection>
     */
    protected $model = HomepageSection::class;

    /**
     * Define the default homepage section state.
     *
     * The default record is the hero section. Tests creating multiple
     * sections should explicitly use the forKey() state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'key' => HomepageSectionKey::Hero,
            'content' => [],
            'sort_order' => HomepageSectionKey::Hero->defaultSortOrder(),
            'is_visible' => HomepageSectionKey::Hero->defaultVisibility(),
        ];
    }

    /**
     * Configure the factory for a specific canonical section.
     */
    public function forKey(HomepageSectionKey $key): static
    {
        return $this->state(fn (array $attributes): array => [
            'key' => $key,
            'sort_order' => $key->defaultSortOrder(),
            'is_visible' => $key->defaultVisibility(),
        ]);
    }

    /**
     * Configure the section with a specific content payload.
     *
     * @param  array<string, mixed>  $content
     */
    public function withContent(array $content): static
    {
        return $this->state(fn (array $attributes): array => [
            'content' => $content,
        ]);
    }

    /**
     * Configure the section as publicly hidden.
     */
    public function hidden(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_visible' => false,
        ]);
    }

    /**
     * Configure the section as publicly visible.
     */
    public function visible(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_visible' => true,
        ]);
    }
}
