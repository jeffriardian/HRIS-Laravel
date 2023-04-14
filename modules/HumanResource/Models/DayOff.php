<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;

class DayOff extends Model
{
    protected $fillable = ['name'];

    public function employee()
    {
        return $this->hasMany(Employee::class);
    }
}
