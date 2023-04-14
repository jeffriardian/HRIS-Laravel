<?php

namespace Modules\HumanResource\Database\seeds;

use Illuminate\Database\Seeder;
use Modules\HumanResource\Models\Inventory as Model;

class InventorySeeder extends Seeder
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
            ['id'=>1,'inventory_category_id'=>'1','name'=>'HP','type'=>'HP7634','serial_number'=>'123456789','description'=>'Ram 8GB, HDD 1TB, Processor Intel Core I7','total_stock'=>'10','available_stock'=>'10','use_stock'=>'0','damage_stock'=>'0','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>2,'inventory_category_id'=>'1','name'=>'Toshiba','type'=>'T7263','serial_number'=>'987654321','description'=>'Ram 8GB, HDD 1TB, Processor Intel Core I7','total_stock'=>'5','available_stock'=>'5','use_stock'=>'0','damage_stock'=>'0','created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
        ];

        Model::insert($data);
    }
}
