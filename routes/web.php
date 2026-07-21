<?php

use App\Http\Controllers\PublicSite\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Web Routes
|--------------------------------------------------------------------------
|
| Happy Kiddos is implemented as one server-rendered homepage with anchor
| navigation. Filament registers the administration routes separately.
|
*/

Route::get('/', [HomeController::class, 'index'])
    ->name('home');
