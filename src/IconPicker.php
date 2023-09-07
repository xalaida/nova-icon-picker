<?php

namespace Nevadskiy\Nova\IconPicker;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\SupportsDependentFields;
use RuntimeException;

class IconPicker extends Field
{
    use SupportsDependentFields;

    /**
     * The configuration callbacks.
     *
     * @var array
     */
    public static $configCallbacks = [];

    /**
     * @inheritdoc
     */
    public $component = 'icon-picker';

    /**
     * Indicates if the reset button should be visible.
     *
     * @var bool
     */
    public $resettable = true;

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
     * The size of the icon on the index view.
     *
     * @var int
     */
    public $indexSize = 32;

    /**
     * The size of the icon on the detail view.
     *
     * @var int
     */
    public $detailSize = 64;

    /**
     * @inheritdoc
     */
    public function __construct($name, $attribute = null, callable $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

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
     * Specify the size of the icon on the index view.
     */
    public function indexSize(int $size): static
    {
        $this->indexSize = $size;

        return $this;
    }

    /**
     * Specify the size of the icon on the detail view.
     */
    public function detailSize(int $size): static
    {
        $this->detailSize = $size;

        return $this;
    }

    /**
     * Register the SVG iconset using the given options.
     */
    public function iconset(string $name, string $path, string $prefix = ''): static
    {
        $this->iconsets[$name] = new SvgIconset($name, $path, $prefix);

        if (! $prefix && ! $this->fallbackIconset) {
            $this->fallbackIconset($name);
        }

        return $this;
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
     * Display the "reset" button.
     */
    public function resettable(bool $resettable = true): static
    {
        $this->resettable = $resettable;

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
        $serialization = parent::jsonSerialize();

        $value = $serialization['value'];

        [$iconset, $icon] = $this->findMatchingIcon($value);

        return array_merge($serialization, [
            'indexSize' => $this->indexSize,
            'detailSize' => $this->detailSize,
            'resettable' => $this->resettable,
            'iconsets' => array_values($this->iconsets),
            'iconset' => $iconset?->name,
            'preview' => $icon?->contents(),
        ]);
    }

    /**
     * Resolve the icon with iconset.
     */
    protected function findMatchingIcon(?string $value): array
    {
        if (is_null($value)) {
            return [null, null];
        }

        foreach ($this->iconsets as $iconset) {
            $icon = $iconset->match($value);

            if (! is_null($icon)) {
                return [$iconset, $icon];
            }
        }

        if (! is_null($this->fallbackIconset)) {
            $iconset = $this->getIconset($this->fallbackIconset);

            return [$iconset, $iconset->icon($value)];
        }

        return [null, null];
    }
}
