<?php

namespace Modules\Engineering\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Signature;

class EngineeringMonthlyPlanning extends Model
{
    use Signature;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['engineering_annual_planning_id','month','is_active'];

    public function engineeringAnnualPlanning(){
        return $this->belongsTo(EngineeringAnnualPlanning::class);
    }

    public function details(){
        return $this->hasMany(EngineeringMonthlyPlanningDetail::class);
    }

    public static function boot() {
        parent::boot();

        static::deleting(function($model) { // before delete() method call this
             $model->details()->delete();
        });
    }
}
