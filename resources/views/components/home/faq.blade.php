@props(['section', 'items' => []])

@php
		/*
		 * Read only the expected FAQ fields from the section content.
		 * Every displayed value remains escaped through standard Blade output.
		 */
		$content = is_array($section?->content) ? $section->content : [];

		$eyebrow = trim((string) ($content['eyebrow'] ?? ''));
		$heading = trim((string) ($content['heading'] ?? ''));
@endphp

@if ($heading !== '' && $items !== [])
		<section id="faq" aria-labelledby="homepage-faq-heading"
				class="border-brand-border bg-brand-section-alt scroll-mt-20 border-b">
				<div
						class="mx-auto grid max-w-[1440px] gap-10 px-4 py-16 sm:px-6 sm:py-20 lg:grid-cols-[minmax(0,0.7fr)_minmax(0,1.3fr)] lg:gap-16 lg:px-10 lg:py-24 xl:px-20">
						<div class="max-w-xl lg:sticky lg:top-32 lg:self-start">
								@if ($eyebrow !== '')
										<p class="text-brand-eyebrow text-xs font-semibold uppercase tracking-[0.16em] sm:text-sm">
												{{ $eyebrow }}
										</p>
								@endif

								<h2 id="homepage-faq-heading"
										class="font-display text-brand-ink mt-3 text-3xl font-semibold leading-tight tracking-[-0.03em] sm:text-4xl lg:text-5xl">
										{{ $heading }}
								</h2>

								<p class="text-brand-body mt-5 max-w-lg text-base leading-8">
										Find quick answers about our products, ordering process,
										delivery, and Montessori-inspired learning spaces.
								</p>
						</div>

						<div class="space-y-4">
								@foreach ($items as $item)
										<details name="homepage-faq"
												class="rounded-brand-card border-brand-border bg-brand-surface shadow-brand-card group overflow-hidden border">
												<summary
														class="text-brand-ink hover:bg-brand-section focus-visible:ring-brand-primary flex min-h-16 cursor-pointer list-none items-center justify-between gap-6 px-5 py-5 text-left font-semibold transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-inset motion-reduce:transition-none sm:px-6 [&::-webkit-details-marker]:hidden">
														<span class="text-base leading-7 sm:text-lg">
																{{ $item['question'] }}
														</span>

														<span aria-hidden="true"
																class="border-brand-border bg-brand-canvas text-brand-primary flex size-9 shrink-0 items-center justify-center rounded-full border">
																<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"
																		class="size-5 transition-transform duration-200 group-open:rotate-45 motion-reduce:transition-none">
																		<path stroke-linecap="round" d="M12 5v14M5 12h14" />
																</svg>
														</span>
												</summary>

												<div class="border-brand-border border-t px-5 py-5 sm:px-6 sm:py-6">
														<p class="text-brand-body whitespace-pre-line text-base leading-8">
																{{ $item['answer'] }}
														</p>
												</div>
										</details>
								@endforeach
						</div>
				</div>
		</section>
@endif
