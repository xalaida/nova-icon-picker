<?php

namespace Nevadskiy\Nova\Icon;

use JsonSerializable;

class SvgIcon implements JsonSerializable
{
    public function __construct(
        public string $name,
        public string $path,
    ) {
    }

    public function contents(): string
    {
        return file_get_contents($this->path);
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'contents' => $this->contents(),
        ];
    }
}
