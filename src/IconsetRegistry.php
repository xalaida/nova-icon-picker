<?php

namespace Nevadskiy\Nova\Icon;

class IconsetRegistry
{
    protected array $sets = [];

    public function register(string $name, SvgIconset $set): void
    {
        $this->sets[$name] = $set;
    }

    public function get(string $name): SvgIconset
    {
        return $this->sets[$name];
    }

    public function all(): array
    {
        return $this->sets;
    }
}
