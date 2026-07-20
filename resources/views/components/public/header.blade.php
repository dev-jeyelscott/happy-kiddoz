<header
    x-data="{ open: false }"
    x-on:click.outside="open = false"
    x-on:keydown.escape.window="
        if (open) {
            open = false;
            $nextTick(() => $refs.toggle.focus());
        }
    "
    x-on:resize.window="
        if (window.innerWidth >= 1024) {
            open = false;
        }
    "
    class="sticky top-0 z-50 border-b border-brand-border/80 bg-brand-canvas/95 backdrop-blur"
>
    <div class="mx-auto flex min-h-20 max-w-7xl items-center justify-between gap-6 px-4 sm:px-6 lg:px-8">
        <a
            href="{{ route('home') }}"
            aria-label="{{ $brandName }} home"
            class="inline-flex shrink-0 items-center rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-primary focus:ring-offset-2 focus:ring-offset-brand-canvas"
        >
            @if ($logoUrl)
                <img
                    src="{{ $logoUrl }}"
                    alt="{{ $brandName }}"
                    width="80"
                    height="60"
                    class="h-14 w-auto object-contain"
                >
            @else
                <span class="font-display text-xl font-semibold text-brand-ink">
                    {{ $brandName }}
                </span>
            @endif
        </a>

        @if ($navigationLinks !== [])
            <nav
                aria-label="Primary navigation"
                class="hidden lg:block"
            >
                <ul class="flex items-center gap-8">
                    @foreach ($navigationLinks as $link)
                        <li>
                            <a
                                href="{{ $link['url'] }}"
                                class="rounded-md text-sm font-semibold text-brand-body transition-colors hover:text-brand-primary focus:outline-none focus:ring-2 focus:ring-brand-primary focus:ring-offset-2 focus:ring-offset-brand-canvas motion-reduce:transition-none"
                            >
                                {{ $link['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>
        @endif

        <div class="hidden shrink-0 lg:block">
            @if ($primaryShopUrl)
                <a
                    href="{{ $primaryShopUrl }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex min-h-11 items-center justify-center rounded-full bg-brand-primary px-6 py-3 text-sm font-semibold text-white shadow-brand-card transition-colors hover:bg-brand-primary-hover focus:outline-none focus:ring-2 focus:ring-brand-primary focus:ring-offset-2 focus:ring-offset-brand-canvas motion-reduce:transition-none"
                >
                    Shop Now
                </a>
            @endif
        </div>

        @if ($navigationLinks !== [] || $primaryShopUrl)
            <button
                x-ref="toggle"
                type="button"
                x-on:click="open = ! open"
                x-bind:aria-expanded="open.toString()"
                x-bind:aria-label="
                    open
                        ? 'Close primary navigation'
                        : 'Open primary navigation'
                "
                aria-controls="mobile-navigation"
                class="inline-flex size-11 items-center justify-center rounded-full border border-brand-border bg-brand-surface text-brand-ink shadow-sm transition-colors hover:bg-brand-section focus:outline-none focus:ring-2 focus:ring-brand-primary focus:ring-offset-2 focus:ring-offset-brand-canvas motion-reduce:transition-none lg:hidden"
            >
                <svg
                    x-show="! open"
                    aria-hidden="true"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    class="size-6"
                >
                    <path
                        stroke-linecap="round"
                        d="M4 7h16M4 12h16M4 17h16"
                    />
                </svg>

                <svg
                    x-show="open"
                    x-cloak
                    aria-hidden="true"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    class="size-6"
                >
                    <path
                        stroke-linecap="round"
                        d="M6 6l12 12M18 6 6 18"
                    />
                </svg>
            </button>
        @endif
    </div>

    @if ($navigationLinks !== [] || $primaryShopUrl)
        <nav
            id="mobile-navigation"
            x-show="open"
            x-cloak
            x-transition:enter="transition ease-out duration-150 motion-reduce:transition-none"
            x-transition:enter-start="-translate-y-2 opacity-0"
            x-transition:enter-end="translate-y-0 opacity-100"
            x-transition:leave="transition ease-in duration-100 motion-reduce:transition-none"
            x-transition:leave-start="translate-y-0 opacity-100"
            x-transition:leave-end="-translate-y-2 opacity-0"
            x-on:click="
                if ($event.target.closest('a')) {
                    open = false;
                }
            "
            aria-label="Mobile primary navigation"
            class="border-t border-brand-border bg-brand-canvas px-4 py-5 shadow-brand-card sm:px-6 lg:hidden"
        >
            <div class="mx-auto max-w-7xl">
                @if ($navigationLinks !== [])
                    <ul class="flex flex-col gap-1">
                        @foreach ($navigationLinks as $link)
                            <li>
                                <a
                                    href="{{ $link['url'] }}"
                                    class="flex min-h-11 items-center rounded-xl px-4 py-3 text-base font-semibold text-brand-ink transition-colors hover:bg-brand-section focus:outline-none focus:ring-2 focus:ring-brand-primary motion-reduce:transition-none"
                                >
                                    {{ $link['label'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif

                @if ($primaryShopUrl)
                    <a
                        href="{{ $primaryShopUrl }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="mt-4 inline-flex min-h-11 w-full items-center justify-center rounded-full bg-brand-primary px-6 py-3 text-sm font-semibold text-white transition-colors hover:bg-brand-primary-hover focus:outline-none focus:ring-2 focus:ring-brand-primary focus:ring-offset-2 focus:ring-offset-brand-canvas motion-reduce:transition-none"
                    >
                        Shop Now
                    </a>
                @endif
            </div>
        </nav>
    @endif
</header>
