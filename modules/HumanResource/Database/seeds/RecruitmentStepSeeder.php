<?php

namespace Modules\HumanResource\Database\seeds;

use Illuminate\Database\Seeder;
use Modules\HumanResource\Models\RecruitmentStep as Model;

class RecruitmentStepSeeder extends Seeder
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
            ['id'=>1,'name'=>'Interview HRD','min_score'=>15,'description'=>'Interview HRD adalah langkah pertama yang harus di lakukan dalam perekrutan pegawai','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>2,'name'=>'Technical Test','min_score'=>15,'description'=>'Tehnical Test adalah test tehnik kandidat atau kemampuan yang biasanya di lakukan untuk bagian yang buth keahlian khusus','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>3,'name'=>'Psikotest','min_score'=>15,'description'=>'Psikotest adalah test untuk mengetahui kepribadian dari kandidat yang menentukan apakah dia cocok menjadi pegawai di SMM atau tidak','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>4,'name'=>'Interview Direksi','min_score'=>15,'description'=>'Interview Kedua dilaksanakan dengan direksi dimana keputusan akan di diputuskan di sini','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
        ];

        Model::insert($data);
    }
}
