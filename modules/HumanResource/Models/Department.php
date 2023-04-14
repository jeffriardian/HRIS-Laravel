<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class Department extends Model
{
    use SoftDeletes, Signature;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['department_level_id','code','name', 'description','is_active'];

    public function employees(){
        return $this->hasMany(Employee::class);
    }
	
	public function departmentLevel(){
        return $this->belongsTo(DepartmentLevel::class);
    }

    public function parent()
    {
        return $this->belongsTo(Department::class, 'parent_id')->with('parent');
    }

    public function children()
    {
        return $this->hasMany(Department::class, 'parent_id')->with('children');
    }

    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }
}
