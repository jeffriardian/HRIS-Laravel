<?php

namespace Modules\HumanResource\Models;

use App\Traits\Signature;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ImprovementIdeaFile extends Model
{
    use SoftDeletes, Signature;
    protected $fillable = ['improvement_idea_id', 'file', 'is_active'];

    public function improvementIdea()
    {
        return $this->belongsTo(ImprovementIdea::class);
    }
}
