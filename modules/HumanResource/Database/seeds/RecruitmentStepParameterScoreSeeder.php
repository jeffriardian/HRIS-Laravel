<?php

namespace Modules\HumanResource\Database\seeds;

use Illuminate\Database\Seeder;
use Modules\HumanResource\Models\RecruitmentStepParameterScore as Model;

class RecruitmentStepParameterScoreSeeder extends Seeder
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
            ['id'=>1,'recruitment_step_parameter_id'=>1,'score_id'=>1,'description'=>"Deskripsi Bintang 1",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>2,'recruitment_step_parameter_id'=>1,'score_id'=>2,'description'=>"Deskripsi Bintang 2",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>3,'recruitment_step_parameter_id'=>1,'score_id'=>3,'description'=>"Deskripsi Bintang 3",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>4,'recruitment_step_parameter_id'=>1,'score_id'=>4,'description'=>"Deskripsi Bintang 41",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>5,'recruitment_step_parameter_id'=>1,'score_id'=>5,'description'=>"Deskripsi Bintang 5",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>6,'recruitment_step_parameter_id'=>2,'score_id'=>1,'description'=>"Deskripsi Bintang 1",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>7,'recruitment_step_parameter_id'=>2,'score_id'=>2,'description'=>"Deskripsi Bintang 2",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>8,'recruitment_step_parameter_id'=>2,'score_id'=>3,'description'=>"Deskripsi Bintang 3",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>9,'recruitment_step_parameter_id'=>2,'score_id'=>4,'description'=>"Deskripsi Bintang 41",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>10,'recruitment_step_parameter_id'=>2,'score_id'=>5,'description'=>"Deskripsi Bintang 5",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>11,'recruitment_step_parameter_id'=>3,'score_id'=>1,'description'=>"Deskripsi Bintang 1",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>12,'recruitment_step_parameter_id'=>3,'score_id'=>2,'description'=>"Deskripsi Bintang 2",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>13,'recruitment_step_parameter_id'=>3,'score_id'=>3,'description'=>"Deskripsi Bintang 3",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>14,'recruitment_step_parameter_id'=>3,'score_id'=>4,'description'=>"Deskripsi Bintang 41",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>15,'recruitment_step_parameter_id'=>3,'score_id'=>5,'description'=>"Deskripsi Bintang 5",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>16,'recruitment_step_parameter_id'=>4,'score_id'=>1,'description'=>"Deskripsi Bintang 1",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>17,'recruitment_step_parameter_id'=>4,'score_id'=>2,'description'=>"Deskripsi Bintang 2",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>18,'recruitment_step_parameter_id'=>4,'score_id'=>3,'description'=>"Deskripsi Bintang 3",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>19,'recruitment_step_parameter_id'=>4,'score_id'=>4,'description'=>"Deskripsi Bintang 41",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>20,'recruitment_step_parameter_id'=>4,'score_id'=>5,'description'=>"Deskripsi Bintang 5",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>21,'recruitment_step_parameter_id'=>5,'score_id'=>1,'description'=>"Deskripsi Bintang 1",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>22,'recruitment_step_parameter_id'=>5,'score_id'=>2,'description'=>"Deskripsi Bintang 2",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>23,'recruitment_step_parameter_id'=>5,'score_id'=>3,'description'=>"Deskripsi Bintang 3",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>24,'recruitment_step_parameter_id'=>5,'score_id'=>4,'description'=>"Deskripsi Bintang 41",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>25,'recruitment_step_parameter_id'=>5,'score_id'=>5,'description'=>"Deskripsi Bintang 5",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>26,'recruitment_step_parameter_id'=>6,'score_id'=>1,'description'=>"Deskripsi Bintang 1",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>27,'recruitment_step_parameter_id'=>6,'score_id'=>2,'description'=>"Deskripsi Bintang 2",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>28,'recruitment_step_parameter_id'=>6,'score_id'=>3,'description'=>"Deskripsi Bintang 3",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>29,'recruitment_step_parameter_id'=>6,'score_id'=>4,'description'=>"Deskripsi Bintang 41",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>30,'recruitment_step_parameter_id'=>6,'score_id'=>5,'description'=>"Deskripsi Bintang 5",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>31,'recruitment_step_parameter_id'=>7,'score_id'=>1,'description'=>"Deskripsi Bintang 1",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>32,'recruitment_step_parameter_id'=>7,'score_id'=>2,'description'=>"Deskripsi Bintang 2",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>33,'recruitment_step_parameter_id'=>7,'score_id'=>3,'description'=>"Deskripsi Bintang 3",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>34,'recruitment_step_parameter_id'=>7,'score_id'=>4,'description'=>"Deskripsi Bintang 41",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>35,'recruitment_step_parameter_id'=>7,'score_id'=>5,'description'=>"Deskripsi Bintang 5",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>36,'recruitment_step_parameter_id'=>8,'score_id'=>1,'description'=>"Deskripsi Bintang 1",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>37,'recruitment_step_parameter_id'=>8,'score_id'=>2,'description'=>"Deskripsi Bintang 2",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>38,'recruitment_step_parameter_id'=>8,'score_id'=>3,'description'=>"Deskripsi Bintang 3",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>39,'recruitment_step_parameter_id'=>8,'score_id'=>4,'description'=>"Deskripsi Bintang 41",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>40,'recruitment_step_parameter_id'=>8,'score_id'=>5,'description'=>"Deskripsi Bintang 5",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>41,'recruitment_step_parameter_id'=>9,'score_id'=>1,'description'=>"Deskripsi Bintang 1",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>42,'recruitment_step_parameter_id'=>9,'score_id'=>2,'description'=>"Deskripsi Bintang 2",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>43,'recruitment_step_parameter_id'=>9,'score_id'=>3,'description'=>"Deskripsi Bintang 3",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>44,'recruitment_step_parameter_id'=>9,'score_id'=>4,'description'=>"Deskripsi Bintang 41",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>45,'recruitment_step_parameter_id'=>9,'score_id'=>5,'description'=>"Deskripsi Bintang 5",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>46,'recruitment_step_parameter_id'=>10,'score_id'=>1,'description'=>"Deskripsi Bintang 1",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>47,'recruitment_step_parameter_id'=>10,'score_id'=>2,'description'=>"Deskripsi Bintang 2",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>48,'recruitment_step_parameter_id'=>10,'score_id'=>3,'description'=>"Deskripsi Bintang 3",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>49,'recruitment_step_parameter_id'=>10,'score_id'=>4,'description'=>"Deskripsi Bintang 41",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>50,'recruitment_step_parameter_id'=>10,'score_id'=>5,'description'=>"Deskripsi Bintang 5",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>51,'recruitment_step_parameter_id'=>11,'score_id'=>1,'description'=>"Deskripsi Bintang 1",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>52,'recruitment_step_parameter_id'=>11,'score_id'=>2,'description'=>"Deskripsi Bintang 2",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>53,'recruitment_step_parameter_id'=>11,'score_id'=>3,'description'=>"Deskripsi Bintang 3",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>54,'recruitment_step_parameter_id'=>11,'score_id'=>4,'description'=>"Deskripsi Bintang 41",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>55,'recruitment_step_parameter_id'=>11,'score_id'=>5,'description'=>"Deskripsi Bintang 5",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>56,'recruitment_step_parameter_id'=>12,'score_id'=>1,'description'=>"Deskripsi Bintang 1",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>57,'recruitment_step_parameter_id'=>12,'score_id'=>2,'description'=>"Deskripsi Bintang 2",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>58,'recruitment_step_parameter_id'=>12,'score_id'=>3,'description'=>"Deskripsi Bintang 3",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>59,'recruitment_step_parameter_id'=>12,'score_id'=>4,'description'=>"Deskripsi Bintang 41",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>60,'recruitment_step_parameter_id'=>12,'score_id'=>5,'description'=>"Deskripsi Bintang 5",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>61,'recruitment_step_parameter_id'=>13,'score_id'=>1,'description'=>"Deskripsi Bintang 1",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>62,'recruitment_step_parameter_id'=>13,'score_id'=>2,'description'=>"Deskripsi Bintang 2",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>63,'recruitment_step_parameter_id'=>13,'score_id'=>3,'description'=>"Deskripsi Bintang 3",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>64,'recruitment_step_parameter_id'=>13,'score_id'=>4,'description'=>"Deskripsi Bintang 41",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>65,'recruitment_step_parameter_id'=>13,'score_id'=>5,'description'=>"Deskripsi Bintang 5",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>66,'recruitment_step_parameter_id'=>14,'score_id'=>1,'description'=>"Deskripsi Bintang 1",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>67,'recruitment_step_parameter_id'=>14,'score_id'=>2,'description'=>"Deskripsi Bintang 2",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>68,'recruitment_step_parameter_id'=>14,'score_id'=>3,'description'=>"Deskripsi Bintang 3",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>69,'recruitment_step_parameter_id'=>14,'score_id'=>4,'description'=>"Deskripsi Bintang 41",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>70,'recruitment_step_parameter_id'=>14,'score_id'=>5,'description'=>"Deskripsi Bintang 5",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>71,'recruitment_step_parameter_id'=>15,'score_id'=>1,'description'=>"Deskripsi Bintang 1",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>72,'recruitment_step_parameter_id'=>15,'score_id'=>2,'description'=>"Deskripsi Bintang 2",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>73,'recruitment_step_parameter_id'=>15,'score_id'=>3,'description'=>"Deskripsi Bintang 3",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>74,'recruitment_step_parameter_id'=>15,'score_id'=>4,'description'=>"Deskripsi Bintang 41",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>75,'recruitment_step_parameter_id'=>15,'score_id'=>5,'description'=>"Deskripsi Bintang 5",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>76,'recruitment_step_parameter_id'=>16,'score_id'=>1,'description'=>"Deskripsi Bintang 1",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>77,'recruitment_step_parameter_id'=>16,'score_id'=>2,'description'=>"Deskripsi Bintang 2",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>78,'recruitment_step_parameter_id'=>16,'score_id'=>3,'description'=>"Deskripsi Bintang 3",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>79,'recruitment_step_parameter_id'=>16,'score_id'=>4,'description'=>"Deskripsi Bintang 41",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>80,'recruitment_step_parameter_id'=>16,'score_id'=>5,'description'=>"Deskripsi Bintang 5",'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now], 
        ];
        Model::insert($data);

        
    }
}
