@props(['item'])

@php
		$rating = (int) ($item['rating'] ?? 0);
		$quote = trim((string) ($item['quote'] ?? ''));
		$customerIdentifier = trim((string) ($item['customer_identifier'] ?? ''));
		$avatarInitial = trim((string) ($item['avatar_initial'] ?? ''));
@endphp

<article
		{{ $attributes->class([
		    'flex min-h-64 flex-col rounded-brand-card',
		    'border border-brand-border bg-brand-surface p-6 sm:p-8',
		    'shadow-brand-card',
		]) }}
		data-review-card>
		<div role="img" aria-label="{{ $rating }} out of 5 stars" class="flex items-center gap-1">
				@for ($star = 1; $star <= 5; $star++)
						<svg aria-hidden="true" viewBox="0 0 24 24" fill="currentColor" @class([
								'size-5',
								'text-brand-eyebrow-soft' => $star <= $rating,
								'text-brand-border' => $star > $rating,
						])>
								<path
										d="M12 2.75l2.74 5.55 6.13.89-4.43 4.32 1.05 6.1L12 16.72l-5.49 2.89 1.05-6.1-4.43-4.32 6.13-.89L12 2.75Z" />
						</svg>
				@endfor
		</div>

		<blockquote class="mt-5 flex flex-1 flex-col">
				<p class="text-brand-body text-base leading-7">
						&ldquo;{{ $quote }}&rdquo;
				</p>

				<footer class="mt-auto flex items-center gap-3 pt-7">
						<span aria-hidden="true"
								class="bg-brand-section text-brand-primary grid size-11 shrink-0 place-items-center rounded-full text-sm font-semibold lowercase">
								{{ $avatarInitial }}
						</span>

						<cite class="text-brand-ink break-all text-sm font-semibold not-italic">
								{{ $customerIdentifier }}
						</cite>
				</footer>
		</blockquote>
</article>
