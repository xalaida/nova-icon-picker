<?php

namespace Nevadskiy\Nova\Icon;

use Illuminate\Support\Str;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\PresentsImages;
use RuntimeException;

class Icon extends Field
{
    use PresentsImages;

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
     * @inheritdoc
     */
    public function __construct($name, $attribute = null, callable $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this->indexWidth = 32;
        $this->detailWidth = 64;
    }

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
            prefix: $this->getPrefix($name)
        );

        return $this;
    }

    /**
     * Get the iconset prefix for an icon name.
     */
    protected function getPrefix(string $iconset): string
    {
        if ($this->defaultIconset === $iconset) {
            return '';
        }

        return $iconset . $this->separator;
    }

    /**
     * Get the iconset by the name.
     */
    public function getIconset(string $iconset): SvgIconset
    {
        if (! isset($this->iconsets[$iconset])) {
            throw new RuntimeException(__('Iconset [:iconset] is missing.', ['iconset' => $iconset]));
        }

        return $this->iconsets[$iconset];
    }

    /**
     * Resolve the icon by the name.
     */
    public function resolveIcon(string $name): SvgIcon
    {
        [$iconset, $icon] = $this->parse($name);

        return $this->getIconset($iconset)->icon($icon);
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
        return array_merge(parent::jsonSerialize(), $this->imageAttributes(), [
            'iconsets' => $this->iconsets,
            'preview' => $this->resolvePreview(),
            'iconset' => $this->resolveIconset(),
        ]);
    }

    /**
     * Resolve the icon preview.
     */
    protected function resolvePreview(): ?string
    {
        if ($this->value === null) {
            return null;
        }

        return $this->resolveIcon($this->value)->contents();
    }

    /**
     * Resolve the current iconset.
     */
    protected function resolveIconset(): ?string
    {
        if ($this->value === null) {
            return null;
        }

        return $this->parse($this->value)[0];
    }
}
