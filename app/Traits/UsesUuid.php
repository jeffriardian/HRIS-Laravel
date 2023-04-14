<?php

namespace App\Traits;

use Illuminate\Support\Str;

//https://dev.to/wilburpowery/easily-use-uuids-in-laravel-45be
trait UsesUuid
{
    protected static function bootUsesUuid()
    {
        static::creating(function ($model) {
            if (! $model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public function getIncrementing()
    {
        return false;
    }

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    public function getKeyType()
    {
        return 'string';
    }
}