<?php

namespace Modules\HumanResource\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

use App\Http\Resources\DataCollection;
use Modules\HumanResource\Models\Reimburse as Model;
use Modules\HumanResource\Models\Disbursement as Disbursement;

use DB;

class DisbursementController extends Controller
{
    public function index()
    {
        $data = Model::select(['reimburses.*', 'employees.name as name', 'reimburse_categories.name as reimburse'])
            ->join('employees', 'employees.id', '=', 'reimburses.employee_id')
            ->join('reimburse_categories', 'reimburse_categories.id', '=', 'reimburses.reimburse_category_id')
            ->where('reimburses.approval_status_id', '=', '2')
            ->orwhere('reimburses.approval_status_id', '=', '3')
            ->where('reimburses.reimburse_type', '=', 'cash')
            ->where('reimburses.is_active', '=', '1');

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

    public function destroy($id)
    {
        // $data = OvertimeEmployee::findOrFail($id);
        // $data->delete();

        DB::beginTransaction();
        try {
            if (!empty($id)) {

                //PROSES DATA PENCAIRAN REIMBURSE (DISBURSEMENT)
                $reimburse = $this->getReimburse($id);

                if (!empty($reimburse)){
                    foreach($reimburse as $reimburse){
                        $data_reimbruse = [
                            'reimburse_id' => $reimburse->id,
                            'amount' => $reimburse->total_cost,
                            'date_process' => $reimburse->dateprocess,
                            'is_active' => '1',
                        ];

                        Disbursement::create($data_reimbruse);

                        $data = [
                            'is_active' => '0',
                        ];

                        Model::whereId($id)->update($data);
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

    public function getReimburse($id)
    {
        $reimburse = DB::select('SELECT id, total_cost, cast(NOW() AS date) AS dateprocess FROM reimburses WHERE (id NOT IN (SELECT reimburse_id FROM disbursements))
        AND (reimburse_type = :cash) AND (approval_status_id = 3 OR approval_status_id = 2) AND (is_active = 1) AND ISNULL(deleted_at) AND id =:id',
        array('id'=>$id, 'cash'=>'cash'));

        return $reimburse;
    }
}
