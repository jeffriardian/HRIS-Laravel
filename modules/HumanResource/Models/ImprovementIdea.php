<?php

namespace Modules\HumanResource\Models;

use App\Traits\Signature;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ImprovementIdea extends Model
{
    use SoftDeletes, Signature;
    protected $fillable = ['employee_id', 'title', 'description', 'file', 'is_active'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function improvementIdeaFiles()
    {
        return $this->hasMany(ImprovementIdeaFile::class);
    }
}
