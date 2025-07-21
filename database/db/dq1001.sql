ALTER TABLE `payrolls_comissions` ADD COLUMN `pc_is_paid` TINYINT NULL DEFAULT 0 AFTER `pc_comission_label`;
ALTER TABLE `payrolls_salary_details` ADD COLUMN `pd_salary_paid` TINYINT NULL DEFAULT 0 AFTER `pd_end_date`;
ALTER TABLE `payrolls_salary_details` ADD COLUMN `pd_payroll_transaction` INT NULL DEFAULT 0 AFTER `pd_salary_paid`;
ALTER TABLE `payrolls_transactions` ADD COLUMN `ot_total_comissions` DECIMAL NULL DEFAULT 0 AFTER `pt_total_deductions`;
ALTER TABLE  `payrolls_transactions` ADD COLUMN `pt_transaction_id` INT NULL DEFAULT 0 AFTER `pt_status`;
ALTER TABLE `payrolls_transactions` ADD COLUMN `pt_transaction_date` DATE NULL DEFAULT NULL AFTER `ot_total_comissions`, CHANGE COLUMN `pt_total_deductions` `pt_total_deductions` DECIMAL(10,2) NULL DEFAULT '0.00' AFTER `pt_transaction_id`;
