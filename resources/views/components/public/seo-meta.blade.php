@props([
    'title' => null,
    'description' => null,
    'settings' => [],
])

@php
    /*
    |--------------------------------------------------------------------------
    | Resolve settings supplied by the controller
    |--------------------------------------------------------------------------
    |
    | The controller already loads the complete settings map. Reusing it here
    | prevents the SEO component from executing another database query.
    |
    */

    $settings = is_array($settings) ? $settings : [];

    $siteName = trim(
        (string) ($settings['brand_name'] ?? config('app.name', 'Happy Kiddos'))
    );

    if ($siteName === '') {
        $siteName = 'Happy Kiddos';
    }

    /*
    |--------------------------------------------------------------------------
    | Resolve metadata
    |--------------------------------------------------------------------------
    |
    | A supplied page title is treated as the complete title. This prevents
    | configured titles such as "Happy Kiddos | Montessori Furniture" from
    | becoming "Happy Kiddos | Montessori Furniture | Happy Kiddos".
    |
    */

    $metaTitle = filled($title)
        ? trim((string) $title)
        : trim((string) ($settings['meta_title'] ?? $siteName));

    if ($metaTitle === '') {
        $metaTitle = $siteName;
    }

    $metaDescription = filled($description)
        ? trim((string) $description)
        : trim(
            (string) (
                $settings['meta_description']
                ?? 'Thoughtfully designed Montessori furniture and learning materials for Filipino homes, schools, and learning centers.'
            )
        );

    $faviconPath = $settings['favicon_path'] ?? null;

    $faviconUrl = is_string($faviconPath) && filled($faviconPath)
        ? \Illuminate\Support\Facades\Storage::disk('public')
            ->url(ltrim($faviconPath, '/'))
        : null;
@endphp

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>{{ $metaTitle }}</title>
<meta name="description" content="{{ $metaDescription }}">
<meta name="theme-color" content="#fdfbf7">

@if ($faviconUrl)
    <link rel="icon" href="{{ $faviconUrl }}">
@endif
