<x-layouts.public :title="$metaTitle" :description="$metaDescription">
		@if ($heroSection)
				<x-home.hero :section="$heroSection" :image-url="$heroImageUrl" :links="$heroLinks" />
		@endif

		@if ($categoriesSection)
				<x-home.categories :section="$categoriesSection" :items="$categoryItems" />
		@endif
</x-layouts.public>
