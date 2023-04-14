<?php

namespace Modules\HumanResource\Database\seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CandidateDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = base_path('modules\HumanResource\Database\seeds\sql\candidate_data.sql');
        DB::statement(file_get_contents($data));
    }
}
