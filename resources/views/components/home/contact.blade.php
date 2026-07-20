@props([
    'section',
    'details' => [],
    'links' => [],
    'brandName' => 'Happy Kiddos',
    'logoUrl' => null,
    'copyrightText' => '',
    'privacyPolicyUrl' => null,
    'termsUrl' => null,
])

@php
		$content = is_array($section?->content) ? $section->content : [];

		$heading = trim((string) ($content['heading'] ?? 'Contact Happy Kiddos'));

		$description = trim((string) ($content['description'] ?? ''));

		$linksPanelHeading = trim((string) ($content['links_panel_heading'] ?? 'Shop and follow us'));

		$brandName = trim((string) $brandName);

		if ($brandName === '') {
		    $brandName = 'Happy Kiddos';
		}
@endphp

<section id="contact" aria-labelledby="homepage-contact-heading" class="bg-brand-canvas">
		<div class="mx-auto max-w-[1440px] px-4 py-12 sm:px-6 sm:py-16 lg:px-10 lg:py-20 xl:px-20">
				<div class="rounded-brand-panel bg-brand-primary shadow-brand-image overflow-hidden text-white">
						<div @class([
								'grid gap-8 px-6 py-10 sm:px-8 sm:py-12 lg:px-14 lg:py-14',
								'lg:grid-cols-[minmax(0,1fr)_minmax(22rem,0.9fr)] lg:items-stretch' =>
										$links !== [],
						])>
								<div class="flex flex-col justify-center">
										<h2 id="homepage-contact-heading"
												class="font-display max-w-2xl text-3xl font-semibold leading-tight tracking-[-0.03em] text-white sm:text-4xl lg:text-5xl">
												{{ $heading }}
										</h2>

										@if ($description !== '')
												<p class="mt-5 max-w-2xl text-base leading-7 text-white/85 sm:text-lg">
														{{ $description }}
												</p>
										@endif

										@if ($details !== [])
												<address class="mt-8 not-italic">
														<ul class="space-y-4">
																@foreach ($details as $detail)
																		@php
																				$detailKey = trim((string) ($detail['key'] ?? ''));

																				$detailLabel = trim((string) ($detail['label'] ?? ''));

																				$detailValue = trim((string) ($detail['value'] ?? ''));

																				$detailHref = trim((string) ($detail['href'] ?? ''));

																				$opensNewTab = (bool) ($detail['opens_new_tab'] ?? false);
																		@endphp

																		@if ($detailValue !== '')
																				<li class="flex items-start gap-3">
																						<span aria-hidden="true"
																								class="mt-0.5 inline-flex size-6 shrink-0 items-center justify-center text-white/90">
																								@switch($detailKey)
																										@case('email')
																												<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-5">
																														<rect x="3.5" y="5" width="17" height="14" rx="2" />
																														<path stroke-linecap="round" stroke-linejoin="round" d="m5 7 7 5 7-5" />
																												</svg>
																										@break

																										@case('phone')
																												<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-5">
																														<path stroke-linecap="round" stroke-linejoin="round"
																																d="M7.2 3.8 10 8.6 7.9 10a14.3 14.3 0 0 0 6.1 6.1l1.4-2.1 4.8 2.8-.9 3a2 2 0 0 1-2.2 1.4C9.6 20 4 14.4 2.8 6.9a2 2 0 0 1 1.4-2.2l3-.9Z" />
																												</svg>
																										@break

																										@case('viber')
																												<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-5">
																														<path stroke-linecap="round" stroke-linejoin="round"
																																d="M5 5.5A9 9 0 0 1 19 17l1 4-4-1A9 9 0 0 1 5 5.5Z" />
																														<path stroke-linecap="round" d="M9 8.5c.4 3.2 2.3 5.1 5.5 5.5" />
																												</svg>
																										@break

																										@default
																												<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-5">
																														<path stroke-linecap="round" stroke-linejoin="round"
																																d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z" />
																														<circle cx="12" cy="10" r="2.5" />
																												</svg>
																								@endswitch
																						</span>

																						<div class="min-w-0">
																								<span class="sr-only">
																										{{ $detailLabel }}:
																								</span>

																								@if ($detailHref !== '')
																										<a href="{{ $detailHref }}"
																												@if ($opensNewTab) target="_blank"
                                                            rel="noopener noreferrer" @endif
																												class="focus:ring-offset-brand-primary break-words rounded-sm text-sm leading-6 text-white underline-offset-4 transition-colors hover:text-white/80 hover:underline focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 motion-reduce:transition-none sm:text-base">
																												{{ $detailValue }}
																										</a>
																								@else
																										<span class="break-words text-sm leading-6 text-white sm:text-base">
																												{{ $detailValue }}
																										</span>
																								@endif
																						</div>
																				</li>
																		@endif
																@endforeach
														</ul>
												</address>
										@endif
								</div>

								@if ($links !== [])
										<div
												class="rounded-brand-panel flex min-h-full flex-col justify-center bg-white/10 p-6 ring-1 ring-white/10 sm:p-8 lg:p-10">
												<h3 class="font-display text-2xl font-semibold text-white">
														{{ $linksPanelHeading }}
												</h3>

												<div class="mt-7 flex flex-wrap gap-3">
														@foreach ($links as $link)
																@php
																		$linkKey = trim((string) ($link['key'] ?? ''));

																		$linkLabel = trim((string) ($link['label'] ?? ''));

																		$linkUrl = trim((string) ($link['url'] ?? ''));

																		$linkClasses = match ($linkKey) {
																		    'shopee' => 'border-marketplace-shopee bg-marketplace-shopee text-white hover:brightness-95',
																		    'lazada' => 'border-marketplace-lazada bg-marketplace-lazada text-white hover:brightness-110',
																		    default => 'border-white bg-white text-brand-ink hover:bg-brand-section',
																		};
																@endphp

																@if ($linkLabel !== '' && $linkUrl !== '')
																		<a href="{{ $linkUrl }}" target="_blank" rel="noopener noreferrer"
																				aria-label="Open Happy Kiddos on {{ $linkLabel }}" @class([
																						'inline-flex min-h-11 items-center justify-center gap-2 rounded-full border px-5 py-2.5 text-sm font-semibold shadow-sm transition-[background-color,border-color,filter] focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-brand-primary motion-reduce:transition-none',
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
										</div>
								@endif
						</div>
				</div>

				<footer class="text-brand-muted grid gap-6 py-8 text-sm md:grid-cols-[1fr_auto_1fr] md:items-center">
						<a href="{{ route('home') }}" aria-label="{{ $brandName }} home"
								class="focus:ring-brand-primary focus:ring-offset-brand-canvas inline-flex w-fit items-center rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2">
								@if ($logoUrl)
										<img src="{{ $logoUrl }}" alt="{{ $brandName }}" width="96" height="64" loading="lazy"
												decoding="async" class="h-12 w-auto object-contain">
								@else
										<span class="font-display text-brand-ink text-xl font-semibold">
												{{ $brandName }}
										</span>
								@endif
						</a>

						<p class="leading-6 md:text-center">
								{{ $copyrightText }}
						</p>

						@if ($privacyPolicyUrl || $termsUrl)
								<nav aria-label="Legal" class="flex flex-wrap gap-x-6 gap-y-3 md:justify-end">
										@if ($privacyPolicyUrl)
												<a href="{{ $privacyPolicyUrl }}" target="_blank" rel="noopener noreferrer"
														class="text-brand-body hover:text-brand-primary focus:ring-brand-primary focus:ring-offset-brand-canvas rounded-sm font-medium underline-offset-4 transition-colors hover:underline focus:outline-none focus:ring-2 focus:ring-offset-2 motion-reduce:transition-none">
														Privacy Policy
												</a>
										@endif

										@if ($termsUrl)
												<a href="{{ $termsUrl }}" target="_blank" rel="noopener noreferrer"
														class="text-brand-body hover:text-brand-primary focus:ring-brand-primary focus:ring-offset-brand-canvas rounded-sm font-medium underline-offset-4 transition-colors hover:underline focus:outline-none focus:ring-2 focus:ring-offset-2 motion-reduce:transition-none">
														Terms of Service
												</a>
										@endif
								</nav>
						@endif
				</footer>
		</div>
</section>
