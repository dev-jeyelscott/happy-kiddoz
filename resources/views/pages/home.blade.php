<x-layouts.public :title="$metaTitle" :description="$metaDescription">
		@if ($heroSection)
				<x-home.hero :section="$heroSection" :image-url="$heroImageUrl" :links="$heroLinks" />
		@endif

		@if ($categoriesSection)
				<x-home.categories :section="$categoriesSection" :items="$categoryItems" />
		@endif

		@if ($aboutSection)
				<x-home.about :section="$aboutSection" :image-url="$aboutImageUrl" :paragraphs="$aboutParagraphs" />
		@endif

		@if ($advantagesSection)
				<x-home.advantages :section="$advantagesSection" :items="$advantageItems" />
		@endif

		@if ($reviewsSection && $reviewItems !== [])
				<x-home.reviews :section="$reviewsSection" :items="$reviewItems" />
		@endif
</x-layouts.public>
