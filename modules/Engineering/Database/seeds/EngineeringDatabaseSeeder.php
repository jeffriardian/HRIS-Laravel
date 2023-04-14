<?php

namespace Modules\Engineering\Database\seeds;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class EngineeringDatabaseSeeder extends Seeder
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
        $this->call(ServiceTypeSeeder::class);
        $this->call(FailureTypeSeeder::class);
        $this->call(WorkOrderActionSeeder::class);
        $this->call(MachineSeeder::class);
    }
}
