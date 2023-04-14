<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

use Spatie\Permission\Traits\HasRoles;
use App\Traits\Signature;
use Modules\HumanResource\Models\Employee;

class User extends Authenticatable
{
    use Notifiable, HasRoles, SoftDeletes, Signature;

    const API_TOKEN_LENGTH = 50;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'employee_id', 'name', 'password', 'is_active', 'last_logged_at', 'last_logged_ip', 'api_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function chats()
    {
        return $this->hasMany(Chat::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
