<x-layouts.public
    :title="$metaTitle"
    :description="$metaDescription"
    :homepage-sections="$homepageSections"
    :site-settings="$siteSettings"
>
    @if ($heroSection)
        <x-home.hero
            :section="$heroSection"
            :image-url="$heroImageUrl"
            :links="$heroLinks"
        />
    @endif

    @if ($categoriesSection)
        <x-home.categories
            :section="$categoriesSection"
            :items="$categoryItems"
        />
    @endif

    @if ($aboutSection)
        <x-home.about
            :section="$aboutSection"
            :image-url="$aboutImageUrl"
            :paragraphs="$aboutParagraphs"
        />
    @endif

    @if ($advantagesSection)
        <x-home.advantages
            :section="$advantagesSection"
            :items="$advantageItems"
        />
    @endif

    @if ($reviewsSection)
        <x-home.reviews
            :section="$reviewsSection"
            :items="$reviewItems"
        />
    @endif

    @if ($faqSection && $faqItems !== [])
        <x-home.faq
            :section="$faqSection"
            :items="$faqItems"
        />
    @endif

    @if ($contactSection)
        <x-home.contact
            :section="$contactSection"
            :details="$contactDetails"
            :links="$contactLinks"
            :brand-name="$contactBrandName"
            :logo-url="$contactLogoUrl"
            :copyright-text="$footerCopyright"
            :privacy-policy-url="$privacyPolicyUrl"
            :terms-url="$termsUrl"
        />
    @endif
</x-layouts.public>