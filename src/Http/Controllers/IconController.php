<?php

namespace Nevadskiy\Nova\Icon\Http\Controllers;

use Nevadskiy\Nova\Icon\IconRegistry;

// @todo add permissions.
// @todo caching.
class IconController
{
    public function index(string $iconset, IconRegistry $registry): array
    {
        // @todo get resource and attribute.

        return $registry->iconset($iconset)->icons();
    }
}
