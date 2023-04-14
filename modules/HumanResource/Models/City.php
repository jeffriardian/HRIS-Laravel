<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class City extends Model
{
    use SoftDeletes, Signature;
    protected $fillable = ['nama','id_propinsi','is_active'];
    // protected $fillable = [];
}
