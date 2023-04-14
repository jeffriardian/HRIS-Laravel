<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class MenuItem extends Model
{
    use SoftDeletes, Signature;
}
