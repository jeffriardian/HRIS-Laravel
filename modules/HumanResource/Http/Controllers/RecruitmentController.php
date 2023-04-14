<?php

namespace Modules\HumanResource\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

use App\Http\Resources\DataCollection;

use App\Models\User;
use App\Notifications\Notify as ModelNotification;
use Modules\HumanResource\Models\Recruitment as Model;
use Modules\HumanResource\Models\RecruitmentDetail as Detail;
use Modules\HumanResource\Models\CandidateFile as CandidateFile;
use Modules\HumanResource\Models\RecruitmentFile as RecruitmentFile;
use Modules\HumanResource\Models\Score;
use Modules\HumanResource\Models\Candidate;
use Modules\HumanResource\Models\CandidateChildren;
use Modules\HumanResource\Models\CandidateCouple;
use Modules\HumanResource\Models\CandidateEducationBackground;
use Modules\HumanResource\Models\CandidateEmergency;
use Modules\HumanResource\Models\CandidateJobExperience;
use Modules\HumanResource\Models\CandidateLanguage;
use Modules\HumanResource\Models\CandidateReference;
use Modules\HumanResource\Models\CandidateParent;
use Modules\HumanResource\Models\CandidateAquintance;
use Modules\HumanResource\Models\CandidateSibling;
use Modules\HumanResource\Models\CandidateTraining;
use Modules\HumanResource\Models\Employee;
use Modules\HumanResource\Models\EmployeeParent;
use Modules\HumanResource\Models\EmployeeJobExperience;
use Modules\HumanResource\Models\EmployeeEducationBackground;
use Modules\HumanResource\Models\EmployeeSibling;
use Modules\HumanResource\Models\EmployeeCouple;
use Modules\HumanResource\Models\EmployeeChildren;


use DB;
use Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Http\UploadedFile;

class RecruitmentController extends Controller
{
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $keyword = request()->keyword;
        $data = Model::with(['candidate' => function($query){
            return $query->with('position','company');
        }, 'recruitmentStep'])->where('is_active', 1);

        $data->orderBy('recruitments.id', (request()->order_direction == 'true' ? 'DESC' : 'ASC'));
        
