<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;

uses(RefreshDatabase::class);

it('keeps the Happy Kiddoz homepage available', function () {
    $this->get(route('home'))
        ->assertOk();
});

it('does not expose obsolete restaurant pages', function () {
    $removedGetRoutes = [
        '/menu',
        '/gallery',
        '/banquet-hall',
        '/reservation-request',
        '/order-inquiry',
        '/contact',
    ];

    foreach ($removedGetRoutes as $uri) {
        $this->get($uri)
            ->assertNotFound();
    }
});

it('does not expose obsolete restaurant form endpoints', function () {
    $removedPostRoutes = [
        '/reservation-requests',
        '/order-inquiries',
        '/contact-inquiries',
    ];

    foreach ($removedPostRoutes as $uri) {
        $this->post($uri)
            ->assertNotFound();
    }
});

it('does not create obsolete restaurant tables', function () {
    $removedTables = [
        'pages',
        'menu_categories',
        'menu_items',
        'gallery_images',
        'reservation_requests',
        'order_inquiries',
        'contact_inquiries',
    ];

    foreach ($removedTables as $table) {
        expect(Schema::hasTable($table))
            ->toBeFalse("The obsolete [{$table}] table still exists.");
    }
});

it('does not autoload obsolete restaurant models', function () {
    $removedModels = [
        'App\\Models\\Page',
        'App\\Models\\MenuCategory',
        'App\\Models\\MenuItem',
        'App\\Models\\GalleryImage',
        'App\\Models\\ReservationRequest',
        'App\\Models\\OrderInquiry',
        'App\\Models\\ContactInquiry',
    ];

    foreach ($removedModels as $model) {
        expect(class_exists($model))
            ->toBeFalse("The obsolete [{$model}] model still exists.");
    }
});

it('does not autoload obsolete Filament resources', function () {
    $removedResources = [
        'App\\Filament\\Resources\\Pages\\PageResource',
        'App\\Filament\\Resources\\MenuCategories\\MenuCategoryResource',
        'App\\Filament\\Resources\\MenuItems\\MenuItemResource',
        'App\\Filament\\Resources\\GalleryImages\\GalleryImageResource',
        'App\\Filament\\Resources\\ReservationRequests\\ReservationRequestResource',
        'App\\Filament\\Resources\\OrderInquiries\\OrderInquiryResource',
        'App\\Filament\\Resources\\ContactInquiries\\ContactInquiryResource',
    ];

    foreach ($removedResources as $resource) {
        expect(class_exists($resource))
            ->toBeFalse("The obsolete [{$resource}] resource still exists.");
    }
});
