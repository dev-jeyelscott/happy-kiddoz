<x-filament-widgets::widget>
		<x-filament::section>
				<x-slot name="heading">
						Quick actions
				</x-slot>

				<x-slot name="description">
						Update the homepage content, configure global website details,
						or review the public page.
				</x-slot>

				<div class="grid gap-3 md:grid-cols-3">
						<x-filament::button :href="$this->homepageSectionsUrl()" icon="heroicon-o-rectangle-group" size="lg" tag="a">
								Edit homepage sections
						</x-filament::button>

						<x-filament::button :href="$this->siteSettingsUrl()" color="gray" icon="heroicon-o-cog-6-tooth" size="lg" tag="a">
								Manage site settings
						</x-filament::button>

						<x-filament::button :href="$this->publicHomepageUrl()" color="gray" icon="heroicon-o-arrow-top-right-on-square"
								rel="noopener noreferrer" size="lg" tag="a" target="_blank">
								View public website
						</x-filament::button>
				</div>
		</x-filament::section>
</x-filament-widgets::widget>
