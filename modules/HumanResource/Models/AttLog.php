<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class AttLog extends Model
{
    use SoftDeletes, Signature;

    protected $fillable = ['sn', 'scan_date', 'pin', 'verifymode', 'inoutmode', 'reserved', 'work_code', 'att_id' ,'is_active'];
}
