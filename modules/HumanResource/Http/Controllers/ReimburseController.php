<?php

namespace Modules\HumanResource\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

use App\Http\Resources\DataCollection;
use Modules\HumanResource\Models\Reimburse as Model;

use DB;

class ReimburseController extends Controller
{
    public function index()
    {
        $data = Model::select(['reimburses.*', 'employees.name as name', 'reimburse_categories.name as reimburse'])
            ->join('employees', 'employees.id', '=', 'reimburses.employee_id')
            ->join('reimburse_categories', 'reimburse_categories.id', '=', 'reimburses.reimburse_category_id')
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

    public function store(Request $request)
    {
        $this->validate($request, [
            'employee_id' => 'required',
            'reimburse_category_id' => 'required',
            'description' => 'required',
            'total_cost' => 'required',
            'reimburse_type' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $noreimburse = $this->getReimburseNumber();

            if (!empty($noreimburse)) {
                foreach($noreimburse as $noreimburse){
                    $data = [
                        'reimburse_category_id' => $request->input('reimburse_category_id'),
                        'employee_id' => $request->input('employee_id'),
                        'reimburse_number' => $noreimburse->no,
                        'description' => $request->input('description'),
                        'total_cost' => $request->input('total_cost'),
                        'reimburse_type' => $request->input('reimburse_type'),
                        'is_active' => $request->input('is_active'),
                    ];

                    $model = Model::create($data);
                }
                if ($request->images) {
                    foreach ($request->images as $image) { 
                    // dd($request->candidate_id);
                    $image->storeAs('/public/human-resources/reimburse',time() . '.' . $image->getClientOriginalExtension());
                        $model->fileReimburses()->create([
                            'reimburse_id' => $model->id,
                            'file' => time() . '.' . $image->getClientOriginalExtension()
                        ]);
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

    public function show($id)
    {
        $data = Model::with(['fileReimburses'])->findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $data], Response::HTTP_OK);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'employee_id' => 'required',
            'reimburse_category_id' => 'required',
            'description' => 'required',
            'total_cost' => 'required',
            'reimburse_type' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $data = [
                'reimburse_category_id' => $request->input('reimburse_category_id'),
                'employee_id' => $request->input('employee_id'),
                'description' => $request->input('description'),
                'total_cost' => $request->input('total_cost'),
                'reimburse_type' => $request->input('reimburse_type'),
                'is_active' => $request->input('is_active'),
            ];

            Model::whereId($id)->update($data);

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

    public function GetReimburseNumber()
    {
        $noreimburse = DB::select('select (MAX(id)+1) as id, concat("RMB", "/", "SMM", "/",YEAR(NOW()),"/",LPAD(((select coalesce(MAX(id),0)
        from reimburses where YEAR(created_at) = YEAR(NOW()))+1),"5","0")) AS no from reimburses');

        return $noreimburse;
    }
}
