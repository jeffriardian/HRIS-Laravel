import layout from "./layout.vue";

import activityLog from "./activity-log";
import approveBusinesstrip from "./approve-businesstrip";
import approveOvertime from "./approve-overtime";
import approveReimburse from "./approve-reimburse";
import approveIntterogation from "./approve-intterogation";
import attendance from "./attendance";
import attlog from "./attlog";
import bloodType from "./blood-type";
import businessTrip from "./business-trip";
import bpjsKesehatan from "./bpjs-kesehatan";
import bpjsKetenagakerjaan from "./bpjs-ketenagakerjaan";
import candidate from "./candidate";
import company from "./company";
import city from "./city";
import contract from "./contract";
// import reportRecruitment from "./report-recruitment";
import cooperativeMember from "./cooperative-member";
import country from "./country";
import department from "./department";
import departmentLevel from "./department-level";
import disbursement from "./disbursement";
import employee from "./employee";
import externalEmployee from "./external-employee";
import employeeStatus from "./employee-status";
import cooperativeMemberType from "./cooperative-member-type";
import exitClearance from "./exit-clearance";
import exitClearanceParameter from "./exitclearance-parameter";
import historyEmployeeStatus from "./history-employee-status";
import historyEmployeePosition from "./history-employee-position";
import improvementIdea from "./improvement-idea";
import incidentPenalty from "./incident-penalty";
import interrogationReport from "./interrogation-report";
// import interrogationReportType from "./interrogation-report-type";
import insurance from "./insurance";
import inventory from "./inventory";
import inventoryCategory from "./inventory-category";
import inventoryDamage from "./inventory-damage";
import inventoryEmployee from "./inventory-employee";
import inventoryGood from "./inventory-good";
import inventoryReceipt from "./inventory-receipt";
import inventoryReturn from "./inventory-return";
import kpi from "./kpi";
import leave from "./leave";
import leaveCategory from "./leave-category";
import loan from "./loan";
import loanTransaction from "./loan-transaction";
import maritalStatus from "./marital-status";
import memorandum from "./memorandum";
import memorandumParameter from "./memorandum-parameter";
import month from "./month";
import officeHour from "./office-hour";
import overtime from "./overtime";
import payroll from "./payroll";
import payrollComponent from "./payroll-component";
import payrollComponentType from "./payrollcomponent-type";
import payrollParameter from "./payroll-parameter";
import payrollType from "./payroll-type";
import period from "./period";
import position from "./position";
import pph from "./pph";
import ptkp from "./ptkp";
import publicHoliday from "./public-holiday";
import receiptParameter from "./receipt-parameter";
import recruitment from "./recruitment";
import recruitmentStep from "./recruitment-step";
import recruitmentStepParameter from "./recruitment-step-parameter";
import reimburse from "./reimburse";
import reimburseCategory from "./reimburse-category";
import religion from "./religion";
import salary from "./salary";
import salaryType from "./salary-type";
import saving from "./saving";
import scheduleRecruitment from "./schedule-recruitment";
import score from "./score";
import statusRecruitment from "./status-recruitment";
import training from "./training";
import trainingType from "./training-type";
import transportation from "./transportation";
import year from "./year";
import report from "./report";

export default [
    {
        path: "/human-resource",
        component: layout,
        children: [
            ...activityLog,
            ...approveBusinesstrip,
            ...approveOvertime,
            ...approveReimburse,
            ...approveIntterogation,
            ...attendance,
            ...attlog,
            ...businessTrip,
            ...bloodType,
            ...bpjsKesehatan,
            ...bpjsKetenagakerjaan,
            ...candidate,
            ...company,
            ...city,
            ...report,
            // ...reportRecruitment,
            ...contract,
            ...cooperativeMember,
            ...country,
            ...department,
            ...departmentLevel,
            ...disbursement,
            ...employee,
            ...externalEmployee,
            ...employeeStatus,
            ...cooperativeMemberType,
            ...exitClearance,
            ...exitClearanceParameter,
            ...historyEmployeePosition,
            ...historyEmployeeStatus,
            ...improvementIdea,
            ...incidentPenalty,
            ...insurance,
            ...interrogationReport,
            // ...interrogationReportType,
            ...inventory,
            ...inventoryCategory,
            ...inventoryDamage,
            ...inventoryEmployee,
            ...inventoryGood,
            ...inventoryReceipt,
            ...inventoryReturn,
            ...kpi,
            ...leave,
            ...leaveCategory,
            ...loan,
            ...loanTransaction,
            ...maritalStatus,
            ...memorandum,
            ...memorandumParameter,
            ...month,
            ...officeHour,
            ...overtime,
            ...payroll,
            ...payrollComponent,
            ...payrollComponentType,
            ...payrollParameter,
            ...payrollType,
            ...period,
            ...position,
            ...pph,
            ...ptkp,
            ...publicHoliday,
            ...receiptParameter,
            ...recruitment,
            ...recruitmentStep,
            ...recruitmentStepParameter,
            ...religion,
            ...reimburse,
            ...reimburseCategory,
            ...salary,
            ...salaryType,
            ...saving,
            ...scheduleRecruitment,
            ...score,
            ...statusRecruitment,
            ...training,
            ...trainingType,
            ...transportation,
            ...year
        ]
    }
];
