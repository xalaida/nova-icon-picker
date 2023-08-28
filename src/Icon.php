<?php

namespace Nevadskiy\Nova\Icon;

use Laravel\Nova\Fields\Field;

class Icon extends Field
{
    public $component = 'icon-field';

    public function __construct($name, $attribute = null, callable $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this->fillUsing(function ($request, $model, $attribute, $requestAttribute) {
            if ($request->exists($requestAttribute)) {
                $icon = $this->parseIcon($request, $requestAttribute);

                $value = is_null($icon) ? $icon : "{$icon['type']}/{$icon['icon']}";

                $this->fillModelWithData($model, $value, $attribute);
            }
        });

        $this->resolveUsing(function ($value) {
            if (! $value) {
                return $value;
            }

            [$type, $icon] = explode('/', $value, 2);

            return json_encode([
                'type' => $type,
                'icon' => $icon,
            ]);
        });
    }

    public function parseIcon($request, $requestAttribute): ?array
    {
        $requestValue = $request->input($requestAttribute);

        if ($this->isValidNullValue($requestValue)) {
            return null;
        }

        return json_decode($requestValue, true);
    }
}
