<?php

namespace Modules\HumanResource\Database\seeds;

use Illuminate\Database\Seeder;
use Modules\HumanResource\Models\RecruitmentStepParameter as Model;

class RecruitmentStepParameterSeeder extends Seeder
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
            ['id'=>1,'recruitment_step_id'=>1,'created_by'=>1,'name'=>'Pendidikan Formal','description'=>'Tingkat pendidikan (formal) yang dapat menunjang posisi yang dilamar','updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>2,'recruitment_step_id'=>1,'created_by'=>1,'name'=>'Pengalaman Kerja','description'=>'Kesesuaian antara berbagai hal yang pernah ditangani dalam pekerjaan sebelumnya dengan pekerjaan yang dilamar','updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>3,'recruitment_step_id'=>1,'created_by'=>1,'name'=>'Pengetahuan Teknis','description'=>'Pengetahuan praktis atau teoritis yang dikuasi berkenaan dengan posisi yang dilamar','updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>4,'recruitment_step_id'=>1,'created_by'=>1,'name'=>'Keterampilan Teknis','description'=>'Kemampuan mengalami permasalahan yang mungkin terjadi pada posisi yang dilamar','updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>5,'recruitment_step_id'=>1,'created_by'=>1,'name'=>'Motivasi','description'=>'Menunjukkan besarnya semangat serta minat terhadap kondisi pekerjaan yang dilamar','updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>6,'recruitment_step_id'=>1,'created_by'=>1,'name'=>'Kerja Sama','description'=>'Kemampuan beradaptasi dan bekerja sama dengan rekan kerja','updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>7,'recruitment_step_id'=>2,'created_by'=>1,'name'=>'Analisis','description'=>'Menunjukan sikap sopan santun dalam bertutur kata maupun bertingkah laku','updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>8,'recruitment_step_id'=>2,'created_by'=>1,'name'=>'Programming dan Database','description'=>'Kemampuan mengungkapkan ide secara sistematis dan jelas sehingga dapat dipahami dengan benar oleh orang lain','updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>9,'recruitment_step_id'=>2,'created_by'=>1,'name'=>'Kejujuran','updated_by'=>1,'description'=>'Kemampuan dalam berbahasa asing yang dapat dipahami dengan benar oleh orang lain','created_at'=>$now,'updated_at'=>$now],
            ['id'=>10,'recruitment_step_id'=>2,'created_by'=>1,'name'=>'Kepribadian','updated_by'=>1,'description'=>'Cara berpakaian, kerapihan, serta keadaan fisik secara umum. Ada tidaknya cacat','created_at'=>$now,'updated_at'=>$now],
            ['id'=>11,'recruitment_step_id'=>2,'created_by'=>1,'name'=>'Loyalitas','updated_by'=>1,'description'=>'Kemampuan untuk mengorganisir orang lain dan bawahan yang berada dibawah pengawasannya','created_at'=>$now,'updated_at'=>$now],
            ['id'=>12,'recruitment_step_id'=>3,'created_by'=>1,'name'=>'Analisis','updated_by'=>1,'description'=>'Riwayat pekerjaan kandidat di perusahaan sebelumnya','created_at'=>$now,'updated_at'=>$now],
            ['id'=>13,'recruitment_step_id'=>3,'created_by'=>1,'name'=>'Programming dan Database','description'=>'','updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>14,'recruitment_step_id'=>3,'created_by'=>1,'name'=>'Kejujuran','updated_by'=>1,'description'=>'','created_at'=>$now,'updated_at'=>$now],
            ['id'=>15,'recruitment_step_id'=>3,'created_by'=>1,'name'=>'Kepribadian','updated_by'=>1,'description'=>'','created_at'=>$now,'updated_at'=>$now],
            ['id'=>16,'recruitment_step_id'=>3,'created_by'=>1,'name'=>'Loyalitas','updated_by'=>1,'description'=>'','created_at'=>$now,'updated_at'=>$now],
        ];

        Model::insert($data);
    }
}
