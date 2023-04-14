<?php

namespace Modules\Engineering\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Signature;

class EngineeringAnnualPlanning extends Model
{
    use Signature;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['year','revision','is_active'];

    public function details(){
        return $this->hasMany(EngineeringAnnualPlanningDetail::class);
    }

    public static function boot() {
        parent::boot();

        static::deleting(function($model) { // before delete() method call this
             $model->details()->delete();
        });
    }
}
