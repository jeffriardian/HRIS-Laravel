<?php

namespace Modules\HumanResource\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Modules\HumanResource\Services\CalculateOvertimeServiceV2;

class AutoCalculateOvertimeCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'auto:calculate-overtime';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Untuk menghitung overtime secara otomatis untuk karyawan yang diberikan tambahan lembur tanpa SPL per harinya dan untuk semua karyawan yg mendapatkan otomatis lembur.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Log::info('Command successfully');
        (new CalculateOvertimeServiceV2())->autoCalculateHourlyOvertime();
    }
}
