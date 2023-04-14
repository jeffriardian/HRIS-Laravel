<?php

namespace Modules\HumanResource\Database\seeds;

use Illuminate\Database\Seeder;
use Modules\HumanResource\Models\OfficeHourDetail as Model;

class OfficeHourDetailSeeder extends Seeder
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
            ['id'=>1,'office_hour_id'=>'1', 'weekday_in'=>'07:00', 'weekday_out'=>'16:30', 'weekend_in'=>'07:00', 'weekend_out'=>'16:00', 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>2,'office_hour_id'=>'2', 'weekday_in'=>'07:00', 'weekday_out'=>'17:00', 'weekend_in'=>'07:00', 'weekend_out'=>'17:00', 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>3,'office_hour_id'=>'3', 'weekday_in'=>'08:00', 'weekday_out'=>'16:00', 'weekend_in'=>'08:00', 'weekend_out'=>'16:00', 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>4,'office_hour_id'=>'4', 'weekday_in'=>'07:00', 'weekday_out'=>'15:00', 'weekend_in'=>'07:00', 'weekend_out'=>'15:00', 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>5,'office_hour_id'=>'5', 'weekday_in'=>'09:00', 'weekday_out'=>'17:00', 'weekend_in'=>'09:00', 'weekend_out'=>'17:00', 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>6,'office_hour_id'=>'6', 'weekday_in'=>'08:00', 'weekday_out'=>'18:00', 'weekend_in'=>'08:00', 'weekend_out'=>'18:00', 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>7,'office_hour_id'=>'7', 'weekday_in'=>'07:00', 'weekday_out'=>'17:00', 'weekend_in'=>'07:00', 'weekend_out'=>'16:00', 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>8,'office_hour_id'=>'8', 'weekday_in'=>'07:00', 'weekday_out'=>'17:00', 'weekend_in'=>'07:00', 'weekend_out'=>'12:00', 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>9,'office_hour_id'=>'9', 'weekday_in'=>'07:30', 'weekday_out'=>'17:00', 'weekend_in'=>'07:30', 'weekend_out'=>'17:00', 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>10,'office_hour_id'=>'10', 'weekday_in'=>'07:00', 'weekday_out'=>'16:30', 'weekend_in'=>'07:00', 'weekend_out'=>'13:00', 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>11,'office_hour_id'=>'11', 'weekday_in'=>'08:00', 'weekday_out'=>'17:00', 'weekend_in'=>'08:00', 'weekend_out'=>'17:00', 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>12,'office_hour_id'=>'12', 'weekday_in'=>'07:00', 'weekday_out'=>'16:00', 'weekend_in'=>'07:00', 'weekend_out'=>'16:00', 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>13,'office_hour_id'=>'13', 'weekday_in'=>'06:00', 'weekday_out'=>'14:00', 'weekend_in'=>'06:00', 'weekend_out'=>'14:00', 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],

            ['id'=>14,'office_hour_id'=>'14', 'weekday_in'=>'04:00', 'weekday_out'=>'09:00', 'weekend_in'=>'04:00', 'weekend_out'=>'09:00', 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>15,'office_hour_id'=>'14', 'weekday_in'=>'10:00', 'weekday_out'=>'16:00', 'weekend_in'=>'10:00', 'weekend_out'=>'16:00', 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>16,'office_hour_id'=>'14', 'weekday_in'=>'18:00', 'weekday_out'=>'23:59', 'weekend_in'=>'18:00', 'weekend_out'=>'23:59', 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],

            ['id'=>17,'office_hour_id'=>'15', 'weekday_in'=>'04:00', 'weekday_out'=>'07:00', 'weekend_in'=>'04:00', 'weekend_out'=>'07:00', 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>18,'office_hour_id'=>'15', 'weekday_in'=>'11:00', 'weekday_out'=>'16:00', 'weekend_in'=>'11:00', 'weekend_out'=>'16:00', 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>19,'office_hour_id'=>'15', 'weekday_in'=>'20:00', 'weekday_out'=>'23:59', 'weekend_in'=>'20:00', 'weekend_out'=>'23:59', 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],

            ['id'=>20,'office_hour_id'=>'16', 'weekday_in'=>'04:00', 'weekday_out'=>'07:00', 'weekend_in'=>'04:00', 'weekend_out'=>'07:00', 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>21,'office_hour_id'=>'16', 'weekday_in'=>'13:00', 'weekday_out'=>'15:00', 'weekend_in'=>'13:00', 'weekend_out'=>'15:00', 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>22,'office_hour_id'=>'16', 'weekday_in'=>'20:00', 'weekday_out'=>'23:59', 'weekend_in'=>'20:00', 'weekend_out'=>'23:59', 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>23,'office_hour_id'=>'16', 'weekday_in'=>'10:00', 'weekday_out'=>'12:59', 'weekend_in'=>'10:00', 'weekend_out'=>'12:59', 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],

            ['id'=>24,'office_hour_id'=>'17', 'weekday_in'=>'08:00', 'weekday_out'=>'09:00', 'weekend_in'=>'08:00', 'weekend_out'=>'09:00', 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>25,'office_hour_id'=>'17', 'weekday_in'=>'04:00', 'weekday_out'=>'07:00', 'weekend_in'=>'04:00', 'weekend_out'=>'07:00', 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>26,'office_hour_id'=>'17', 'weekday_in'=>'11:00', 'weekday_out'=>'16:00', 'weekend_in'=>'11:00', 'weekend_out'=>'16:00', 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>27,'office_hour_id'=>'17', 'weekday_in'=>'20:00', 'weekday_out'=>'23:59', 'weekend_in'=>'20:00', 'weekend_out'=>'23:59', 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],

            ['id'=>28,'office_hour_id'=>'18', 'weekday_in'=>'05:00', 'weekday_out'=>'07:00', 'weekend_in'=>'05:00', 'weekend_out'=>'07:00', 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>29,'office_hour_id'=>'18', 'weekday_in'=>'13:00', 'weekday_out'=>'15:00', 'weekend_in'=>'13:00', 'weekend_out'=>'15:00', 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>30,'office_hour_id'=>'18', 'weekday_in'=>'21:00', 'weekday_out'=>'23:00', 'weekend_in'=>'21:00', 'weekend_out'=>'23:00', 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],

            ['id'=>31,'office_hour_id'=>'19', 'weekday_in'=>'07:00', 'weekday_out'=>'08:00', 'weekend_in'=>'07:00', 'weekend_out'=>'08:00', 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>32,'office_hour_id'=>'19', 'weekday_in'=>'05:00', 'weekday_out'=>'06:59', 'weekend_in'=>'05:00', 'weekend_out'=>'06:59', 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>33,'office_hour_id'=>'19', 'weekday_in'=>'08:00', 'weekday_out'=>'09:00', 'weekend_in'=>'08:00', 'weekend_out'=>'09:00', 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],

            ['id'=>34,'office_hour_id'=>'20', 'weekday_in'=>'06:00', 'weekday_out'=>'10:00', 'weekend_in'=>'06:00', 'weekend_out'=>'10:00', 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>35,'office_hour_id'=>'20', 'weekday_in'=>'17:00', 'weekday_out'=>'23:00', 'weekend_in'=>'17:00', 'weekend_out'=>'23:00', 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],

            ['id'=>36,'office_hour_id'=>'21', 'weekday_in'=>'05:00', 'weekday_out'=>'07:00', 'weekend_in'=>'05:00', 'weekend_out'=>'07:00', 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>37,'office_hour_id'=>'21', 'weekday_in'=>'12:00', 'weekday_out'=>'14:00', 'weekend_in'=>'12:00', 'weekend_out'=>'14:00', 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],

            ['id'=>38,'office_hour_id'=>'21', 'weekday_in'=>'21:00', 'weekday_out'=>'23:00', 'weekend_in'=>'21:00', 'weekend_out'=>'23:00', 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>39,'office_hour_id'=>'21', 'weekday_in'=>'04:00', 'weekday_out'=>'08:00', 'weekend_in'=>'04:00', 'weekend_out'=>'08:00', 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>40,'office_hour_id'=>'21', 'weekday_in'=>'11:00', 'weekday_out'=>'15:00', 'weekend_in'=>'11:00', 'weekend_out'=>'15:00', 'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
        ];

        Model::insert($data);
    }
}
