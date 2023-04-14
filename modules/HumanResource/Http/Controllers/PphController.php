<?php

namespace Modules\HumanResource\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

use App\Http\Resources\DataCollection;
use Modules\HumanResource\Models\Pph as Model;
use Modules\HumanResource\Models\PphDetail as Detail;
use Modules\HumanResource\Models\PphParameter as Parameter;
use DB;

class PphController extends Controller
{
    public function index()
    {
        $data = Model::orderBy(request()->order_column, (request()->order_direction == 'true' ? 'DESC' : 'ASC'));
        if (request()->keyword != '') {
            $data = $data->where('name', 'LIKE', '%' . request()->keyword . '%');
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
            'month_id' => 'required',
            'year_id' => 'required',
        ]);

        $month = $request->input('month_id');
        $year = $request->input('year_id');

        DB::beginTransaction();
        try {
            //PROSES PENALTY (INCIDENT)
            $payroll = $this->getPayroll($month, $year);

            if (!empty($payroll)){
                foreach($payroll as $payroll){
                    $data_pph = [
                        'employee_id'       => $payroll->employee_id,
                        'salary'            => '0',
                        'company_allowance' => '0',
                        'bruto'             => '0',
                        'deduction'         => '0',
                        'netto_month'       => '0',
                        'netto_year'        => '0',
                        'ptkp'              => '0',
                        'pkp'               => '0',
                        'pph21_owed_year'   => '0',
                        'pph21_owed_month'  => '0',
                        'month_period'      => $month,
                        'year_period'       => $year,
                    ];

                    $save = Model::create($data_pph);
                    $pphid = $save->id;

                    $parameterpph = $this->getParameterPph();

                    if (!empty($parameterpph)) {
                        foreach ($parameterpph as $parameterpph) {
                            $code = $parameterpph->parameter_code;

                            $data_detail_pph = [
                                'pph_id'        => $save->id,
                                'parameter_id'  => $parameterpph->id,
                                'amount'        => $payroll->$code,
                            ];

                            $savedetail = Detail::create($data_detail_pph);
                        }
                    }

                    if (($payroll->biayajabatan) > 500000){
                        $pphdetailbiayajabatanid = $this->GetBiayaJabatanId($pphid);

                        if (!empty($pphdetailbiayajabatanid)) {
                            foreach ($pphdetailbiayajabatanid as $pphdetailbiayajabatanid) {

                                $detailid = $pphdetailbiayajabatanid->id;

                                $update_biayajabatan = [
                                    'amount'        => '500000',
                                ];

                                Detail::whereId($detailid)->update($update_biayajabatan);
                            }
                        }
                    }

                    $ptkpcode = $payroll->ptkp_code;
                    $pphdetailtunjanganpphid = $this->GetTunjanganPphId($pphid);
                    $pkpsetahun = $this->GetpkpSetahun($pphid, $ptkpcode);

                    if (!empty($pphdetailtunjanganpphid)) {
                        foreach ($pphdetailtunjanganpphid as $pphdetailtunjanganpphid) {
                            $detailid = $pphdetailtunjanganpphid->id;

                            if (!empty($pkpsetahun)) {
                                foreach ($pkpsetahun as $pkpsetahun) {
                                    $pkp = $pkpsetahun->pkp;

                                    if ($pkp <= 47500000){
                                        $pkpsetahunpengurang= $pkp;
                                        $tunjanganpph = (($pkpsetahunpengurang)*(5/95)/12);

                                        $update_tunjanganpph = [
                                            'amount'        => $tunjanganpph,
                                        ];

                                        Detail::whereId($detailid)->update($update_tunjanganpph);

                                    }else if ($pkp > 47500000 and $pkp < 217500000){
                                        $pkpsetahunpengurang= $pkp - 47500000;
                                        $tunjanganpph = ((($pkpsetahunpengurang)*(15/85)+2500000)/12);

                                        $update_tunjanganpph = [
                                            'amount'        => $tunjanganpph,
                                        ];

                                        Detail::whereId($detailid)->update($update_tunjanganpph);

                                    }else if ($pkp > 217500000 and $pkp < 405000000){
                                        $pkpsetahunpengurang= $pkp - 217500000;
                                        $tunjanganpph = ((($pkpsetahunpengurang)*(25/75)+32500000)/12);

                                        $update_tunjanganpph = [
                                            'amount'        => $tunjanganpph,
                                        ];

                                        Detail::whereId($detailid)->update($update_tunjanganpph);

                                    }else if ($pkp > 405000000){
                                        $pkpsetahunpengurang= $pkp - 405000000;
                                        $tunjanganpph = ((($pkpsetahunpengurang)*(30/70)+95000000)/12);

                                        $update_tunjanganpph = [
                                            'amount'        => $tunjanganpph,
                                        ];

                                        Detail::whereId($detailid)->update($update_tunjanganpph);
                                    }
                                }
                            }
                        }
                    }

                    $gajipph = $this->getGajipph($pphid);

                    if (!empty($gajipph)) {
                        foreach ($gajipph as $gajipph) {
                            $update_gaji = [
                                'salary'        => $gajipph->gaji,
                            ];

                            Model::whereId($pphid)->update($update_gaji);
                        }
                    }

                    $jaminanpph = $this->getJaminanpph($pphid);

                    if (!empty($jaminanpph)) {
                        foreach ($jaminanpph as $jaminanpph) {
                            $update_jaminan = [
                                'company_allowance'        => $jaminanpph->jaminan,
                            ];

                            Model::whereId($pphid)->update($update_jaminan);
                        }
                    }

                    $brutopph = $this->getBrutopph($pphid);

                    if (!empty($brutopph)) {
                        foreach ($brutopph as $brutopph) {
                            $update_bruto = [
                                'bruto'        => $brutopph->bruto,
                            ];

                            Model::whereId($pphid)->update($update_bruto);
                        }
                    }

                    $potonganpph = $this->getPotonganpph($pphid);

                    if (!empty($potonganpph)) {
                        foreach ($potonganpph as $potonganpph) {
                            $update_potongan = [
                                'deduction'        => $potonganpph->potongan,
                            ];

                            Model::whereId($pphid)->update($update_potongan);
                        }
                    }

                    $ptkpcode = $payroll->ptkp_code;
                    $ptkp = $this->GetPtkp($ptkpcode);

                    if (!empty($ptkp)) {
                        foreach ($ptkp as $ptkp) {
                            $jumlahptkp = [
                                'ptkp'        => $ptkp->amount_ptkp,
                            ];

                            Model::whereId($pphid)->update($jumlahptkp);
                        }
                    }

                    $pkppph = $this->getPkppph($pphid);

                    if (!empty($pkppph)) {
                        foreach ($pkppph as $pkppph) {
                            $update_pkp = [
                                'pph21_owed_month'  => $pkppph->nettosebulan,
                                'pph21_owed_year'   => $pkppph->nettosetahun,
                                'pkp'               => $pkppph->pkp,
                            ];

                            Model::whereId($pphid)->update($update_pkp);

                            $pkp = $pkppph->pkp;

                            if ($pkp <= 50000000){
                                $pph21setahun = (0.05*$pkp);
                                $pph21sebulan = (0.05*$pkp)/12;

                                $update_pph21 = [
                                    'pph21_owed_year'  => $pph21setahun,
                                    'pph21_owed_month'  => $pph21sebulan,
                                ];

                                Model::whereId($pphid)->update($update_pph21);

                            }else if($pkp > 50000000 and $pkp <= 250000000){
                                $pph21setahun = ((0.15*($pkp-50000000))+2500000);
                                $pph21sebulan = ((0.15*($pkp-50000000))+2500000)/12;

                                $update_pph21 = [
                                    'pph21_owed_year'  => $pph21setahun,
                                    'pph21_owed_month'  => $pph21sebulan,
                                ];

                                Model::whereId($pphid)->update($update_pph21);

                            }else if($pkp > 250000000 and $pkp <= 500000000){
                                $pph21setahun = ((0.25*($pkp-250000000))+32500000);
                                $pph21sebulan = ((0.25*($pkp-250000000))+32500000)/12;

                                $update_pph21 = [
                                    'pph21_owed_year'  => $pph21setahun,
                                    'pph21_owed_month'  => $pph21sebulan,
                                ];

                                Model::whereId($pphid)->update($update_pph21);

                            }else if($pkp > 500000000){
                                $pph21setahun = ((0.30*($pkp-500000000))+95000000);
                                $pph21sebulan = ((0.30*($pkp-500000000))+95000000)/12;

                                $update_pph21 = [
                                    'pph21_owed_year'  => $pph21setahun,
                                    'pph21_owed_month'  => $pph21sebulan,
                                ];

                                Model::whereId($pphid)->update($update_pph21);

                            }
                        }
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
        $data = Model::findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $data], Response::HTTP_OK);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
        ]);

