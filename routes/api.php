<?php

use Illuminate\Support\Facades\Route;
use Nevadskiy\Nova\Icon\Http\Controllers\IconController;

Route::get('/{resource}/fields/{field}/iconsets/{iconset}', [IconController::class, 'index']);
