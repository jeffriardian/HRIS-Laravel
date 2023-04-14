<?php

namespace Modules\HumanResource\Database\seeds;

use Illuminate\Database\Seeder;
use Modules\HumanResource\Models\PphParameter as Model;

class PphParameterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = now();
        $data = [
            ['id'=>1,'parameter_code'=>'jumlahgapok','nama_parameter_pph'=>'Gaji Pokok','tipe_pph'=>'Pendapatan','status_parameter_pph'=>'Gaji','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>2,'parameter_code'=>'premihadir','nama_parameter_pph'=>'Premi Hadir','tipe_pph'=>'Pendapatan','status_parameter_pph'=>'Gaji','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>3,'parameter_code'=>'premiprod','nama_parameter_pph'=>'Premi Produksi','tipe_pph'=>'Pendapatan','status_parameter_pph'=>'Gaji','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>4,'parameter_code'=>'uangjabatan','nama_parameter_pph'=>'Tunjangan Jabatan','tipe_pph'=>'Pendapatan','status_parameter_pph'=>'Gaji','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>5,'parameter_code'=>'totallembur','nama_parameter_pph'=>'Lembur','tipe_pph'=>'Pendapatan','status_parameter_pph'=>'Gaji','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>6,'parameter_code'=>'tlain','nama_parameter_pph'=>'Tunjangan Lain','tipe_pph'=>'Pendapatan','status_parameter_pph'=>'Gaji','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>7,'parameter_code'=>'bonus','nama_parameter_pph'=>'Bonus','tipe_pph'=>'Pendapatan','status_parameter_pph'=>'Gaji','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>8,'parameter_code'=>'bpjstkper','nama_parameter_pph'=>'BPJS Tenaga Kerja (Perusahaan)','tipe_pph'=>'Pendapatan','status_parameter_pph'=>'Jaminan','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>9,'parameter_code'=>'pensiunper','nama_parameter_pph'=>'Jaminan Pensiun (Perusahaan)','tipe_pph'=>'Pendapatan','status_parameter_pph'=>'Jaminan','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>10,'parameter_code'=>'bpjskesper','nama_parameter_pph'=>'BPJS Kesehatan (Perusahaan)','tipe_pph'=>'Pendapatan','status_parameter_pph'=>'Jaminan','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>11,'parameter_code'=>'bpjstkkar','nama_parameter_pph'=>'BPJS Tenaga Kerja (Karyawan)','tipe_pph'=>'Potongan','status_parameter_pph'=>'Potongan','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>12,'parameter_code'=>'pensiunkar','nama_parameter_pph'=>'Jaminan Pensiun (Karyawan)','tipe_pph'=>'Potongan','status_parameter_pph'=>'Potongan','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>13,'parameter_code'=>'bpjskeskar','nama_parameter_pph'=>'BPJS Kesehatan (Karyawan)','tipe_pph'=>'Potongan','status_parameter_pph'=>'Potongan','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>14,'parameter_code'=>'biayajabatan','nama_parameter_pph'=>'Biaya Jabatan','tipe_pph'=>'Potongan','status_parameter_pph'=>'Potongan','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>15,'parameter_code'=>'tunjanganpph','nama_parameter_pph'=>'Tunjangan PPH','tipe_pph'=>'Pendapatan','status_parameter_pph'=>'Gaji','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
        ];

        Model::insert($data);
    }
}
