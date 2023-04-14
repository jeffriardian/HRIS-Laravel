<?php

namespace Modules\Engineering\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Signature;

class EngineeringDailyPlanning extends Model
{
    use Signature;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['engineering_weekly_planning_id', 'day', 'is_active'];

    public function engineeringWeeklyPlanning(){
        return $this->belongsTo(EngineeringWeeklyPlanning::class);
    }

    public function details(){
        return $this->hasMany(EngineeringDailyPlanningDetail::class);
    }

    public static function boot() {
        parent::boot();

        static::deleting(function($model) {
             $model->details()->delete();
        });
    }
}
