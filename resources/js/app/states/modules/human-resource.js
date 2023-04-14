import activityLog from "../../modules/human-resource/activity-log/state";
import approveBusinesstrip from "../../modules/human-resource/approve-businesstrip/state";
import approveOvertime from "../../modules/human-resource/approve-overtime/state";
import approveReimburse from "../../modules/human-resource/approve-reimburse/state";
import approveIntterogation from "../../modules/human-resource/approve-intterogation/state";
import attendance from "../../modules/human-resource/attendance/state";
import attlog from "../../modules/human-resource/attlog/state";
import bloodType from "../../modules/human-resource/blood-type/state";
import businessTrip from "../../modules/human-resource/business-trip/state";
import bpjsKesehatan from "../../modules/human-resource/bpjs-kesehatan/state";
import bpjsKetenagakerjaan from "../../modules/human-resource/bpjs-ketenagakerjaan/state";
import candidate from "../../modules/human-resource/candidate/state";
import company from "../../modules/human-resource/company/state";
import contract from "../../modules/human-resource/contract/state";
import city from "../../modules/human-resource/city/state";
import report from "../../modules/human-resource/report/state";
import cooperativeMember from "../../modules/human-resource/cooperative-member/state";
import country from "../../modules/human-resource/country/state";
import department from "../../modules/human-resource/department/state";
import departmentLevel from "../../modules/human-resource/department-level/state";
import disbursement from "../../modules/human-resource/disbursement/state";
import employee from "../../modules/human-resource/employee/state";
import externalEmployee from "../../modules/human-resource/external-employee/state";
import employeeStatus from "../../modules/human-resource/employee-status/state";
import cooperativeMemberType from "../../modules/human-resource/cooperative-member-type/state";
import exitClearance from "../../modules/human-resource/exit-clearance/state";
import exitClearanceParameter from "../../modules/human-resource/exitclearance-parameter/state";
import historyEmployeePosition from "../../modules/human-resource/history-employee-position/state";
import historyEmployeeStatus from "../../modules/human-resource/history-employee-status/state";
import improvementIdea from "../../modules/human-resource/improvement-idea/state";
import interrogationReport from "../../modules/human-resource/interrogation-report/state";
import interrogationReportType from "../../modules/human-resource/interrogation-report-type/state";
import incidentCategory from "../../modules/human-resource/incident-category/state";
import incidentPenalty from "../../modules/human-resource/incident-penalty/state";
import insurance from "../../modules/human-resource/insurance/state";
import inventory from "../../modules/human-resource/inventory/state";
import inventoryCategory from "../../modules/human-resource/inventory-category/state";
import inventoryDamage from "../../modules/human-resource/inventory-damage/state";
import inventoryEmployee from "../../modules/human-resource/inventory-employee/state";
import inventoryGood from "../../modules/human-resource/inventory-good/state";
import inventoryReceipt from "../../modules/human-resource/inventory-receipt/state";
import inventoryReturn from "../../modules/human-resource/inventory-return/state";
import kpi from "../../modules/human-resource/kpi/state";
import leave from "../../modules/human-resource/leave/state";
import leaveCategory from "../../modules/human-resource/leave-category/state";
import loan from "../../modules/human-resource/loan/state";
import loanTransaction from "../../modules/human-resource/loan-transaction/state";
import maritalStatus from "../../modules/human-resource/marital-status/state";
import memorandum from "../../modules/human-resource/memorandum/state";
import memorandumParameter from "../../modules/human-resource/memorandum-parameter/state";
import month from "../../modules/human-resource/month/state";
import officeHour from "../../modules/human-resource/office-hour/state";
import overtime from "../../modules/human-resource/overtime/state";
import payroll from "../../modules/human-resource/payroll/state";
import payrollComponent from "../../modules/human-resource/payroll-component/state";
import payrollComponentType from "../../modules/human-resource/payrollcomponent-type/state";
import payrollParameter from "../../modules/human-resource/payroll-parameter/state";
import payrollType from "../../modules/human-resource/payroll-type/state";
import period from "../../modules/human-resource/period/state";
import position from "../../modules/human-resource/position/state";
import pph from "../../modules/human-resource/pph/state";
import ptkp from "../../modules/human-resource/ptkp/state";
import publicHoliday from "../../modules/human-resource/public-holiday/state";
import receiptParameter from "../../modules/human-resource/receipt-parameter/state";
import recruitment from "../../modules/human-resource/recruitment/state";
import recruitmentStep from "../../modules/human-resource/recruitment-step/state";
import recruitmentStepParameter from "../../modules/human-resource/recruitment-step-parameter/state";
import reimburse from "../../modules/human-resource/reimburse/state";
import reimburseCategory from "../../modules/human-resource/reimburse-category/state";
import religion from "../../modules/human-resource/religion/state";
import salary from "../../modules/human-resource/salary/state";
import salaryType from "../../modules/human-resource/salary-type/state";
import saving from "../../modules/human-resource/saving/state";
import scheduleRecruitment from "../../modules/human-resource/schedule-recruitment/state";
import score from "../../modules/human-resource/score/state";
import statusRecruitment from "../../modules/human-resource/status-recruitment/state";
import training from "../../modules/human-resource/training/state";
import trainingType from "../../modules/human-resource/training-type/state";
import transportation from "../../modules/human-resource/transportation/state";
import year from "../../modules/human-resource/year/state";
import dummyAttendance from "../../modules/human-resource/dummy-attendance/state";

export default {
    modules: {
        activityLog,
        approveBusinesstrip,
        approveOvertime,
        approveReimburse,
        approveIntterogation,
        attendance,
        attlog,
        businessTrip,
        bloodType,
        bpjsKesehatan,
        bpjsKetenagakerjaan,
        candidate,
        company,
        contract,
        cooperativeMember,
        country,
        city,
        department,
        departmentLevel,
        disbursement,
        dummyAttendance,
        externalEmployee,
        employee,
        employeeStatus,
        cooperativeMemberType,
        exitClearance,
        exitClearanceParameter,
        historyEmployeePosition,
        historyEmployeeStatus,
        improvementIdea,
        incidentCategory,
        incidentPenalty,
        insurance,
        interrogationReport,
        interrogationReportType,
        inventory,
        inventoryCategory,
        inventoryDamage,
        inventoryEmployee,
        inventoryGood,
        inventoryReceipt,
        inventoryReturn,
        kpi,
        leave,
        leaveCategory,
        loan,
        loanTransaction,
        maritalStatus,
        memorandum,
        memorandumParameter,
        month,
        officeHour,
        overtime,
        payroll,
        payrollComponent,
        payrollComponentType,
        payrollParameter,
        payrollType,
        period,
        position,
        pph,
        ptkp,
        publicHoliday,
        receiptParameter,
        recruitment,
        recruitmentStep,
        report,
        recruitmentStepParameter,
        religion,
        reimburse,
        reimburseCategory,
        salary,
        salaryType,
        saving,
        scheduleRecruitment,
        score,
        statusRecruitment,
        training,
        trainingType,
        transportation,
        year
    }
};
