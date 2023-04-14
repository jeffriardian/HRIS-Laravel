<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class PublicHoliday extends Model
{
    use SoftDeletes, Signature;

    protected $fillable = ['holiday_date', 'description', 'is_active'];
}
