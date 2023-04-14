<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;
use Spatie\Activitylog\Traits\LogsActivity;

class ExitClearance extends Model
{
    use SoftDeletes, Signature, LogsActivity;

    protected $fillable = ['employee_id', 'exit_clearance_number', 'is_active'];

    protected static $logName = "Exit Clearance";
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    public function parameters()
    {
        return $this->hasMany(ExitClearanceParameter::class);
    }
}
