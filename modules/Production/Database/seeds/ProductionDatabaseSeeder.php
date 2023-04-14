<?php

namespace Modules\Production\Database\seeds;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class ProductionDatabaseSeeder extends Seeder
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
        $this->call(MachineGroupSeeder::class);
        $this->call(MachinePartSeeder::class);
		$this->call(MachineBrandSeeder::class);
        $this->call(MachineTypeSeeder::class);
        $this->call(MachineSeeder::class);
    }
}
