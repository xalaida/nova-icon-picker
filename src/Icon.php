<?php

namespace Nevadskiy\Nova\Icon;

use Illuminate\Support\Str;
use Laravel\Nova\Fields\Field;

// @todo asRawSvg() method that allows to pass custom svg icon.
class Icon extends Field
{
    public $component = 'icon-field';

    public array $iconsets = [];

    public function iconsets(array $iconsets): static
    {
        $this->iconsets = $iconsets;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return array_merge(parent::jsonSerialize(), [
            'iconsets' => $this->resolveIconsets(),
            'contents' => $this->resolveContents()
        ]);
    }

    protected function resolveIconsets(): array
    {
        return collect(resolve(IconRegistry::class)->iconsets())
            ->map(fn (SvgIconset $iconset, string $name) => [
                'name' => $name,
                'display' => Str::title($name), // @todo localize
            ])
            ->values()
            ->all();
    }

    protected function resolveContents(): ?string
    {
        if (is_null($this->value)) {
            return null;
        }

        return resolve(IconRegistry::class)
            ->icon($this->value)
            ->contents();
    }
}
