<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class Training extends Model
{
    use SoftDeletes, Signature;

    protected $fillable = ['training_type_id', 'name', 'training_date', 'is_active'];

    public function trainingType(){
        return $this->belongsTo(TrainingType::class);
    }

    public function trainingEmployees(){
        return $this->hasMany(TrainingEmployees::class);
    }
}
