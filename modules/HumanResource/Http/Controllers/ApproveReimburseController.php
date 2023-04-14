<?php

namespace Modules\HumanResource\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

use App\Http\Resources\DataCollection;
use Modules\HumanResource\Models\Reimburse as Model;
use Modules\HumanResource\Models\BusinessTrip as BusinessTrip;

use DB;

class ApproveReimburseController extends Controller
{
    public function index()
    {
        $data = Model::select(['reimburses.*', 'employees.name as name', 'reimburse_categories.name as reimburse'])
            ->join('employees', 'employees.id', '=', 'reimburses.employee_id')
            ->join('reimburse_categories', 'reimburse_categories.id', '=', 'reimburses.reimburse_category_id')
            ->where('reimburses.approval_status_id', '=', '1');

        $data->orderBy(request()->order_column, (request()->order_direction == 'true' ? 'DESC' : 'ASC'));

        if (request()->keyword != '') {
            $data = $data->where('employees.name', 'LIKE', '%' . request()->keyword . '%')
                        ->orWhere('reimburse_categories.name', 'LIKE', '%' . request()->keyword . '%');
        }
        return new DataCollection($data->paginate(request()->per_page));
    }

    public function loadAll()
    {
        $data = Model::orderBy('id', 'ASC');
        return new DataCollection($data->get());
    }

    public function show($id)
    {
        $data = Model::with('employee')->findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $data], Response::HTTP_OK);
    }

    public function approve($id)
    {
        DB::beginTransaction();
        try {
            if (!empty($id)) {

                $approve_reimburse = [
                    'approval_status_id' => '3',
                    'updated_at' => now()
                ];

                Model::whereId($id)->update($approve_reimburse);

                $business = $this->GetBusinessTripId($id);

                if (!empty($business)) {
                    foreach($business as $business){
                        $approve_businesstrip = [
                            'approval_status_id' => '3',
                            'updated_at' => now()
                        ];

                        BusinessTrip::whereId($business->id)->update($approve_businesstrip);
                    }
                }
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
        // $data = OvertimeEmployee::findOrFail($id);
        // $data->delete();

        DB::beginTransaction();
        try {
            if (!empty($id)) {

                $approve_reimburse = [
                    'approval_status_id' => '5',
                    'updated_at' => now()
                ];

                Model::whereId($id)->update($approve_reimburse);

                $business = $this->GetBusinessTripId($id);

                if (!empty($business)) {
                    foreach($business as $business){
                        $approve_businesstrip = [
                            'approval_status_id' => '5',
                            'updated_at' => now()
                        ];

                        BusinessTrip::whereId($business->id)->update($approve_businesstrip);
                    }
                }
            }

            DB::commit();
            return response()->json(['status' => 'success'], Response::HTTP_CREATED);
        }catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'data' => $e->getMessage()]);
        }
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

    public function GetBusinessTripId($id)
    {
        $business = DB::select('SELECT * FROM business_trips WHERE business_trip_number = (SELECT reimburse_number FROM reimburses WHERE id = :id)',
        array('id' => $id));

        return $business;
    }
}
