<?php

namespace Nevadskiy\Nova\Icon;

use Illuminate\Support\Str;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use JsonSerializable;

class SvgIconset implements JsonSerializable
{
    public const EXTENSION = '.svg';

    public function __construct(
        public string $path,
        public string $name,
        public string $display,
        public string $prefix = '',
    ) {
    }

    public function match(string $icon): ?SvgIcon
    {
        if (! $this->prefix) {
            return null;
        }

        if (! Str::startsWith($icon, $this->prefix)) {
            return null;
        }

        return $this->icon(Str::after($icon, $this->prefix));
    }

    public function icon(string $name): SvgIcon
    {
        return new SvgIcon(
            name: $this->prefix . $name,
            path: $this->path . DIRECTORY_SEPARATOR . $name . static::EXTENSION,
        );
    }

    public function icons(): array
    {
        return collect(Finder::create()->in($this->path)->files())
            ->map(fn (SplFileInfo $file) => new SvgIcon(
                name: $this->prefix . $file->getFilenameWithoutExtension(),
                path: $file->getPathname(),
            ))
            ->values()
            ->all();
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'display' => $this->display,
        ];
    }
}
