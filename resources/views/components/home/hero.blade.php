@props(['section', 'imageUrl' => null, 'links' => []])

@php
		$content = is_array($section?->content) ? $section->content : [];

		$eyebrow = trim((string) ($content['eyebrow'] ?? ''));
		$headline = trim((string) ($content['headline'] ?? ''));
		$description = trim((string) ($content['description'] ?? ''));
		$imageAlt = trim((string) ($content['image_alt'] ?? ''));
		$ratingStatement = trim((string) ($content['rating_statement'] ?? ''));

		$rawRating = $content['rating_value'] ?? null;

		$ratingValue = is_numeric($rawRating) ? max(0, min(5, (float) $rawRating)) : null;

		$ratingLabel = $ratingValue !== null ? number_format($ratingValue, 1) : null;
@endphp

<section aria-labelledby="homepage-hero-heading" class="border-brand-border bg-brand-canvas overflow-hidden border-b">
		<div class="mx-auto grid max-w-[1440px] lg:min-h-[640px] lg:grid-cols-[minmax(0,0.93fr)_minmax(0,1.07fr)]">
				<div class="flex items-center px-4 py-14 sm:px-6 sm:py-16 lg:px-10 lg:py-20 xl:px-20">
						<div class="max-w-2xl">
								@if ($eyebrow !== '')
										<p
												class="border-brand-border bg-brand-section-alt text-brand-eyebrow inline-flex max-w-full items-center gap-2 rounded-full border px-4 py-2 text-xs font-semibold uppercase leading-5 tracking-[0.14em] sm:text-sm">
												<svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
														class="size-4 shrink-0">
														<path stroke-linecap="round" stroke-linejoin="round"
																d="M19.5 4.5c-5.4.1-9.3 1.8-11.6 5.1-1.8 2.6-2.2 5.5-2.3 8.4m.1 0c2.2-3.7 5-6.6 8.7-8.7M5.7 18c3.4.1 6.3-.7 8.6-2.6 3.1-2.5 4.6-6.2 5.2-10.9" />
												</svg>

												<span>{{ $eyebrow }}</span>
										</p>
								@endif

								<h1 id="homepage-hero-heading"
										class="font-display text-brand-ink mt-7 max-w-xl text-4xl font-semibold leading-[0.98] tracking-[-0.035em] sm:text-5xl lg:text-6xl xl:text-[4.25rem]">
										{{ $headline }}
								</h1>

								@if ($description !== '')
										<p class="text-brand-body mt-7 max-w-xl text-base leading-7 sm:text-lg sm:leading-8">
												{{ $description }}
										</p>
								@endif

								@if ($links !== [])
										<div class="mt-8 flex flex-wrap gap-3">
												@foreach ($links as $link)
														@php
																$linkKey = (string) ($link['key'] ?? '');
																$linkLabel = trim((string) ($link['label'] ?? ''));
																$linkUrl = trim((string) ($link['url'] ?? ''));

																$linkClasses = match ($linkKey) {
																    'shopee' => 'border-marketplace-shopee bg-marketplace-shopee text-white hover:brightness-95',
																    'lazada' => 'border-marketplace-lazada bg-marketplace-lazada text-white hover:brightness-110',
																    default => 'border-brand-border bg-brand-surface text-brand-ink hover:bg-brand-section',
																};
														@endphp

														@if ($linkLabel !== '' && $linkUrl !== '')
																<a href="{{ $linkUrl }}" target="_blank" rel="noopener noreferrer"
																		aria-label="Open Happy Kiddos on {{ $linkLabel }}" @class([
																				'inline-flex min-h-11 items-center justify-center gap-2 rounded-full border px-5 py-2.5 text-sm font-semibold shadow-sm transition-[background-color,border-color,filter] focus:outline-none focus:ring-2 focus:ring-brand-primary focus:ring-offset-2 focus:ring-offset-brand-canvas motion-reduce:transition-none',
																				$linkClasses,
																		])>
																		@switch($linkKey)
																				@case('facebook')
																						<span aria-hidden="true"
																								class="flex size-5 items-center justify-center rounded-full border border-current text-xs font-bold">
																								f
																						</span>
																				@break

																				@case('instagram')
																						<svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
																								class="size-5">
																								<rect x="3.5" y="3.5" width="17" height="17" rx="5" />
																								<circle cx="12" cy="12" r="4" />
																								<circle cx="17.5" cy="6.5" r=".75" fill="currentColor" stroke="none" />
																						</svg>
																				@break

																				@default
																						<svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
																								class="size-5">
																								<path stroke-linecap="round" stroke-linejoin="round" d="M6.5 8.5h11l-.8 11h-9.4l-.8-11Z" />
																								<path stroke-linecap="round" d="M9 8.5a3 3 0 0 1 6 0" />
																						</svg>
																		@endswitch

																		<span>{{ $linkLabel }}</span>
																</a>
														@endif
												@endforeach
										</div>
								@endif

								@if ($ratingValue !== null || $ratingStatement !== '')
										<div class="mt-9 flex flex-wrap items-center gap-x-4 gap-y-3 text-sm"
												@if ($ratingValue !== null) aria-label="Rated {{ $ratingLabel }} out of 5" @endif>
												@if ($ratingValue !== null)
														<div class="flex items-center gap-2">
																<span aria-hidden="true" class="text-brand-primary flex items-center gap-0.5">
																		@for ($star = 0; $star < 5; $star++)
																				<svg viewBox="0 0 20 20" fill="currentColor" class="size-4">
																						<path
																								d="m10 1.8 2.35 4.76 5.25.76-3.8 3.7.9 5.23L10 13.78l-4.7 2.47.9-5.23-3.8-3.7 5.25-.76L10 1.8Z" />
																				</svg>
																		@endfor
																</span>

																<span class="text-brand-ink font-semibold">
																		{{ $ratingLabel }}
																</span>
														</div>
												@endif

												@if ($ratingStatement !== '')
														<p class="text-brand-muted leading-6">
																{{ $ratingStatement }}
														</p>
												@endif
										</div>
								@endif
						</div>
				</div>

				<div class="bg-brand-section relative min-h-[360px] overflow-hidden sm:min-h-[500px] lg:min-h-full">
						@if ($imageUrl)
								<img src="{{ $imageUrl }}" alt="{{ $imageAlt }}" width="948" height="948" loading="eager"
										fetchpriority="high" decoding="async" class="absolute inset-0 h-full w-full object-cover object-center">
						@else
								<div aria-hidden="true" class="bg-brand-section absolute inset-0 grid place-items-center">
										<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4"
												class="text-brand-eyebrow-soft/40 size-20">
												<path stroke-linecap="round" stroke-linejoin="round"
														d="M4 16.5 8.5 12l3 3 2.5-2.5 6 6M5.5 5.5h13a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-13a2 2 0 0 1-2-2v-9a2 2 0 0 1 2-2Zm9 3.5h.01" />
										</svg>
								</div>
						@endif
				</div>
		</div>
</section>
