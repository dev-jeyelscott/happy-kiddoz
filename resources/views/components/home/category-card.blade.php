@props(['item'])

@php
		$title = trim((string) ($item['title'] ?? ''));
		$description = trim((string) ($item['description'] ?? ''));
		$imageUrl = trim((string) ($item['image_url'] ?? ''));
		$imageAlt = trim((string) ($item['image_alt'] ?? ''));
		$externalUrl = trim((string) ($item['external_url'] ?? ''));

		$isLinked = $externalUrl !== '';

		$cardClasses = [
		    'group flex h-full flex-col overflow-hidden rounded-brand-card border border-brand-border bg-brand-surface shadow-brand-card',
		    'transition-[transform,box-shadow,border-color] duration-200 hover:-translate-y-1 hover:border-brand-eyebrow-soft/50 hover:shadow-brand-image focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-primary focus-visible:ring-offset-2 focus-visible:ring-offset-brand-surface motion-reduce:transform-none motion-reduce:transition-none' => $isLinked,
		];
@endphp

@if ($isLinked)
		<a href="{{ $externalUrl }}" target="_blank" rel="noopener noreferrer" aria-label="Open {{ $title }} category"
				data-category-card @class($cardClasses)>
		@else
				<article data-category-card @class($cardClasses)>
@endif
<div class="bg-brand-section relative aspect-[4/3] overflow-hidden">
		@if ($imageUrl !== '')
				<img src="{{ $imageUrl }}" alt="{{ $imageAlt }}" width="800" height="600" loading="lazy" decoding="async"
						@class([
								'h-full w-full object-cover',
								'transition-transform duration-300 group-hover:scale-[1.025] motion-reduce:transform-none motion-reduce:transition-none' => $isLinked,
						])>
		@else
				<div aria-hidden="true" class="bg-brand-section grid h-full w-full place-items-center">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4"
								class="text-brand-eyebrow-soft/40 size-16">
								<path stroke-linecap="round" stroke-linejoin="round"
										d="M4 16.5 8.5 12l3 3 2.5-2.5 6 6M5.5 5.5h13a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-13a2 2 0 0 1-2-2v-9a2 2 0 0 1 2-2Zm9 3.5h.01" />
						</svg>
				</div>
		@endif
</div>

<div class="flex flex-1 flex-col px-5 py-5 sm:px-6 sm:py-6">
		<h3 class="font-display text-brand-ink text-xl font-semibold leading-tight tracking-[-0.02em]">
				{{ $title }}
		</h3>

		@if ($description !== '')
				<p class="text-brand-muted mt-2 text-sm leading-6">
						{{ $description }}
				</p>
		@endif
</div>
@if ($isLinked)
		</a>
@else
		</article>
@endif
