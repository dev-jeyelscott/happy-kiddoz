@props([
    'title' => null,
    'description' => null,
    'homepageSections' => null,
    'siteSettings' => [],
])

<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    class="scroll-smooth motion-reduce:scroll-auto"
>
<head>
    <x-public.seo-meta
        :title="$title"
        :description="$description"
        :settings="$siteSettings"
    />

    @fonts(['body', 'display'])
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-brand-canvas text-brand-body min-h-screen font-sans antialiased">
    <a
        href="#main-content"
        class="bg-brand-primary shadow-brand-card focus:ring-brand-primary focus:ring-offset-brand-canvas fixed left-4 top-4 z-[100] -translate-y-24 rounded-full px-4 py-2 text-sm font-semibold text-white transition-transform focus:translate-y-0 focus:outline-none focus:ring-2 focus:ring-offset-2 motion-reduce:transition-none"
    >
        Skip to main content
    </a>

    <div class="min-h-screen">
        <x-public-site.header
            :sections="$homepageSections"
            :settings="$siteSettings"
        />

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
