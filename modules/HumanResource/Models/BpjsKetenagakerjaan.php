<?php

namespace Modules\HumanResource\Models;

use App\Traits\Signature;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class BpjsKetenagakerjaan extends Model
{
    use Signature, LogsActivity;

    protected $fillable = [
        'card_number',
        'jkk',
        'jkm',
        'pk_tk',
        'tk_tk',
        'pk_jp',
        'tk_jp',
        'tk_date',
        'jp_date',
        'month',
        'year',
        'office',
    ];

    protected static $logName = "Bpjs Ketenagakerjaan";
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;
}
