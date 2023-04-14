<?php

namespace Modules\HumanResource\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

use App\Http\Resources\DataCollection;

use App\Models\User;
use App\Notifications\Notify as ModelNotification;
use Modules\HumanResource\Models\RecruitmentStep as Model;
use Modules\HumanResource\Models\RecruitmentStepParameter as Parameter;
use Modules\HumanResource\Models\RecruitmentStepParameterScore as ParameterScore;

use DB;

class RecruitmentStepController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * 
     */
    public function index()
    {
        $data = Model::orderBy(request()->order_column, (request()->order_direction == 'true' ? 'DESC' : 'ASC'));
        if (request()->keyword != '') {
            $data = $data->where('id', 'LIKE', '%' . request()->keyword . '%')
           ->orWhere('name', 'LIKE', '%' . request()->keyword . '%');
        }
        return new DataCollection($data->paginate(request()->per_page));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function loadAll()
    {
        $data = Model::orderBy('id', 'ASC');
        return new DataCollection($data->get());
    }

    public function loadNextStep($id)
    {
        $data = DB::select('select id, name from recruitment_steps a where a.id not in (select b.recruitment_step_id from recruitments b where b.candidate_id ="'.$id.'")');
        return new DataCollection($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
     
        $total = 5 * count($request->parameters);
        $this->validate($request, [
            'name' => 'required',
            'min_score' => 'required|numeric|max:'.$total,
            'description' => 'required',
            'parameters.*.description'=> 'required',
            'parameters.*.recruitment_step_parameter_scores.*.description' => 'required'
        ],[
            'parameters.*.description.required' => 'This Description field is required.',
            'parameters.*.recruitment_step_parameter_scores.*.description.required' => 'This Description field is required.',
        ]);

        DB::beginTransaction();
        try {
            $data = [
                'name' => $request->input('name'),
                'min_score' => $request->input('min_score'),
                'description' => $request->input('description'),
                'is_active' => 1
            ];

            $step = Model::create($data);
            // dd($step->recruitmentStepParameter());
            // proccesRelationWithRequest($step->recruitmentStepParameter(), $request->parameters);
            foreach($request->parameters as $parameter){
                // dd($parameter);
                $model = Parameter::create(array_merge($parameter,['recruitment_step_id' => $step->id]));
                foreach($parameter['recruitment_step_parameter_scores'] as $detail){
                    // dd($detail);
                    // dd($model->id);
                    $parameter_data = [
                        'recruitment_step_parameter_id' => $model->id,
                        'score_id' => $detail['score_id'],
                        'description' => $detail['description'],
                        'is_active' => 1
                    ];
                    // // dd(isset($detail['id']));
                    
                        ParameterScore::create($parameter_data);
                }
                // $data_parameter = Parameter::createOrUpdate($parameter_data);
                //     foreach($request->scores as $score){
                //         $data_score = [
                //             'recruitment_step_parameter_id' => $data_parameter->id,
                //             'score_id' => $score->id,
                //             'description' => $parameter->scoredescripion.$i
                //         ];
                        // $i++;
                //         ParameterScore::createOrUpdate($data_score);
                //     }
            }
            DB::commit();
            return response()->json(['status' => 'success'], Response::HTTP_CREATED);
        }catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'data' => $e->getMessage()]);
        }
    }

    public function storeScore(Request $request)
    {
        dd($request->all());
        DB::beginTransaction();
        try {
           ParameterScore::createOrUpdate($request); 
            DB::commit();
            return response()->json(['status' => 'success'], Response::HTTP_CREATED);
        }catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'data' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Model::with('recruitmentStepParameter','recruitmentStepParameter.recruitmentStepParameterScores')->findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $data], Response::HTTP_OK);
    }

    public function showDetail($id)
    {
        $data = ParameterScore::where('recruitment_step_parameter_id', $id)->get();
        return response()->json(['status' => 'success', 'data' => $data], Response::HTTP_OK);
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        $total = 5 * count($request->parameters);
        $this->validate($request, [
            'name' => 'required',
            'min_score' => 'required|numeric|max:'.$total,
            'description' => 'required',
            'parameters.*.description'=> 'required',
            'parameters.*.recruitment_step_parameter_scores.*.description' => 'required'
        ],[
            'parameters.*.description.required' => 'This Description field is required.',
            'parameters.*.recruitment_step_parameter_scores.*.description.required' => 'This Description field is required.',
        ]);
        
        DB::beginTransaction();
        try {
        $data = [
            'name' => $request->input('name'),
            'min_score' => $request->input('min_score'),
            'description' => $request->input('description'),
            'is_active' => $request->input('is_active'),
        ];
        
        $step = Model::findOrFail($id);
        $step->update($data);
        proccesRelationWithRequest($step->recruitmentStepParameter(), $request->parameters);
        // $i = 1;
        foreach($request->parameters as $parameter){
            foreach($parameter['recruitment_step_parameter_scores'] as $detail){
                // dd($detail);
                
                $parameter_data = [
                    'recruitment_step_parameter_id' => $parameter['id'],
                    'score_id' => $detail['score_id'],
                    'description' => $detail['description'],
                    'is_active' => 1
                ];
                // dd(isset($detail['id']));
                if(isset($detail['id']) != false){
                    $parameterScore = ParameterScore::find($detail['id']);
                    $parameterScore->update($parameter_data);
                }else{
                    ParameterScore::create($parameter_data);
                }
            }
            // $data_parameter = Parameter::createOrUpdate($parameter_data);
            //     foreach($request->scores as $score){
            //         $data_score = [
            //             'recruitment_step_parameter_id' => $data_parameter->id,
            //             'score_id' => $score->id,
            //             'description' => $parameter->scoredescripion.$i
            //         ];
            //         $i++;
            //         ParameterScore::createOrUpdate($data_score);
            //     }
        }

        // dd($request->parameters, $request->scores);
        DB::commit();
        return response()->json(['status' => 'success'], Response::HTTP_OK);
    }catch (\Exception $e) {
        DB::rollback();
        return response()->json(['status' => 'error', 'data' => $e->getMessage()]);
    }
        // Model::create($data);

        // $data = Model::findOrFail($id);
        // $data->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function trash()
    {
        $data = Model::onlyTrashed()->orderBy('created_at', 'DESC');
        if (request()->keyword != '') {
            $data = $data->where('module', 'LIKE', '%' . request()->keyword . '%');
        }
        return new DataCollection($data->paginate(request()->per_page));
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $data = Model::findOrFail($id);
        $data->restore();
        return response()->json(['status' => 'success'], Response::HTTP_OK);
    }
}
