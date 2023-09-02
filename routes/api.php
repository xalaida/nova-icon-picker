<?php

use Illuminate\Support\Facades\Route;
use Nevadskiy\Nova\Icon\Http\Controllers\IconController;

Route::get('/iconsets/{iconset}/icons', [IconController::class, 'index']);
