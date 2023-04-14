<?php

namespace Modules\HumanResource\Database\seeds;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class HumanResourceDatabaseSeeder extends Seeder
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

        $this->call(BloodTypeSeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(DepartmentLevelSeeder::class);
        $this->call(DepartmentSeeder::class);
        $this->call(PositionSeeder::class);
        $this->call(ReligionSeeder::class);
        $this->call(PayrollTypeSeeder::class);
        $this->call(SalaryTypeSeeder::class);
        $this->call(MaritalStatusSeeder::class);
        $this->call(ContractSeeder::class);
        $this->call(CompanySeeder::class);
        $this->call(OfficeHourSeeder::class);
        // $this->call(OfficeHourDetailSeeder::class);
        $this->call(PtkpSeeder::class);
        $this->call(DayOffTableSeeder::class);
        $this->call(EmployeeSeeder::class);
        $this->call(EmployeeSalaryTableSeeder::class);
        $this->call(PeriodSeeder::class);
        $this->call(EmployeeStatusSeeder::class);
        $this->call(TrainingTypeSeeder::class);
        $this->call(LeaveCategorySeeder::class);
        $this->call(ReceiptParameterSeeder::class);
        $this->call(TransportationSeeder::class);
        $this->call(ApprovalStatusSeeder::class);
        $this->call(ReimburseCategorySeeder::class);
        $this->call(InventoryCategorySeeder::class);
        $this->call(InventorySeeder::class);
        $this->call(CooperativeCategorySeeder::class);
        $this->call(CooperativeDescriptionSeeder::class);
        $this->call(CooperativeTypeSeeder::class);
        $this->call(CooperativeBasicSavingSeeder::class);
        $this->call(InventoryReceiptSeeder::class);
        $this->call(PphParameterSeeder::class);
        $this->call(MonthSeeder::class);
        $this->call(YearSeeder::class);
        $this->call(PayrollComponentSeeder::class);
        $this->call(PayrollComponentTypeSeeder::class);

        $this->call(EmployeeTypeCooperativeSeeder::class);

        $this->call(MemorandumParameterSeeder::class);
        $this->call(RecruitmentStepParameterSeeder::class);
        $this->call(RecruitmentStepSeeder::class);
        // $this->call(RecruitmentStepParameterScoreSeeder::class);
        $this->call(ScoreSeeder::class);
        $this->call(CitySeeder::class);
        $this->call(StatusRecruitmentSeeder::class);
        $this->call(ExitClearanceParameterSeeder::class);

        $this->call(IncidentCategorySeeder::class);

        $this->call(InterrogationReportActorTypesTableSeeder::class);

        // payroll
        // $this->call(AttLogSeeder::class);
        // $this->call(LeaveSeeder::class);

        // $data = base_path('/modules/HumanResource/Database/seeds/sql/employees.sql');
        // $data = base_path('/modules/HumanResource/Database/seeds/sql/employee_salaries.sql');
        // DB::statement(file_get_contents($data));

        $this->call(RecruitmentStepParameterScoreSeeder::class);
        
        //Interrogation Report
        $this->call(InterrogationReportTypeSeeder::class);
        // $this->call(CandidateDataSeeder::class);
        
        $this->call(AocSeeder::class);
        $this->call(MealSeeder::class);
        $this->call(UmkSeeder::class);
        $this->call(WageSeeder::class);
        $this->call(CooperativeMemberTypeSeeder::class);

        $this->call(DummyAttendanceTableSeeder::class);

        $this->call(OfficeHourDetailTableSeeder::class);
    }
}
