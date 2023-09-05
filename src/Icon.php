<?php

namespace Nevadskiy\Nova\Icon;

use Illuminate\Support\Str;
use Laravel\Nova\Fields\Field;

/**
 * @todo asRawSvg() method that allows to pass custom svg icon.
 * @todo add ability to configure iconsets from service provider.
 */
class Icon extends Field
{
    /**
     * @inheritdoc
     */
    public $component = 'icon-field';

    /**
     * The iconset separator for an icon name.
     *
     * @var string
     */
    public $separator = '/';

    /**
     * The name of the default iconset.
     *
     * @var string
     */
    public $defaultIconset = 'default';

    /**
     * The list of iconsets.
     *
     * @var array
     */
    public $iconsets = [];

    /**
     * Specify the iconset separator for an icon name.
     */
    public function separator(string $separator): static
    {
        $this->separator = $separator;

        return $this;
    }

    /**
     * Specify the name of the default iconset.
     */
    public function defaultIconset(string $iconset): static
    {
        $this->defaultIconset = $iconset;

        return $this;
    }

    /**
     * Register the SVG iconset by the given path.
     */
    public function iconset(string $path, string $display, string $name = null): static
    {
        $name = $name ?? str_replace(' ', '_', Str::lower($name));

        $this->iconsets[$name] = new SvgIconset(
            path: $path,
            display: $display,
            prefix: $this->prefix($name)
        );

        return $this;
    }

    /**
     * Get the iconset prefix for an icon name.
     */
    protected function prefix(string $iconset): string
    {
        if ($this->defaultIconset === $iconset) {
            return $iconset;
        }

        return $iconset . $this->separator;
    }

    /**
     * Resolve the icon by the name.
     */
    public function resolveIcon(string $name): SvgIcon
    {
        [$iconset, $icon] = $this->parse($name);

        return $this->iconsets[$iconset]->icon($icon);
    }

    /**
     * Parse the icon value to get the iconset and icon names.
     */
    protected function parse(string $value): array
    {
        $segments = explode($this->separator, $value, 2);

        if (count($segments) === 1) {
            return [$this->defaultIconset, $value];
        }

        $iconset = $segments[0];

        if (isset($this->iconsets[$iconset])) {
            return [$iconset, $segments[1]];
        }

        return [$this->defaultIconset, $value];
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize(): array
    {
        return array_merge(parent::jsonSerialize(), [
            'iconsets' => $this->iconsets,
            'preview' => $this->resolvePreview()
        ]);
    }

    /**
     * Resolve the icon preview.
     */
    protected function resolvePreview(): ?string
    {
        // @todo resolve only if field is visible.

        if ($this->value === null) {
            return null;
        }

        return $this->resolveIcon($this->value)->contents();
    }
}
