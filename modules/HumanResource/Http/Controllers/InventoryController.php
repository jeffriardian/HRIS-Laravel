<?php

namespace Modules\HumanResource\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

use App\Http\Resources\DataCollection;
use Modules\HumanResource\Models\Inventory as Model;
use Modules\HumanResource\Models\InventoryReceipt as Receipt;

use DB;

class InventoryController extends Controller
{
    public function index()
    {
        $data = Model::select(['inventories.*', 'inventory_categories.name as inventory'])
            ->join('inventory_categories', 'inventory_categories.id', '=', 'inventories.inventory_category_id');

        $data->orderBy(request()->order_column, (request()->order_direction == 'true' ? 'DESC' : 'ASC'));

        if (request()->keyword != '') {
            $data = $data->where('employees.name', 'LIKE', '%' . request()->keyword . '%')
                        ->orWhere('inventory_categories.name', 'LIKE', '%' . request()->keyword . '%');
        }
        return new DataCollection($data->paginate(request()->per_page));
    }

    public function loadAll()
    {
        // $data = Model::orderBy('id', 'ASC');

        $data = Model::select(DB::raw('CONCAT(name, "-", type) as name'), 'id');
        return new DataCollection($data->get());
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'serial_number' => 'required|string|max:255',
            'inventory_category_id' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $data = [
                'inventory_category_id' => $request->input('inventory_category_id'),
                'name' => $request->input('name'),
                'type' => $request->input('type'),
                'serial_number' => $request->input('serial_number'),
                'description' => $request->input('description'),
                'total_stock' => $request->input('total_stock'),
                'available_stock' => $request->input('total_stock'),
                'use_stock' => '0',
                'damage_stock' => '0',
            ];

            $inventoryid = Model::create($data);

            $inventory = [
                'inventory_id' => $inventoryid->id,
                'quantity' => $request->input('total_stock'),
            ];

            Receipt::create($inventory);

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
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'serial_number' => 'required|string|max:255',
            'inventory_category_id' => 'required',
        ]);

        $data = Model::findOrFail($id);
        $data->update($request->all());
        return response()->json(['status' => 'success'], Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $data = Model::findOrFail($id);
        $data->delete();
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
}
