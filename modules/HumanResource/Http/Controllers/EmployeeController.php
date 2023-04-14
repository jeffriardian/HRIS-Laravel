<?php

namespace Modules\HumanResource\Http\Controllers;

use App\Http\Resources\DataCollection;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

use App\Models\User;
use App\Exports\Employee as ModelExcel;
use App\Notifications\Notify as ModelNotification;
use Modules\HumanResource\Models\Employee as Model;
use Modules\HumanResource\Exports\Employee as ModelExport;
use Modules\HumanResource\Models\EmployeeSalary as Salary;
use Modules\HumanResource\Models\EmployeeContract as Contract;

use Excel;
use Illuminate\Support\Facades\DB;
use PDF;
use Illuminate\Support\Facades\Storage;
use Modules\HumanResource\Models\EmployeeContact;
use Modules\HumanResource\Models\HistoryEmployeePosition;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Model::select(['employees.*', 'departments.name as department'])
            ->join('departments', 'departments.id', '=', 'employees.department_id');

        $data->orderBy(request()->order_column, (request()->order_direction == 'true' ? 'DESC' : 'ASC'));

        if (request()->keyword != '') {
            $data = $data->where('departments.name', 'LIKE', '%' . request()->keyword . '%')
                ->orWhere('employees.nik', 'LIKE', '%' . request()->keyword . '%')
                ->orWhere('employees.ktp', 'LIKE', '%' . request()->keyword . '%')
                ->orWhere('employees.kk', 'LIKE', '%' . request()->keyword . '%')
                ->orWhere('employees.npwp', 'LIKE', '%' . request()->keyword . '%')
                ->orWhere('employees.sim', 'LIKE', '%' . request()->keyword . '%')
                ->orWhere('employees.name', 'LIKE', '%' . request()->keyword . '%')
                ->orWhere('employees.email', 'LIKE', '%' . request()->keyword . '%')
                ->orWhere('employees.phone', 'LIKE', '%' . request()->keyword . '%')
                ->orWhere('employees.address', 'LIKE', '%' . request()->keyword . '%')
                ->orWhere('employees.birth_place', 'LIKE', '%' . request()->keyword . '%');
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
        $data = Model::with(['position', 'department']);
        foreach (request()->input() as $key => $value) {
            if ($key != 'order_by') {
                $data->where($key, $value);
            }
        }

        if (request()->has('order_by')) {
            foreach (json_decode(request()->order_by) as $key => $value) {
                $data->orderBy($key,  $value);
            }
        }
        $data->orderBy('id', 'ASC');
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
            'department_id' => 'required',
            'position_id' => 'required',
            'birth_at' => 'required',
            'religion_id' => 'required',
            'blood_type_id' => 'required',
            'marital_status_id' => 'required',
            'employee_status_id' => 'required',
            'company_id' => 'required',
            'office_hour_id' => 'required',
            'payroll_type_id' => 'required',
            'salary_type_id' => 'required',
            'join_at' => 'required',
            'nik' => 'required|unique:employees,nik',
            'name' => 'required|string|max:255',
            'gender' => 'required',
            'photo' => 'nullable|image|mimes:png,jpg,jpeg',
        ]);

        $nameImage = null;
        DB::beginTransaction();
        try {
            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $nameImage = $request->name . '-' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/images/employee-photos', $nameImage);
            }
            $data = [
                'department_id' => $request->input('department_id'),
                'religion_id' => $request->input('religion_id'),
                'blood_type_id' => $request->input('blood_type_id'),
                'payroll_type_id' => $request->input('payroll_type_id'),
                'marital_status_id' => $request->input('marital_status_id'),
                'employee_status_id' => $request->input('employee_status_id'),
                'company_id' => $request->input('company_id'),
                'position_id' => $request->input('position_id'),
                'ptkp_code' => $request->input('ptkp_code'),
                'salary_type_id' => $request->input('salary_type_id'),
                'office_hour_id' => $request->input('office_hour_id'),
                'nik' => $request->input('nik'),
                'ktp' => $request->input('ktp'),
                'kk' => $request->input('kk'),
                'npwp' => $request->input('npwp'),
                'sim' => $request->input('sim'),
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'address' => $request->input('address'),
                'address_ktp' => $request->input('address_ktp'),
                'photo' => $nameImage,
                'gender' => $request->input('gender'),
                'birth_place' => $request->input('birth_place'),
                'birth_at' => $request->input('birth_at'),
                'join_at' => $request->input('join_at'),
                'leave_at' => date('Y-m-d', strtotime($request->input('leave_at'))),
                'is_active' => $request->input('is_active') == false ? 0 : 1,
                'pin' => $request->input('pin'),
            ];

            $employee = Model::create($data);

            // $employee = Model::create($request->except('contacts'));
            foreach (json_decode($request->contacts) as $contact) {
                if ($contact->type != null && $contact->description != null) {
                    $data = [
                        'employee_id' => $employee->id,
                        'type' => $contact->type,
                        'description' => $contact->description
                    ];
                    EmployeeContact::create($data);
                }
            }
            // proccesRelationWithRequest(
            //     $employee->contacts(),
            //    $request->contacts
            // );

            $data_contract = [
                'employee_id' => $employee->id,
                'start_at' => date('Y-m-d', strtotime($request->input('join_at'))),
                'end_at' => date('Y-m-d', strtotime($request->input('leave_at'))),
                'is_active' => '1',
            ];

            Contract::create($data_contract);

            HistoryEmployeePosition::create([
                'employee_id' => $employee->id,
                'start_date' => date('Y-m-d', strtotime($request->input('join_at'))),
                'department_id' => $request->department_id,
                'position_id' => $request->position_id,
                'is_active' => $request->is_active == false ? 0 : 1
            ]);

            DB::commit();
            return response()->json(['status' => 'success'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollback();
            Storage::delete('public/images/employee-photos/' . $nameImage);
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
        $data = Model::with('department', 'contacts')->findOrFail($id);
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
        $employee = Model::findOrFail($id);

        $this->validate($request, [
            'department_id' => 'required',
            'position_id' => 'required',
            'religion_id' => 'required',
            'blood_type_id' => 'required',
            'marital_status_id' => 'required',
            'employee_status_id' => 'required',
            'company_id' => 'required',
            'office_hour_id' => 'required',
            'payroll_type_id' => 'required',
            'salary_type_id' => 'required',
            'join_at' => 'required',
            'nik' => 'required|unique:employees,nik,' . $id,
            'name' => 'required|string|max:255',
            'gender' => 'required',
            'photo' => 'nullable|image|mimes:png,jpg,jpeg',
        ]);

        // $rules = [
        //     '*.type' => 'required',
        //     '*.description' => 'required',
        // ];

        // $validator = Validator::make(json_decode($request->contacts, true), $rules);

        // if ($validator->fails()) {
        //     return response()->json(['errors' => $validator->errors()], 422);
        // }

        $nameImage = $employee->photo;
        DB::beginTransaction();
        try {
            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $nameImage = $request->name . '-' . time() . '.' . $file->getClientOriginalExtension();
                Storage::delete('public/images/employee-photos/' . $employee->photo);
                $file->storeAs('public/images/employee-photos', $nameImage);
            }
            $data = $request->except(['nik', 'contacts']);
            $data['birth_at'] = $data['birth_at'];
            $data['join_at'] = $data['join_at'];
            $data['leave_at'] = date('Y-m-d', strtotime($data['leave_at']));
            $data['photo'] = $nameImage;

            $employee->update($data);

            // dd(count(json_decode($request->contacts)));
            $countContacts = EmployeeContact::where('employee_id', $id)->count();
            // dd($countContacts);
            $contacts = json_decode($request->contacts);
            if ($countContacts == count($contacts)) { // form jumlah form contact sama dengan jumlah contact yg ada di DB
                // update contact
                foreach ($contacts as $contact) {
                    if ($contact->type != null && $contact->description != null) {
                        $data = [
                            'type' => $contact->type,
                            'description' => $contact->description
                        ];
                        EmployeeContact::whereId($contact->id)->update($data);
                    }
                }
            } else { // jika  jumlah form contact tidak sama dengan jumlah contact yg ada di DB
                EmployeeContact::whereIn('employee_id', [$id])->delete(); // delete semua contact
                foreach ($contacts as $contact) {
                    if ($contact->type != null && $contact->description != null) {
                        $data = [
                            'employee_id' => $employee->id,
                            'type' => $contact->type,
                            'description' => $contact->description
                        ];
                        EmployeeContact::create($data);
                    }
                }
            }

            // proccesRelationWithRequest(
            //     $employee->contacts(),
            //     $request->contacts
            // );

            DB::commit();
            return response()->json(['status' => 'success'], Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'data' => $e->getMessage()]);
        }
    }

    /**
     * Export excel function.
     *
     * @return \Maatwebsite\Excel\Facades\Excel
     */
    public function exportExcel()
    {
        $now = now();
        return Excel::download(new ModelExport, "employee_list_{$now}.xlsx");
    }

    /**
     * Export excel function.
     *
     * @return \Barryvdh\DomPDF\Facade
     */
    public function exportPdf()
    {
        $models = Model::with(['department'])->orderBy('id', 'ASC')->get();
        $pdf = PDF::loadView('humanresource::pdf.employee', compact('models'));
        return $pdf->stream();
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

        $user = auth()->user();
        //$message = $user->chats()->create([ 'message' => 'test hai' ]);
        //broadcast(new \App\Events\MessagePosted($message, $user))->toOthers();
        //$user->notify(new ModelNotification($data->name, config('constants.action.delete')));

        $users = User::all();
        $notificationData = [
            'model' => Model::class,
            'model_id' => $id,
            'model_name' => $data->name,
            'action' => config('constants.action.delete'),
            'by' => auth()->user()->id,

        ];
        Notification::send($users, new ModelNotification($notificationData));

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

    public function migrationData()
    {
        DB::beginTransaction();
        try {
            $migration = $this->getEmployee();

            foreach ($migration as $migration) {
                $data = [
                    'department_id' => $migration->department_id,
                    'position_id' => $migration->position_id,
                    'religion_id' => $migration->religion_id,
                    'blood_type_id' => $migration->blood_type_id,
                    'marital_status_id' => $migration->marital_status_id,
                    'employee_status_id' => $migration->employee_status_id,
                    'company_id' => $migration->company_id,
                    'office_hour_id' => $migration->office_hour_id,
                    'payroll_type_id' => $migration->payroll_type_id,
                    'salary_type_id' => $migration->salary_type_id,
                    'day_off_id' => $migration->day_off_id,
                    'ptkp_code' => $migration->ptkp_code,
                    'nik' => $migration->nik,
                    'old_nik' => $migration->old_nik,
                    'ktp' => $migration->ktp,
                    'kk' => $migration->kk,
                    'npwp' => $migration->npwp,
                    'sim' => $migration->sim,
                    'pin' => $migration->pin,
                    'name' => $migration->name,
                    'email' => $migration->email,
                    'phone' => $migration->phone,
                    'address' => $migration->address,
                    'address_ktp' => $migration->address_ktp,
                    'photo' => $migration->photo,
                    'gender' => $migration->gender,
                    'birth_place' => $migration->birth_place,
                    'birth_at' => $migration->birth_at,
                    'join_at' => date('Y-m-d', strtotime($migration->join_at)),
                    'leave_at' => date('Y-m-d', strtotime($migration->leave_at)),
                    'is_active' => $migration->is_active,
                ];

                $employee = Model::create($data);

                $basicsalary = $this->getBasicSalary();

                foreach ($basicsalary as $basicsalary) {
                    $data = [
                        'employee_id' => $employee->id,
                        'payroll_component_id' => $basicsalary->id,
                        'amount' => $migration->gapok,
                        'is_active' => 1,
                    ];

                    Salary::create($data);
                }

                $wage = $this->getWage();

                foreach ($wage as $wage) {
                    $data = [
                        'employee_id' => $employee->id,
                        'payroll_component_id' => $wage->id,
                        'amount' => $migration->PremiHadir,
                        'is_active' => 1,
                    ];

                    Salary::create($data);
                }

                $functional = $this->getFunctionalSalary();

                foreach ($functional as $functional) {
                    $data = [
                        'employee_id' => $employee->id,
                        'payroll_component_id' => $functional->id,
                        'amount' => $migration->ujabat,
                        'is_active' => 1,
                    ];

                    Salary::create($data);
                }

                $meal = $this->getMeal();

                foreach ($meal as $meal) {
                    $data = [
                        'employee_id' => $employee->id,
                        'payroll_component_id' => $meal->id,
                        'amount' => $migration->meal,
                        'is_active' => 1,
                    ];

                    Salary::create($data);
                }

                $aoc = $this->getAoc();

                foreach ($aoc as $aoc) {
                    $data = [
                        'employee_id' => $employee->id,
                        'payroll_component_id' => $aoc->id,
                        'amount' => $migration->aoc,
                        'is_active' => 1,
                    ];

                    Salary::create($data);
                }
            }

            DB::commit();
            return response()->json(['status' => 'success'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'data' => $e->getMessage()]);
        }
    }

    public function getBasicSalary()
    {
        $basicsalary = DB::select('SELECT id FROM payroll_components WHERE code = "basic_salary"');

        return $basicsalary;
    }

    public function getWage()
    {
        $wage = DB::select('SELECT id FROM payroll_components WHERE code = "wage"');

        return $wage;
    }

    public function getFunctionalSalary()
    {
        $functional = DB::select('SELECT id FROM payroll_components WHERE code = "functional_allowance"');

        return $functional;
    }

    public function getMeal()
    {
        $meal = DB::select('SELECT id FROM payroll_components WHERE code = "meal"');

        return $meal;
    }

    public function getAoc()
    {
        $aoc = DB::select('SELECT id FROM payroll_components WHERE code = "aoc"');

        return $aoc;
    }

    public function getEmployee()
    {
        $migration = DB::connection('sqlsrv')->select("select coalesce(gapok,0) as gapok, coalesce(PremiHadir, 0) as PremiHadir, 999 as day_off_id,
        stapajak as ptkp_code, nik as old_nik, noktp as ktp, NULL as kk, npwp as npwp, NULL as sim, pin as pin, coalesce(gapok,0) as ujabat,
        coalesce(um,0) as meal, coalesce(aoc,0) as aoc,
        nama as name, email as email, nohp as phone, alamat as address, alamatktp as address_ktp,NULL as photo, TmpLahir as birth_place,
        CAST(tgl_lahir as date) as birth_at, CAST(tmk as date) as join_at, CAST(tgl_selesai as date) as leave_at, status as is_active,

        CASE
        WHEN kodeprs = '001' THEN 1
        WHEN kodeprs = '002' THEN 2
        WHEN kodeprs != '001' and kodeprs != '002' THEN 999
        WHEN kodeprs != '' or kodeprs IS NULL THEN 999
        END AS company_id,

		concat(REPLICATE('0',3-LEN(RTRIM(CASE
        WHEN kodeprs = '001' THEN 1
        WHEN kodeprs = '002' THEN 2
        WHEN kodeprs != '001' and kodeprs != '002' THEN 999
        WHEN kodeprs != '' or kodeprs IS NULL THEN 999
        END))) + RTRIM(CASE
        WHEN kodeprs = '001' THEN 1
        WHEN kodeprs = '002' THEN 2
        WHEN kodeprs != '001' and kodeprs != '002' THEN 999
        WHEN kodeprs != '' or kodeprs IS NULL THEN 999
        END),
		'.',
		REPLICATE('0',3-LEN(RTRIM(CASE
        WHEN kodebag = 'JBR' or kodebag = 'JKT' or kodebag = 'JTG' or kodebag = 'JTM' or kodebag = 'BDG' or kodebag = 'BLI' or kodebag = 'BMS' or kodebag = 'BSD' or
		kodebag = 'CKG' or kodebag = 'CLC' or kodebag = 'CRB' or kodebag = 'CUP' or kodebag = 'GB' or kodebag = 'Gdg' or kodebag = 'Log' or kodebag = 'LTM' or kodebag = 'PLM' or
		kodebag = 'PRM' or kodebag = 'PTR' or kodebag = 'PWK' or kodebag = 'QCI' or kodebag = 'SAL' or kodebag = 'SL' or kodebag = 'SMG' or kodebag = 'TG' or kodebag = 'YGY' or
		kodebag = '109' or kodebag = '111' or kodebag = '117' or kodebag = 'ADM' or kodebag = 'BKL' or kodebag = 'DP' or kodebag = 'DPH' or kodebag = 'EXP' or kodebag = 'OBT' or
		kodebag = 'TKN' or kodebag = 'UTL' or kodebag = 'WSH' THEN 999
        WHEN kodebag = '1R' and kodedep = 'F&A' THEN 9
        WHEN kodebag = '1R' and kodedep = 'GBJ' THEN 28
        WHEN kodebag = '1R' and kodedep = 'HRG' THEN 25
        WHEN kodebag = '1R' and kodedep = 'IC' THEN 16
        WHEN kodebag = '1R' and kodedep = 'Lab' THEN 21
        WHEN kodebag = '1R' and kodedep = 'Mkt' THEN 14
        WHEN kodebag = '1R' and kodedep = 'PPC' THEN 15
        WHEN kodebag = '1R' and kodedep = 'Prd' THEN 18
        WHEN kodebag = '1R' and kodedep = 'QA' THEN 999
        WHEN kodebag = '1R' and kodedep = 'QMS' THEN 2
        WHEN kodebag = '1R' and kodedep = 'Rnd' THEN 20
        WHEN kodebag = '1R' and kodedep = 'DP' THEN 999
        WHEN kodebag = 'DRV' and kodedep = 'DP' THEN 999
        WHEN kodebag = 'DRV' and kodedep = 'Exp' THEN 17
        WHEN kodebag = 'DRV' and kodedep = 'F&A' THEN 9
        WHEN kodebag = 'FKL' and kodedep = 'GBJ' THEN 28
        WHEN kodebag = 'FKL' and kodedep = 'Prd' THEN 18
        WHEN kodebag = '1A' THEN 17
        WHEN kodebag = '1B' THEN 65
        WHEN kodebag = '1C' THEN 55
        WHEN kodebag = '1D' THEN 53
        WHEN kodebag = '1E' THEN 52
        WHEN kodebag = '1F' THEN 52
        WHEN kodebag = '1G' THEN 52
        WHEN kodebag = '1H' THEN 57
        WHEN kodebag = '1I' THEN 59
        WHEN kodebag = '1J' THEN 64
        WHEN kodebag = '1K' THEN 54
        WHEN kodebag = '1KK' THEN 65
        WHEN kodebag = '1L' THEN 61
        WHEN kodebag = '1M' THEN 63
        WHEN kodebag = '1N' THEN 4
        WHEN kodebag = '1N1' THEN 12
        WHEN kodebag = '1O' THEN 72
        WHEN kodebag = '1P' THEN 38
        WHEN kodebag = '1P1' THEN 34
        WHEN kodebag = '1Q' THEN 49
        WHEN kodebag = '1Q1' THEN 50
        WHEN kodebag = '1S' THEN 29
        WHEN kodebag = '1SS' THEN 28
        WHEN kodebag = '1T ' THEN 51
        WHEN kodebag = '1U' THEN 33
        WHEN kodebag = 'ACC' THEN 40
        WHEN kodebag = 'BE' THEN 18
        WHEN kodebag = 'BF' THEN 18
        WHEN kodebag = 'BG' THEN 18
        WHEN kodebag = 'BI' THEN 18
        WHEN kodebag = 'BJ' THEN 18
        WHEN kodebag = 'BL' THEN 18
        WHEN kodebag = 'BLW' THEN 18
        WHEN kodebag = 'BTL' THEN 18
        WHEN kodebag = 'CCD' THEN 18
        WHEN kodebag = 'DCR' THEN 35
        WHEN kodebag = 'DD' THEN 999
        WHEN kodebag = 'ENG' THEN 4
        WHEN kodebag = 'Fin' THEN 39
        WHEN kodebag = 'GA' THEN 18
        WHEN kodebag = 'GBJ' THEN 28
        WHEN kodebag = 'GBK' THEN 30
        WHEN kodebag = 'GC' THEN 18
        WHEN kodebag = 'GD' THEN 18
        WHEN kodebag = 'GDU' THEN 30
        WHEN kodebag = 'GG' THEN 27
        WHEN kodebag = 'GLN' THEN 18
        WHEN kodebag = 'HRD' THEN 24
        WHEN kodebag = 'HRG' THEN 25
        WHEN kodebag = 'IAU' THEN 36
        WHEN kodebag = 'IC' THEN 16
        WHEN kodebag = 'IT' THEN 13
        WHEN kodebag = 'LAB' THEN 21
        WHEN kodebag = 'MGM' THEN 1
        WHEN kodebag = 'MKT' THEN 44
        WHEN kodebag = 'N1' THEN 10
        WHEN kodebag = 'N2' THEN 11
        WHEN kodebag = 'PEM' THEN 23
        WHEN kodebag = 'PGL' THEN 30
        WHEN kodebag = 'PGR' THEN 49
        WHEN kodebag = 'PIC' THEN 5
        WHEN kodebag = 'PJK' THEN 41
        WHEN kodebag = 'PPC' THEN 15
        WHEN kodebag = 'PRD' THEN 18
        WHEN kodebag = 'QA' THEN 22
        WHEN kodebag = 'QC' THEN 22
        WHEN kodebag = 'QCD' THEN 66
        WHEN kodebag = 'QCS' THEN 68
        WHEN kodebag = 'QCT' THEN 67
        WHEN kodebag = 'R&D' THEN 20
        WHEN kodebag = 'RTR' THEN 69
        WHEN kodebag = 'SEC' THEN 37
        WHEN kodebag = 'SPL' THEN 26
        WHEN kodebag = 'TGL' THEN 30
        WHEN kodebag = 'UMM' THEN 38
        WHEN kodebag = 'W1' THEN 18
        WHEN kodebag = 'W2' THEN 18
        WHEN kodebag = 'W3' THEN 18
        WHEN kodebag = '' or kodebag IS NULL THEN 999
        END))) + RTRIM(CASE
        WHEN kodebag = 'JBR' or kodebag = 'JKT' or kodebag = 'JTG' or kodebag = 'JTM' or kodebag = 'BDG' or kodebag = 'BLI' or kodebag = 'BMS' or kodebag = 'BSD' or
		kodebag = 'CKG' or kodebag = 'CLC' or kodebag = 'CRB' or kodebag = 'CUP' or kodebag = 'GB' or kodebag = 'Gdg' or kodebag = 'Log' or kodebag = 'LTM' or kodebag = 'PLM' or
		kodebag = 'PRM' or kodebag = 'PTR' or kodebag = 'PWK' or kodebag = 'QCI' or kodebag = 'SAL' or kodebag = 'SL' or kodebag = 'SMG' or kodebag = 'TG' or kodebag = 'YGY' or
		kodebag = '109' or kodebag = '111' or kodebag = '117' or kodebag = 'ADM' or kodebag = 'BKL' or kodebag = 'DP' or kodebag = 'DPH' or kodebag = 'EXP' or kodebag = 'OBT' or
		kodebag = 'TKN' or kodebag = 'UTL' or kodebag = 'WSH' THEN 999
        WHEN kodebag = '1R' and kodedep = 'F&A' THEN 9
        WHEN kodebag = '1R' and kodedep = 'GBJ' THEN 28
        WHEN kodebag = '1R' and kodedep = 'HRG' THEN 25
        WHEN kodebag = '1R' and kodedep = 'IC' THEN 16
        WHEN kodebag = '1R' and kodedep = 'Lab' THEN 21
        WHEN kodebag = '1R' and kodedep = 'Mkt' THEN 14
        WHEN kodebag = '1R' and kodedep = 'PPC' THEN 15
        WHEN kodebag = '1R' and kodedep = 'Prd' THEN 18
        WHEN kodebag = '1R' and kodedep = 'QA' THEN 999
        WHEN kodebag = '1R' and kodedep = 'QMS' THEN 2
        WHEN kodebag = '1R' and kodedep = 'Rnd' THEN 20
        WHEN kodebag = '1R' and kodedep = 'DP' THEN 999
        WHEN kodebag = 'DRV' and kodedep = 'DP' THEN 999
        WHEN kodebag = 'DRV' and kodedep = 'Exp' THEN 17
        WHEN kodebag = 'DRV' and kodedep = 'F&A' THEN 9
        WHEN kodebag = 'FKL' and kodedep = 'GBJ' THEN 28
        WHEN kodebag = 'FKL' and kodedep = 'Prd' THEN 18
        WHEN kodebag = '1A' THEN 17
        WHEN kodebag = '1B' THEN 65
        WHEN kodebag = '1C' THEN 55
        WHEN kodebag = '1D' THEN 53
        WHEN kodebag = '1E' THEN 52
        WHEN kodebag = '1F' THEN 52
        WHEN kodebag = '1G' THEN 52
        WHEN kodebag = '1H' THEN 57
        WHEN kodebag = '1I' THEN 59
        WHEN kodebag = '1J' THEN 64
        WHEN kodebag = '1K' THEN 54
        WHEN kodebag = '1KK' THEN 65
        WHEN kodebag = '1L' THEN 61
        WHEN kodebag = '1M' THEN 63
        WHEN kodebag = '1N' THEN 4
        WHEN kodebag = '1N1' THEN 12
        WHEN kodebag = '1O' THEN 72
        WHEN kodebag = '1P' THEN 38
        WHEN kodebag = '1P1' THEN 34
        WHEN kodebag = '1Q' THEN 49
        WHEN kodebag = '1Q1' THEN 50
        WHEN kodebag = '1S' THEN 29
        WHEN kodebag = '1SS' THEN 28
        WHEN kodebag = '1T ' THEN 51
        WHEN kodebag = '1U' THEN 33
        WHEN kodebag = 'ACC' THEN 40
        WHEN kodebag = 'BE' THEN 18
        WHEN kodebag = 'BF' THEN 18
        WHEN kodebag = 'BG' THEN 18
        WHEN kodebag = 'BI' THEN 18
        WHEN kodebag = 'BJ' THEN 18
        WHEN kodebag = 'BL' THEN 18
        WHEN kodebag = 'BLW' THEN 18
        WHEN kodebag = 'BTL' THEN 18
        WHEN kodebag = 'CCD' THEN 18
        WHEN kodebag = 'DCR' THEN 35
        WHEN kodebag = 'DD' THEN 999
        WHEN kodebag = 'ENG' THEN 4
        WHEN kodebag = 'Fin' THEN 39
        WHEN kodebag = 'GA' THEN 18
        WHEN kodebag = 'GBJ' THEN 28
        WHEN kodebag = 'GBK' THEN 30
        WHEN kodebag = 'GC' THEN 18
        WHEN kodebag = 'GD' THEN 18
        WHEN kodebag = 'GDU' THEN 30
        WHEN kodebag = 'GG' THEN 27
        WHEN kodebag = 'GLN' THEN 18
        WHEN kodebag = 'HRD' THEN 24
        WHEN kodebag = 'HRG' THEN 25
        WHEN kodebag = 'IAU' THEN 36
        WHEN kodebag = 'IC' THEN 16
        WHEN kodebag = 'IT' THEN 13
        WHEN kodebag = 'LAB' THEN 21
        WHEN kodebag = 'MGM' THEN 1
        WHEN kodebag = 'MKT' THEN 44
        WHEN kodebag = 'N1' THEN 10
        WHEN kodebag = 'N2' THEN 11
        WHEN kodebag = 'PEM' THEN 23
        WHEN kodebag = 'PGL' THEN 30
        WHEN kodebag = 'PGR' THEN 49
        WHEN kodebag = 'PIC' THEN 5
        WHEN kodebag = 'PJK' THEN 41
        WHEN kodebag = 'PPC' THEN 15
        WHEN kodebag = 'PRD' THEN 18
        WHEN kodebag = 'QA' THEN 22
        WHEN kodebag = 'QC' THEN 22
        WHEN kodebag = 'QCD' THEN 66
        WHEN kodebag = 'QCS' THEN 68
        WHEN kodebag = 'QCT' THEN 67
        WHEN kodebag = 'R&D' THEN 20
        WHEN kodebag = 'RTR' THEN 69
        WHEN kodebag = 'SEC' THEN 37
        WHEN kodebag = 'SPL' THEN 26
        WHEN kodebag = 'TGL' THEN 30
        WHEN kodebag = 'UMM' THEN 38
        WHEN kodebag = 'W1' THEN 18
        WHEN kodebag = 'W2' THEN 18
        WHEN kodebag = 'W3' THEN 18
        WHEN kodebag = '' or kodebag IS NULL THEN 999
        END),
		'.',
		REPLICATE('0',5-LEN(RTRIM(NRP))) + RTRIM(NRP)) as nik,

        CASE
        WHEN kodebag = 'JBR' or kodebag = 'JKT' or kodebag = 'JTG' or kodebag = 'JTM' or kodebag = 'BDG' or kodebag = 'BLI' or kodebag = 'BMS' or kodebag = 'BSD' or
		kodebag = 'CKG' or kodebag = 'CLC' or kodebag = 'CRB' or kodebag = 'CUP' or kodebag = 'GB' or kodebag = 'Gdg' or kodebag = 'Log' or kodebag = 'LTM' or kodebag = 'PLM' or
		kodebag = 'PRM' or kodebag = 'PTR' or kodebag = 'PWK' or kodebag = 'QCI' or kodebag = 'SAL' or kodebag = 'SL' or kodebag = 'SMG' or kodebag = 'TG' or kodebag = 'YGY' or
		kodebag = '109' or kodebag = '111' or kodebag = '117' or kodebag = 'ADM' or kodebag = 'BKL' or kodebag = 'DP' or kodebag = 'DPH' or kodebag = 'EXP' or kodebag = 'OBT' or
		kodebag = 'TKN' or kodebag = 'UTL' or kodebag = 'WSH' THEN 999
        WHEN kodebag = '1R' and kodedep = 'F&A' THEN 9
        WHEN kodebag = '1R' and kodedep = 'GBJ' THEN 28
        WHEN kodebag = '1R' and kodedep = 'HRG' THEN 25
        WHEN kodebag = '1R' and kodedep = 'IC' THEN 16
        WHEN kodebag = '1R' and kodedep = 'Lab' THEN 21
        WHEN kodebag = '1R' and kodedep = 'Mkt' THEN 14
        WHEN kodebag = '1R' and kodedep = 'PPC' THEN 15
        WHEN kodebag = '1R' and kodedep = 'Prd' THEN 18
        WHEN kodebag = '1R' and kodedep = 'QA' THEN 999
        WHEN kodebag = '1R' and kodedep = 'QMS' THEN 2
        WHEN kodebag = '1R' and kodedep = 'Rnd' THEN 20
        WHEN kodebag = '1R' and kodedep = 'DP' THEN 999
        WHEN kodebag = 'DRV' and kodedep = 'DP' THEN 999
        WHEN kodebag = 'DRV' and kodedep = 'Exp' THEN 17
        WHEN kodebag = 'DRV' and kodedep = 'F&A' THEN 9
        WHEN kodebag = 'FKL' and kodedep = 'GBJ' THEN 28
        WHEN kodebag = 'FKL' and kodedep = 'Prd' THEN 18
        WHEN kodebag = '1A' THEN 17
        WHEN kodebag = '1B' THEN 65
        WHEN kodebag = '1C' THEN 55
        WHEN kodebag = '1D' THEN 53
        WHEN kodebag = '1E' THEN 52
        WHEN kodebag = '1F' THEN 52
        WHEN kodebag = '1G' THEN 52
        WHEN kodebag = '1H' THEN 57
        WHEN kodebag = '1I' THEN 59
        WHEN kodebag = '1J' THEN 64
        WHEN kodebag = '1K' THEN 54
        WHEN kodebag = '1KK' THEN 65
        WHEN kodebag = '1L' THEN 61
        WHEN kodebag = '1M' THEN 63
        WHEN kodebag = '1N' THEN 4
        WHEN kodebag = '1N1' THEN 12
        WHEN kodebag = '1O' THEN 72
        WHEN kodebag = '1P' THEN 38
        WHEN kodebag = '1P1' THEN 34
        WHEN kodebag = '1Q' THEN 49
        WHEN kodebag = '1Q1' THEN 50
        WHEN kodebag = '1S' THEN 29
        WHEN kodebag = '1SS' THEN 28
        WHEN kodebag = '1T ' THEN 51
        WHEN kodebag = '1U' THEN 33
        WHEN kodebag = 'ACC' THEN 40
        WHEN kodebag = 'BE' THEN 18
        WHEN kodebag = 'BF' THEN 18
        WHEN kodebag = 'BG' THEN 18
        WHEN kodebag = 'BI' THEN 18
        WHEN kodebag = 'BJ' THEN 18
        WHEN kodebag = 'BL' THEN 18
        WHEN kodebag = 'BLW' THEN 18
        WHEN kodebag = 'BTL' THEN 18
        WHEN kodebag = 'CCD' THEN 18
        WHEN kodebag = 'DCR' THEN 35
        WHEN kodebag = 'DD' THEN 999
        WHEN kodebag = 'ENG' THEN 4
        WHEN kodebag = 'Fin' THEN 39
        WHEN kodebag = 'GA' THEN 18
        WHEN kodebag = 'GBJ' THEN 28
        WHEN kodebag = 'GBK' THEN 30
        WHEN kodebag = 'GC' THEN 18
        WHEN kodebag = 'GD' THEN 18
        WHEN kodebag = 'GDU' THEN 30
        WHEN kodebag = 'GG' THEN 27
        WHEN kodebag = 'GLN' THEN 18
        WHEN kodebag = 'HRD' THEN 24
        WHEN kodebag = 'HRG' THEN 25
        WHEN kodebag = 'IAU' THEN 36
        WHEN kodebag = 'IC' THEN 16
        WHEN kodebag = 'IT' THEN 13
        WHEN kodebag = 'LAB' THEN 21
        WHEN kodebag = 'MGM' THEN 1
        WHEN kodebag = 'MKT' THEN 44
        WHEN kodebag = 'N1' THEN 10
        WHEN kodebag = 'N2' THEN 11
        WHEN kodebag = 'PEM' THEN 23
        WHEN kodebag = 'PGL' THEN 30
        WHEN kodebag = 'PGR' THEN 49
        WHEN kodebag = 'PIC' THEN 5
        WHEN kodebag = 'PJK' THEN 41
        WHEN kodebag = 'PPC' THEN 15
        WHEN kodebag = 'PRD' THEN 18
        WHEN kodebag = 'QA' THEN 22
        WHEN kodebag = 'QC' THEN 22
        WHEN kodebag = 'QCD' THEN 66
        WHEN kodebag = 'QCS' THEN 68
        WHEN kodebag = 'QCT' THEN 67
        WHEN kodebag = 'R&D' THEN 20
        WHEN kodebag = 'RTR' THEN 69
        WHEN kodebag = 'SEC' THEN 37
        WHEN kodebag = 'SPL' THEN 26
        WHEN kodebag = 'TGL' THEN 30
        WHEN kodebag = 'UMM' THEN 38
        WHEN kodebag = 'W1' THEN 18
        WHEN kodebag = 'W2' THEN 18
        WHEN kodebag = 'W3' THEN 18
        WHEN kodebag = 'SPG' THEN 999
        WHEN kodebag = 'SXP' THEN 999
        WHEN kodebag = 'SQI' THEN 999
        WHEN kodebag = 'STG' THEN 999
        WHEN kodebag = 'SW1' THEN 999
        WHEN kodebag = 'SW2' THEN 999
        WHEN kodebag = 'SW3' THEN 999
        WHEN kodebag = 'SPM' THEN 999
        WHEN kodebag = 'SMT' THEN 999
        WHEN kodebag = 'SGK' THEN 999
        WHEN kodebag = 'SGJ' THEN 999
        WHEN kodebag = 'SEN' THEN 999
        WHEN kodebag = 'SWT' THEN 999
        WHEN kodebag = 'SFK' THEN 999
        WHEN kodebag = 'HLF' THEN 999
        WHEN kodebag = 'HLP' THEN 999
        WHEN kodebag = 'HPA' THEN 999
        WHEN kodebag = 'HPB' THEN 999
        WHEN kodebag = 'HPC' THEN 999
        WHEN kodebag = 'HPD' THEN 999
        WHEN kodebag = 'HPE' THEN 999
        WHEN kodebag = 'HPF' THEN 999
        WHEN kodebag = 'HPG' THEN 999
        WHEN kodebag = 'HPH' THEN 999
        WHEN kodebag = 'HPI' THEN 999
        WHEN kodebag = 'HPJ' THEN 999
        WHEN kodebag = 'HPK' THEN 999
        WHEN kodebag = 'HPL' THEN 999
        WHEN kodebag = '' or kodebag IS NULL THEN 999
        END AS department_id,

        999 AS position_id,

        CASE
        WHEN agama = 'Islam' THEN 1
        WHEN agama = 'Kristen' THEN 2
        WHEN agama = 'Hindu' THEN 4
        WHEN agama = 'Budha' THEN 5
        WHEN agama != '' or agama IS NULL THEN 999
        END AS religion_id,

        CASE
        WHEN Gdara = 'A' THEN 1
        WHEN Gdara = 'B' THEN 2
        WHEN Gdara = '0' THEN 3
        WHEN Gdara = 'AB' THEN 4
        WHEN Gdara != '' or Gdara IS NULL THEN 999
        END AS blood_type_id,

        CASE
        WHEN stskawin = 0 THEN 1
        WHEN stskawin = 1 THEN 2
        WHEN stskawin != '' or stskawin IS NULL THEN 999
        END AS marital_status_id,

        CASE
        WHEN stskontrak = 2 THEN 1
        WHEN stskontrak != 2 or stskontrak != '' or stskontrak IS NULL THEN 999
        END AS employee_status_id,

        CASE
        WHEN shift = '001' THEN 1
        WHEN shift = '002' THEN 2
        WHEN shift = '006' THEN 3
        WHEN shift = '007' THEN 4
        WHEN shift = '008' THEN 5
        WHEN shift = '011' THEN 6
        WHEN shift = '1716' THEN 7
        WHEN shift = 'Du' THEN 8
        WHEN shift = 'He' THEN 9
        WHEN shift = 'LT' THEN 10
        WHEN shift = 'NS17' THEN 11
        WHEN shift = 'NSP2' THEN 12
        WHEN shift = 'T1' THEN 13
        WHEN shift = '100' THEN 14
        WHEN shift = '101' THEN 15
        WHEN shift = '102' THEN 16
        WHEN shift = '103' THEN 17
        WHEN shift = 'Cnt' THEN 18
        WHEN shift = 'GBK1' THEN 19
        WHEN shift = 'OBAT' THEN 20
        WHEN shift = 'QCI2' THEN 21
        WHEN shift = 'TGLN' THEN 22
        WHEN shift != '' or shift IS NULL THEN 999
        END AS office_hour_id,

        CASE
        WHEN gaji = 'B' THEN 1
        WHEN gaji = 'H' THEN 2
        WHEN gaji != '' or gaji IS NULL THEN 999
        END AS payroll_type_id,

        CASE
        WHEN jgaji = 1 THEN 1
        WHEN jgaji = 2 THEN 2
        WHEN jgaji != '' or jgaji IS NULL THEN 999
        END AS salary_type_id,
        CASE
        WHEN jk = 'L' THEN 1
        WHEN jk = 'P' THEN 0
        END AS gender

        from karyawan where nama != '' order by nik");

        return $migration;
    }

    // public function getEmployee()
    // {
    //     $migration = DB::connection('sqlsrv')->select("select stapajak as ptkp_code, nik as nik, nik as old_nik, noktp as ktp, NULL as kk, npwp as npwp, NULL as sim, pin as pin,
    //     nama as name, email as email, nohp as phone, alamat as address, alamatktp as address_ktp,NULL as photo, TmpLahir as birth_place,
    //     CAST(tgl_lahir as date) as birth_at, CAST(tgl_mulai as date) as join_at, CAST(tgl_selesai as date) as leave_at, status as is_active,
    //     CASE
    //     WHEN kodebag = 'JBR' or kodebag = 'JKT' or kodebag = 'JTG' or kodebag = 'JTM' or kodebag = 'BDG' or kodebag = 'BLI' or kodebag = 'BMS' or kodebag = 'BSD' or
    // 	kodebag = 'CKG' or kodebag = 'CLC' or kodebag = 'CRB' or kodebag = 'CUP' or kodebag = 'GB' or kodebag = 'Gdg' or kodebag = 'Log' or kodebag = 'LTM' or kodebag = 'PLM' or
    // 	kodebag = 'PRM' or kodebag = 'PTR' or kodebag = 'PWK' or kodebag = 'QCI' or kodebag = 'SAL' or kodebag = 'SL' or kodebag = 'SMG' or kodebag = 'TG' or kodebag = 'YGY' or
    // 	kodebag = '109' or kodebag = '111' or kodebag = '117' or kodebag = 'ADM' or kodebag = 'BKL' or kodebag = 'DP' or kodebag = 'DPH' or kodebag = 'EXP' or kodebag = 'OBT' or
    // 	kodebag = 'TKN' or kodebag = 'UTL' or kodebag = 'WSH' THEN 999
    //     WHEN kodebag = '1R' and kodedep = 'F&A' THEN 9
    //     WHEN kodebag = '1R' and kodedep = 'GBJ' THEN 28
    //     WHEN kodebag = '1R' and kodedep = 'HRG' THEN 25
    //     WHEN kodebag = '1R' and kodedep = 'IC' THEN 16
    //     WHEN kodebag = '1R' and kodedep = 'Lab' THEN 21
    //     WHEN kodebag = '1R' and kodedep = 'Mkt' THEN 14
    //     WHEN kodebag = '1R' and kodedep = 'PPC' THEN 15
    //     WHEN kodebag = '1R' and kodedep = 'Prd' THEN 18
    //     WHEN kodebag = '1R' and kodedep = 'QA' THEN 999
    //     WHEN kodebag = '1R' and kodedep = 'QMS' THEN 2
    //     WHEN kodebag = '1R' and kodedep = 'Rnd' THEN 20
    //     WHEN kodebag = 'DRV' and kodedep = 'DP' THEN 999
    //     WHEN kodebag = 'DRV' and kodedep = 'Exp' THEN 17
    //     WHEN kodebag = 'DRV' and kodedep = 'F&A' THEN 9
    //     WHEN kodebag = 'FKL' and kodedep = 'GBJ' THEN 28
    //     WHEN kodebag = 'FKL' and kodedep = 'Prd' THEN 18
    //     WHEN kodebag = '1A' THEN 17
    //     WHEN kodebag = '1B' THEN 65
    //     WHEN kodebag = '1C' THEN 55
    //     WHEN kodebag = '1D' THEN 53
    //     WHEN kodebag = '1E' THEN 52
    //     WHEN kodebag = '1F' THEN 52
    //     WHEN kodebag = '1G' THEN 52
    //     WHEN kodebag = '1H' THEN 57
    //     WHEN kodebag = '1I' THEN 59
    //     WHEN kodebag = '1J' THEN 64
    //     WHEN kodebag = '1K' THEN 54
    //     WHEN kodebag = '1KK' THEN 65
    //     WHEN kodebag = '1L' THEN 61
    //     WHEN kodebag = '1M' THEN 63
    //     WHEN kodebag = '1N' THEN 4
    //     WHEN kodebag = '1N1' THEN 12
    //     WHEN kodebag = '1O' THEN 72
    //     WHEN kodebag = '1P' THEN 38
    //     WHEN kodebag = '1P1' THEN 34
    //     WHEN kodebag = '1Q' THEN 49
    //     WHEN kodebag = '1Q1' THEN 50
    //     WHEN kodebag = '1S' THEN 29
    //     WHEN kodebag = '1SS' THEN 28
    //     WHEN kodebag = '1T ' THEN 51
    //     WHEN kodebag = '1U' THEN 33
    //     WHEN kodebag = 'ACC' THEN 40
    //     WHEN kodebag = 'BE' THEN 18
    //     WHEN kodebag = 'BF' THEN 18
    //     WHEN kodebag = 'BG' THEN 18
    //     WHEN kodebag = 'BI' THEN 18
    //     WHEN kodebag = 'BJ' THEN 18
    //     WHEN kodebag = 'BL' THEN 18
    //     WHEN kodebag = 'BLW' THEN 18
    //     WHEN kodebag = 'BTL' THEN 18
    //     WHEN kodebag = 'CCD' THEN 18
    //     WHEN kodebag = 'DCR' THEN 35
    //     WHEN kodebag = 'DD' THEN 999
    //     WHEN kodebag = 'ENG' THEN 4
    //     WHEN kodebag = 'Fin' THEN 39
    //     WHEN kodebag = 'GA' THEN 18
    //     WHEN kodebag = 'GBJ' THEN 28
    //     WHEN kodebag = 'GBK' THEN 30
    //     WHEN kodebag = 'GC' THEN 18
    //     WHEN kodebag = 'GD' THEN 18
    //     WHEN kodebag = 'GDU' THEN 30
    //     WHEN kodebag = 'GG' THEN 27
    //     WHEN kodebag = 'GLN' THEN 18
    //     WHEN kodebag = 'HRD' THEN 24
    //     WHEN kodebag = 'HRG' THEN 25
    //     WHEN kodebag = 'IAU' THEN 36
    //     WHEN kodebag = 'IC' THEN 16
    //     WHEN kodebag = 'IT' THEN 13
    //     WHEN kodebag = 'LAB' THEN 21
    //     WHEN kodebag = 'MGM' THEN 1
    //     WHEN kodebag = 'MKT' THEN 44
    //     WHEN kodebag = 'N1' THEN 10
    //     WHEN kodebag = 'N2' THEN 11
    //     WHEN kodebag = 'PEM' THEN 23
    //     WHEN kodebag = 'PGL' THEN 30
    //     WHEN kodebag = 'PGR' THEN 49
    //     WHEN kodebag = 'PIC' THEN 5
    //     WHEN kodebag = 'PJK' THEN 41
    //     WHEN kodebag = 'PPC' THEN 15
    //     WHEN kodebag = 'PRD' THEN 18
    //     WHEN kodebag = 'QA' THEN 22
    //     WHEN kodebag = 'QC' THEN 22
    //     WHEN kodebag = 'QCD' THEN 66
    //     WHEN kodebag = 'QCS' THEN 68
    //     WHEN kodebag = 'QCT' THEN 67
    //     WHEN kodebag = 'R&D' THEN 20
    //     WHEN kodebag = 'RTR' THEN 69
    //     WHEN kodebag = 'SEC' THEN 37
    //     WHEN kodebag = 'SPL' THEN 26
    //     WHEN kodebag = 'TGL' THEN 30
    //     WHEN kodebag = 'UMM' THEN 38
    //     WHEN kodebag = 'W1' THEN 18
    //     WHEN kodebag = 'W2' THEN 18
    //     WHEN kodebag = 'W3' THEN 18
    //     WHEN kodebag = '' or kodebag IS NULL THEN 999
    //     END AS department_id,

    //     999 AS position_id,

    //     CASE
    //     WHEN agama = 'Islam' THEN 1
    //     WHEN agama = 'Kristen' THEN 2
    //     WHEN agama = 'Hindu' THEN 4
    //     WHEN agama = 'Budha' THEN 5
    //     WHEN agama != '' or agama IS NULL THEN 999
    //     END AS religion_id,

    //     CASE
    //     WHEN Gdara = 'A' THEN 1
    //     WHEN Gdara = 'B' THEN 2
    //     WHEN Gdara = '0' THEN 3
    //     WHEN Gdara = 'AB' THEN 4
    //     WHEN Gdara != '' or Gdara IS NULL THEN 999
    //     END AS blood_type_id,

    //     CASE
    //     WHEN stskawin = 0 THEN 1
    //     WHEN stskawin = 1 THEN 2
    //     WHEN stskawin != '' or stskawin IS NULL THEN 999
    //     END AS marital_status_id,

    //     CASE
    //     WHEN stskontrak = 1 THEN 1
    //     WHEN stskontrak != 1 or stskontrak != '' or stskontrak IS NULL THEN 999
    //     END AS employee_status_id,

    //     CASE
    //     WHEN kodeprs = '001' THEN 1
    //     WHEN kodeprs = '002' THEN 2
    //     WHEN kodeprs != '001' and kodeprs != '002' THEN 999
    //     WHEN kodeprs != '' or kodeprs IS NULL THEN 999
    //     END AS company_id,

    //     CASE
    //     WHEN shift = '001' THEN 1
    //     WHEN shift = '002' THEN 2
    //     WHEN shift = '006' THEN 3
    //     WHEN shift = '007' THEN 4
    //     WHEN shift = '008' THEN 5
    //     WHEN shift = '011' THEN 6
    //     WHEN shift = '1716' THEN 7
    //     WHEN shift = 'Du' THEN 8
    //     WHEN shift = 'He' THEN 9
    //     WHEN shift = 'LT' THEN 10
    //     WHEN shift = 'NS17' THEN 11
    //     WHEN shift = 'NSP2' THEN 12
    //     WHEN shift = 'T1' THEN 13
    //     WHEN shift = '100' THEN 14
    //     WHEN shift = '101' THEN 15
    //     WHEN shift = '102' THEN 16
    //     WHEN shift = '103' THEN 17
    //     WHEN shift = 'Cnt' THEN 18
    //     WHEN shift = 'GBK1' THEN 19
    //     WHEN shift = 'OBAT' THEN 20
    //     WHEN shift = 'QCI2' THEN 21
    //     WHEN shift = 'TGLN' THEN 22
    //     WHEN shift != '' or shift IS NULL THEN 999
    //     END AS office_hour_id,

    //     CASE
    //     WHEN gaji = 'B' THEN 1
    //     WHEN gaji = 'H' THEN 2
    //     WHEN gaji != '' or gaji IS NULL THEN 999
    //     END AS payroll_type_id,

    //     CASE
    //     WHEN jgaji = 1 THEN 1
    //     WHEN jgaji = 2 THEN 2
    //     WHEN jgaji != '' or jgaji IS NULL THEN 999
    //     END AS salary_type_id,
    //     CASE
    //     WHEN jk = 'L' THEN 1
    //     WHEN jk = 'P' THEN 0
    //     END AS gender

    //     from karyawan where nama != '' order by nama");

    //     return $migration;
    // }

    // public function getEmployee()
    // {
    //     $migration = DB::connection('sqlsrv')->select("select stapajak as ptkp_code, nik as nik, nik as old_nik, noktp as ktp, NULL as kk, npwp as npwp, NULL as sim, pin as pin,
    //     nama as name, email as email, nohp as phone, alamat as address, alamatktp as address_ktp,NULL as photo, TmpLahir as birth_place,
    //     CAST(tgl_lahir as date) as birth_at, CAST(tgl_mulai as date) as join_at, CAST(tgl_selesai as date) as leave_at, status as is_active,
    //     CASE
    // 	--SMM
    //     WHEN kodedep = 'BOD' THEN 1
    //     WHEN kodedep = 'BKL' THEN 12
    //     WHEN kodedep = 'EN1' THEN 10
    //     WHEN kodedep = 'Exp' THEN 17
    //     WHEN kodedep = 'IC' THEN 16
    //     WHEN kodedep = 'IT' THEN 13
    //     WHEN kodedep = 'Lab' THEN 7
    //     WHEN kodedep = 'Mkt' THEN 14
    //     WHEN kodedep = 'N2' THEN 11
    //     WHEN kodedep = 'Pem' THEN 23
    //     WHEN kodedep = 'PPC' THEN 15
    //     WHEN kodedep = 'QC' THEN 22
    //     WHEN kodedep = 'Rnd' THEN 20
    //     WHEN kodedep = 'UB' THEN 72
    //     WHEN kodedep = 'WTP' THEN 34
    // 	--SMM & ATM
    //     WHEN kodedep = 'ENG' THEN 4
    //     WHEN kodedep = 'F&A' THEN 9
    //     WHEN kodedep = 'HRG' THEN 25
    //     WHEN kodedep = 'PIC' THEN 5
    //     WHEN kodedep = 'Prd' THEN 18
    //     WHEN kodedep = 'QMS' THEN 2
    // 	--ATM
    //     WHEN kodedep = 'DD ' THEN 999
    //     WHEN kodedep = 'GBJ' THEN 28
    //     WHEN kodedep = 'GBK' THEN 30
    //     WHEN kodedep = 'IAU' THEN 999
    //     WHEN kodedep = 'PRM' THEN 999
    //     WHEN kodedep = 'QA' THEN 999
    // 	--!= SMM & ATM
    //     WHEN kodedep = 'CDV' THEN 999
    //     WHEN kodedep = 'DP ' THEN 999
    //     WHEN kodedep = 'Tek' THEN 999
    //     WHEN kodedep = 'UWB' THEN 999
    //     WHEN kodedep = '' or kodedep IS NULL THEN 999

    //     END AS department_id,

    //     999 AS position_id,

    //     CASE
    //     WHEN agama = 'Islam' THEN 1
    //     WHEN agama = 'Kristen' THEN 2
    //     WHEN agama = 'Hindu' THEN 4
    //     WHEN agama = 'Budha' THEN 5
    //     WHEN agama != '' or agama IS NULL THEN 999
    //     END AS religion_id,

    //     CASE
    //     WHEN Gdara = 'A' THEN 1
    //     WHEN Gdara = 'B' THEN 2
    //     WHEN Gdara = '0' THEN 3
    //     WHEN Gdara = 'AB' THEN 4
    //     WHEN Gdara != '' or Gdara IS NULL THEN 999
    //     END AS blood_type_id,

    //     CASE
    //     WHEN stskawin = 0 THEN 1
    //     WHEN stskawin = 1 THEN 2
    //     WHEN stskawin != '' or stskawin IS NULL THEN 999
    //     END AS marital_status_id,

    //     CASE
    //     WHEN stskontrak = 1 THEN 1
    //     WHEN stskontrak != 1 or stskontrak != '' or stskontrak IS NULL THEN 999
    //     END AS employee_status_id,

    //     CASE
    //     WHEN kodeprs = '001' THEN 1
    //     WHEN kodeprs = '002' THEN 2
    //     WHEN kodeprs != '001' and kodeprs != '002' THEN 999
    //     WHEN kodeprs != '' or kodeprs IS NULL THEN 999
    //     END AS company_id,

    //     CASE
    //     WHEN shift = '001' THEN 1
    //     WHEN shift = '002' THEN 2
    //     WHEN shift = '006' THEN 3
    //     WHEN shift = '007' THEN 4
    //     WHEN shift = '008' THEN 5
    //     WHEN shift = '011' THEN 6
    //     WHEN shift = '1716' THEN 7
    //     WHEN shift = 'Du' THEN 8
    //     WHEN shift = 'He' THEN 9
    //     WHEN shift = 'LT' THEN 10
    //     WHEN shift = 'NS17' THEN 11
    //     WHEN shift = 'NSP2' THEN 12
    //     WHEN shift = 'T1' THEN 13
    //     WHEN shift = '100' THEN 14
    //     WHEN shift = '101' THEN 15
    //     WHEN shift = '102' THEN 16
    //     WHEN shift = '103' THEN 17
    //     WHEN shift = 'Cnt' THEN 18
    //     WHEN shift = 'GBK1' THEN 19
    //     WHEN shift = 'OBAT' THEN 20
    //     WHEN shift = 'QCI2' THEN 21
    //     WHEN shift = 'TGLN' THEN 22
    //     WHEN shift != '' or shift IS NULL THEN 999
    //     END AS office_hour_id,

    //     CASE
    //     WHEN gaji = 'B' THEN 1
    //     WHEN gaji = 'H' THEN 2
    //     WHEN gaji != '' or gaji IS NULL THEN 999
    //     END AS payroll_type_id,

    //     CASE
    //     WHEN jgaji = 1 THEN 1
    //     WHEN jgaji = 2 THEN 2
    //     WHEN jgaji != '' or jgaji IS NULL THEN 999
    //     END AS salary_type_id,
    //     CASE
    //     WHEN jk = 'L' THEN 1
    //     WHEN jk = 'P' THEN 0
    //     END AS gender

    //     from karyawan where nama != '' order by nama ");

    //     return $migration;
    // }
}
