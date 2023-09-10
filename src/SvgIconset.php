<?php

namespace Nevadskiy\Nova\IconPicker;

use Illuminate\Support\Str;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use JsonSerializable;

class SvgIconset implements JsonSerializable
{
    public const EXTENSION = '.svg';

    public function __construct(
        public string $name,
        public string $uri,
        public string $path,
        public string $prefix = '',
    ) {
    }

    public function match(string $value): bool
    {
        if (! $this->prefix) {
            return false;
        }

        return Str::startsWith($value, $this->prefix);
    }

    public function icon(string $value): SvgIcon
    {
        return new SvgIcon(
            $value,
            $this->path . DIRECTORY_SEPARATOR . Str::after($value, $this->prefix) . static::EXTENSION
        );
    }

    public function icons(): array
    {
        return collect(Finder::create()->in($this->path)->files())
            ->map(fn (SplFileInfo $file) => new SvgIcon(
                $this->prefix . $file->getFilenameWithoutExtension(),
                $file->getPathname(),
            ))
            ->values()
            ->all();
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'uri' => $this->uri,
        ];
    }
}
