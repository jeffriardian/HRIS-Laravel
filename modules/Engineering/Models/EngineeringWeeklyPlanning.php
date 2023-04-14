<?php

namespace Modules\Engineering\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Signature;

class EngineeringWeeklyPlanning extends Model
{
    use Signature;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['engineering_monthly_planning_id','week','revision','is_active'];

    public function monthlyPlanning(){
        return $this->belongsTo(EngineeringMonthlyPlanning::class);
    }

    public function details(){
        return $this->hasMany(EngineeringWeeklyPlanningDetail::class);
    }

    public static function boot() {
        parent::boot();

        static::deleting(function($model) { // before delete() method call this
             $model->details()->delete();
        });
    }
}
