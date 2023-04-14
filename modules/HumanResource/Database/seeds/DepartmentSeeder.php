<?php

namespace Modules\HumanResource\Database\seeds;

use Illuminate\Database\Seeder;
use Modules\HumanResource\Models\Department as Model;

class DepartmentSeeder extends Seeder
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
            ['id'=>999,'code'=>'UD','department_level_id'=>0,'parent_id'=>0,'name'=>'Undefined','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>1,'code'=>'BOD','department_level_id'=>1,'parent_id'=>0,'name'=>'Board Of Director','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>2,'code'=>'QMS','department_level_id'=>2,'parent_id'=>1,'name'=>'Quality Management System','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>3,'code'=>'OPS','department_level_id'=>2,'parent_id'=>1,'name'=>'Operasional','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>4,'code'=>'ENG','department_level_id'=>3,'parent_id'=>1,'name'=>'Engineering','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>5,'code'=>'PPIC','department_level_id'=>3,'parent_id'=>3,'name'=>'PPIC','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>6,'code'=>'MPRD','department_level_id'=>3,'parent_id'=>3,'name'=>'Produksi','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>7,'code'=>'MLAB','department_level_id'=>3,'parent_id'=>3,'name'=>'Laboratorium','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>8,'code'=>'HRGA','department_level_id'=>4,'parent_id'=>1,'name'=>'HRGA','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>9,'code'=>'FNA','department_level_id'=>4,'parent_id'=>1,'name'=>'Finance & Accounting','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>10,'code'=>'ZB','department_level_id'=>4,'parent_id'=>4,'name'=>'Zona Basah','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>11,'code'=>'ZK','department_level_id'=>4,'parent_id'=>4,'name'=>'Zona Kering','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>12,'code'=>'BO','department_level_id'=>4,'parent_id'=>4,'name'=>'Bengkel Otomotif','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>13,'code'=>'IT','department_level_id'=>4,'parent_id'=>1,'name'=>'IT','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>14,'code'=>'MKT','department_level_id'=>4,'parent_id'=>1,'name'=>'Marketing','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>15,'code'=>'PPC','department_level_id'=>4,'parent_id'=>5,'name'=>'PPC','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>16,'code'=>'IC','department_level_id'=>4,'parent_id'=>5,'name'=>'Inventory Control','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>17,'code'=>'EKS','department_level_id'=>4,'parent_id'=>5,'name'=>'Ekspedisi','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>18,'code'=>'PRD','department_level_id'=>4,'parent_id'=>6,'name'=>'Produksi','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>19,'code'=>'UTW','department_level_id'=>4,'parent_id'=>6,'name'=>'Utility & WWTP','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>20,'code'=>'RND','department_level_id'=>4,'parent_id'=>7,'name'=>'R & D','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>21,'code'=>'LAB','department_level_id'=>4,'parent_id'=>7,'name'=>'Lab','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>22,'code'=>'QC','department_level_id'=>4,'parent_id'=>7,'name'=>'QC','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>23,'code'=>'PMB','department_level_id'=>4,'parent_id'=>1,'name'=>'Pembelian','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>24,'code'=>'HRD','department_level_id'=>5,'parent_id'=>8,'name'=>'HRD','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>25,'code'=>'GA','department_level_id'=>5,'parent_id'=>8,'name'=>'GA','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>26,'code'=>'SPL','department_level_id'=>5,'parent_id'=>8,'name'=>'Sipil','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>27,'code'=>'GDG','department_level_id'=>5,'parent_id'=>16,'name'=>'Gudang Greige','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>28,'code'=>'GBJ','department_level_id'=>5,'parent_id'=>16,'name'=>'Gudang Barang Jadi','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>29,'code'=>'GDO','department_level_id'=>5,'parent_id'=>16,'name'=>'Gudang Obat','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>30,'code'=>'GDU','department_level_id'=>5,'parent_id'=>16,'name'=>'Gudang Umum','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>31,'code'=>'DYE','department_level_id'=>5,'parent_id'=>18,'name'=>'Dyeing','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>32,'code'=>'FSH','department_level_id'=>5,'parent_id'=>18,'name'=>'Finishing','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>33,'code'=>'UTI','department_level_id'=>5,'parent_id'=>19,'name'=>'Utility','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>34,'code'=>'WWTP','department_level_id'=>5,'parent_id'=>19,'name'=>'WWTP','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],

            ['id'=>35,'code'=>'DCR','department_level_id'=>999,'parent_id'=>2,'name'=>'Document Control','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>36,'code'=>'AMI','department_level_id'=>999,'parent_id'=>2,'name'=>'Audit Mutu Internal','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>37,'code'=>'SCT','department_level_id'=>999,'parent_id'=>25,'name'=>'Security','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>38,'code'=>'PU','department_level_id'=>999,'parent_id'=>25,'name'=>'Perbantuan Umum','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>39,'code'=>'FIN','department_level_id'=>999,'parent_id'=>9,'name'=>'Finance','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>40,'code'=>'ACC','department_level_id'=>999,'parent_id'=>9,'name'=>'Accounting','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>41,'code'=>'PJK','department_level_id'=>999,'parent_id'=>9,'name'=>'Pajak','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>42,'code'=>'SFW','department_level_id'=>999,'parent_id'=>13,'name'=>'Software','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>43,'code'=>'HRW','department_level_id'=>999,'parent_id'=>13,'name'=>'Hardware','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>44,'code'=>'MRKT','department_level_id'=>999,'parent_id'=>14,'name'=>'Marketing','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>45,'code'=>'PLN','department_level_id'=>999,'parent_id'=>15,'name'=>'Planning','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>46,'code'=>'PPCE','department_level_id'=>999,'parent_id'=>15,'name'=>'PPC Online Celup','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>47,'code'=>'PPCA','department_level_id'=>999,'parent_id'=>15,'name'=>'PPC Online Calender','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>48,'code'=>'FUL','department_level_id'=>999,'parent_id'=>15,'name'=>'Follow Up Lapangan','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>49,'code'=>'PGR','department_level_id'=>999,'parent_id'=>27,'name'=>'Penerimaan Greige','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>50,'code'=>'PMT','department_level_id'=>999,'parent_id'=>27,'name'=>'Pemartaian','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>51,'code'=>'CNT','department_level_id'=>999,'parent_id'=>31,'name'=>'Continuous','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>52,'code'=>'CLW','department_level_id'=>999,'parent_id'=>31,'name'=>'Celup Warna','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>53,'code'=>'TO','department_level_id'=>999,'parent_id'=>31,'name'=>'Timbang Obat','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>54,'code'=>'CLH','department_level_id'=>999,'parent_id'=>31,'name'=>'Celup Haspel/Belakang','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>55,'code'=>'MCZ','department_level_id'=>999,'parent_id'=>31,'name'=>'Mercerized','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>56,'code'=>'CDD','department_level_id'=>999,'parent_id'=>32,'name'=>'Calator/Dryer Depan','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>57,'code'=>'CD','department_level_id'=>999,'parent_id'=>32,'name'=>'Calator Depan','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>58,'code'=>'DD','department_level_id'=>999,'parent_id'=>32,'name'=>'Dryer Depan','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>59,'code'=>'CLL','department_level_id'=>999,'parent_id'=>32,'name'=>'Callender','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>60,'code'=>'CDB','department_level_id'=>999,'parent_id'=>32,'name'=>'Calator/Dryer Belakang','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>61,'code'=>'CB','department_level_id'=>999,'parent_id'=>32,'name'=>'Calator Belakang','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>62,'code'=>'DB','department_level_id'=>999,'parent_id'=>32,'name'=>'Dryer Belakang','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>63,'code'=>'SET','department_level_id'=>999,'parent_id'=>32,'name'=>'Setting','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>64,'code'=>'FNS','department_level_id'=>999,'parent_id'=>32,'name'=>'Finish','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>65,'code'=>'INS','department_level_id'=>999,'parent_id'=>32,'name'=>'Inspekting','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>66,'code'=>'QCD','department_level_id'=>999,'parent_id'=>22,'name'=>'Quality Control Depan','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>67,'code'=>'QCT','department_level_id'=>999,'parent_id'=>22,'name'=>'Quality Control Timur','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>68,'code'=>'QCS','department_level_id'=>999,'parent_id'=>22,'name'=>'Quality Control Setting','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>69,'code'=>'RTR','department_level_id'=>999,'parent_id'=>22,'name'=>'Returan','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>70,'code'=>'PRS','department_level_id'=>999,'parent_id'=>21,'name'=>'Laboratorium Proses','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>71,'code'=>'SPC','department_level_id'=>999,'parent_id'=>21,'name'=>'Spectro','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>72,'code'=>'BOI','department_level_id'=>999,'parent_id'=>33,'name'=>'Boiler','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now]
        ];

        Model::insert($data);
    }
}
