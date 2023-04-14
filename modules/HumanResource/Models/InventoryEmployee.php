<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class InventoryEmployee extends Model
{
    use SoftDeletes, Signature;

    protected $fillable = ['employee_id', 'inventory_id', 'date_borrow', 'date_return', 'status', 'compensation', 'is_active'];

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }
}
