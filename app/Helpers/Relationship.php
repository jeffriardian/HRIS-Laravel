<?php

use Illuminate\Database\Eloquent\Relations\Relation;

if (!function_exists('proccesRelationWithRequest')) {
    function proccesRelationWithRequest(Relation $relation, array $items, $key = 'id')
    {
        $relation->getResults()->each(function($model) use ($items, $key) {
            foreach ($items as $item) {
                if ($model->{$key} === ($item[$key] ?? null)) {
                    $model->fill($item)->save();
                    return;
                }
            }

            return $model->delete();
        });

        foreach ($items as $item) {
            if (!isset($item[$key]))
                $relation->create($item);
        };
    }
}