<?php

namespace Nevadskiy\Nova\Icon;

use Illuminate\Support\Str;
use Laravel\Nova\Fields\Field;

// @todo asRawSvg() method that allows to pass custom svg icon.
class Icon extends Field
{
    public $component = 'icon-field';

    public array $sets = [];

//    public function __construct($name, $attribute = null, callable $resolveCallback = null)
//    {
//        parent::__construct($name, $attribute, $resolveCallback);
//
//        $this->fillUsing(function ($request, $model, $attribute, $requestAttribute) {
//            if ($request->exists($requestAttribute)) {
//                $icon = $this->parseIcon($request, $requestAttribute);
//
//                $value = is_null($icon) ? $icon : "{$icon['type']}/{$icon['icon']}";
//
//                $this->fillModelWithData($model, $value, $attribute);
//            }
//        });
//
//        $this->resolveUsing(function ($value) {
//            if (! $value) {
//                return $value;
//            }
//
//            [$type, $icon] = explode('/', $value, 2);
//
//            return json_encode([
//                'type' => $type,
//                'icon' => $icon,
//            ]);
//        });
//    }
//
//    public function parseIcon($request, $requestAttribute): ?array
//    {
//        $requestValue = $request->input($requestAttribute);
//
//        if ($this->isValidNullValue($requestValue)) {
//            return null;
//        }
//
//        return json_decode($requestValue, true);
//    }

    public function sets(array $sets): static
    {
        $this->sets = $sets;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return array_merge(parent::jsonSerialize(), [
            'sets' => $this->resolveSets(),
        ]);
    }

    protected function resolveSets(): array
    {
        return collect(resolve(IconsetRegistry::class)->all())
            ->map(fn (SvgIconset $iconset, string $name) => [
                'name' => $name,
                'display' => Str::title($name),
            ])
            ->values()
            ->all();
    }
}
