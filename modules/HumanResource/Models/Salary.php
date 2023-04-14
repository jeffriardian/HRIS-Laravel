<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class Salary extends Model
{
    use SoftDeletes, Signature;

    protected $fillable = ['employee_id', 'basic_salary', 'wage', 'bonus','is_active'];
}
