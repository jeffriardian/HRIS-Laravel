<?php

namespace Modules\HumanResource\Http\Controllers;

use App\Http\Resources\DataCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\HumanResource\Models\KpiEmployeeDetail;
use Modules\HumanResource\Models\KpiFormula;

class KpiController extends Controller
{
    public function kpiReport()
    {
        // return response()->json()
    }

    public function kpiEmployee()
    {
        $data =  KpiEmployeeDetail::latest()->orderBy(request()->order_column, (request()->order_direction == 'true' ? 'DESC' : 'ASC'));
        return new DataCollection($data->paginate(request()->per_page));
    }

    public function kpiFormula()
    {
        $data = KpiFormula::with('kpiGrades')->where('assessment_point', 'LIKE', '%' . request()->keyword . '%')
            ->orWhere('unit_of_measurement', 'LIKE', '%' . request()->keyword . '%')
            ->orWhere('target', 'LIKE', '%' . request()->keyword . '%')
            ->orWhere('numerator', 'LIKE', '%' . request()->keyword . '%')
            ->orWhere('denominator', 'LIKE', '%' . request()->keyword . '%')
            ->orderBy(request()->order_column, (request()->order_direction == 'true' ? 'DESC' : 'ASC'));
        return new DataCollection($data->paginate(request()->per_page));
    }

    public function kpiFormulaOption()
    {
        return new DataCollection(KpiFormula::get());
    }

    public function updateKpiFormula(Request $request)
    {
        // dd($request->kpi_grades);
        $request->validate([
            'assessment_point' => 'required',
            'unit_of_measurement' => 'required',
            'target' => 'required',
            'numerator' => 'required',
            'denominator' => 'required',
            'target_point' => 'required',
            'kpi_grades.*.score_min' => 'required',
            'kpi_grades.*.score_max' => 'required',
            'kpi_grades.*.grade' => 'required',
        ]);

        DB::beginTransaction();
        try {
            KpiFormula::whereId($request->id)->update($request->only([
                'assessment_point', 'unit_of_measurement', 'target',  'numerator', 'denominator', 'target_point'
            ]));

            $kpiFormula = KpiFormula::findOrFail($request->id);

            proccesRelationWithRequest(
                $kpiFormula->kpiGrades(),
                $request->kpi_grades
            );

            DB::commit();
            return response()->json(['status' => 'success'], Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['status' => 'error', 'data' => $th->getMessage()]);
        }
    }
}
