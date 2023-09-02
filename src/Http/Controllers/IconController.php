<?php

namespace Nevadskiy\Nova\Icon\Http\Controllers;

use Nevadskiy\Nova\Icon\IconsetRegistry;

// @todo add permissions.
// @todo caching.
class IconController
{
    public function index(string $iconset, IconsetRegistry $registry): array
    {
        return $registry->get($iconset)->icons();
    }
}
