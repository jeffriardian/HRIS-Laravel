<?php

namespace Modules\HumanResource\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

use App\Http\Resources\DataCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\HumanResource\Models\InterrogationReport as Model;
use Modules\HumanResource\Models\InterrogationReportActor;
use Modules\HumanResource\Models\InterrogationReportImage;
use Modules\Engineering\Models\Machine;
use Modules\HumanResource\Models\Inventory;


class InterrogationReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $data = Model::select(['interrogation_reports.*', 'employees.name as name'])
        //     ->join('employees', 'employees.id', '=', 'interrogation_reports.employee_id');

        // $data->orderBy(request()->order_column, (request()->order_direction == 'true' ? 'DESC' : 'ASC'));

        $data = Model::with('incidentCategory')->where('is_active', 1)->orderBy(request()->order_column, (request()->order_direction == 'true' ? 'DESC' : 'ASC'));

        if (request()->keyword != '') {
            $search = request()->keyword;
            $data->whereHas('incidentCategory', function ($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%');
            })->orWhere('date_of_incident', 'LIKE', '%' . $search . '%')->orWhere('lost_cost', 'LIKE', '%' . $search . '%');
        }
        return new DataCollection($data->paginate(request()->per_page));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function loadAll()
    {
        $data = Model::orderBy('id', 'ASC');
        return new DataCollection($data->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $this->validate($request, [
            'incident_category_id' => 'required',
            'date_of_incident' => 'required',
            'suspect_actor' => 'required',
            'suspect_actor.*.suspects' => 'required',
            'images' => 'required'
            // 'images.*' => 'required|array|min:1'
        ],[
            'images.required' => 'You have to upload an evidence',
            'suspect_actor.required' => 'This suspect field is required',
            'suspect_actor.*.suspects.required' => 'You have to choose an employee that suspected or responsible'
        ]);
                                            

        DB::beginTransaction();
        try {
            switch ($request->input('interrogation_report_type_id')) {
                case 6:
                        $typeable_type = 'Modules\Engineering\Models\Machine';
                        $typeable_id = $request->input('typeable_id');
                  break;
                case 5:
                        $typeable_type = 'Modules\HumanResource\Models\Inventory';
                        $typeable_id = $request->input('typeable_id');
                  break;
              }
            $data = [
                'incident_category_id' => $request->input('incident_category_id'),
                'date_of_incident' => $request->input('date_of_incident'),
                'incident_chronologic' => $request->input('incident_chronologic'),
                'lost_cost' => $request->input('lost_cost'),
                'typeable_type' => $typeable_type,
                'typeable_id' => $typeable_id,
                'interrogation_report_type_id' => $request->input('interrogation_report_type_id'),
            ];

            $interrogation = Model::create($data);
            
            if($request->witness_actor){
                foreach ($request->witness_actor as $witnesses => $value) {
                    if($value['employeeStatus'] == "true"){
                        InterrogationReportActor::create([
                            'interrogation_report_id' => $interrogation->id,
                            'interrogation_report_actor_type_id' => 1,
                            'external_employee_id' => $value['external'],
                            // 'employee_id' => 0,
                            'description' => $value['description']
                        ]);
                    }else{
                        InterrogationReportActor::create([
                            'interrogation_report_id' => $interrogation->id,
                            'interrogation_report_actor_type_id' => 1,
                            // 'external_employee_id' => 0,
                            'employee_id' => $value['witnesses'],
                            'description' => $value['description']
                        ]);
                    }
                }
            }
                foreach ($request->suspect_actor as $suspects => $value) {
                    // dd($request->all());
                    if($value['employeeStatus'] == "true"){
                        InterrogationReportActor::create([
                            'interrogation_report_id' => $interrogation->id,
                            'interrogation_report_actor_type_id' => 2,
                            'external_employee_id' => $value['external'],
                            // 'employee_id' => 0,
                            'description' => $value['description']
                        ]);
                    }else{
                        InterrogationReportActor::create([
                            'interrogation_report_id' => $interrogation->id,
                            'interrogation_report_actor_type_id' => 2,
                            // 'external_employee_id' => 0,
                            'employee_id' => $value['suspects'],
                            'description' => $value['description']
                        ]);
                    }
                }

                foreach($request->images as $listFile => $value){
                    // $FileData = (array) $value['caption'];
                    $fileName = time() . '.' . $value['image']->getClientOriginalExtension();
                    InterrogationReportImage::create([
                        'interrogation_report_id' => $interrogation->id,
                        'image' => $fileName,
                        'note' => $value['note']
                    ]);
                    $value['image']->storeAs('/public/images/interrogation-report', $fileName);
                }

            DB::commit();
            return response()->json(['status' => 'success'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
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
        $data = Model::with('typeable', 'interrogationReportType','incidentCategory', 'interrogationReportActors.employee', 'interrogationReportActors.externalEmployee','interrogationReportActors', 'interrogationReportImages')->findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $data], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $this->validate($request, [
            'incident_category_id' => 'required',
            'date_of_incident' => 'required',
            'suspect_actor' => 'required',
            'suspect_actor.*.suspects' => 'required',
            'images' => 'required'
        ],[
            'images.required' => 'You have to upload an evidence',
            'suspect_actor.required' => 'This suspect field is required',
            'suspect_actor.*.suspects.required' => 'You have to choose an employee that suspected or responsible'
        ]);
        if($request->orderStatus == "false"){
            $interrogation_report_type_id = null;
            $typeable_type = null;
            $typeable_id = null;
            $typeable_type = null;
        }else{
            $interrogation_report_type_id = $request->interrogation_report_type_id;
            $typeable_id = $request->typeable_id;
            if($interrogation_report_type_id == 5){
                $typeable_type = 'Modules\HumanResource\Models\Inventory';
            }else{
                $typeable_type = 'Modules\Engineering\Models\Machine';
            }
        }

        if($request->lostStatus == "true"){
            $lostcost = $request->lost_cost;
        }else{
            $lostcost = 0;
        }

        $data = [
            'incident_category_id' => $request->incident_category_id,
            'date_of_incident' => $request->date_of_incident,
            'incident_chronologic' => $request->incident_chronologic,
            'interrogation_report_type_id' => $interrogation_report_type_id,
            'typeable_type' => $typeable_type,
            'typeable_id' => $typeable_id,
            'lost_cost' => $lostcost,
            'is_active' => 1
        ];
        $master = Model::whereId($id)->first();
        $master->update($data);

        if ($request->has('images')) {

            $interrogation = Model::findOrFail($id);
            $data =[];
            
            foreach ($request->images as $image) {
                
                $type = gettype($image['image']);
                if($type != "string"){
                    $nameFile = 'InterrogationImages-' . Str::random(5) . '.' . $image['image']->getClientOriginalExtension();
                    array_push($data, [
                        'interrogation_report_id' => $id,
                        'image' => $nameFile,
                        'note' => $image['note']
                    ]);
                }else{
                    array_push($data, [
                        'interrogation_report_id' => $id,
                        'image' => $image['image'],
                        'note' => $image['note']
                    ]);
                }
            }
            
            proccesRelationWithRequest(
                $interrogation->interrogationReportImages(),
                $data
            );
        }

        $interrogationActors = InterrogationReportActor::where('interrogation_report_id', $id)->get();
        
        $witnesses = $request->witness_actor;
        $suspects = $request->suspect_actor;
        if($witnesses == null){
            $witnesses = [];
        }
        
        
        if (count($interrogationActors) == (count($witnesses) + count($suspects))) {

            foreach ($witnesses as $index => $value) {
                if($value['employeeStatus'] == "true"){
                    InterrogationReportActor::whereId($value['id'])->update([
                        'external_employee_id' => $value['external'],
                        'employee_id' => 0,
                        'description' => $value['description']
                    ]);
                }else{
                    InterrogationReportActor::whereId($value['id'])->update([
                        'employee_id' => $value['witnesses'],
                        'external_employee_id' => 0,
                        'description' => $value['description']
                    ]);
                }
            }

            foreach ($suspects as $index => $value) {
                if($value['employeeStatus'] == "true"){
                    InterrogationReportActor::whereId($value['id'])->update([
                        'external_employee_id' => $value['external'],
                        'employee_id' => 0,
                        'description' => $value['description']
                    ]);
                }else{
                    InterrogationReportActor::whereId($value['id'])->update([
                        'external_employee_id' => 0,
                        'employee_id' => $value['suspects'],
                        'description' => $value['description']
                    ]);
                }
            }
        } else {
            InterrogationReportActor::where('interrogation_report_id', $id)->delete();

            if($request->witness_actor){
                foreach ($request->witness_actor as $witnesses => $value) {
                    if($value['employeeStatus'] == "true"){
                        InterrogationReportActor::create([
                            'interrogation_report_id' => $id,
                            'interrogation_report_actor_type_id' => 1,
                            'external_employee_id' => $value['external'],
                            // 'employee_id' => 0,
                            'description' => $value['description']
                        ]);
                    }else{
                        InterrogationReportActor::create([
                            'interrogation_report_id' => $id,
                            'interrogation_report_actor_type_id' => 1,
                            // 'external_employee_id' => 0,
                            'employee_id' => $value['witnesses'],
                            'description' => $value['description']
                        ]);
                    }
                }
            }

            foreach ($request->suspect_actor as $suspects => $value) {
                // dd($request->all());
                if($value['employeeStatus'] == "true"){
                    InterrogationReportActor::create([
                        'interrogation_report_id' => $id,
                        'interrogation_report_actor_type_id' => 2,
                        'external_employee_id' => $value['external'],
                        // 'employee_id' => 0,
                        'description' => $value['description']
                    ]);
                }else{
                    InterrogationReportActor::create([
                        'interrogation_report_id' => $id,
                        'interrogation_report_actor_type_id' => 2,
                        // 'external_employee_id' => 0,
                        'employee_id' => $value['suspects'],
                        'description' => $value['description']
                    ]);
                }
            }
        }

        return response()->json(['status' => 'success'], Response::HTTP_OK);
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
