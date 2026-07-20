@props(['icon'])

@php
		use App\Enums\HomepageAdvantageIcon;

		/*
		 * Only enum instances produced by the controller may reach the SVG
		 * renderer. Arbitrary strings from the CMS are not rendered.
		 */
		$iconValue = $icon instanceof HomepageAdvantageIcon ? $icon->value : null;
@endphp

@if ($iconValue !== null)
		<svg aria-hidden="true" focusable="false" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
				stroke-linecap="round" stroke-linejoin="round" class="size-6" data-advantage-icon="{{ $iconValue }}">
				@switch($iconValue)
						@case('sparkles')
								<path d="m12 3 1.45 4.55L18 9l-4.55 1.45L12 15l-1.45-4.55L6 9l4.55-1.45L12 3Z" />
								<path d="m5 15 .8 2.2L8 18l-2.2.8L5 21l-.8-2.2L2 18l2.2-.8L5 15Z" />
								<path d="m19 13 .7 1.8 1.8.7-1.8.7L19 18l-.7-1.8-1.8-.7 1.8-.7L19 13Z" />
						@break

						@case('leaf')
								<path d="M20 4c-7.2.1-12 3.7-12 9a5 5 0 0 0 5 5c5.3 0 8.9-4.8 9-12a2 2 0 0 0-2-2Z" />
								<path d="M4 20c2.7-5.3 6.8-8.7 12.5-10.5" />
						@break

						@case('blocks')
								<rect x="3" y="4" width="7" height="7" rx="1.5" />
								<rect x="14" y="4" width="7" height="7" rx="1.5" />
								<rect x="8.5" y="13" width="7" height="7" rx="1.5" />
								<path d="M6.5 7.5h.01M17.5 7.5h.01M12 16.5h.01" />
						@break

						@case('shield')
								<path d="M12 3 19 6v5c0 4.5-2.9 7.8-7 10-4.1-2.2-7-5.5-7-10V6l7-3Z" />
								<path d="m9 12 2 2 4-4" />
						@break

						@case('truck')
								<path d="M3 6h11v10H3V6Z" />
								<path d="M14 9h4l3 3v4h-7V9Z" />
								<path d="M17 9v4h4" />
								<circle cx="7" cy="18" r="2" />
								<circle cx="18" cy="18" r="2" />
						@break
				@endswitch
		</svg>
@endif
