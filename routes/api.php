<?php

use Illuminate\Support\Facades\Route;
use Nevadskiy\Nova\IconPicker\Http\Controllers\IconController;

Route::get('/{resource}/fields/{field}/iconsets/{iconset}', [IconController::class, 'index']);
