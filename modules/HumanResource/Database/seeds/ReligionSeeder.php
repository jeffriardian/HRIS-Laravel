<?php

namespace Modules\HumanResource\Database\seeds;

use Illuminate\Database\Seeder;
use Modules\HumanResource\Models\Religion as Model;

class ReligionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = now();
        //source : https://www.indonesia.go.id/profil/agama
        $data = [
            ['id'=>999,'name'=>'Undefined','worship_place'=>'','description'=>'','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>1,'name'=>'Islam','worship_place'=>'Masjid','description'=>'Mayoritas penduduk Indonesia memeluk agama Islam. Saat ini ada lebih dari 207 juta muslim di Indonesia. Kitab suci agama Islam adalah Al-Qur’an.','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>2,'name'=>'Protestan','worship_place'=>'Gereja','description'=>'Agama Kristen Protestan adalah sebuah denominasi dalam agama Kristen, yang muncul setelah protes Marthin Luther pada 1517. Kitab suci Protestan adalah Al-Kitab.','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>3,'name'=>'Katolik','worship_place'=>'Gereja','description'=>'Kristen Katolik di Indonesia berawal dari kedatangan bangsa Portugis ke kepulauan Maluku, dan orang Maluku adalah yang pertama menjadi Katolik di Indonesia. Kitab suci agama ini adalah Al-Kitab.','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>4,'name'=>'Hindu','worship_place'=>'Pura','description'=>'Hindu memiliki sejarah yang paling panjang dibanding agama resmi lain di Tanah Air. Bali memiliki penganut agama hindu terbesar. Kitab suci Hindu adalah Veda/Weda.','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>5,'name'=>'Buddha','worship_place'=>'Vihara','description'=>'Agama Buddha merupakan agama tertua di dunia dan juga di Indonesia, yang berasal dari India. Buddha berkembang cukup baik di daerah Asia. Kitab suci agama Buddha adalah Tripitaka.','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>6,'name'=>'Khonghucu','worship_place'=>'Klenteng/Litang','description'=>'Penyebaran agama Khonghucu ke Tanah Air dilakukan oleh orang-orang Tionghoa yang merantau ke Indonesia. Shishu Wujing adalah nama kitab suci Khonghucu.','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now]
        ];

        Model::insert($data);
    }
}
