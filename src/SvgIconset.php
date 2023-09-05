<?php

namespace Nevadskiy\Nova\Icon;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use JsonSerializable;

class SvgIconset implements JsonSerializable
{
    public const EXTENSION = '.svg';

    public function __construct(
        public string $path,
        public string $display,
        public string $prefix = '',
    ) {
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
            'display' => $this->display,
        ];
    }
}
