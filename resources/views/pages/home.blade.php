<x-layouts.public
    :title="$metaTitle"
    :description="$metaDescription"
>
    @if ($heroSection)
        <x-home.hero
            :section="$heroSection"
            :image-url="$heroImageUrl"
            :links="$heroLinks"
        />
    @endif
</x-layouts.public>