        $data = Model::findOrFail($id);
        $data->update($request->all());
        return response()->json(['status' => 'success'], Response::HTTP_OK);
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

    public function getParameterPph()
    {
        $parameterpph = DB::select('SELECT * FROM pph_parameters');

        return $parameterpph;
    }

    public function GetBiayaJabatanId($pphid)
    {
        $pphdetailbiayajabatanid = DB::select('select id from pph_details where pph_id = :pphid and parameter_id = (select id from pph_parameters where parameter_code = :biayajabatan)',
         array('pphid' => $pphid, 'biayajabatan' => 'biayajabatan'));

        return $pphdetailbiayajabatanid;
    }

    public function GetTunjanganPphId($pphid)
    {
        $pphdetailtunjanganpphid = DB::select('select id from pph_details where pph_id = :pphid and parameter_id = (select id from pph_parameters where parameter_code = :tunjanganpph)',
         array('pphid' => $pphid, 'tunjanganpph' => 'tunjanganpph'));

        return $pphdetailtunjanganpphid;
    }

    public function GetpkpSetahun($pphid, $ptkpcode)
    {
        $pkpsetahun = DB::select('select (if ((coalesce((12*((SELECT sum(amount) as bruto FROM pph_details b WHERE b.pph_id =
        a.pph_id and b.parameter_id in (select c.id from pph_parameters c where c.status_parameter_pph = :gaji or
         c.status_parameter_pph = :jaminan)) - (SELECT sum(amount) as bruto FROM pph_details d WHERE d.pph_id = :pphid and
         d.parameter_id in (select e.id from pph_parameters e where e.status_parameter_pph = :potongan))))-
         (select amount_ptkp from ptkps where code = :ptkpcode))) < 0, 0, (coalesce((12*((SELECT sum(amount) as bruto
         FROM pph_details b WHERE b.pph_id = a.pph_id and b.parameter_id in (select c.id from pph_parameters c where
          c.status_parameter_pph = :gaji1 or c.status_parameter_pph = :jaminan1)) - (SELECT sum(amount) as bruto
           FROM pph_details d WHERE d.pph_id = :pphid1 and d.parameter_id in (select e.id from pph_parameters e where
           e.status_parameter_pph = :potongan1))))-(select amount_ptkp from ptkps where code = :ptkpcode1))))) as pkp
        from pph_details a where a.pph_id = :pphid2 limit 1',
         array('pphid' => $pphid, 'ptkpcode' => $ptkpcode, 'gaji' => 'gaji', 'jaminan' => 'jaminan', 'potongan' => 'potongan',
        'pphid1' => $pphid, 'ptkpcode1' => $ptkpcode, 'gaji1' => 'gaji', 'jaminan1' => 'jaminan', 'potongan1' => 'potongan',
        'pphid2' => $pphid
        ));

        return $pkpsetahun;
    }

    public function getGajipph($pphid)
    {
        $gajipph = DB::select('SELECT COALESCE(SUM(amount),0) as gaji FROM pph_details a WHERE a.pph_id = :idpph and
         a.parameter_id in (select b.id from pph_parameters b where b.status_parameter_pph = :statusgaji)',
         array('idpph' => $pphid, 'statusgaji' => 'gaji'));

        return $gajipph;
    }

    public function getJaminanpph($pphid)
    {
        $jaminanpph = DB::select('SELECT COALESCE(SUM(amount),0) as jaminan FROM pph_details a WHERE a.pph_id = :idpph and
        a.parameter_id in (select b.id from pph_parameters b where b.status_parameter_pph = :statusgaji)',
         array('idpph' => $pphid, 'statusgaji' => 'jaminan'));

        return $jaminanpph;
    }

    public function getBrutopph($pphid)
    {
        $brutopph = DB::select('SELECT COALESCE(SUM(amount),0) as bruto FROM pph_details a WHERE a.pph_id = :idpph and
         a.parameter_id in (select b.id from pph_parameters b where b.status_parameter_pph = :statusgaji or
         b.status_parameter_pph = :statusgaji1)',
         array('idpph' => $pphid, 'statusgaji' => 'gaji', 'statusgaji1' => 'jaminan'));

        return $brutopph;
    }

    public function getPotonganpph($pphid)
    {
        $potonganpph = DB::select('SELECT COALESCE(SUM(amount),0) as potongan FROM pph_details a WHERE a.pph_id = :idpph and
         a.parameter_id in (select b.id from pph_parameters b where b.status_parameter_pph = :statusgaji)',
         array('idpph' => $pphid, 'statusgaji' => 'potongan'));

        return $potonganpph;
    }

    public function GetPtkp($ptkpcode)
    {
        $ptkp = DB::select('select amount_ptkp from ptkps where code = :ptkpcode',
         array('ptkpcode' => $ptkpcode));

        return $ptkp;
    }

    public function getPkppph($pphid)
    {
        $pkppph = DB::select('select (bruto-deduction) as nettosebulan,
        (12*(bruto-deduction)) as nettosetahun,
        (IF(((12*(bruto-deduction))-(ptkp))<0,0,((12*(bruto-deduction))-(ptkp)))) as pkp,
        (round((0.05)*(IF(((12*(bruto-deduction))-(ptkp))<0,0,((12*(bruto-deduction))-(ptkp)))),0)) as pph21setahun,
        round(((round((0.05)*(IF(((12*(bruto-deduction))-(ptkp))<0,0,((12*(bruto-deduction))-(ptkp)))),0))/12),0) as pph21sebulan
        from pphs where id = :pphid',
        array('pphid' => $pphid));

        return $pkppph;
    }

    public function getPayroll($month, $year)
    {
        $payroll = DB::select('SELECT a.employee_id, b.ptkp_code, 0 AS tunjanganpph, SUM(a.salary) AS jumlahgapok, SUM(a.wage) AS premihadir, 0 AS premiprod, 0 AS uangjabatan,
        SUM(a.total_overtime) AS totallembur, 0 AS tlain, 0 AS bonus,

        sum(a.salary+a.total_overtime+a.wage) as gajikotor,

        SUM(a.bpjs_tk_company) AS bpjstkper, SUM(a.pension_company) AS pensiunper, SUM(a.bpjs_kes_company) AS bpjskesper,
        SUM(a.bpjs_tk_employee) AS bpjstkkar, SUM(a.pension_employee) AS pensiunkar, SUM(a.bpjs_kes_employee) AS bpjskeskar,

        FLOOR(0.05*((sum(a.salary))+(sum(a.wage))+(sum(a.total_overtime))+
        (sum(coalesce(a.bpjs_tk_company,0)))+(sum(coalesce(a.pension_company,0)))+sum(coalesce(a.bpjs_kes_company,0)))) as biayajabatan,

        sum(coalesce(a.bpjs_tk_employee,0)) as bpjstkkar,
        sum(coalesce(a.pension_employee,0)) as pensiunkar,
        sum(coalesce(a.bpjs_kes_employee,0)) as bpjskeskar,
        sum(coalesce(a.cooperative_deduction,0)) as potkop,
        0 as potlain

        FROM payrolls a INNER JOIN employees b ON a.employee_id=b.id
        WHERE a.payroll_period_id IN (SELECT id FROM payroll_periods WHERE MONTH(date_created) = :bulan AND YEAR(date_created) = :tahun) AND b.ptkp_code IS NOT NULL
        GROUP BY a.employee_id',
        array('bulan'=>$month, 'tahun'=>$year));

        return $payroll;
    }
}
