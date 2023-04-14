<?php

namespace Modules\HumanResource\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

use App\Http\Resources\DataCollection;
use Modules\HumanResource\Models\Payroll as Model;
use Modules\HumanResource\Models\PayrollPeriod as Period;
use Modules\HumanResource\Models\Penalty;
use Modules\HumanResource\Models\IncidentPenalty;
use Modules\HumanResource\Models\Disbursement;
use Modules\HumanResource\Models\Reimburse;
use Modules\HumanResource\Models\LoanTransaction;
use Modules\HumanResource\Models\SavingTransaction;
use Modules\HumanResource\Models\Cooperative;

use DB;

class PayrollController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Model::select(['payrolls.*', 'employees.name as employee'])
            ->join('employees', 'employees.id', '=', 'payrolls.employee_id');

        $data->orderBy(request()->order_column, (request()->order_direction == 'true' ? 'DESC' : 'ASC'));

        if (request()->keyword != '') {
            $data = $data->where('employees.name', 'LIKE', '%' . request()->keyword . '%');
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
            'start_period' => 'required',
            'end_period' => 'required',
            'date_created' => 'required',
            'company_id' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $dateprocess = date('Y-m-d', strtotime($request->input('date_created')));
            $startperiod = date('Y-m-d', strtotime($request->input('start_period')));
            $endperiod = date('Y-m-d', strtotime($request->input('end_period')));
            $companyid = $request->input('company_id');

            //CEK DATA PAYROLL SUDAH DI PROSES ATAU BELUM SESUAI FILTER BY COMPANY, PERIODE AWAL, PERIODE AKHIR DAN TANGGAL GAJIAN
            $payroll = $this->checkPayroll($companyid, $startperiod, $endperiod, $dateprocess);

            if (empty($payroll))
            {
                //PROSES PENALTY (INCIDENT)
                $penalty = $this->getPenalty();

                if (!empty($penalty)){
                    foreach($penalty as $penalty){
                        $penaltyid = $penalty->id;
                        $totalpenalty = $penalty->penalty;
                        $installment = $penalty->installment_count;
                        $total = $totalpenalty/$installment;

                        //cek data sudah ada atau belum di database
                        $cekpenalty = $this->checkPenalty($penaltyid, $dateprocess);

                        if (empty($cekpenalty)){
                            $amountpenalty = $this->getAmountPenalty($penaltyid);

                            foreach($amountpenalty as $amountpenalty){
                                $amount = $amountpenalty->amount;

                                if ($amount < $totalpenalty){
                                    $data_penalty = [
                                        'incident_penalty_id' => $penaltyid,
                                        'amount' => $total,
                                        'date_process' => $dateprocess,
                                        'is_active' => '1',
                                    ];

                                    Penalty::create($data_penalty);
                                }
                            }
                        }

                        $updatestatus = $this->UpdateStatusIncidentPenalty();

                        if (!empty($updatestatus))
                        {
                            foreach($updatestatus as $updatestatus)
                            {
                                $id = $updatestatus->id;

                                $data = [
                                    'is_active' => $updatestatus->is_active,
                                ];

                                IncidentPenalty::whereId($id)->update($data);
                            }
                        }
                    }
                }

                //PROSES DATA PENCAIRAN REIMBURSE (DISBURSEMENT)
                $reimburse = $this->getReimburse();

                if (!empty($reimburse)){
                    foreach($reimburse as $reimburse){
                        $reimburseid = $reimburse->id;

                        //cek data sudah ada atau belum di database
                        $disbursement = $this->checkDisbursement($reimburseid, $dateprocess);

                        if (empty($disbursement))
                        {
                            $data_reimbruse = [
                                'reimburse_id' => $reimburse->id,
                                'amount' => $reimburse->total_cost,
                                'date_process' => $dateprocess,
                                'is_active' => '1',
                            ];

                            Disbursement::create($data_reimbruse);

                            $data = [
                                'is_active' => '0',
                            ];

                            Reimburse::whereId($reimburse->id)->update($data);
                        }
                    }
                }

                //PROSES DATA PAYROLL
                $period = [
                    'company_id' => $request->input('company_id'),
                    'start_period' => date('Y-m-d', strtotime($request->input('start_period'))),
                    'end_period' => date('Y-m-d', strtotime($request->input('end_period'))),
                    'date_created' => date('Y-m-d', strtotime($request->input('date_created'))),
                    'is_active' => '1',
                ];

                $periode = Period::create($period);

                $payroll = $this->getPayroll($companyid, $startperiod, $endperiod, $dateprocess);

                if (!empty($payroll)) {
                    foreach($payroll as $payroll){
                        $data = [
                            'payroll_period_id' => $periode->id,
                            'employee_id' => $payroll->id,
                            'total_work' => $payroll->total_work,
                            'salary' => $payroll->salary,
                            'wage' => $payroll->wage,
                            'production_allowance' => '0',
                            'position_allowance' => '0',
                            'bonus' => '0',
                            'total_salary' => $payroll->total_salary,
                            'h' => $payroll->h,
                            's' => $payroll->s,
                            'i' => $payroll->i,
                            'a' => $payroll->a,
                            'c' => $payroll->c,
                            'off' => $payroll->off,
                            'st' => $payroll->st,
                            'late_less' => $payroll->late_less,
                            'late_more' => $payroll->late_more,
                            'half_day' => $payroll->half_day,
                            'amount_s' => $payroll->amount_s,
                            'amount_i' => $payroll->amount_i,
                            'amount_a' => $payroll->amount_a,
                            'amount_c' => $payroll->amount_c,
                            'amount_off' => $payroll->amount_off,
                            'amount_st' => $payroll->amount_st,
                            'amount_late_less' => $payroll->amount_late_less,
                            'amount_late_more' => $payroll->amount_late_more,
                            'amount_half_day' => $payroll->amount_half_day,
                            'total_deduction_attendance' => $payroll->total_deduction_attendance,
                            'count_overtime_1' => $payroll->count_overtime_1,
                            'amount_overtime_1' => $payroll->amount_overtime_1,
                            'overtime_1' => $payroll->overtime_1,
                            'count_overtime_2' => $payroll->count_overtime_2,
                            'amount_overtime_2' => $payroll->amount_overtime_2,
                            'overtime_2' => $payroll->overtime_2,
                            'count_overtime_2_weekend' => $payroll->count_overtime_2_weekend,
                            'amount_overtime_2_weekend' => $payroll->amount_overtime_2_weekend,
                            'overtime_2_weekend' => $payroll->overtime_2_weekend,
                            'count_overtime_3_weekend' => $payroll->count_overtime_3_weekend,
                            'amount_overtime_3_weekend' => $payroll->amount_overtime_3_weekend,
                            'overtime_3_weekend' => $payroll->overtime_3_weekend,
                            'total_overtime' => $payroll->total_overtime,
                            'reimburse' => $payroll->reimburse,
                            'bruto' => $payroll->bruto,
                            'bpjs_tk_company' => $payroll->bpjstk_company,
                            'bpjs_kes_company' => $payroll->bpjskes_company,
                            'pension_company' => $payroll->pension_company,
                            'cooperative_deduction' => $payroll->cooperative,
                            'bpjs_tk_employee' => $payroll->bpjs_tk_employee,
                            'bpjs_kes_employee' => $payroll->bpjs_kes_employee,
                            'pension_employee' => $payroll->pension_employee,
                            'other_deduction' => '0',
                            'penalty' => $payroll->penalty,
                            'total_deduction' => $payroll->total_deduction,
                            'netto' => $payroll->netto,
                            'start_period' => date('Y-m-d', strtotime($request->input('start_period'))),
                            'end_period' => date('Y-m-d', strtotime($request->input('end_period'))),
                            'is_active' => '1',
                        ];

                        Model::create($data);
                    }
                }

                //PROSES DATA PINJAMAN DAN SIMPANAN POKOK KOPERASI
                $loansaving = $this->getLoanSaving($companyid);

                if (!empty($loansaving)){
                    foreach($loansaving as $loansaving){
                        $employeeid = $loansaving->employee_id;
                        $loan = $loansaving->loan;
                        $saving = $loansaving->saving;
                        $loanbalance = $loansaving->loan_balance;
                        $savingbalance = $loansaving->saving_balance;
                        $installmentpayment = $loansaving->installment_payment;
                        $installmentcount = $loansaving->installment_count;

                        if ($loan != 0){
                            $data_loan = [
                                'employee_id' => $employeeid,
                                'cooperative_description_id' => '6',
                                'amount' => $loan,
                                'is_active' => '1',
                            ];

                            LoanTransaction::create($data_loan);
                        }

                        if ($saving != 0){
                            $data_saving = [
                                'employee_id' => $employeeid,
                                'cooperative_description_id' => '2',
                                'amount' => $saving,
                                'is_active' => '1',
                            ];

                            SavingTransaction::create($data_saving);
                        }

                        $data_cooperative = [
                            'loan_balance' => $loanbalance,
                            'saving_balance' => $savingbalance,
                            'installment_payment' => $installmentpayment,
                            'installment_count' => $installmentcount,
                            'is_active' => '1',
                        ];

                        Cooperative::whereId($loansaving->id)->update($data_cooperative);
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Model::findOrFail($id);
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
        // $this->validate($request, [
        //     'employee_id' => 'required',
        //     'leave_category_id' => 'required',
        // ]);

        // $data = [
        //     'leave_category_id' => $request->input('leave_category_id'),
        //     'employee_id' => $request->input('employee_id'),
        //     'total' => $request->input('total'),
        //     'start_date' => date('Y-m-d', strtotime($request->input('start_date'))),
        //     'end_date' => date('Y-m-d', strtotime($request->input('end_date'))),
        //     'is_active' => $request->input('is_active'),
        // ];

        // Model::whereId($id)->update($data);

        // return response()->json(['status' => 'success'], Response::HTTP_OK);
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

    public function getLoanSaving($companyid)
    {
        $loansaving = DB::select('SELECT id, a.employee_id,
        a.installment_payment AS loan,
        (SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1) AS saving,
        ((SELECT COALESCE(SUM(amount),0) FROM saving_transactions WHERE (employee_id = a.employee_id) AND (cooperative_description_id = 2 OR cooperative_description_id = 3))-
        (SELECT COALESCE(SUM(amount),0) FROM saving_transactions WHERE (employee_id = a.employee_id) AND (cooperative_description_id = 4))) AS saving_balance,
        ((SELECT COALESCE(SUM(amount),0) FROM loan_transactions WHERE employee_id = a.employee_id AND cooperative_description_id = 5)-
        (SELECT COALESCE(SUM(amount),0) FROM loan_transactions WHERE employee_id = a.employee_id AND cooperative_description_id = 6)) AS loan_balance,
        CASE
        WHEN
        ((((SELECT COALESCE(SUM(amount),0) FROM loan_transactions WHERE employee_id = a.employee_id AND cooperative_description_id = 5)-
        (SELECT COALESCE(SUM(amount),0) FROM loan_transactions WHERE employee_id = a.employee_id AND cooperative_description_id = 6)))=0)
        THEN
        0
        WHEN
        ((((SELECT COALESCE(SUM(amount),0) FROM loan_transactions WHERE employee_id = a.employee_id AND cooperative_description_id = 5)-
        (SELECT COALESCE(SUM(amount),0) FROM loan_transactions WHERE employee_id = a.employee_id AND cooperative_description_id = 6)))!=0)
        THEN
        a.installment_payment
        END AS installment_payment,
        CASE
        WHEN
        ((((SELECT COALESCE(SUM(amount),0) FROM loan_transactions WHERE employee_id = a.employee_id AND cooperative_description_id = 5)-
        (SELECT COALESCE(SUM(amount),0) FROM loan_transactions WHERE employee_id = a.employee_id AND cooperative_description_id = 6)))=0)
        THEN
        0
        WHEN
        ((((SELECT COALESCE(SUM(amount),0) FROM loan_transactions WHERE employee_id = a.employee_id AND cooperative_description_id = 5)-
        (SELECT COALESCE(SUM(amount),0) FROM loan_transactions WHERE employee_id = a.employee_id AND cooperative_description_id = 6)))!=0)
        THEN
        a.installment_count
        END AS installment_count

        FROM cooperatives a
        WHERE a.employee_id IN (SELECT id FROM employees WHERE company_id = :companyid)',
        array('companyid'=>$companyid));

        return $loansaving;
    }

    public function getReimburse()
    {
        $reimburse = DB::select('SELECT id, total_cost FROM reimburses WHERE (id NOT IN (SELECT reimburse_id FROM disbursements))
        AND (reimburse_type = :payroll) AND (approval_status_id = 3 OR approval_status_id = 2)  AND (is_active = 1) AND ISNULL(deleted_at)',
        array('payroll'=>'payroll'));

        return $reimburse;
    }

    public function checkDisbursement($reimburseid, $dateprocess)
    {
        $disbursement = DB::select('SELECT * FROM disbursements WHERE reimburse_id = :reimburseid AND date_process = :dateprocess',
        array('reimburseid'=>$reimburseid, 'dateprocess'=>$dateprocess));

        return $disbursement;
    }

    public function getPenalty()
    {
        $penalty = DB::select('SELECT b.id, a.employee_id, a.lost_cost, b.interrogation_report_id, b.penalty, b.installment_count
        FROM interrogation_reports a INNER JOIN incident_penalties b ON a.id=b.interrogation_report_id
        WHERE b.is_active = 1 AND b.penalty != 0 AND b.installment_count != 0  AND ISNULL(a.deleted_at) AND ISNULL(a.deleted_at)');

        return $penalty;
    }

    public function checkPenalty($penaltyid, $dateprocess)
    {
        $cekpenalty = DB::select('SELECT id FROM penalties WHERE incident_penalty_id = :penaltyid AND date_process = :dateprocess',
        array('penaltyid'=>$penaltyid, 'dateprocess'=>$dateprocess));

        return $cekpenalty;
    }

    public function getAmountPenalty($penaltyid)
    {
        $amountpenalty = DB::select('SELECT COALESCE(SUM(amount),0) AS amount FROM penalties WHERE incident_penalty_id = :penaltyid',
        array('penaltyid'=>$penaltyid));

        return $amountpenalty;
    }

    public function UpdateStatusIncidentPenalty()
    {
        $updatestatus = DB::select('SELECT a.id, a.penalty, (SELECT COALESCE(SUM(amount),0) FROM penalties WHERE incident_penalty_id = a.id) AS penalty_paid,
        CASE
        WHEN
        (a.penalty) = ((SELECT COALESCE(SUM(amount),0) FROM penalties WHERE incident_penalty_id = a.id))
        THEN
        0
        WHEN
        (a.penalty) > ((SELECT COALESCE(SUM(amount),0) FROM penalties WHERE incident_penalty_id = a.id))
        THEN
        1
        END AS is_active
        FROM incident_penalties a WHERE a.is_active = 1');

        return $updatestatus;
    }

    public function checkPayroll($companyid, $startperiod, $endperiod, $dateprocess)
    {
        $cekpenalty = DB::select('SELECT * FROM payroll_periods WHERE company_id = :companyid
        AND start_period = :startperiod AND end_period = :endperiod AND date_created = :dateprocess',
        array('companyid'=>$companyid, 'startperiod'=>$startperiod, 'endperiod'=>$endperiod, 'dateprocess'=>$dateprocess));

        return $cekpenalty;
    }

    public function getPayroll($companyid, $startperiod, $endperiod, $dateprocess)
    {
        $payroll = DB::select('SELECT a.id, a.company_id,
        (SELECT salary FROM employees WHERE id=a.id) AS salary,
        (SELECT wage FROM employees WHERE id=a.id) AS wage,
        (SELECT salary FROM employees WHERE id=a.id)+(SELECT wage FROM employees WHERE id=a.id) AS total_salary,

        (SELECT COUNT(b.id) FROM attendances b WHERE (b.employee_id = a.id) AND (STATUS <> :libur1)
        AND (date_work BETWEEN :startperiod1 AND :endperiod1)) AS total_work,

        (SELECT COUNT(b.id) FROM attendances b WHERE (b.employee_id = a.id) AND (STATUS <> :libur2)
        AND (STATUS <> :sickleave1 AND STATUS <> :ijin1 AND STATUS <> :alpa1
        AND STATUS <> :annualleave1 AND STATUS <> :officialleave1)
        AND (date_work BETWEEN :startperiod2 AND :endperiod2)) AS h,

        (SELECT COUNT(b.id) FROM attendances b WHERE (b.employee_id = a.id) AND (STATUS = :sickleave2) AND (date_work BETWEEN :startperiod3 AND :endperiod3)) AS s,
        (SELECT COUNT(b.id) FROM attendances b WHERE (b.employee_id = a.id) AND (STATUS = :ijin2) AND (date_work BETWEEN :startperiod4 AND :endperiod4)) AS i,
        (SELECT COUNT(b.id) FROM attendances b WHERE (b.employee_id = a.id) AND (STATUS = :alpa2) AND (date_work BETWEEN :startperiod5 AND :endperiod5)) AS a,
        (SELECT COUNT(b.id) FROM attendances b WHERE (b.employee_id = a.id) AND (STATUS = :annualleave2 OR STATUS = :officialleave2) AND (date_work BETWEEN :startperiod6 AND :endperiod6)) AS c,
        (SELECT COUNT(b.id) FROM attendances b WHERE (b.employee_id = a.id) AND (STATUS = :off1) AND (date_work BETWEEN :startperiod7 AND :endperiod7)) AS off,
        (SELECT COUNT(b.id) FROM attendances b WHERE (b.employee_id = a.id) AND (STATUS = :sakit1) AND (date_work BETWEEN :startperiod8 AND :endperiod8)) AS st,
        (SELECT COUNT(b.id) FROM attendances b WHERE (b.employee_id = a.id) AND (STATUS = :lateless1) AND (date_work BETWEEN :startperiod9 AND :endperiod9)) AS late_less,
        (SELECT COUNT(b.id) FROM attendances b WHERE (b.employee_id = a.id) AND (STATUS = :latemore1) AND (date_work BETWEEN :startperiod10 AND :endperiod10)) AS late_more,
        (SELECT COUNT(b.id) FROM attendances b WHERE (b.employee_id = a.id) AND (STATUS = :halfday1) AND (date_work BETWEEN :startperiod11 AND :endperiod11)) AS half_day,

        FLOOR(((SELECT wage FROM employees WHERE id=a.id)/26)*
        (SELECT COUNT(b.id) FROM attendances b WHERE (b.employee_id = a.id) AND (STATUS = :sickleave3) AND (date_work BETWEEN :startperiod12 AND :endperiod12))) AS amount_s,

        FLOOR((((SELECT salary FROM employees WHERE id=a.id)+(SELECT wage FROM employees WHERE id=a.id))/26)*
        (SELECT COUNT(b.id) FROM attendances b WHERE (b.employee_id = a.id) AND (STATUS = :ijin3) AND (date_work BETWEEN :startperiod13 AND :endperiod13))) AS amount_i,

        FLOOR((((SELECT salary FROM employees WHERE id=a.id)+(SELECT wage FROM employees WHERE id=a.id))/26)*
        (SELECT COUNT(b.id) FROM attendances b WHERE (b.employee_id = a.id) AND (STATUS = :alpa3) AND (date_work BETWEEN :startperiod14 AND :endperiod14))) AS amount_a,

        (0*(SELECT COUNT(b.id) FROM attendances b WHERE (b.employee_id = a.id) AND (STATUS = :annualleave3 OR STATUS = :officialleave3)
        AND (date_work BETWEEN :startperiod15 AND :endperiod15))) AS amount_c,

        FLOOR((((SELECT salary FROM employees WHERE id=a.id)+(SELECT wage FROM employees WHERE id=a.id))/26)*
        (SELECT COUNT(b.id) FROM attendances b WHERE (b.employee_id = a.id) AND (STATUS = :off2) AND (date_work BETWEEN :startperiod16 AND :endperiod16))) AS amount_off,

        FLOOR((((SELECT salary FROM employees WHERE id=a.id)+(SELECT wage FROM employees WHERE id=a.id))/26)*
        (SELECT COUNT(b.id) FROM attendances b WHERE (b.employee_id = a.id) AND (STATUS = :sakit2) AND (date_work BETWEEN :startperiod17 AND :endperiod17))) AS amount_st,

        (0*(SELECT COUNT(b.id) FROM attendances b WHERE (b.employee_id = a.id) AND (STATUS = :lateless2) AND (date_work BETWEEN :startperiod18 AND :endperiod18))) AS amount_late_less,

        FLOOR(((SELECT salary FROM employees WHERE id=a.id)/26/2)*
        (SELECT COUNT(b.id) FROM attendances b WHERE (b.employee_id = a.id) AND (STATUS = :latemore2) AND (date_work BETWEEN :startperiod19 AND :endperiod19))) AS amount_late_more,

        FLOOR(((SELECT salary FROM employees WHERE id=a.id)/26/2)*
        (SELECT COUNT(b.id) FROM attendances b WHERE (b.employee_id = a.id) AND (STATUS = :halfday2) AND (date_work BETWEEN :startperiod20 AND :endperiod20))) AS amount_half_day,

        (FLOOR(((SELECT wage FROM employees WHERE id=a.id)/26)*
        (SELECT COUNT(b.id) FROM attendances b WHERE (b.employee_id = a.id) AND (STATUS = :sickleave5) AND (date_work BETWEEN :startperiod21 AND :endperiod21)))+
        FLOOR((((SELECT salary FROM employees WHERE id=a.id)+(SELECT wage FROM employees WHERE id=a.id))/26)*
        (SELECT COUNT(b.id) FROM attendances b WHERE (b.employee_id = a.id) AND (STATUS = :ijin5) AND (date_work BETWEEN :startperiod22 AND :endperiod22)))+
        FLOOR((((SELECT salary FROM employees WHERE id=a.id)+(SELECT wage FROM employees WHERE id=a.id))/26)*
        (SELECT COUNT(b.id) FROM attendances b WHERE (b.employee_id = a.id) AND (STATUS = :alpa5) AND (date_work BETWEEN :startperiod23 AND :endperiod23)))+
        FLOOR((((SELECT salary FROM employees WHERE id=a.id)+(SELECT wage FROM employees WHERE id=a.id))/26)*
        (SELECT COUNT(b.id) FROM attendances b WHERE (b.employee_id = a.id) AND (STATUS = :off4) AND (date_work BETWEEN :startperiod24 AND :endperiod24)))+
        FLOOR((((SELECT salary FROM employees WHERE id=a.id)+(SELECT wage FROM employees WHERE id=a.id))/26)*
        (SELECT COUNT(b.id) FROM attendances b WHERE (b.employee_id = a.id) AND (STATUS = :sakit4) AND (date_work BETWEEN :startperiod25 AND :endperiod25)))+
        FLOOR(((SELECT salary FROM employees WHERE id=a.id)/26/2)*
        (SELECT COUNT(b.id) FROM attendances b WHERE (b.employee_id = a.id) AND (STATUS = :latemore4) AND (date_work BETWEEN :startperiod26 AND :endperiod26)))+
        FLOOR(((SELECT salary FROM employees WHERE id=a.id)/26/2)*
        (SELECT COUNT(b.id) FROM attendances b WHERE (b.employee_id = a.id) AND (STATUS = :halfday4) AND (date_work BETWEEN :startperiod27 AND :endperiod27))))
        AS total_deduction_attendance,

        (SELECT COALESCE(SUM(overtime_1), 0) FROM overtime_payrolls WHERE (employee_id = a.id) AND (date_overtime BETWEEN :startperiod28 AND :endperiod28)) AS count_overtime_1,
        FLOOR(((SELECT salary FROM employees WHERE id=a.id)*1.5)/173) AS amount_overtime_1,
        ((SELECT COALESCE(SUM(overtime_1), 0) FROM overtime_payrolls WHERE (employee_id = a.id) AND (date_overtime BETWEEN :startperiod29 AND :endperiod29))*
        FLOOR(((SELECT salary FROM employees WHERE id=a.id)*1.5)/173)) AS overtime_1,

        (SELECT COALESCE(SUM(overtime_2), 0) FROM overtime_payrolls WHERE (employee_id = a.id) AND (date_overtime BETWEEN :startperiod30 AND :endperiod30)) AS count_overtime_2,
        FLOOR(((SELECT salary FROM employees WHERE id=a.id)*2)/173) AS amount_overtime_2,
        ((SELECT COALESCE(SUM(overtime_2), 0) FROM overtime_payrolls WHERE (employee_id = a.id) AND (date_overtime BETWEEN :startperiod31 AND :endperiod31))*
        FLOOR(((SELECT salary FROM employees WHERE id=a.id)*2)/173)) AS overtime_2,

        (SELECT COALESCE(SUM(overtime_weekend_2), 0) FROM overtime_payrolls WHERE (employee_id = a.id) AND (date_overtime BETWEEN :startperiod32 AND :endperiod32)) AS count_overtime_2_weekend,
        FLOOR(((SELECT salary FROM employees WHERE id=a.id)*2)/173) AS amount_overtime_2_weekend,
        ((SELECT COALESCE(SUM(overtime_weekend_2), 0) FROM overtime_payrolls WHERE (employee_id = a.id) AND (date_overtime BETWEEN :startperiod33 AND :endperiod33))*
        FLOOR(((SELECT salary FROM employees WHERE id=a.id)*2)/173)) AS overtime_2_weekend,

        (SELECT COALESCE(SUM(overtime_weekend_3), 0) FROM overtime_payrolls WHERE (employee_id = a.id) AND (date_overtime BETWEEN :startperiod34 AND :endperiod34)) AS count_overtime_3_weekend,
        FLOOR(((SELECT salary FROM employees WHERE id=a.id)*3)/173) AS amount_overtime_3_weekend,
        ((SELECT COALESCE(SUM(overtime_weekend_3), 0) FROM overtime_payrolls WHERE (employee_id = a.id) AND (date_overtime BETWEEN :startperiod35 AND :endperiod35))*
        FLOOR(((SELECT salary FROM employees WHERE id=a.id)*3)/173)) AS overtime_3_weekend,

        (((SELECT COALESCE(SUM(overtime_1), 0) FROM overtime_payrolls WHERE (employee_id = a.id) AND (date_overtime BETWEEN :startperiod36 AND :endperiod36))*
        FLOOR(((SELECT salary FROM employees WHERE id=a.id)*1.5)/173))+
        ((SELECT COALESCE(SUM(overtime_2), 0) FROM overtime_payrolls WHERE (employee_id = a.id) AND (date_overtime BETWEEN :startperiod37 AND :endperiod37))*
        FLOOR(((SELECT salary FROM employees WHERE id=a.id)*2)/173))+
        ((SELECT COALESCE(SUM(overtime_weekend_2), 0) FROM overtime_payrolls WHERE (employee_id = a.id) AND (date_overtime BETWEEN :startperiod38 AND :endperiod38))*
        FLOOR(((SELECT salary FROM employees WHERE id=a.id)*2)/173))+
        ((SELECT COALESCE(SUM(overtime_weekend_3), 0) FROM overtime_payrolls WHERE (employee_id = a.id) AND (date_overtime BETWEEN :startperiod39 AND :endperiod39))*
        FLOOR(((SELECT salary FROM employees WHERE id=a.id)*3)/173))) AS total_overtime,

        (SELECT coalesce(SUM(amount),0) AS amount FROM disbursements WHERE date_process = :datecreated1 AND reimburse_id IN
        (SELECT id FROM reimburses WHERE employee_id = a.id)) AS reimburse,

        (SELECT salary FROM employees WHERE id=a.id)+
        (SELECT wage FROM employees WHERE id=a.id)+
        (SELECT coalesce(SUM(amount),0) AS amount FROM disbursements WHERE date_process = :datecreated2 AND reimburse_id IN
        (SELECT id FROM reimburses WHERE employee_id = a.id))+
        (((SELECT COALESCE(SUM(overtime_1), 0) FROM overtime_payrolls WHERE (employee_id = a.id) AND (date_overtime BETWEEN :startperiod40 AND :endperiod40))*
        FLOOR(((SELECT salary FROM employees WHERE id=a.id)*1.5)/173))+
        ((SELECT COALESCE(SUM(overtime_2), 0) FROM overtime_payrolls WHERE (employee_id = a.id) AND (date_overtime BETWEEN :startperiod41 AND :endperiod41))*
        FLOOR(((SELECT salary FROM employees WHERE id=a.id)*2)/173))+
        ((SELECT COALESCE(SUM(overtime_weekend_2), 0) FROM overtime_payrolls WHERE (employee_id = a.id) AND (date_overtime BETWEEN :startperiod42 AND :endperiod42))*
        FLOOR(((SELECT salary FROM employees WHERE id=a.id)*2)/173))+
        ((SELECT COALESCE(SUM(overtime_weekend_3), 0) FROM overtime_payrolls WHERE (employee_id = a.id) AND (date_overtime BETWEEN :startperiod43 AND :endperiod43))*
        FLOOR(((SELECT salary FROM employees WHERE id=a.id)*3)/173))) AS bruto,


        COALESCE((SELECT (installment_payment)+(SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1) FROM cooperatives WHERE employee_id = a.id),0) AS cooperative,
        0 AS bpjstk_company,
        0 AS bpjskes_company,
        0 AS pension_company,
        (SELECT bpjstk FROM insurances ORDER BY id DESC LIMIT 1) AS bpjs_tk_employee,
        (SELECT bpjskes FROM insurances ORDER BY id DESC LIMIT 1) AS bpjs_kes_employee,
        0 AS pension_employee,

        (SELECT coalesce(SUM(amount),0) AS amount FROM penalties WHERE date_process = :datecreated3 AND incident_penalty_id IN
        (SELECT id FROM incident_penalties WHERE interrogation_report_id IN (SELECT id FROM interrogation_reports WHERE employee_id = a.id))) AS penalty,

        ((FLOOR(((SELECT wage FROM employees WHERE id=a.id)/26)*
        (SELECT COUNT(b.id) FROM attendances b WHERE (b.employee_id = a.id) AND (STATUS = :sickleave6) AND (date_work BETWEEN :startperiod44 AND :endperiod44)))+
        FLOOR((((SELECT salary FROM employees WHERE id=a.id)+(SELECT wage FROM employees WHERE id=a.id))/26)*
        (SELECT COUNT(b.id) FROM attendances b WHERE (b.employee_id = a.id) AND (STATUS = :ijin6) AND (date_work BETWEEN :startperiod45 AND :endperiod45)))+
        FLOOR((((SELECT salary FROM employees WHERE id=a.id)+(SELECT wage FROM employees WHERE id=a.id))/26)*
        (SELECT COUNT(b.id) FROM attendances b WHERE (b.employee_id = a.id) AND (STATUS = :alpa6) AND (date_work BETWEEN :startperiod46 AND :endperiod46)))+
        FLOOR((((SELECT salary FROM employees WHERE id=a.id)+(SELECT wage FROM employees WHERE id=a.id))/26)*
        (SELECT COUNT(b.id) FROM attendances b WHERE (b.employee_id = a.id) AND (STATUS = :off5) AND (date_work BETWEEN :startperiod47 AND :endperiod47)))+
        FLOOR((((SELECT salary FROM employees WHERE id=a.id)+(SELECT wage FROM employees WHERE id=a.id))/26)*
        (SELECT COUNT(b.id) FROM attendances b WHERE (b.employee_id = a.id) AND (STATUS = :sakit5) AND (date_work BETWEEN :startperiod48 AND :endperiod48)))+
        FLOOR(((SELECT salary FROM employees WHERE id=a.id)/26/2)*
        (SELECT COUNT(b.id) FROM attendances b WHERE (b.employee_id = a.id) AND (STATUS = :latemore5) AND (date_work BETWEEN :startperiod49 AND :endperiod49)))+
        FLOOR(((SELECT salary FROM employees WHERE id=a.id)/26/2)*
        (SELECT COUNT(b.id) FROM attendances b WHERE (b.employee_id = a.id) AND (STATUS = :halfday5) AND (date_work BETWEEN :startperiod50 AND :endperiod50))))+
        (SELECT bpjstk FROM insurances ORDER BY id DESC LIMIT 1)+
        (SELECT bpjskes FROM insurances ORDER BY id DESC LIMIT 1)+
        (SELECT coalesce(SUM(amount),0) AS amount FROM penalties WHERE date_process = :datecreated4 AND incident_penalty_id IN
        (SELECT id FROM incident_penalties WHERE interrogation_report_id IN (SELECT id FROM interrogation_reports WHERE employee_id = a.id)))+
        (COALESCE((SELECT (installment_payment)+(SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1) FROM cooperatives WHERE employee_id = a.id),0))) AS total_deduction,

        ((SELECT salary FROM employees WHERE id=a.id)+
        (SELECT wage FROM employees WHERE id=a.id)+
        (SELECT coalesce(SUM(amount),0) AS amount FROM disbursements WHERE date_process = :datecreated5 AND reimburse_id IN
        (SELECT id FROM reimburses WHERE employee_id = a.id))+
        (((SELECT COALESCE(SUM(overtime_1), 0) FROM overtime_payrolls WHERE (employee_id = a.id) AND (date_overtime BETWEEN :startperiod51 AND :endperiod51))*
        FLOOR(((SELECT salary FROM employees WHERE id=a.id)*1.5)/173))+
        ((SELECT COALESCE(SUM(overtime_2), 0) FROM overtime_payrolls WHERE (employee_id = a.id) AND (date_overtime BETWEEN :startperiod52 AND :endperiod52))*
        FLOOR(((SELECT salary FROM employees WHERE id=a.id)*2)/173))+
        ((SELECT COALESCE(SUM(overtime_weekend_2), 0) FROM overtime_payrolls WHERE (employee_id = a.id) AND (date_overtime BETWEEN :startperiod53 AND :endperiod53))*
        FLOOR(((SELECT salary FROM employees WHERE id=a.id)*2)/173))+
        ((SELECT COALESCE(SUM(overtime_weekend_3), 0) FROM overtime_payrolls WHERE (employee_id = a.id) AND (date_overtime BETWEEN :startperiod54 AND :endperiod54))*
        FLOOR(((SELECT salary FROM employees WHERE id=a.id)*3)/173)))
        -
        ((FLOOR(((SELECT wage FROM employees WHERE id=a.id)/26)*
        (SELECT COUNT(b.id) FROM attendances b WHERE (b.employee_id = a.id) AND (STATUS = :sickleave7) AND (date_work BETWEEN :startperiod55 AND :endperiod55)))+
        FLOOR((((SELECT salary FROM employees WHERE id=a.id)+(SELECT wage FROM employees WHERE id=a.id))/26)*
        (SELECT COUNT(b.id) FROM attendances b WHERE (b.employee_id = a.id) AND (STATUS = :ijin7) AND (date_work BETWEEN :startperiod56 AND :endperiod56)))+
        FLOOR((((SELECT salary FROM employees WHERE id=a.id)+(SELECT wage FROM employees WHERE id=a.id))/26)*
        (SELECT COUNT(b.id) FROM attendances b WHERE (b.employee_id = a.id) AND (STATUS = :alpa7) AND (date_work BETWEEN :startperiod57 AND :endperiod57)))+
        FLOOR((((SELECT salary FROM employees WHERE id=a.id)+(SELECT wage FROM employees WHERE id=a.id))/26)*
        (SELECT COUNT(b.id) FROM attendances b WHERE (b.employee_id = a.id) AND (STATUS = :off6) AND (date_work BETWEEN :startperiod58 AND :endperiod58)))+
        FLOOR((((SELECT salary FROM employees WHERE id=a.id)+(SELECT wage FROM employees WHERE id=a.id))/26)*
        (SELECT COUNT(b.id) FROM attendances b WHERE (b.employee_id = a.id) AND (STATUS = :sakit6) AND (date_work BETWEEN :startperiod59 AND :endperiod59)))+
        FLOOR(((SELECT salary FROM employees WHERE id=a.id)/26/2)*
        (SELECT COUNT(b.id) FROM attendances b WHERE (b.employee_id = a.id) AND (STATUS = :latemore6) AND (date_work BETWEEN :startperiod60 AND :endperiod60)))+
        FLOOR(((SELECT salary FROM employees WHERE id=a.id)/26/2)*
        (SELECT COUNT(b.id) FROM attendances b WHERE (b.employee_id = a.id) AND (STATUS = :halfday6) AND (date_work BETWEEN :startperiod61 AND :endperiod61))))+
        (SELECT bpjstk FROM insurances ORDER BY id DESC LIMIT 1)+
        (SELECT bpjskes FROM insurances ORDER BY id DESC LIMIT 1)+
        (SELECT coalesce(SUM(amount),0) AS amount FROM penalties WHERE date_process = :datecreated6 AND incident_penalty_id IN
        (SELECT id FROM incident_penalties WHERE interrogation_report_id IN (SELECT id FROM interrogation_reports WHERE employee_id = a.id)))+
        (COALESCE((SELECT (installment_payment)+(SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1) FROM cooperatives WHERE employee_id = a.id),0)))) AS netto

        FROM employees a WHERE a.company_id = :companyid',

        array('companyid'=>$companyid,'libur1'=>'Libur', 'libur2'=>'Libur',
        'sickleave1'=>'Sick Leave', 'sickleave2'=>'Sick Leave','sickleave3'=>'Sick Leave', 'sickleave5'=>'Sick Leave', 'sickleave6'=>'Sick Leave', 'sickleave7'=>'Sick Leave',
        'ijin1'=>'Ijin', 'ijin2'=>'Ijin','ijin3'=>'Ijin', 'ijin5'=>'Ijin', 'ijin6'=>'Ijin', 'ijin7'=>'Ijin',
        'alpa1'=>'Alpa', 'alpa2'=>'Alpa','alpa3'=>'Alpa', 'alpa5'=>'Alpa', 'alpa6'=>'Alpa', 'alpa7'=>'Alpa',
        'annualleave1'=>'Annual Leave', 'annualleave2'=>'Annual Leave','annualleave3'=>'Annual Leave',
        'officialleave1'=>'Official Leave', 'officialleave2'=>'Official Leave','officialleave3'=>'Official Leave',
        'off1'=>'Off', 'off2'=>'Off', 'off4'=>'Off', 'off5'=>'Off', 'off6'=>'Off',
        'sakit1'=>'Sakit', 'sakit2'=>'Sakit', 'sakit4'=>'Sakit', 'sakit5'=>'Sakit', 'sakit6'=>'Sakit',
        'lateless1'=>'Telat < 2 Jam','lateless2'=>'Telat < 2 Jam',
        'latemore1'=>'Telat > 2 Jam', 'latemore2'=>'Telat > 2 Jam', 'latemore4'=>'Telat > 2 Jam', 'latemore5'=>'Telat > 2 Jam', 'latemore6'=>'Telat > 2 Jam',
        'halfday1'=>'Setengah Hari', 'halfday2'=>'Setengah Hari', 'halfday4'=>'Setengah Hari', 'halfday5'=>'Setengah Hari', 'halfday6'=>'Setengah Hari',

        'startperiod1'=>$startperiod, 'endperiod1'=>$endperiod, 'startperiod2'=>$startperiod, 'endperiod2'=>$endperiod, 'startperiod3'=>$startperiod, 'endperiod3'=>$endperiod,
        'startperiod4'=>$startperiod, 'endperiod4'=>$endperiod, 'startperiod5'=>$startperiod, 'endperiod5'=>$endperiod, 'startperiod6'=>$startperiod, 'endperiod6'=>$endperiod,
        'startperiod7'=>$startperiod, 'endperiod7'=>$endperiod, 'startperiod8'=>$startperiod, 'endperiod8'=>$endperiod, 'startperiod9'=>$startperiod, 'endperiod9'=>$endperiod,
        'startperiod10'=>$startperiod, 'endperiod10'=>$endperiod, 'startperiod11'=>$startperiod, 'endperiod11'=>$endperiod, 'startperiod12'=>$startperiod, 'endperiod12'=>$endperiod,
        'startperiod13'=>$startperiod, 'endperiod13'=>$endperiod, 'startperiod14'=>$startperiod, 'endperiod14'=>$endperiod, 'startperiod15'=>$startperiod, 'endperiod15'=>$endperiod,
        'startperiod16'=>$startperiod, 'endperiod16'=>$endperiod, 'startperiod17'=>$startperiod, 'endperiod17'=>$endperiod, 'startperiod18'=>$startperiod, 'endperiod18'=>$endperiod,
        'startperiod19'=>$startperiod, 'endperiod19'=>$endperiod, 'startperiod20'=>$startperiod, 'endperiod20'=>$endperiod, 'startperiod21'=>$startperiod, 'endperiod21'=>$endperiod,
        'startperiod22'=>$startperiod, 'endperiod22'=>$endperiod, 'startperiod23'=>$startperiod, 'endperiod23'=>$endperiod, 'startperiod24'=>$startperiod, 'endperiod24'=>$endperiod,
        'startperiod25'=>$startperiod, 'endperiod25'=>$endperiod, 'startperiod26'=>$startperiod, 'endperiod26'=>$endperiod, 'startperiod27'=>$startperiod, 'endperiod27'=>$endperiod,
        'startperiod28'=>$startperiod, 'endperiod28'=>$endperiod, 'startperiod29'=>$startperiod, 'endperiod29'=>$endperiod, 'startperiod30'=>$startperiod, 'endperiod30'=>$endperiod,
        'startperiod31'=>$startperiod, 'endperiod31'=>$endperiod, 'startperiod32'=>$startperiod, 'endperiod32'=>$endperiod, 'startperiod33'=>$startperiod, 'endperiod33'=>$endperiod,
        'startperiod34'=>$startperiod, 'endperiod34'=>$endperiod, 'startperiod35'=>$startperiod, 'endperiod35'=>$endperiod, 'startperiod36'=>$startperiod, 'endperiod36'=>$endperiod,
        'startperiod37'=>$startperiod, 'endperiod37'=>$endperiod, 'startperiod38'=>$startperiod, 'endperiod38'=>$endperiod, 'startperiod39'=>$startperiod, 'endperiod39'=>$endperiod,
        'startperiod40'=>$startperiod, 'endperiod40'=>$endperiod, 'startperiod41'=>$startperiod, 'endperiod41'=>$endperiod, 'startperiod42'=>$startperiod, 'endperiod42'=>$endperiod,
        'startperiod43'=>$startperiod, 'endperiod43'=>$endperiod, 'startperiod44'=>$startperiod, 'endperiod44'=>$endperiod, 'startperiod45'=>$startperiod, 'endperiod45'=>$endperiod,
        'startperiod46'=>$startperiod, 'endperiod46'=>$endperiod, 'startperiod47'=>$startperiod, 'endperiod47'=>$endperiod, 'startperiod48'=>$startperiod, 'endperiod48'=>$endperiod,
        'startperiod49'=>$startperiod, 'endperiod49'=>$endperiod, 'startperiod50'=>$startperiod, 'endperiod50'=>$endperiod, 'startperiod51'=>$startperiod, 'endperiod51'=>$endperiod,
        'startperiod52'=>$startperiod, 'endperiod52'=>$endperiod, 'startperiod53'=>$startperiod, 'endperiod53'=>$endperiod, 'startperiod54'=>$startperiod, 'endperiod54'=>$endperiod,
        'startperiod55'=>$startperiod, 'endperiod55'=>$endperiod, 'startperiod56'=>$startperiod, 'endperiod56'=>$endperiod, 'startperiod57'=>$startperiod, 'endperiod57'=>$endperiod,
        'startperiod58'=>$startperiod, 'endperiod58'=>$endperiod, 'startperiod59'=>$startperiod, 'endperiod59'=>$endperiod, 'startperiod60'=>$startperiod, 'endperiod60'=>$endperiod,
        'startperiod61'=>$startperiod, 'endperiod61'=>$endperiod,
        'datecreated1'=>$dateprocess, 'datecreated2'=>$dateprocess, 'datecreated3'=>$dateprocess, 'datecreated4'=>$dateprocess, 'datecreated5'=>$dateprocess, 'datecreated6'=>$dateprocess));

        return $payroll;
    }

    // public function getPayroll($company)
    // {
    //     $payroll = DB::select('SELECT a.id, a.company_id,
    //     FLOOR((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)/2) AS salary,
    //     FLOOR((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)/2) AS wage,
    //     (FLOOR((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)/2)+FLOOR((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)/2)) AS total_salary,

    //     (SELECT COUNT(b.id) FROM attendances b WHERE b.employee_id = a.id AND STATUS <> :libur1) AS total_work,

    //     (SELECT COUNT(b.id) FROM attendances b WHERE b.employee_id = a.id AND STATUS <> :libur2
    //     AND STATUS <> :sickleave1 AND STATUS <> :ijin1 AND STATUS <> :alpa1
    //     AND STATUS <> :annualleave1 AND STATUS <> :officialleave1) AS h,

    //     (SELECT COUNT(b.id) FROM attendances b WHERE b.employee_id = a.id AND STATUS = :sickleave2) AS s,
    //     (SELECT COUNT(b.id) FROM attendances b WHERE b.employee_id = a.id AND STATUS = :ijin2) AS i,
    //     (SELECT COUNT(b.id) FROM attendances b WHERE b.employee_id = a.id AND STATUS = :alpa2) AS a,
    //     (SELECT COUNT(b.id) FROM attendances b WHERE (b.employee_id = a.id) AND (STATUS = :annualleave2 OR STATUS = :officialleave2)) AS c,
    //     (SELECT COUNT(b.id) FROM attendances b WHERE b.employee_id = a.id AND STATUS = :off1) AS off,
    //     (SELECT COUNT(b.id) FROM attendances b WHERE b.employee_id = a.id AND STATUS = :sakit1) AS st,
    //     (SELECT COUNT(b.id) FROM attendances b WHERE b.employee_id = a.id AND STATUS = :lateless1) AS late_less,
    //     (SELECT COUNT(b.id) FROM attendances b WHERE b.employee_id = a.id AND STATUS = :latemore1) AS late_more,
    //     (SELECT COUNT(b.id) FROM attendances b WHERE b.employee_id = a.id AND STATUS = :halfday1) AS half_day,

    //     FLOOR(((FLOOR((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)/2)/26)*
    //     (SELECT COUNT(b.id) FROM attendances b WHERE b.employee_id = a.id AND STATUS = :sickleave3))) AS amount_s,

    //     FLOOR(((FLOOR((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)/2)+
    //     FLOOR((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)/2))/26)*
    //     (SELECT COUNT(b.id) FROM attendances b WHERE b.employee_id = a.id AND STATUS = :ijin3)) AS amount_i,

    //     FLOOR(((FLOOR((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)/2)+
    //     FLOOR((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)/2))/26)*
    //     (SELECT COUNT(b.id) FROM attendances b WHERE b.employee_id = a.id AND STATUS = :alpa3)) AS amount_a,

    //     (0*(SELECT COUNT(b.id) FROM attendances b WHERE (b.employee_id = a.id) AND (STATUS = :annualleave3 OR STATUS = :officialleave3))) AS amount_c,

    //     FLOOR(((FLOOR((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)/2)+
    //     FLOOR((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)/2))/26)*
    //     (SELECT COUNT(b.id) FROM attendances b WHERE b.employee_id = a.id AND STATUS = :off2)) AS amount_off,

    //     FLOOR(((FLOOR((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)/2)+
    //     FLOOR((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)/2))/26)*
    //     (SELECT COUNT(b.id) FROM attendances b WHERE b.employee_id = a.id AND STATUS = :sakit2)) AS amount_st,

    //     (0*(SELECT COUNT(b.id) FROM attendances b WHERE b.employee_id = a.id AND STATUS = :lateless2)) AS amount_late_less,

    //     FLOOR((FLOOR((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)/2)/26/2)*
    //     (SELECT COUNT(b.id) FROM attendances b WHERE b.employee_id = a.id AND STATUS = :latemore2)) AS amount_late_more,

    //     FLOOR((FLOOR((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)/2)/26/2)*
    //     (SELECT COUNT(b.id) FROM attendances b WHERE b.employee_id = a.id AND STATUS = :halfday2)) AS amount_half_day,

    //     (FLOOR(((FLOOR((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)/2)/26)*
    //     (SELECT COUNT(b.id) FROM attendances b WHERE b.employee_id = a.id AND STATUS = :sickleave4)))+
    //     FLOOR(((FLOOR((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)/2)+
    //     FLOOR((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)/2))/26)*
    //     (SELECT COUNT(b.id) FROM attendances b WHERE b.employee_id = a.id AND STATUS = :ijin4))+
    //     FLOOR(((FLOOR((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)/2)+
    //     FLOOR((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)/2))/26)*
    //     (SELECT COUNT(b.id) FROM attendances b WHERE b.employee_id = a.id AND STATUS = :alpa4))+
    //     FLOOR(((FLOOR((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)/2)+
    //     FLOOR((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)/2))/26)*
    //     (SELECT COUNT(b.id) FROM attendances b WHERE b.employee_id = a.id AND STATUS = :off3))+
    //     FLOOR(((FLOOR((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)/2)+
    //     FLOOR((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)/2))/26)*
    //     (SELECT COUNT(b.id) FROM attendances b WHERE b.employee_id = a.id AND STATUS = :sakit3))+
    //     FLOOR((FLOOR((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)/2)/26/2)*
    //     (SELECT COUNT(b.id) FROM attendances b WHERE b.employee_id = a.id AND STATUS = :latemore3))+
    //     FLOOR((FLOOR((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)/2)/26/2)*
    //     (SELECT COUNT(b.id) FROM attendances b WHERE b.employee_id = a.id AND STATUS = :halfday3))) AS total_deduction_attendance,

    //     (SELECT COALESCE(SUM(overtime_1), 0) FROM overtime_payrolls WHERE employee_id = a.id) AS count_overtime_1,
    //     FLOOR(((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)*1.5)/173) AS amount_overtime_1,
    //     ((SELECT COALESCE(SUM(overtime_1), 0) FROM overtime_payrolls WHERE employee_id = a.id)*
    //     FLOOR(((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)*1.5)/173)) AS overtime_1,

    //     (SELECT COALESCE(SUM(overtime_2), 0) FROM overtime_payrolls WHERE employee_id = a.id) AS count_overtime_2,
    //     FLOOR(((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)*2)/173) AS amount_overtime_2,
    //     ((SELECT COALESCE(SUM(overtime_2), 0) FROM overtime_payrolls WHERE employee_id = a.id)*
    //     FLOOR(((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)*2)/173)) AS overtime_2,

    //     (SELECT COALESCE(SUM(overtime_weekend_2), 0) FROM overtime_payrolls WHERE employee_id = a.id) AS count_overtime_2_weekend,
    //     FLOOR(((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)*2)/173) AS amount_overtime_2_weekend,
    //     ((SELECT COALESCE(SUM(overtime_weekend_2), 0) FROM overtime_payrolls WHERE employee_id = a.id)*
    //     FLOOR(((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)*2)/173)) AS overtime_2_weekend,

    //     (SELECT COALESCE(SUM(overtime_weekend_3), 0) FROM overtime_payrolls WHERE employee_id = a.id) AS count_overtime_3_weekend,
    //     FLOOR(((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)*3)/173) AS amount_overtime_3_weekend,
    //     ((SELECT COALESCE(SUM(overtime_weekend_3), 0) FROM overtime_payrolls WHERE employee_id = a.id)*
    //     FLOOR(((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)*3)/173)) AS overtime_3_weekend,

    //     (((SELECT COALESCE(SUM(overtime_1), 0) FROM overtime_payrolls WHERE employee_id = a.id)*
    //     FLOOR(((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)*1.5)/173))+
    //     ((SELECT COALESCE(SUM(overtime_2), 0) FROM overtime_payrolls WHERE employee_id = a.id)*
    //     FLOOR(((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)*2)/173))+
    //     ((SELECT COALESCE(SUM(overtime_weekend_2), 0) FROM overtime_payrolls WHERE employee_id = a.id)*
    //     FLOOR(((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)*2)/173))+
    //     ((SELECT COALESCE(SUM(overtime_weekend_3), 0) FROM overtime_payrolls WHERE employee_id = a.id)*
    //     FLOOR(((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)*3)/173))) AS total_overtime,

    //     ((FLOOR((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)/2)+FLOOR((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)/2))+
    //     (((SELECT COALESCE(SUM(overtime_1), 0) FROM overtime_payrolls WHERE employee_id = a.id)*
    //     FLOOR(((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)*1.5)/173))+
    //     ((SELECT COALESCE(SUM(overtime_2), 0) FROM overtime_payrolls WHERE employee_id = a.id)*
    //     FLOOR(((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)*2)/173))+
    //     ((SELECT COALESCE(SUM(overtime_weekend_2), 0) FROM overtime_payrolls WHERE employee_id = a.id)*
    //     FLOOR(((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)*2)/173))+
    //     ((SELECT COALESCE(SUM(overtime_weekend_3), 0) FROM overtime_payrolls WHERE employee_id = a.id)*
    //     FLOOR(((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)*3)/173)))) AS bruto,

    //     0 AS cooperatives,
    //     0 AS bpjstk_company,
    //     0 AS bpjskes_company,
    //     0 AS pension_company,
    //     (SELECT bpjstk FROM insurances ORDER BY id DESC LIMIT 1) AS bpjs_tk_employee,
    //     (SELECT bpjskes FROM insurances ORDER BY id DESC LIMIT 1) AS bpjs_kes_employee,
    //     0 AS pension_employee,

    //     ((FLOOR(((FLOOR((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)/2)/26)*
    //     (SELECT COUNT(b.id) FROM attendances b WHERE b.employee_id = a.id AND STATUS = :sickleave5)))+
    //     FLOOR(((FLOOR((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)/2)+
    //     FLOOR((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)/2))/26)*
    //     (SELECT COUNT(b.id) FROM attendances b WHERE b.employee_id = a.id AND STATUS = :ijin5))+
    //     FLOOR(((FLOOR((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)/2)+
    //     FLOOR((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)/2))/26)*
    //     (SELECT COUNT(b.id) FROM attendances b WHERE b.employee_id = a.id AND STATUS = :alpa5))+
    //     FLOOR(((FLOOR((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)/2)+
    //     FLOOR((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)/2))/26)*
    //     (SELECT COUNT(b.id) FROM attendances b WHERE b.employee_id = a.id AND STATUS = :off4))+
    //     FLOOR(((FLOOR((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)/2)+
    //     FLOOR((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)/2))/26)*
    //     (SELECT COUNT(b.id) FROM attendances b WHERE b.employee_id = a.id AND STATUS = :sakit4))+
    //     FLOOR((FLOOR((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)/2)/26/2)*
    //     (SELECT COUNT(b.id) FROM attendances b WHERE b.employee_id = a.id AND STATUS = :latemore4))+
    //     FLOOR((FLOOR((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)/2)/26/2)*
    //     (SELECT COUNT(b.id) FROM attendances b WHERE b.employee_id = a.id AND STATUS = :halfday4)))+
    //     (SELECT bpjstk FROM insurances ORDER BY id DESC LIMIT 1)+
    //     (SELECT bpjskes FROM insurances ORDER BY id DESC LIMIT 1)) AS total_deduction,

    //     (FLOOR((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)/2)+
    //     FLOOR((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)/2)+
    //     0+
    //     (((SELECT COALESCE(SUM(overtime_1), 0) FROM overtime_payrolls WHERE employee_id = a.id)*
    //     FLOOR(((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)*1.5)/173))+
    //     ((SELECT COALESCE(SUM(overtime_2), 0) FROM overtime_payrolls WHERE employee_id = a.id)*
    //     FLOOR(((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)*2)/173))+
    //     ((SELECT COALESCE(SUM(overtime_weekend_2), 0) FROM overtime_payrolls WHERE employee_id = a.id)*
    //     FLOOR(((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)*2)/173))+
    //     ((SELECT COALESCE(SUM(overtime_weekend_3), 0) FROM overtime_payrolls WHERE employee_id = a.id)*
    //     FLOOR(((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)*3)/173)))-
    //     ((FLOOR(((FLOOR((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)/2)/26)*
    //     (SELECT COUNT(b.id) FROM attendances b WHERE b.employee_id = a.id AND STATUS = :sickleave6)))+
    //     FLOOR(((FLOOR((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)/2)+
    //     FLOOR((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)/2))/26)*
    //     (SELECT COUNT(b.id) FROM attendances b WHERE b.employee_id = a.id AND STATUS = :ijin6))+
    //     FLOOR(((FLOOR((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)/2)+
    //     FLOOR((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)/2))/26)*
    //     (SELECT COUNT(b.id) FROM attendances b WHERE b.employee_id = a.id AND STATUS = :alpa6))+
    //     FLOOR(((FLOOR((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)/2)+
    //     FLOOR((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)/2))/26)*
    //     (SELECT COUNT(b.id) FROM attendances b WHERE b.employee_id = a.id AND STATUS = :off5))+
    //     FLOOR(((FLOOR((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)/2)+
    //     FLOOR((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)/2))/26)*
    //     (SELECT COUNT(b.id) FROM attendances b WHERE b.employee_id = a.id AND STATUS = :sakit5))+
    //     FLOOR((FLOOR((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)/2)/26/2)*
    //     (SELECT COUNT(b.id) FROM attendances b WHERE b.employee_id = a.id AND STATUS = :latemore5))+
    //     FLOOR((FLOOR((SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1)/2)/26/2)*
    //     (SELECT COUNT(b.id) FROM attendances b WHERE b.employee_id = a.id AND STATUS = :halfday5)))+
    //     (SELECT bpjstk FROM insurances ORDER BY id DESC LIMIT 1)+
    //     (SELECT bpjskes FROM insurances ORDER BY id DESC LIMIT 1))) AS netto

    //     FROM employees a WHERE a.company_id = :companyid',

    // array('companyid'=>$company,'libur1'=>'Libur', 'libur2'=>'Libur',
    // 'sickleave1'=>'Sick Leave', 'sickleave2'=>'Sick Leave','sickleave3'=>'Sick Leave', 'sickleave4'=>'Sick Leave', 'sickleave5'=>'Sick Leave', 'sickleave6'=>'Sick Leave',
    // 'ijin1'=>'Ijin', 'ijin2'=>'Ijin','ijin3'=>'Ijin', 'ijin4'=>'Ijin', 'ijin5'=>'Ijin', 'ijin6'=>'Ijin',
    // 'alpa1'=>'Alpa', 'alpa2'=>'Alpa','alpa3'=>'Alpa', 'alpa4'=>'Alpa', 'alpa5'=>'Alpa', 'alpa6'=>'Alpa',
    // 'annualleave1'=>'Annual Leave', 'annualleave2'=>'Annual Leave','annualleave3'=>'Annual Leave',
    // 'officialleave1'=>'Official Leave', 'officialleave2'=>'Official Leave','officialleave3'=>'Official Leave',
    // 'off1'=>'Off', 'off2'=>'Off','off3'=>'Off', 'off4'=>'Off', 'off5'=>'Off',
    // 'sakit1'=>'Sakit', 'sakit2'=>'Sakit','sakit3'=>'Sakit', 'sakit4'=>'Sakit', 'sakit5'=>'Sakit',
    // 'lateless1'=>'Telat < 2 Jam','lateless2'=>'Telat < 2 Jam',
    // 'latemore1'=>'Telat > 2 Jam', 'latemore2'=>'Telat > 2 Jam','latemore3'=>'Telat > 2 Jam', 'latemore4'=>'Telat > 2 Jam', 'latemore5'=>'Telat > 2 Jam',
    // 'halfday1'=>'Setengah Hari', 'halfday2'=>'Setengah Hari','halfday3'=>'Setengah Hari', 'halfday4'=>'Setengah Hari', 'halfday5'=>'Setengah Hari'));

    //     return $payroll;
    // }
}
