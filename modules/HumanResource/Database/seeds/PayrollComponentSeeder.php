<?php

namespace Modules\HumanResource\Database\seeds;

use Illuminate\Database\Seeder;
use Modules\HumanResource\Models\PayrollComponent as Model;

class PayrollComponentSeeder extends Seeder
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
            ['id'=>1,'payroll_component_type_id'=>'1','status'=>'Income','time'=>'Daily','code'=>'basic_salary','name'=>'Basic Salary','effective_date'=>'2020-01-01','taxable'=>0,'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>2,'payroll_component_type_id'=>'1','status'=>'Income','time'=>'Daily','code'=>'wage','name'=>'Wage','effective_date'=>'2020-01-01','taxable'=>0,'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>3,'payroll_component_type_id'=>'1','status'=>'Income','time'=>'Daily','code'=>'functional_allowance','name'=>'Functional Allowance','effective_date'=>'2020-01-01','taxable'=>0,'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>4,'payroll_component_type_id'=>'1','status'=>'Income','time'=>'Daily','code'=>'production_allowance','name'=>'Production Allowance','effective_date'=>'2020-01-01','taxable'=>0,'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],

            ['id'=>5,'payroll_component_type_id'=>'2','status'=>'Income','time'=>'One Time','code'=>'bonus','name'=>'Bonus','effective_date'=>'2020-01-01','taxable'=>0,'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>6,'payroll_component_type_id'=>'2','status'=>'Income','time'=>'Daily','code'=>'meal','name'=>'Meal','effective_date'=>'2020-01-01','taxable'=>0,'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>7,'payroll_component_type_id'=>'2','status'=>'Income','time'=>'One Time','code'=>'overtime1','name'=>'Overtime 1','effective_date'=>'2020-01-01','taxable'=>0,'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>8,'payroll_component_type_id'=>'2','status'=>'Income','time'=>'One Time','code'=>'overtime2','name'=>'Overtime 2','effective_date'=>'2020-01-01','taxable'=>0,'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>9,'payroll_component_type_id'=>'2','status'=>'Income','time'=>'One Time','code'=>'overtime2_weekend','name'=>'Overtime 2 Weekend','effective_date'=>'2020-01-01','taxable'=>0,'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>10,'payroll_component_type_id'=>'2','status'=>'Income','time'=>'One Time','code'=>'overtime3_weekend','name'=>'Overtime 3 Weekend','effective_date'=>'2020-01-01','taxable'=>0,'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>11,'payroll_component_type_id'=>'2','status'=>'Income','time'=>'One Time','code'=>'total_overtime','time'=>'Monthly','name'=>'Total Overtime','effective_date'=>'2020-01-01','taxable'=>0,'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>12,'payroll_component_type_id'=>'2','status'=>'Income','time'=>'One Time','code'=>'reimburse','name'=>'Reimburse','effective_date'=>'2020-01-01','taxable'=>0,'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>13,'payroll_component_type_id'=>'2','status'=>'Income','time'=>'One Time','code'=>'','name'=>'Other Allowance','effective_date'=>'2020-01-01','taxable'=>0,'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],

            ['id'=>14,'payroll_component_type_id'=>'3','status'=>'Deduction','time'=>'One Time','code'=>'s','name'=>'Sick Deduction','effective_date'=>'2020-01-01','taxable'=>0,'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>15,'payroll_component_type_id'=>'3','status'=>'Deduction','time'=>'One Time','code'=>'st','name'=>'Sick Without Doctor Note Deduction','effective_date'=>'2020-01-01','taxable'=>0,'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>16,'payroll_component_type_id'=>'3','status'=>'Deduction','time'=>'One Time','code'=>'i','name'=>'Permission Deduction','effective_date'=>'2020-01-01','taxable'=>0,'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>17,'payroll_component_type_id'=>'3','status'=>'Deduction','time'=>'One Time','code'=>'a','name'=>'Alpa Deduction','effective_date'=>'2020-01-01','taxable'=>0,'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>18,'payroll_component_type_id'=>'3','status'=>'Deduction','time'=>'One Time','code'=>'off','name'=>'Off Deduction','effective_date'=>'2020-01-01','taxable'=>0,'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>19,'payroll_component_type_id'=>'3','status'=>'Deduction','time'=>'One Time','code'=>'late_less','name'=>'Late less than 2 Hours','effective_date'=>'2020-01-01','taxable'=>0,'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>20,'payroll_component_type_id'=>'3','status'=>'Deduction','time'=>'One Time','code'=>'late_more','name'=>'Late more than 2 Hours','effective_date'=>'2020-01-01','taxable'=>0,'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>21,'payroll_component_type_id'=>'3','status'=>'Deduction','time'=>'One Time','code'=>'half_day','name'=>'Half Day','effective_date'=>'2020-01-01','taxable'=>0,'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>22,'payroll_component_type_id'=>'3','status'=>'Deduction','time'=>'One Time','code'=>'total_attendance','name'=>'Total Attendance Deduction','effective_date'=>'2020-01-01','taxable'=>0,'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],

            ['id'=>23,'payroll_component_type_id'=>'3','status'=>'Deduction','time'=>'Monthly','code'=>'cooperative','name'=>'Cooperatives Deduction','effective_date'=>'2020-01-01','taxable'=>0,'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>24,'payroll_component_type_id'=>'3','status'=>'Deduction','time'=>'Monthly','code'=>'aoc','name'=>'Aoc Deduction','effective_date'=>'2020-01-01','taxable'=>0,'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>25,'payroll_component_type_id'=>'3','status'=>'Deduction','time'=>'One Time','code'=>'other_deduction','name'=>'Other Deduction','effective_date'=>'2020-01-01','taxable'=>0,'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>26,'payroll_component_type_id'=>'3','status'=>'Deduction','time'=>'One Time','code'=>'jht','name'=>'Jaminan Hari Tua','effective_date'=>'2020-01-01','taxable'=>0,'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>27,'payroll_component_type_id'=>'3','status'=>'Deduction','time'=>'One Time','code'=>'pensiun','name'=>'Jaminan Pensiun','effective_date'=>'2020-01-01','taxable'=>0,'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>28,'payroll_component_type_id'=>'3','status'=>'Deduction','time'=>'One Time','code'=>'bpjskes_employee','name'=>'BPJS Kesehatan By Employee','effective_date'=>'2020-01-01','taxable'=>0,'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>29,'payroll_component_type_id'=>'3','status'=>'Premi','time'=>'One Time','code'=>'jkm','name'=>'Jaminan Kematian','effective_date'=>'2020-01-01','taxable'=>0,'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>30,'payroll_component_type_id'=>'3','status'=>'Premi','time'=>'One Time','code'=>'jkk','name'=>'Jaminan Kecelakaan Kerja','effective_date'=>'2020-01-01','taxable'=>0,'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
            ['id'=>31,'payroll_component_type_id'=>'3','status'=>'Premi','time'=>'One Time','code'=>'bpjskes_company','name'=>'BPJS Kesehatan By Company','effective_date'=>'2020-01-01','taxable'=>0,'created_by'=>1,'updated_by'=>1,'created_at'=>$now,'updated_at'=>$now],
        ];

        Model::insert($data);
    }
}
