<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class Country extends Model
{
    use SoftDeletes, Signature;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['code','name', 'description','is_active'];
}
