-- INSERT INTO `candidates` (`id`, `position_id`, `religion_id`, `marital_status_id`, `company_id`, `photo`, `ktp`, `name`, `gender`, `address`, `post_code`, `birth_place`, `birth_at`, `has_sim`, `sim`, `email`, `phone_number`, `achivement`, `thesis_title`, `last_salary`, `last_facility`, `achivement_during_work`, `last_position`, `last_job_desc`, `applying_reason`, `work_environment`, `expected_salaries`, `expected_facilities`, `work_out_of_town`, `placed_out_of_town`, `work_accident`, `date_of_accident`, `psychological_check`, `date_of_check`, `check_place`, `check_purpose`, `vehicle_type`, `vehicle_belong`, `employees_name`, `employees_relationship`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
-- (2, 2, 1, 2, 2, 'Tes-1599787832.jpg', '123498700000', 'Aksal', '1', 'Bandung', '4220', 'BANDUNG', '2000-09-09', 'Yes', '12348790', 'aksal@gmail.com', '+6285795560811', 'Olimpiade Matematika', '-', 400000, 'Laptop', '-', NULL, 'Membuat Program', 'Mempunya standar yang tinggi', 'Office', 7000000, 'Laptop', 'Yes', 'Yes', 'Yes', '2020-08-31', 'Yes', '2020-08-31', 'IDK', 'IDK', 'Motor', 'Orang Tua', NULL, NULL, 1, 1, 1, '2020-09-11 01:30:33', '2020-09-11 06:11:18', NULL);



-- INSERT INTO `candidate_childrens` (`id`, `candidate_id`, `name`, `date_of_birth`, `gender`, `last_education`, `company_name`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
-- (3, 2, 'Nana', '2020-09-02', '0', '-', '-', 1, 1, 1, '2020-09-11 06:11:18', '2020-09-11 06:11:18', NULL);



-- -- INSERT INTO `candidate_couples` (`id`, `candidate_id`, `name`, `date_of_birth`, `gender`, `last_education`, `company_name`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
-- -- (1, 2, 'Susi', '2020-08-31', '0', 'SMA', '-', 1, 1, 1, '2020-09-11 06:11:18', '2020-09-11 06:11:18', NULL);



-- -- INSERT INTO `candidate_education_backgrounds` (`id`, `candidate_id`, `school_name`, `major`, `city`, `entry_year`, `graduation_year`, `score`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
-- -- (9, 2, 'SDN IBU DEWI 2 CIANJUR', '-', 'CIANJUR', 2006, 2012, 25, 1, 1, 1, '2020-09-11 06:11:18', '2020-09-11 06:11:18', NULL),
-- -- (10, 2, 'SMPN 1 CIANJUR', '-', 'CIANJUR', 2012, 2015, 27, 1, 1, 1, '2020-09-11 06:11:18', '2020-09-11 06:11:18', NULL),
-- -- (11, 2, 'SMAN 1 CIANJUR', 'IPA', 'CIANJUR', 2015, 2018, 30, 1, 1, 1, '2020-09-11 06:11:18', '2020-09-11 06:11:18', NULL);


-- -- INSERT INTO `candidate_emergencies` (`id`, `candidate_id`, `name`, `address`, `phone`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
-- -- (2, 2, 'Tini Surtini', 'Cianjur', '8129348', 1, 1, 1, '2020-09-11 06:11:18', '2020-09-11 06:11:18', NULL);


-- -- INSERT INTO `candidate_job_experiences` (`id`, `candidate_id`, `company_name`, `address`, `phone`, `position`, `start_year`, `end_year`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
-- -- (2, 1, 'Kecamatan', 'Btununggal', '1823489', 'Programmer', 1970, 1970, 1, 1, 1, '2020-09-10 09:32:28', '2020-09-10 09:32:28', NULL);


-- -- INSERT INTO `candidate_languages` (`id`, `candidate_id`, `language`, `speaking`, `writing`, `reading`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
-- -- (3, 2, 'Indonesia', 'Excellent', 'Excellent', 'Excellent', 1, 1, 1, '2020-09-11 06:11:18', '2020-09-11 06:11:18', NULL),
-- -- (4, 2, 'Inggris', 'OK', 'OK', 'OK', 1, 1, 1, '2020-09-11 06:11:18', '2020-09-11 06:11:18', NULL);


-- -- INSERT INTO `candidate_parents` (`id`, `candidate_id`, `type`, `name`, `date_of_birth`, `gender`, `last_education`, `company_name`, `address`, `phone`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
-- -- (5, 2, 'Biological', 'Tini Surtini', '2020-09-01', '0', NULL, '-', 'Cianjur', '81234912', 1, 1, 1, '2020-09-11 06:11:18', '2020-09-11 06:11:18', NULL),
-- -- (6, 2, 'In Law', 'Tina Surtina', '2020-09-07', '0', NULL, '-', 'Cimahi', '812349', 1, 1, 1, '2020-09-11 06:11:18', '2020-09-11 06:11:18', NULL);


-- -- INSERT INTO `candidate_references` (`id`, `candidate_id`, `name`, `address`, `phone`, `job`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
-- -- (3, 2, 'Hana', '-', '81234987', 'Staff', 1, 1, 1, '2020-09-11 06:11:18', '2020-09-11 06:11:18', NULL);


-- -- INSERT INTO `candidate_relatives` (`id`, `candidate_id`, `relation`, `name`, `gender`, `company_name`, `position`, `phone`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
-- -- (3, 2, 'Friend', 'Haris', '1', 'PT Amidis Tirta Mulia', 'Operator Zona Basah', 'undefined', 1, 1, 1, '2020-09-11 06:11:18', '2020-09-11 06:11:18', NULL),
-- -- (4, 2, 'Family', 'Rina', '0', 'PT Sarana Makin Mulya', 'Security', 'undefined', 1, 1, 1, '2020-09-11 06:11:18', '2020-09-11 06:11:18', NULL);


-- -- INSERT INTO `candidate_siblings` (`id`, `candidate_id`, `name`, `date_of_birth`, `gender`, `last_education`, `company_name`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
-- -- (4, 2, 'Rendi', '2020-08-31', '1', 'SMP', '-', 1, 1, 1, '2020-09-11 06:11:18', '2020-09-11 06:11:18', NULL);


-- -- INSERT INTO `candidate_trainings` (`id`, `candidate_id`, `training_type`, `organizer`, `year`, `is_active`, `created_by`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
-- -- (3, 2, 'Javascript', 'Oracle', 2020, 1, 1, 1, '2020-09-11 06:11:18', '2020-09-11 06:11:18', NULL);

