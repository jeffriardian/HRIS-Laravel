<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class InventoryReceipt extends Model
{
    use SoftDeletes, Signature;

    protected $fillable = ['inventory_id', 'quantity', 'is_active'];
}
