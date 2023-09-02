<?php

namespace Nevadskiy\Nova\Icon;

class IconsetRegistry
{
    protected array $sets = [];

    public function register(string $name, SvgIconset $set): void
    {
        $this->sets[$name] = $set;
    }
}
