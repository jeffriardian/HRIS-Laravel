<?php

namespace App\Traits;

use App\Observers\Signature as SignatureObserver;

trait Signature
{
    /**
     * The "booting" method of the Signature.
     *
     * @return void
     */

    public static function bootSignature()
    {
        static::observe(new SignatureObserver);
    }
}