<?php

namespace Modules\HumanResource\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

use App\Http\Resources\DataCollection;
use Modules\HumanResource\Models\InventoryReceipt as Model;
use Modules\HumanResource\Models\Inventory as Inventory;
use DB;

class InventoryReceiptController extends Controller
{
    public function index()
    {
        $data = Model::select(['inventory_receipts.*', 'inventories.name as inventory', 'inventories.type as type',
        'inventories.serial_number as serial', 'inventories.name as inventory'])
            ->join('inventories', 'inventories.id', '=', 'inventory_receipts.inventory_id');

        $data->orderBy(request()->order_column, (request()->order_direction == 'true' ? 'DESC' : 'ASC'));

        if (request()->keyword != '') {
            $data = $data->where('inventories.name', 'LIKE', '%' . request()->keyword . '%');
        }
        return new DataCollection($data->paginate(request()->per_page));
    }

    public function loadAll()
    {
        $data = Model::orderBy('id', 'ASC');
        return new DataCollection($data->get());
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'inventory_id' => 'required',
            'quantity' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $data = [
                'inventory_id' => $request->input('inventory_id'),
                'quantity' => $request->input('quantity'),
                'is_active' => '1',
            ];

            Model::create($data);

            $id = $request->input('inventory_id');
            $stock = $this->getStockInventory($id);

            foreach($stock as $stock){

                $update_qty = [
                    'total_stock'        => $stock->total_stock,
                    'available_stock'    => $stock->available,
                    'use_stock'          => $stock->used,
                    'damage_stock'       => $stock->damaged,
                ];

                Inventory::whereId($id)->update($update_qty);
            }

            DB::commit();
            return response()->json(['status' => 'success'], Response::HTTP_CREATED);
        }catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'data' => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $data = Model::findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $data], Response::HTTP_OK);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'inventory_id' => 'required',
            'quantity' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $data = [
                'inventory_id' => $request->input('inventory_id'),
                'quantity' => $request->input('quantity'),
                'is_active' => '1',
            ];

            Model::whereId($id)->update($data);

            $id = $request->input('inventory_id');
            $stock = $this->getStockInventory($id);

            foreach($stock as $stock){

                $update_qty = [
                    'total_stock'        => $stock->total_stock,
                    'available_stock'    => $stock->available,
                    'use_stock'          => $stock->used,
                    'damage_stock'       => $stock->damaged,
                ];

                Inventory::whereId($id)->update($update_qty);
            }

            DB::commit();
            return response()->json(['status' => 'success'], Response::HTTP_CREATED);
        }catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'data' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $data = Model::findOrFail($id);
        $data->delete();

        $inventory = $this->getIdInventory($id);

        if (!empty($inventory)) {
            foreach($inventory as $inventory){
                $id = $inventory->inventory_id;
                $stock = $this->getStockInventory($id);

                foreach($stock as $stock){

                    $update_qty = [
                        'total_stock'        => $stock->total_stock,
                        'available_stock'    => $stock->available,
                        'use_stock'          => $stock->used,
                        'damage_stock'       => $stock->damaged,
                    ];

                    Inventory::whereId($id)->update($update_qty);
                }
            }
        }

        return response()->json(['status' => 'success'], Response::HTTP_OK);
    }

    public function forceDelete($id)
    {
        $data = Model::findOrFail($id);
        $data->forceDelete();
        return response()->json(['status' => 'success'], Response::HTTP_OK);
    }

    public function trash()
    {
        $data = Model::onlyTrashed()->orderBy('created_at', 'DESC');
        if (request()->keyword != '') {
            $data = $data->where('module', 'LIKE', '%' . request()->keyword . '%');
        }
        return new DataCollection($data->paginate(request()->per_page));
    }

    public function restore($id)
    {
        $data = Model::findOrFail($id);
        $data->restore();
        return response()->json(['status' => 'success'], Response::HTTP_OK);
    }

    public function getStockInventory($id)
    {
        $stock = DB::select('SELECT a.id,
        (SELECT SUM(b.quantity) FROM inventory_receipts b WHERE a.id=b.inventory_id AND ISNULL(deleted_at)) AS total_stock,
        (SELECT COUNT(id) FROM inventory_employees b WHERE a.id=b.inventory_id AND b.status = :use1 AND ISNULL(deleted_at)) AS used,
        (SELECT COUNT(id) FROM inventory_employees b WHERE a.id=b.inventory_id AND b.status = :damage1 AND ISNULL(deleted_at)) AS damaged,
        (((SELECT SUM(b.quantity) FROM inventory_receipts b WHERE a.id=b.inventory_id AND ISNULL(deleted_at)))-
        ((SELECT COUNT(id) FROM inventory_employees b WHERE a.id=b.inventory_id AND b.status = :use2 AND ISNULL(deleted_at)))-
        ((SELECT COUNT(id) FROM inventory_employees b WHERE a.id=b.inventory_id AND b.status = :damage2 AND ISNULL(deleted_at)))) AS available
        from inventories a WHERE a.id = :id',
         array('id' => $id, 'use1'=>'Used', 'use2'=>'Used', 'damage1'=>'Return Damaged', 'damage2'=>'Return Damaged'));

        return $stock;
    }

    public function getIdInventory($id)
    {
        $stock = DB::select('select inventory_id from inventory_employees where id = :id',
         array('id' => $id));

        return $stock;
    }
}
