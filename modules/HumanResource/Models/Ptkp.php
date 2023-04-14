<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class Ptkp extends Model
{
    use SoftDeletes, Signature;

    protected $fillable = ['code', 'amount_ptkp', 'is_active'];
}
