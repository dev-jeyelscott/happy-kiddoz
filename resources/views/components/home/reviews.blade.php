@props(['section', 'items' => []])

@php
		$content = is_array($section?->content) ? $section->content : [];

		$eyebrow = trim((string) ($content['eyebrow'] ?? ''));
		$heading = trim((string) ($content['heading'] ?? ''));
@endphp

@if ($heading !== '' && $items !== [])
		<section id="reviews" aria-labelledby="homepage-reviews-heading"
				class="border-brand-border bg-brand-section scroll-mt-24 border-b">
				<div class="mx-auto max-w-[1440px] px-4 py-16 sm:px-6 sm:py-20 lg:px-10 lg:py-24 xl:px-20">
						<div class="max-w-4xl">
								@if ($eyebrow !== '')
										<p class="text-brand-eyebrow text-xs font-semibold uppercase tracking-[0.16em] sm:text-sm">
												{{ $eyebrow }}
										</p>
								@endif

								<h2 id="homepage-reviews-heading"
										class="font-display text-brand-ink mt-3 text-3xl font-semibold leading-tight tracking-[-0.03em] sm:text-4xl lg:text-5xl">
										{{ $heading }}
								</h2>
						</div>

						<div class="mt-10 grid grid-cols-1 gap-5 md:grid-cols-2 lg:mt-12 lg:gap-6">
								@foreach ($items as $item)
										<x-home.review-card :item="$item" />
								@endforeach
						</div>
				</div>
		</section>
@endif
