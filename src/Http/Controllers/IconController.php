<?php

namespace Nevadskiy\Nova\Icon\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Laravel\Nova\Http\Requests\NovaRequest;
use Nevadskiy\Nova\Icon\Icon;

class IconController
{
    public function index(NovaRequest $request): JsonResponse
    {
        $resource = $request->newResource();

        $field = $resource->availableFields($request)
            ->whereInstanceOf(Icon::class)
            ->findFieldByAttribute($request->field, function () {
                abort(404);
            });

        // @todo throw 404 if iconset is not available.

        return response()->json(
            $field->getIconset($request->iconset)->icons()
        );
    }
}
