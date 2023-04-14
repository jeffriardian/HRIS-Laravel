<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;

class ExchangeOfficeHour extends Model
{
    protected $fillable = ['employee_id', 'exchanged_employee', 'from_shift', 'to_shift', 'from_date', 'to_date'];
}
