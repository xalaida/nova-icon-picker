<?php

namespace Nevadskiy\Nova\Icon;

class IconRegistry
{
    protected string $separator = '/';

    protected string $default = 'default';

    protected array $iconsets = [];

    public function setSeparator(string $separator): void
    {
        $this->separator = $separator;
    }

    public function setDefault(string $default): void
    {
        $this->default = $default;
    }

    public function addIconset(string $path, string $name = null): void
    {
        $name = $name ?? $this->default;

        $this->iconsets[$name] = new SvgIconset($path, $name . $this->separator);
    }

    public function iconset(string $name): SvgIconset
    {
        return $this->iconsets[$name];
    }

    public function iconsets(): array
    {
        return $this->iconsets;
    }

    public function icon(string $name): SvgIcon
    {
        [$iconset, $icon] = $this->parse($name);

        return $this->iconset($iconset)->icon($icon);
    }

    protected function parse(string $name): array
    {
        $parts = explode($this->separator, $name, 2);

        [$iconset, $icon] = count($parts) === 1
            ? [$this->default, $parts[0]]
            : $parts;

        return [$iconset, $icon];
    }
}
