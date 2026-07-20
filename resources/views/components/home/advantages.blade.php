@props(['section', 'items' => []])

@php
		$content = is_array($section?->content) ? $section->content : [];

		$eyebrow = trim((string) ($content['eyebrow'] ?? ''));
		$heading = trim((string) ($content['heading'] ?? ''));
@endphp

<section id="why-us" aria-labelledby="homepage-advantages-heading"
		class="border-brand-border bg-brand-surface border-b">
		<div class="mx-auto max-w-[1440px] px-4 py-16 sm:px-6 sm:py-20 lg:px-10 lg:py-24 xl:px-20">
				<div class="max-w-4xl">
						@if ($eyebrow !== '')
								<p class="text-brand-eyebrow text-xs font-semibold uppercase tracking-[0.16em] sm:text-sm">
										{{ $eyebrow }}
								</p>
						@endif

						<h2 id="homepage-advantages-heading"
								class="font-display text-brand-ink mt-3 text-3xl font-semibold leading-tight tracking-[-0.03em] sm:text-4xl lg:text-5xl">
								{{ $heading }}
						</h2>
				</div>

				@if ($items !== [])
						<div class="mt-10 flex flex-wrap justify-center gap-5 lg:mt-12 xl:gap-6">
								@foreach ($items as $item)
										<x-home.advantage-card :item="$item" />
								@endforeach
						</div>
				@endif
		</div>
</section>
