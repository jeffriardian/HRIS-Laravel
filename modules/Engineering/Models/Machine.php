<?php

namespace Modules\Engineering\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;
use App\Traits\UsesUuid;

class Machine extends Model
{
    use SoftDeletes, Signature, UsesUuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['code','serial_number','machine_area_code','machine_group_code', 'note', 'brand', 'type', 'year', 'description','is_active'];

    
}
