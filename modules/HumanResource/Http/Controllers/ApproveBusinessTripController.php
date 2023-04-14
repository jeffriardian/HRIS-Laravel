<?php

namespace Modules\HumanResource\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

use App\Http\Resources\DataCollection;
use Modules\HumanResource\Models\BusinessTrip as Model;

use DB;
use Modules\HumanResource\Models\Reimburse;

class ApproveBusinessTripController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Model::select(['business_trips.id', 'business_trips.departure_date', 'business_trips.back_date', 'business_trips.destination', 'business_trips.created_at',
        'business_trips.requirement', 'employees.name as employee', 'business_trips.total_cost as receipt', 'approval_statuses.name as approval'])
                ->join('employees', 'employees.id', '=', 'business_trips.employee_id')
                ->join('approval_statuses', 'approval_statuses.id', '=', 'business_trips.approval_status_id')
                ->where('approval_statuses.name','=','Created User');

        $data->orderBy(request()->order_column, (request()->order_direction == 'true' ? 'DESC' : 'ASC'));

        if (request()->keyword != '') {
            $data = $data->where('employees.name', 'LIKE', '%' . request()->keyword . '%');
                        //  ->orWhere('training_types.name', 'LIKE', '%' . request()->keyword . '%');
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

    public function destroy($id)
    {
        // $data = OvertimeEmployee::findOrFail($id);
        // $data->delete();

        if (!empty($id)) {

            $approve_businesstrip = [
                'approval_status_id'        => '3',
                'updated_at' => now()
            ];

            Model::whereId($id)->update($approve_businesstrip);

            $reimburse = $this->GetReimburseCategory();

            if (!empty($reimburse)) {
                foreach($reimburse as $reimburse){
                    $business = $this->GetBusinessTrip($id);

                    if (!empty($business)) {
                        foreach($business as $business){
                            $data_reimburse = [
                                'reimburse_category_id' => $reimburse->id,
                                'employee_id' => $business->employee_id,
                                'reimburse_number' => $business->business_trip_number,
                                'description' => $business->requirement,
                                'total_cost' => $business->total_cost,
                            ];

                            Reimburse::create($data_reimburse);
                        }
                    }
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

    public function GetBusinessTrip($id)
    {
        $business = DB::select('select a.employee_id, a.business_trip_number, a.requirement, a.total_cost
        from business_trips a where a.id= :id',
        array('id' => $id));

        return $business;
    }

    public function GetReimburseCategory()
    {
        $reimburse = DB::select('select id from reimburse_categories where name= :name',
        array('name' => 'Business Trip'));

        return $reimburse;
    }
}
