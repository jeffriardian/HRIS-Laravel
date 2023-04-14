<?php

namespace Modules\Warehouse\Database\seeds;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class WarehouseDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // $this->call("OthersTableSeeder");
    }
}
