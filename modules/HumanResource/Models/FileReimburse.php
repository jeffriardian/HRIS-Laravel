<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class FileReimburse extends Model
{
    use SoftDeletes, Signature;
    protected $fillable = ['reimburse_id','file','is_active'];

    public function reimburse()
    {
        return $this->belongsTo(Reimburse::class);
    }

}
