@props(['item'])

@php
		$icon = $item['icon'] ?? null;
		$title = trim((string) ($item['title'] ?? ''));
		$description = trim((string) ($item['description'] ?? ''));
@endphp

<article
		{{ $attributes->class([
		    'w-full shrink-0 grow-0',
		    'sm:basis-[calc(50%_-_0.625rem)]',
		    'lg:basis-[calc(33.333%_-_0.875rem)]',
		    'xl:basis-[calc(20%_-_1.2rem)]',
		    'rounded-brand-card border border-brand-border bg-brand-surface p-6',
		    'shadow-brand-card transition-transform duration-200 hover:-translate-y-1',
		    'motion-reduce:transform-none motion-reduce:transition-none',
		]) }}
		data-advantage-card>
		<div class="border-brand-border bg-brand-section text-brand-primary grid size-12 place-items-center rounded-xl border">
				<x-home.advantage-icon :icon="$icon" />
		</div>

		<h3 class="text-brand-ink mt-5 text-base font-semibold leading-6">
				{{ $title }}
		</h3>

		<p class="text-brand-body mt-3 text-sm leading-6">
				{{ $description }}
		</p>
</article>
