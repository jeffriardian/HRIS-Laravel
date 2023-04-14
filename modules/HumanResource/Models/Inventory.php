<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class Inventory extends Model
{
    use SoftDeletes, Signature;

    protected $fillable = ['inventory_category_id', 'name', 'type', 'serial_number', 'description', 'total_stock', 'available_stock', 'is_active'];

    public function inventoryCategory()
    {
        return $this->belongsTo(InventoryCategory::class);
    }
}
