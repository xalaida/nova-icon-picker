<?php

namespace Nevadskiy\Nova\IconPicker\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Middleware\SetCacheHeaders;
use Illuminate\Routing\Controller;
use Laravel\Nova\Http\Requests\NovaRequest;
use Nevadskiy\Nova\IconPicker\IconPicker;

class IconController extends Controller
{
    public function __construct()
    {
        $this->middleware(SetCacheHeaders::using('public;max_age=900'));
    }

    public function index(NovaRequest $request): JsonResponse
    {
        $resource = $request->newResource();

        $field = $resource->availableFields($request)
            ->whereInstanceOf(IconPicker::class)
            ->findFieldByAttribute($request->field, function () {
                abort(404);
            });

        return response()->json(
            $field->getIconsetByUri($request->iconset)->icons()
        );
    }
}
