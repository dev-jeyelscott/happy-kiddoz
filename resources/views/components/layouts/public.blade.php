<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    class="scroll-smooth motion-reduce:scroll-auto"
>
<head>
    <x-public.seo-meta
        :title="$title ?? null"
        :description="$description ?? null"
    />

    @fonts(['body', 'display'])
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-brand-canvas font-sans text-brand-body antialiased">
    <a
        href="#main-content"
        class="fixed left-4 top-4 z-[100] -translate-y-24 rounded-full bg-brand-primary px-4 py-2 text-sm font-semibold text-white shadow-brand-card transition-transform focus:translate-y-0 focus:outline-none focus:ring-2 focus:ring-brand-primary focus:ring-offset-2 focus:ring-offset-brand-canvas motion-reduce:transition-none"
    >
        Skip to main content
    </a>

    <div class="min-h-screen">
        <x-public-site.header />

        <main
            id="main-content"
            tabindex="-1"
            class="focus:outline-none"
        >
            {{ $slot }}
        </main>
    </div>
</body>
</html>
