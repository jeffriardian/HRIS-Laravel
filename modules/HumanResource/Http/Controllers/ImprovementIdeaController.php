<?php

namespace Modules\HumanResource\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use App\Http\Resources\DataCollection;
use Illuminate\Support\Facades\DB;
use Modules\HumanResource\Models\ImprovementIdea as Model;

class ImprovementIdeaController extends Controller
{
    public function index()
    {
        $keyword = request()->keyword;
        $data = Model::with('employee');

        $data->orderBy(request()->order_column, (request()->order_direction == 'true' ? 'DESC' : 'ASC'));

        if (request()->keyword != '') {
            $data = Model::with('employee')->where(function ($query) use ($keyword) {
                $query->whereHas('employee', function ($q) use ($keyword) {
                    $q->where('name', 'LIKE', '%' . $keyword . '%');
                });
            });
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
        $request->validate([
            'employee_id' => 'required',
            'title' => 'required',
            'description' => 'required'
        ]);

        DB::beginTransaction();
        try {
            $data = [
                'employee_id' => $request->input('employee_id'),
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'is_active' => 1
            ];

            $model = Model::create($data);
            if ($request->images) {
                foreach ($request->images as $image) {
                    // dd($request->candidate_id);
                    $image->storeAs('/public/human-resources/improvement', time() . '.' . $image->getClientOriginalExtension());
                    $model->improvementIdeaFiles()->create([
                        'improvement_idea_id' => $model->id,
                        'file' => time() . '.' . $image->getClientOriginalExtension()
                    ]);
                }
            }



            DB::commit();
            return response()->json(['status' => 'success'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'data' => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $data = Model::with(['improvementFileIdeas'])->findOrFail($id);
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
        } catch (\Exception $e) {
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
}