        if (request()->keyword != '') {
            $data = Model::with('candidate')->where(function ($query) use ($keyword) {
                $query->whereHas('candidate', function ($q) use ($keyword) {
                    $q->where('name', 'LIKE', '%' . $keyword . '%');
                });
            });
        }else if(request()->recruitmentStepId != ''){
            $data->where('recruitments.recruitment_step_id', 'LIKE', '%' . request()->recruitmentStepId . '%');
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
        $data = Model::with([''])->orderBy('id', 'ASC');
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
        //Validasi
        // dd($request->all());
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'phone_number' => 'required',
            'position_id' => 'required',
            'birth_place' => 'required',
            'birth_at' => 'required',
            'recruitment_date' => 'required',
            'recruitment_step_id' => 'required',
            'company_id' => 'required',
            ]);
            
            DB::beginTransaction();
        try {
                //Kondisi Jika User tidak mengupload foto
                if($request->hasFile('photo')){
                    //simpan foto
                    $file = $request->file('photo');
                    $photoName = $request->name . '-' . time() . '.' . $file->getClientOriginalExtension();
                    $file->storeAs('/public/human-resources/recruitment', $photoName);
                    //mengambil nik
                    $nik = $this->getNik();
                    //insert into candidate
                    $model = Candidate::create([
                        'position_id' => $request->input('position_id'),
                        'company_id' => $request->input('company_id'),
                        'photo' => $photoName,
                        'ktp' => $request->identity_card_number,
                        'name' => $request->input('name'),
                        'email' => $request->input('email'),
                        'phone_number' => $request->phone_number,
                        'birth_place' => $request->input('birth_place'),
                        'birth_at' => $request->input('birth_at'),
                        'is_active' => 1
                    ]);
                        if(!empty($request->dataUpload)){
                            foreach($request->dataUpload as $listFile => $value){
                                $FileData = (array) $value['caption'];
                                $fileName = $request->name . '-' . time() . '.' . $value['caption']->getClientOriginalExtension();
                                $value['caption']->storeAs('/public/human-resources/recruitment', $fileName);
                                // dd($request->dataUpload);
                                
                                    CandidateFile::create([
                                        'candidate_id' => $this->getMaxCandidate(),
                                        'caption' => $fileName,
                                        'type' => $value['caption']->getClientOriginalExtension(),
                                        'note' => $value['note']
                                    ]);
                            }
                        }
                        Model::create([
                            'candidate_id' => $this->getMaxCandidate(),
                            'recruitment_step_id' => $request->recruitment_step_id,
                            'status_recruitment_id' => 1,
                            'recruitment_date' => $request->recruitment_date,
                            'total_score' => 0,
                            'is_active' => 1
                        ]);

                    

                    DB::commit();
                    return response()->json(['status' => 'success'], Response::HTTP_CREATED);
                }else{
                    $nik = $this->getNik();
                    $model = Candidate::create([
                        'company_id' => $request->input('company_id'),
                        'position_id' => $request->input('position_id'),
                        'ktp' => $request->identity_card_number,
                        'name' => $request->input('name'),
                        'email' => $request->input('email'),
                        'phone_number' => $request->phone_number,
                        'photo' => " ",
                        'birth_place' => $request->input('birth_place'),
                        'birth_at' => $request->input('birth_at'),
                        'is_active' => 1
                    ]);
                        if(!empty($request->dataUpload)){
                            foreach($request->dataUpload as $listFile => $value){
                                    $file = $value['caption'];
                                    $fileName = $request->name . '-' . time() . '.' . $file->getClientOriginalExtension();
                                    $file->storeAs('/public/human-resources/recruitment', $fileName);
                                    
                                    CandidateFile::create([
                                        'candidate_id' => $this->getMaxCandidate(),
                                        'caption' => $fileName,
                                        'type' => $file->getClientOriginalExtension(),
                                        'note' => $value['note']
                                    ]);
                            }
                        }
                    Model::create([
                        'candidate_id' => $this->getMaxCandidate(),
                        'recruitment_step_id' => $request->recruitment_step_id,
                        'status_recruitment_id' => 1,
                        'recruitment_date' => $request->recruitment_date,
                        'total_score' => 0,
                        'is_active' => 1
                    ]);

            

            DB::commit();
            return response()->json(['status' => 'success'], Response::HTTP_CREATED);
                }
            
        }catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'data' => $e->getMessage()]);
        }
    }
    
    private function getFileInstance($file = null)
    {
        if (!empty($file)) {
            $path = base_path($file);
            $file = new UploadedFile($path, basename($path), mime_content_type($path), filesize($path), null, true);
        }
        return $file;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Model::with(['candidate' => function($query){
            return $query->with('position','company','candidateFiles','candidateLanguages','candidateEducationBackgrounds','candidateCouples','candidateChildrens','candidateEmergencies','candidateJobExperiences','candidateParents','candidateReferences','candidateAquintances','candidateSiblings','candidateTrainings');
        },'recruitmentFiles','recruitmentStep.recruitmentStepParameter','recruitmentStep.recruitmentStepParameter.recruitmentStepParameterScores'])->findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $data, 'detail' => $this->getDetail($data->candidate_id)], Response::HTTP_OK);
    }

    public function getDetail($id){
        $detail_recruitment = Detail::with('recruitment','recruitment.recruitmentFiles','recruitmentStep','recruitmentStepParameter','recruitmentStepParameter.recruitmentStepParameterScores','score')
        ->where('candidate_id', $id)->get();
         return $detail_recruitment->groupBy('recruitment_step_id');
    }
    

    public function showFinal($id){
        $data = Model::with(['candidate','candidate.position','candidate.company','candidate.candidateFiles','candidate.candidateEducationBackgrounds','candidate.candidateCouples','candidate.candidateChildrens','candidate.candidateEmergencies','candidate.candidateJobExperiences','candidate.candidateParents','candidate.candidateReferences','candidate.candidateAquintances','candidate.candidateSiblings','candidate.candidateTrainings','candidate.maritalStatus','candidate.religion','candidate.candidateLanguages','recruitmentStep','recruitmentDetails','recruitmentDetails.recruitmentStepParameter','candidate.recruitment.recruitmentFiles','recruitmentDetails.score'])->where('candidate_id', $id)->where('is_active', 0)->get();
        return response()->json(['status' => 'success', 'data' => $data], Response::HTTP_OK);
    }

    public function reject(Request $request, $id){
        DB::beginTransaction();
        try {
            
            $data = [
                'is_active' => 0
            ];
            Candidate::whereId($id)->update($data);
            $data2 = [
                'is_active' => 0,
                'status_recruitment_id' => 4
            ];
            Model::whereId($this->getRecruitment($id))->update($data2);
            DB::commit();
            return response()->json(['status' => 'success'], Response::HTTP_CREATED);
        }catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'data' => $e->getMessage()]);
        }
    }

    public function attend(Request $request, $id){
        DB::beginTransaction();
        try {
            if($request[0]['newStatus'] == 5){
                $data = [
                    'status_recruitment_id' => $request[0]['newStatus'],
                    'note' => $request->recruitmentNote,
                    'is_active' => 0
                ];
                Model::whereId($id)->update($data);
                Model::create([
                    'candidate_id' => $request->candidate_id,
                    'recruitment_step_id' => $request->recruitment_step_id,
                    'status_recruitment_id' => 1,
                    'recruitment_date' => $request->recruitment_date,
                    'total_score' => 0,
                    'is_active' => 1
                    ]);
                DB::commit();
                return response()->json(['status' => 'success'], Response::HTTP_OK);
            }else if($request->status_recruitment_id == 4){
                    $data = [
                        'status_recruitment_id' => $request->status_recruitment_id,
                        'note' => $request->recruitmentNote,
                        'is_active' => 0
                    ];
                    Model::whereId($id)->update($data);
    
                    $data2 = [
                        'is_active' => 0
                    ];  
                    Candidate::whereId($request->candidate_id)->update($data2);
                    DB::commit();
                    return response()->json(['status' => 'success'], Response::HTTP_OK);
            }
            // DB::commit();
            // return response()->json(['status' => 'success'], Response::HTTP_CREATED);
        }catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'data' => $e->getMessage()]);
        }
    }

    public function accept(Request $request, $id){
        // dd($request->all());
        DB::beginTransaction();
        try {
            $data = [
                'is_active' => 0
            ];
            Candidate::whereId($id)->update($data);
            $data2 = [
                'is_active' => 0,
                'status_recruitment_id' => 2
            ];
            Model::whereId($this->getRecruitment($id))->update($data2);
                $data3 = [
                    'department_id' => 0,
                    'religion_id' => $request[0]['candidate']['religion_id'],
                    'blood_type_id' => 0,
                    'marital_status_id' => $request[0]['candidate']['marital_status_id'],
                    'company_id' => $request[0]['candidate']['company_id'],
                    'position_id' => $request[0]['candidate']['position_id'],
                    'nik' => sprintf('%03d',$request[0]['candidate']['company_id']).'.'.sprintf('%03d',$request[0]['candidate']['company_id']).'.'.sprintf('%01d',$this->getNik()),
                    'ktp' => $request[0]['candidate']['ktp'],
                    'sim' => $request[0]['candidate']['sim'],
                    'name' => $request[0]['candidate']['name'],
                    'email' => $request[0]['candidate']['email'],
                    'phone' => $request[0]['candidate']['phone_number'],
                    'address' => $request[0]['candidate']['address'],
                    'photo' => $request[0]['candidate']['photo'],
                    'gender' => $request[0]['candidate']['gender'],
                    'birth_place' => $request[0]['candidate']['birth_place'],
                    'birth_at' => $request[0]['candidate']['birth_at'],
                    'employee_status_id' => 0,
                    'office_hour_id' => 0, 
                    'payroll_type_id' => 0,
                    'salary_type_id' => 0,
                    'is_active' => 1
                ];
            $employee = Employee::create($data3);
            foreach($request[0]['candidate']['candidate_education_backgrounds'] as $listEducation => $value){
                EmployeeEducationBackground::create([
                        'employee_id' => $employee->id,
                        'school_name' => $value['school_name'],
                        'major' => $value['major'],
                        'city' => $value['city'],
                        'entry_year' => $value['entry_year'],
                        'graduation_year' => $value['graduation_year'],
                        'score' => $value['score'],
                        'is_active' => 1
                ]);
            }
            if($request[0]['candidate']['candidate_childrens']){
                foreach($request[0]['candidate']['candidate_childrens'] as $listChildren => $value){
                    
                    EmployeeChildren::create([
                        'employee_id' => $employee->id,
                        'name' => $value['name'],
                        'date_of_birth' => $value['date_of_birth'],
                        'gender' => $value['gender'],
                        'is_active' => 1
                    ]);
                }
            }

            foreach($request[0]['candidate']['candidate_parents'] as $listParent => $value){
                
                EmployeeParent::create([
                    'employee_id' => $employee->id,
                    'type' => $value['type'],
                    'name' => $value['name'],
                    'date_of_birth' => $value['date_of_birth'],
                    'gender' => $value['gender'],
                    'address' => $value['address'],
                    'phone' => $value['phone'],
                    'is_active' => 1
                ]);
            }

            if ($request[0]['candidate']['candidate_siblings']) {
                foreach($request[0]['candidate']['candidate_siblings'] as $listSibling => $value){
                    
                    EmployeeSibling::create([
                        'employee_id' => $employee->id,
                        'name' => $value['name'],
                        'date_of_birth' => $value['date_of_birth'],
                        'gender' => $value['gender'],
                        'is_active' => 1
                    ]);
                }
            }

            if ($request[0]['candidate']['candidate_couples']) {
                foreach($request[0]['candidate']['candidate_couples'] as $listSibling => $value){
                    
                    EmployeeCouple::create([
                        'employee_id' => $employee->id,
                        'name' => $value['name'],
                        'date_of_birth' => $value['date_of_birth'],
                        'gender' => $value['gender'],
                        'is_active' => 1
                    ]);
                }
            }

            if($request[0]['candidate']['candidate_job_experiences']){
                foreach($request[0]['candidate']['candidate_job_experiences'] as $listJobExperience => $value){
                    EmployeeJobExperience::create([
                        'employee_id' => $employee->id,
                        'company_name' => $value['company_name'],
                        'address' => $value['address'],
                        'phone' => $value['phone'],
                        'position' => $value['position'],
                        'start_date' => $value['start_date'],
                        'end_date' => $value['end_date'],
                        'is_active' => 1
                    ]);
                }
            }
            DB::commit();
            return response()->json(['status' => 'success'], Response::HTTP_CREATED);
        }catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'data' => $e->getMessage()]);
        }
    }

    public function getRecruitment($id){
        $recruitment = Model::where('candidate_id', $id);
        return $recruitment->max('id');
    }

    public function getNik(){
        $nik = Employee::select('*')->get();
        return $nik->count('id') + 1;
    }

    public function updateCandidate(Request $request, $id)
    {
        
        
        DB::beginTransaction();
        try {
            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $fileName = $request->name . '-' . time() . '.' . $file->getClientOriginalExtension();
                // dd($request->candidate_id);
                $file->storeAs('/public/human-resources/recruitment', $fileName);
                $data2 = [
                    'company_id' => $request->input('company_id'),
                    'position_id' => $request->input('position_id'),
                    'identity_card_number' => $request->input('identity_card_number'),
                    'name' => $request->input('name'),
                    'email' => $request->input('email'),
                    'phone_number' => $request->input('phone_number'),
                    'attachment' => $fileName,
                    'is_active' => 1 
                ];
                Candidate::whereId($request->candidate_id)->update($data2);
            
                $data = [
                    'recruitment_step_id' => $request->recruitment_step_id,
                    'recruitment_date' => $request->recruitment_date
                ];

                Model::whereId($id)->update($data);
                
                DB::commit();
                return response()->json(['status' => 'success'], Response::HTTP_OK);
            }else{
                $data2 = [
                    'identity_card_number' => $request->identity_card_number,
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone_number' => $request->phone_number,
                    'position_id' => $request->position_id,
                    'company_id' => $request->company_id,
                    'attachment' => $request->old_attachment
                ];
                Candidate::whereId($request->candidate_id)->update($data2);
            
                $data = [
                    'recruitment_step_id' => $request->recruitment_step_id,
                    'recruitment_date' => $request->recruitment_date
                ];

                Model::whereId($id)->update($data);
                
                DB::commit();
                return response()->json(['status' => 'success'], Response::HTTP_OK);
            }
            

        }catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'data' => $e->getMessage()]);
        }


    }

    public function update(Request $request, $id)
    {
        dd($request->all());
        $this->validate($request, [
                'name' => 'required',
                'phone_number' => 'required',
                'religion_id' => 'required',
                'marital_status_id' => 'required',
                'address' => 'required',
                'gender' => 'required',
                'post_code' => 'required',
                'applying_reason' => 'required',
                'expected_salaries' => 'required',
                'expected_facilities' => 'required',
                'work_environment' => 'required',
                'work_out_of_town' => 'required',
                'placed_out_of_town' => 'required',
                'recruitment_step_parameter.*.score' => 'required'
        ]);
        DB::beginTransaction();
        try {
            if ($request->total_score >= $request->min_score) {
                $candidate = Candidate::whereId($request->candidate_id)->update($request->only('position_id','religion_id','marital_status_id','company_id',
                'photo','ktp','name','gender','address','post_code','birth_place','birth_at','sim','email','phone_number','has_sim',
                'achivement','thesis_title','last_salary','last_job_desc','last_facility','achivement_during_work','applying_reason','work_environment','expected_salaries','expected_facilities','work_out_of_town',
                'placed_out_of_town','work_accident','date_of_accident','psychological_check','date_of_check','check_place','check_purpose','vehicle_type',
                'vehicle_belong'));

                if($request->couple_name){
                    CandidateCouple::updateOrCreate([
                        'candidate_id' => $request->candidate_id
                    ],[
                        'candidate_id' => $request->candidate_id,
                        'name' => $request->couple_name,
                        'date_of_birth' => $request->couple_date_of_birth,
                        'gender' => $request->couple_gender
                    ]);
                }
                
                if($request->dataEducation){
                    foreach($request->dataEducation as $listEducation => $value){
                        CandidateEducationBackground::updateOrCreate([
                            'candidate_id' => $request->candidate_id
                        ],[
                            'candidate_id' => $request->candidate_id,
                            'school_name' => $value['school_name'],
                            'major' => $value['major'],
                            'city' => $value['city'],
                            'entry_year' => $value['entry_year'],
                            'graduation_year' => $value['graduation_year'],
                            'score' => $value['score'],
                            'is_active' => 1
                            ]);
                    }
                }

                if($request->dataChildren){
                    foreach($request->dataChildren as $listChildren => $value){
                        
                        CandidateChildren::updateOrCreate([
                            'candidate_id' => $request->candidate_id
                        ],[
                            'candidate_id' => $request->candidate_id,
                            'name' => $value['name'],
                            'date_of_birth' => $value['date_of_birth'],
                            'gender' => $value['gender'],
                            'is_active' => 1
                        ]);
                    }
                }

                if($request->dataParent){
                    foreach($request->dataParent as $listParent => $value){
                        
                        CandidateParent::updateOrCreate([
                            'candidate_id' => $request->candidate_id
                        ],[
                            'candidate_id' => $request->candidate_id,
                            'type' => $value['type'],
                            'name' => $value['name'],
                            'date_of_birth' => $value['date_of_birth'],
                            'gender' => $value['gender'],
                            'address' => $value['address'],
                            'phone' => $value['phone'],
                            'is_active' => 1
                        ]);

                        if($value['emergency'] == "Yes"){
                            CandidateEmergency::updateOrCreate([
                                'candidate_id' => $request->candidate_id
                            ],[
                                'candidate_id' => $request->candidate_id,
                                'name' => $value['name'],
                                'address' => $value['address'],
                                'phone' => $value['phone'],
                                'is_active' => 1
                            ]);
                        }
                    }
                }

                if ($request->dataSibling) {
                    foreach($request->dataSibling as $listSibling => $value){
                        
                        CandidateSibling::updateOrCreate([
                            'candidate_id' => $request->candidate_id
                        ],[
                            'candidate_id' => $request->candidate_id,
                            'name' => $value['name'],
                            'date_of_birth' => $value['date_of_birth'],
                            'gender' => $value['gender'],
                            'is_active' => 1
                        ]);
                    }
                }

                if ($request->dataTraining) {
                    foreach($request->dataTraining as $listTraining => $value){
                        $year = date("Y",strtotime($value['year']));
                        
                        CandidateTraining::updateOrCreate([
                            'candidate_id' => $request->candidate_id
                        ],[
                            'candidate_id' => $request->candidate_id,
                            'training_type' => $value['training_type'],
                            'organizer' => $value['organizer'],
                            'year' => $year,
                            'is_active' => 1
                        ]);
                    }
                }

                if($request->dataJobExperience){
                    foreach($request->dataJobExperience as $listJobExperience => $value){
                        CandidateJobExperience::updateOrCreate([
                            'candidate_id' => $request->candidate_id
                        ],[
                            'candidate_id' => $request->candidate_id,
                            'company_name' => $value['company_name'],
                            'address' => $value['address'],
                            'phone' => $value['phone'],
                            'position' => $value['position'],
                            'start_date' => $value['start_date'],
                            'end_date' => $value['end_date'],
                            'is_active' => 1
                        ]);
                    }
                }

                if ($request->dataReference) {
                    foreach($request->dataReference as $listReference => $value){
                        
                        CandidateReference::updateOrCreate([
                            'candidate_id' => $request->candidate_id
                        ],[
                            'candidate_id' => $request->candidate_id,
                            'name' => $value['name'],
                            'address' => $value['address'],
                            'phone' => $value['phone'],
                            'job' => $value['job'],
                            'is_active' => 1
                        ]);
                    }
                }

                if ($request->dataAquintance) {
                    foreach($request->dataAquintance as $listAquintance => $value){
                        
                        CandidateAquintance::updateOrCreate([
                            'candidate_id' => $request->candidate_id
                        ],[
                            'candidate_id' => $request->candidate_id,
                            'employee_id' => $value['employee_id'],
                            'relation' => $value['relation'],
                            'is_active' => 1
                        ]);
                    }
                }

                if($request->dataLanguage){
                    foreach($request->dataLanguage as $listLanguage => $value){
                        
                        CandidateLanguage::updateOrCreate([
                            'candidate_id' => $request->candidate_id
                        ],[
                            'candidate_id' => $request->candidate_id,
                            'language' => $value['language'],
                            'speaking' => $value['speaking'],
                            'writing' => $value['writing'],
                            'reading' => $value['reading'],
                            'is_active' => 1
                        ]);
                    }
                }

            }
            if($request->newStatus == 5){
                $data = [
                    'status_recruitment_id' => $request->newStatus,
                    'note' => $request->recruitmentNote,
                    'is_active' => 0
                ];
                Model::whereId($id)->update($data);
                Model::create([
                    'candidate_id' => $request->candidate_id,
                    'recruitment_step_id' => $request->next_step,
                    'status_recruitment_id' => 1,
                    'recruitment_date' => $request->next_date,
                    'total_score' => 0,
                    'is_active' => 1
                    ]);
                DB::commit();
                return response()->json(['status' => 'success'], Response::HTTP_OK);
            }else if($request->status_recruitment_id == 4){
                $data = [
                    'status_recruitment_id' => $request->status_recruitment_id,
                    'note' => $request->recruitmentNote,
                    'is_active' => 0
                ];
                Model::whereId($id)->update($data);

                $data2 = [
                    'is_active' => 0
                ];  
                Candidate::whereId($request->candidate_id)->update($data2);
                DB::commit();
                return response()->json(['status' => 'success'], Response::HTTP_OK);
            }else{
                if($request->total_score <= $request->min_score){
                    if($request->file('upload') != null){
                        foreach($request->upload as $listFile => $value){
                            $file = $value['caption'];
                            $fileName = $request->name . '-' . time() . '.' . $file->getClientOriginalExtension();
                            $file->storeAs('/public/human-resources/recruitment', $fileName);
                            // dd($id);
                            RecruitmentFile::create([
                                'recruitment_id' => $id,
                                'caption' => $fileName,
                                'type' => $file->getClientOriginalExtension(),
                                'note' => $value['note']
                            ]);
                        }
                        $data = [
                            'status_recruitment_id' => $request->status_recruitment_id,
                            'note' => $request->recruitmentNote,
                            'is_active' => 0
                        ];
                        Model::whereId($id)->update($data);
        
                        $data2 = [
                            'is_active' => 0
                        ];  
                        Candidate::whereId($request->candidate_id)->update($data2);
                        DB::commit();
                        return response()->json(['status' => 'success'], Response::HTTP_OK);
                    }else{
                        $data = [
                            'status_recruitment_id' => 4,
                            'is_active' => 0
                        ];

                        Model::whereId($id)->update($data);

                        $data2 = [
                            'is_active' => 0
                        ];  
                        Candidate::whereId($request->candidate_id)->update($data2);
                        DB::commit();
                        return response()->json(['status' => 'success'], Response::HTTP_OK);
                    }
                }else{    
                    if($request->file('upload') != null){
                                // dd($request->all());
                                $recruitment = Model::whereId($id)->firstOrFail();
                                    $recruitment->update([
                                        'total_score' => $request->total_score,
                                        'status_recruitment_id' => 2,
                                        'is_active' => 0
                                    ]);

                                    
                                    foreach($request->upload as $listFile => $value){
                                        $file = $value['caption'];
                                        $fileName = $request->name . '-' . time() . '.' . $file->getClientOriginalExtension();
                                        $file->storeAs('/public/human-resources/recruitment', $fileName);
                                        // dd($id);
                                        RecruitmentFile::create([
                                            'recruitment_id' => $id,
                                            'caption' => $fileName,
                                            'type' => $file->getClientOriginalExtension(),
                                            'note' => $value['note']
                                        ]);
                                    }
                                     $detail_data = array_map(function($recruitment) use($request){
                                        return array_merge([
                                             'candidate_id' => $request->candidate_id,
                                            'recruitment_step_id' => $request->old_step,
                                                'score_id' => $request->score
                                         ], $recruitment);
                                    }, $request->recruitment_step_parameter);
                                    $recruitment->recruitmentDetails()->createMany($detail_data);
                                    Model::create([
                                        'candidate_id' => $request->candidate_id,
                                        'recruitment_step_id' => $request->next_step,
                                        'status_recruitment_id' => 1,
                                        'recruitment_date' => $request->next_date,
                                        'total_score' => 0,
                                        'is_active' => 1
                                     ]);
                                
                                
                                DB::commit();
                                return response()->json(['status' => 'success'], Response::HTTP_OK);
                    }else{
                                $recruitment = Model::whereId($id)->firstOrFail();
                                    $recruitment->update([
                                        'total_score' => $request->total_score,
                                        'status_recruitment_id' => 2,
                                        'is_active' => 0
                                    ]);

                                     $detail_data = array_map(function($recruitment) use($request){
                                        return array_merge([
                                             'candidate_id' => $request->candidate_id,
                                            'recruitment_step_id' => $request->old_step,
                                                'score_id' => $request->score
                                         ], $recruitment);
                                    }, $request->recruitment_step_parameter);
                                    $recruitment->recruitmentDetails()->createMany($detail_data);
                                    Model::create([
                                        'candidate_id' => $request->candidate_id,
                                        'recruitment_step_id' => $request->next_step,
                                        'status_recruitment_id' => 1,
                                        'recruitment_date' => $request->next_date,
                                        'total_score' => 0,
                                        'is_active' => 1
                                     ]);
                                
                                
                                DB::commit();
                                return response()->json(['status' => 'success'], Response::HTTP_OK);
                    }
                }
            }
            
           
        }catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'data' => $e->getMessage()]);
        }
    }

    public function totalScore($params){
        $scores = Score::whereIn('id',$params)->get();
        return $scores->score;
    }

    public function getMaxCandidate(){
        $getMax = Candidate::select('*')->get();
        return $getMax->max('id');
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
        $data = Schedule::findOrFail($id);
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
        $data = Schedule::onlyTrashed()->orderBy('created_at', 'DESC');
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
        $data = Schedule::findOrFail($id);
        $data->restore();
        return response()->json(['status' => 'success'], Response::HTTP_OK);
    }
}
