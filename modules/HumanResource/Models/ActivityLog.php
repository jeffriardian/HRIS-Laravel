<?php

namespace Modules\HumanResource\Models;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [];

    protected $table = 'activity_log';

    public function user()
    {
        return $this->belongsTo(User::class, 'causer_id', 'id');
    }

    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('d/m/Y');
    }
}
