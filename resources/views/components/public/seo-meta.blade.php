@props([
    'title' => null,
    'description' => null,
])

@php
    $settings = \App\Models\SiteSetting::keyValueMap();

    $siteName = trim((string) (
        $settings['brand_name']
        ?? config('app.name', 'Happy Kiddoz')
    ));

    $siteName = $siteName !== ''
        ? $siteName
        : 'Happy Kiddoz';

    $defaultTitle = trim((string) (
        $settings['meta_title']
        ?? $siteName
    ));

    $pageTitle = filled($title)
        ? trim((string) $title).' | '.$siteName
        : $defaultTitle;

    $metaDescription = filled($description)
        ? trim((string) $description)
        : trim((string) (
            $settings['meta_description']
            ?? 'Thoughtfully designed Montessori furniture and learning materials for Filipino homes, schools, and learning centers.'
        ));

    $faviconPath = $settings['favicon_path'] ?? null;

    $faviconUrl = is_string($faviconPath) && filled($faviconPath)
        ? \Illuminate\Support\Facades\Storage::disk('public')->url(
            ltrim($faviconPath, '/')
        )
        : null;
@endphp

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>{{ $pageTitle }}</title>
<meta name="description" content="{{ $metaDescription }}">
<meta name="theme-color" content="#fdfbf7">

@if ($faviconUrl)
    <link rel="icon" href="{{ $faviconUrl }}">
@endif
