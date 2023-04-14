<?php

namespace Modules\Production\Database\seeds;

use Illuminate\Database\Seeder;
use Modules\Production\Models\MachineType as Model;

class MachineTypeSeeder extends Seeder
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
            ['code'=>'','name' => 'HT. TECH SOFT H.','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'','name' => 'FABRIC DYEING','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'','name' => 'ECOMERC','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'','name' => 'Inspecting','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'','name' => 'SANTASHRINX','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'','name' => 'SANTABLEACH','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'','name' => 'SANTASPREAD TS 10','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'','name' => 'SANTASTRETCH','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'','name' => 'SANTASTRETCH PLUS','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'','name' => 'SANTASTRETCH PLUS 140','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'','name' => 'HYGROTEX','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'','name' => 'COSFELD BRD','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'','name' => 'D 4420 COSFELD','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'','name' => 'D 4851 COSFELD','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'','name' => 'D 48651 COSFELD','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'','name' => 'D 48653 COSFELD','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'','name' => 'D 49653 COSFELD','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'','name' => 'D - 647111 Galaxy','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'','name' => 'AC 4 - AP','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'','name' => 'AK-REO 2T','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'','name' => 'AK-HTO 75','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'','name' => 'AK-R004','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'','name' => 'AFS 450','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'','name' => 'TGRU – ROR – 800','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'','name' => 'ASM','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'','name' => 'ASM/3','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'','name' => 'ASM/4','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'','name' => 'DNHF – 2400','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'','name' => 'DNHF – 2600','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'','name' => 'PT SMM','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'','name' => 'SMM SETTING 4','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'','name' => 'Rope opening line bianco','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'','name' => 'Rope squeezer dancing roll','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'','name' => 'KUN 1709 PA 0000','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'','name' => 'TUBULAR COMPACTOR','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'','name' => 'KSA 500','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'','name' => 'KSA 800','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'','name' => 'KST 500','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'','name' => 'KST H 1500','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'','name' => 'CUD FV','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'','name' => 'Linea tag lierina','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'','name' => 'LINECE TAGLIERINA','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'','name' => 'WEFTSTRAIGHTENER MDC','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'','name' => 'TEXTINDO','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'','name' => 'Textinno','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'','name' => 'Cylinder TA SHEN','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'','name' => 'EHWA SUNTEXT/PCDA - 50 - 09','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'','name' => 'WEFTSTRAIGHTENER MDC','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'','name' => '03378 M','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['code'=>'','name' => '8349450110/012','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
        ];
        
        Model::insert($data);
    }
}
