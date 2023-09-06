<?php

namespace Nevadskiy\Nova\IconPicker;

use Illuminate\Support\Str;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\PresentsImages;
use RuntimeException;

class Icon extends Field
{
    use PresentsImages;

    /**
     * The configuration callbacks.
     *
     * @var array
     */
    protected static $configCallbacks = [];

    /**
     * @inheritdoc
     */
    public $component = 'icon-field';

    /**
     * The list of iconsets.
     *
     * @var array
     */
    public $iconsets = [];

    /**
     * The name of the fallback iconset.
     *
     * @var string
     */
    public $fallbackIconset;

    /**
     * @inheritdoc
     */
    public function __construct($name, $attribute = null, callable $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this->indexWidth = 32;
        $this->detailWidth = 64;

        $this->applyConfigCallbacks();
    }

    /**
     * Configure the field using the given callback.
     */
    public static function configure(callable $callback): void
    {
        static::$configCallbacks[] = $callback;
    }

    /**
     * Apply the configuration callbacks to the field.
     */
    protected function applyConfigCallbacks(): void
    {
        foreach (static::$configCallbacks as $callback) {
            $callback($this);
        }
    }

    /**
     * Specify the name of the fallback iconset.
     */
    public function fallbackIconset(string $iconset): static
    {
        $this->fallbackIconset = $iconset;

        return $this;
    }

    /**
     * Register the SVG iconset using the given options.
     */
    public function iconset(string $path, string $display, string $name = null, string $prefix = ''): static
    {
        $name = $name ?? str_replace(' ', '_', Str::lower($name));

        $this->iconsets[$name] = new SvgIconset(
            path: $path,
            name: $name,
            display: $display,
            prefix: $prefix
        );

        if (empty($prefix)) {
            $this->fallbackIconset($name);
        }

        return $this;
    }

    /**
     * Get the iconset by the given name.
     */
    public function getIconset(string $name): SvgIconset
    {
        if (! isset($this->iconsets[$name])) {
            throw new RuntimeException(__('Iconset [:iconset] is missing.', ['iconset' => $name]));
        }

        return $this->iconsets[$name];
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize(): array
    {
        return array_merge(parent::jsonSerialize(), $this->imageAttributes(), [
            'iconsets' => array_values($this->iconsets),
            'preview' => $this->resolveIcon()?->contents(),
            'iconset' => $this->resolveIconset()?->name,
        ]);
    }

    /**
     * Resolve the current icon.
     */
    protected function resolveIcon(): ?SvgIcon
    {
        if ($this->value === null) {
            return null;
        }

        foreach ($this->iconsets as $iconset) {
            $icon = $iconset->match($this->value);

            if (! is_null($icon)) {
                return $icon;
            }
        }

        if (! is_null($this->fallbackIconset)) {
            return $this->getIconset($this->fallbackIconset)->icon($this->value);
        }

        return null;
    }

    /**
     * Resolve the current iconset.
     */
    protected function resolveIconset(): ?SvgIconset
    {
        if ($this->value === null) {
            return null;
        }

        foreach ($this->iconsets as $iconset) {
            $icon = $iconset->match($this->value);

            if (! is_null($icon)) {
                return $iconset;
            }
        }

        if (! is_null($this->fallbackIconset)) {
            return $this->getIconset($this->fallbackIconset);
        }

        return null;
    }
}
