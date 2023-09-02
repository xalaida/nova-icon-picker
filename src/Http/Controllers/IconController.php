<?php

namespace Nevadskiy\Nova\Icon\Http\Controllers;

use Nevadskiy\Nova\Icon\IconRegistry;

// @todo add permissions.
// @todo caching.
class IconController
{
    public function index(string $iconset, IconRegistry $registry): array
    {
        return $registry->iconset($iconset)->icons();
    }
}
