@props(['section', 'imageUrl' => null, 'paragraphs' => []])

@php
		$content = is_array($section?->content) ? $section->content : [];

		$eyebrow = trim((string) ($content['eyebrow'] ?? ''));
		$heading = trim((string) ($content['heading'] ?? ''));
		$imageAlt = trim((string) ($content['image_alt'] ?? ''));
		$resolvedImageUrl = trim((string) $imageUrl);
@endphp

<section id="about" aria-labelledby="homepage-about-heading" class="border-brand-border bg-brand-section-alt border-b">
		<div
				class="mx-auto grid max-w-[1440px] items-center gap-10 px-4 py-16 sm:px-6 sm:py-20 lg:grid-cols-[minmax(0,1.02fr)_minmax(0,0.98fr)] lg:gap-14 lg:px-10 lg:py-24 xl:gap-20 xl:px-20">
				<div class="bg-brand-section rounded-brand-panel shadow-brand-image aspect-[6/5] overflow-hidden">
						@if ($resolvedImageUrl !== '')
								<img src="{{ $resolvedImageUrl }}" alt="{{ $imageAlt }}" width="1200" height="1000" loading="lazy"
										decoding="async" class="h-full w-full object-cover">
						@else
								<div aria-hidden="true" class="bg-brand-section grid h-full w-full place-items-center">
										<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4"
												class="text-brand-eyebrow-soft/40 size-20">
												<path stroke-linecap="round" stroke-linejoin="round"
														d="M4 16.5 8.5 12l3 3 2.5-2.5 6 6M5.5 5.5h13a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-13a2 2 0 0 1-2-2v-9a2 2 0 0 1 2-2Zm9 3.5h.01" />
										</svg>
								</div>
						@endif
				</div>

				<div class="max-w-2xl lg:py-6">
						@if ($eyebrow !== '')
								<p class="text-brand-eyebrow text-xs font-semibold uppercase tracking-[0.16em] sm:text-sm">
										{{ $eyebrow }}
								</p>
						@endif

						<h2 id="homepage-about-heading"
								class="font-display text-brand-ink mt-3 text-3xl font-semibold leading-[1.08] tracking-[-0.035em] sm:text-4xl lg:text-[3.25rem]">
								{{ $heading }}
						</h2>

						@if ($paragraphs !== [])
								<div class="text-brand-body mt-6 space-y-5 text-base leading-7 sm:mt-8 sm:text-lg sm:leading-8">
										@foreach ($paragraphs as $paragraph)
												<p data-about-paragraph>
														{{ $paragraph }}
												</p>
										@endforeach
								</div>
						@endif
				</div>
		</div>
</section>
