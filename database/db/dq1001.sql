INSERT INTO `sys_appconfig` (`sa_id`, `sa_config_index`, `sa_config_description`, `sa_config_value`, `sa_config_type`, `sa_is_active`) VALUES ('13', 'show_product_image', 'Show Product Image in Stock List', '0', '1', '1');
INSERT INTO `sys_appconfig` (`sa_id`, `sa_config_index`, `sa_config_description`, `sa_config_value`, `sa_config_type`, `sa_is_active`) VALUES ('14', 'ability_edit_stock_price', 'Ability to Edit Stock Price when transfer', '0', '1', '1');


CREATE TABLE `billing_bills_rvs` (
 `br_id` INT NOT NULL AUTO_INCREMENT,
 `br_deal_id` INT NULL DEFAULT 0,
 `br_bill_id` INT NULL DEFAULT 0,
 `br_client_id` MEDIUMINT NULL DEFAULT 0,
 `br_client_code` VARCHAR(15) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL,
 `br_client_name` VARCHAR(255) NULL DEFAULT NULL,
 `br_bill_amount` DECIMAL NULL DEFAULT 0,
 `br_paid_amount` DECIMAL NULL DEFAULT 0,
 `br_remaining_amount` DECIMAL NULL DEFAULT 0,
 `br_is_deleted` TINYINT NULL DEFAULT 0,
 `br_deleted_by` INT NULL DEFAULT 0,
 PRIMARY KEY (`br_id`),
 INDEX `idx_br_bill_amount` (`br_bill_amount` ASC) INVISIBLE,
 INDEX `idx_br_paid_amount` (`br_paid_amount` ASC) INVISIBLE,
 INDEX `idx_br_remaining_amount` (`br_remaining_amount` ASC) INVISIBLE,
 INDEX `idx_br_client_id` USING BTREE (`br_client_id`) VISIBLE,
 INDEX `fk_br_bill_id_idx` (`br_bill_id` ASC) VISIBLE,
 CONSTRAINT `fk_br_client_id`
     FOREIGN KEY (`br_client_id`)
         REFERENCES `crm_accounts` (`ca_id`)
         ON DELETE CASCADE
         ON UPDATE CASCADE,
 CONSTRAINT `fk_br_bill_id`
     FOREIGN KEY (`br_bill_id`)
         REFERENCES `billing_invoice_payments` (`ip_id`)
         ON DELETE CASCADE
         ON UPDATE CASCADE)
    ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


ALTER TABLE `billing_invoice_payments` ADD COLUMN `ip_payment_status` TINYINT NULL DEFAULT 0 AFTER `ip_payment_type`;
ALTER TABLE `billing_invoice_payments` ADD COLUMN `ip_deal_id` INT NULL DEFAULT 0 AFTER `fk_invoice_id`;


ALTER TABLE `billing_invoice_items` ADD COLUMN `ii_warehouse_id` SMALLINT NULL DEFAULT 0 AFTER `fk_invoice_id`



ALTER TABLE `srm_supplier_products`
    CHANGE COLUMN `sp_product_pruchase_price` `sp_product_pruchase_price` DECIMAL(10,3) NULL DEFAULT '0' ,
    CHANGE COLUMN `sp_product_selling_price` `sp_product_selling_price` DECIMAL(10,3) NULL DEFAULT '0' ,
    CHANGE COLUMN `sp_product_wholesale_price` `sp_product_wholesale_price` DECIMAL(10,3) NULL DEFAULT '0' ,
    CHANGE COLUMN `sp_product_vendor_price` `sp_product_vendor_price` DECIMAL(10,3) NULL DEFAULT '0' ,
    CHANGE COLUMN `sp_product_discount` `sp_product_discount` DECIMAL(10,3) NULL DEFAULT '0' ;


ALTER TABLE `inventory_stocks`
    CHANGE COLUMN `is_wholesale_price` `is_wholesale_price` DECIMAL(10,2) NULL DEFAULT NULL ,
    CHANGE COLUMN `is_price_stock` `is_price_stock` DECIMAL(10,2) NULL DEFAULT '0' ,
    CHANGE COLUMN `is_price_item` `is_price_item` DECIMAL(10,2) NULL DEFAULT '0' ,
    CHANGE COLUMN `is_selling_price` `is_selling_price` DECIMAL(10,2) NULL DEFAULT '0' ,
    CHANGE COLUMN `is_vendor_price` `is_vendor_price` DECIMAL(10,2) NULL DEFAULT '0' ,
    CHANGE COLUMN `is_discount` `is_discount` DECIMAL(10,2) NULL DEFAULT '0' ;



ALTER TABLE `company_details`
    ADD COLUMN `cd_register_number` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL AFTER `cd_contact_email`;


ALTER TABLE `billing_invoice_payments`
    ADD COLUMN `ip_call_result_id` TINYINT NULL DEFAULT 0 AFTER `ip_pay_date`,
ADD COLUMN `ip_result_notes` TEXT CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL AFTER `ip_call_result_id`;


CREATE TABLE `billing_billsresults_workflow` (
 `bw_id` int NOT NULL AUTO_INCREMENT,
 `bw_bill_id` int DEFAULT '0',
 `bw_result_id` tinyint DEFAULT '0',
 `bw_creation_date` date DEFAULT NULL,
 `bw_callback_date` date DEFAULT NULL,
 `bw_result_note` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
 `bw_assigned_to` int DEFAULT '0',
 `bw_is_deleted` tinyint DEFAULT '0',
 `bw_deleted_by` int DEFAULT '0',
 PRIMARY KEY (`bw_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


CREATE TABLE `pos_stores` (
  `ps_id` int NOT NULL AUTO_INCREMENT,
  `ps_company_id` int DEFAULT '0',
  `ps_store_name` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `ps_location` text COLLATE utf8mb3_unicode_ci default NULL,
  `ps_manager_id` int DEFAULT '0',
  `ps_is_active` tinyint DEFAULT '1',
  `ps_is_deleted` tinyint DEFAULT '0',
  `ps_deleted_by` int DEFAULT '0',
  PRIMARY KEY (`ps_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE `pos_store_warehouses` (
    `sw_id` int NOT NULL AUTO_INCREMENT,
    `sw_company_id` int DEFAULT '0',
    `sw_store_id` int DEFAULT '0',
    `sw_warehouse_id` smallint DEFAULT '0',
    `sw_is_default` tinyint DEFAULT '0',
    `sw_is_deleted` tinyint DEFAULT '0',
    PRIMARY KEY (`sw_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


CREATE TABLE `pos_terminals` (
 `pt_id` int NOT NULL AUTO_INCREMENT,
 `pt_store_id` int DEFAULT '0',
 `pt_terminal_name` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
 `pt_description` text COLLATE utf8mb3_unicode_ci,
 `pt_manager_id` int DEFAULT '0',
 `pt_warehouse_id` smallint DEFAULT '0',
 `pt_is_active` tinyint(1) DEFAULT '1',
 `pt_is_deleted` tinyint DEFAULT '0',
 `pt_deleted_by` int DEFAULT '0',
 PRIMARY KEY (`pt_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE `pos_cash_sessions` (
 `cs_session_id` int NOT NULL AUTO_INCREMENT,
 `cs_store_id` int DEFAULT '0',
 `cs_pos_id` int DEFAULT '0',
 `cs_opened_by` int DEFAULT '0',
 `cs_closed_by` int DEFAULT '0',
 `cs_opening_cash` decimal(12,2) DEFAULT '0.00',
 `cs_closing_cash` decimal(12,2) DEFAULT NULL,
 `cs_total_sales` decimal(12,2) DEFAULT '0.00',
 `cs_total_refunds` decimal(12,2) DEFAULT '0.00',
 `cs_total_cash_in` decimal(12,2) DEFAULT '0.00',
 `cs_total_cash_out` decimal(12,2) DEFAULT '0.00',
 `cs_system_cash_expected` decimal(12,2) DEFAULT '0.00',
 `cs_variance` decimal(12,2) DEFAULT NULL,
 `cs_opened_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
 `cs_closed_at` timestamp NULL DEFAULT NULL,
 `cs_is_active` tinyint DEFAULT '1',
 `cs_is_deleted` tinyint DEFAULT '0',
 `cs_deleted_by` int DEFAULT '0',
 PRIMARY KEY (`cs_session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


CREATE TABLE `pos_store_employees` (
`se_store_id` INT NULL DEFAULT 0,
`se_company_id` INT NULL DEFAULT 0,
`se_employee_id` INT NULL DEFAULT 0,
INDEX `fk_se_store_id_idx` (`se_store_id` ASC) VISIBLE,
INDEX `fk_se_company_id_idx` (`se_company_id` ASC) VISIBLE,
INDEX `fk_se_employee_id_idx` (`se_employee_id` ASC) VISIBLE,
CONSTRAINT `fk_se_store_id`
FOREIGN KEY (`se_store_id`)
REFERENCES `pos_stores` (`ps_id`)
ON DELETE CASCADE
ON UPDATE CASCADE,
CONSTRAINT `fk_se_company_id`
FOREIGN KEY (`se_company_id`)
REFERENCES `company_details` (`cd_id`)
ON DELETE CASCADE
ON UPDATE CASCADE,
CONSTRAINT `fk_se_employee_id`
FOREIGN KEY (`se_employee_id`)
REFERENCES `users` (`id`)
ON DELETE CASCADE
ON UPDATE CASCADE)
    ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

ALTER TABLE `acc_expenses` ADD COLUMN `ac_is_paid` TINYINT NULL DEFAULT 0 AFTER `ac_payment_type`;
ALTER TABLE `acc_expenses` ADD COLUMN `ac_payment_id` INT NULL DEFAULT 0 AFTER `ac_category_id`;


CREATE TABLE `usr_allowed_companies` (
`ac_company_id` INT NULL DEFAULT 0,
`ac_user_id` INT NULL DEFAULT 0);


CREATE TABLE sys_governorates (
      sg_id INT PRIMARY KEY AUTO_INCREMENT,
      sg_country_id smallint NOT NULL,
      sg_governorate_name_en VARCHAR(100) NOT NULL,
      sg_governorate_name_ar VARCHAR(100) NOT NULL,
      sg_governorate_code VARCHAR(10),
      sg_capital_city VARCHAR(100),
      sg_area_km2 DECIMAL(10,2),
      sg_population INT,
      sg_created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
      sg_updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      sg_is_deleted tinyint default 0,
      sg_deleted_by int default 0,

      INDEX idx_country_gov (sg_country_id, sg_governorate_name_en),
      INDEX idx_gov_en (sg_governorate_name_en),
      INDEX idx_gov_ar (sg_governorate_name_ar),
      INDEX idx_gov_code (sg_governorate_code)
);


CREATE TABLE sys_districts (
   sd_id INT PRIMARY KEY AUTO_INCREMENT,
   sd_country_id smallINT NOT NULL,
   sd_governorate_id INT NOT NULL,
   sd_district_name_en VARCHAR(100) NOT NULL,
   sd_district_name_ar VARCHAR(100) NOT NULL,
   sd_district_code VARCHAR(10),
   sd_district_type ENUM('District', 'Wilayat', 'Qada', 'Other') DEFAULT 'District',
   sd_capital_city VARCHAR(100),
   sd_area_km2 DECIMAL(10,2),
   sd_population INT,
   sd_created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   sd_updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
   sd_is_deleted tinyint default 0,
   sd_deleted_by int default 0,

   UNIQUE KEY unique_gov_district (sd_governorate_id, sd_district_name_en),

-- Indexes
   INDEX idx_country_district (sd_country_id, sd_district_name_en),
   INDEX idx_district_en (sd_district_name_en),
   INDEX idx_district_ar (sd_district_name_ar),
   INDEX idx_gov_district (sd_governorate_id, sd_district_name_en),
   INDEX idx_district_type (sd_district_type),
   INDEX idx_district_code (sd_district_code)
);


CREATE TABLE sys_cities (
sc_id INT PRIMARY KEY AUTO_INCREMENT,
sc_country_id smallINT default 0,
sc_governorate_id INT default 0,
sc_district_id INT default 0,
sc_city_name_en VARCHAR(255) Default NULL,
sc_city_name_ar VARCHAR(255) Default NULL,
sc_city_type ENUM('City', 'Town', 'Village', 'Municipality', 'Other') DEFAULT 'City',
sc_population INT default 0,
sc_is_capital BOOLEAN DEFAULT FALSE,
sc_is_governorate_capital BOOLEAN DEFAULT FALSE,
sc_is_district_capital BOOLEAN DEFAULT FALSE,
sc_is_major_city BOOLEAN DEFAULT FALSE,
sc_elevation_m INT default 0,
sc_latitude DECIMAL(10, 8) default 0,
sc_longitude DECIMAL(11, 8) default 0,
sc_postal_code VARCHAR(20) default 0,
sc_created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
sc_updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
sc_is_deleted tinyint default 0,
sc_deleted_by int default 0,
-- Foreign key constraints

-- Unique constraint
UNIQUE KEY unique_district_city (sc_district_id, sc_city_name_en),

-- Indexes
INDEX idx_country_city (sc_country_id, sc_city_name_en),
INDEX idx_city_en (sc_city_name_en),
INDEX idx_city_ar (sc_city_name_ar),
INDEX idx_gov_city (sc_governorate_id, sc_city_name_en),
INDEX idx_district_city (sc_district_id, sc_city_name_en),
INDEX idx_city_type (sc_city_type),
INDEX idx_population (sc_population),
INDEX idx_location (sc_latitude, sc_longitude),
INDEX idx_capitals (sc_is_capital, sc_is_governorate_capital, sc_is_district_capital),
INDEX idx_major_cities (sc_is_major_city)
);



ALTER TABLE `inventory_customers`
    ADD COLUMN `ic_birth_date` DATE NULL DEFAULT NULL AFTER `ic_customer_mobile`,
ADD COLUMN `ic_hobbies` TEXT NULL DEFAULT NULL AFTER `ic_birth_date`;



CREATE TABLE `inventory_warehouse_movement` (
    `wm_id` int NOT NULL AUTO_INCREMENT,
    `wm_warehouse_id` smallint DEFAULT '0',
    `wm_product_id` int DEFAULT '0',
    `wm_quantity` decimal(10,0) DEFAULT '0',
    `wm_action_date` datetime DEFAULT NULL,
    `wm_action_type` varchar(10) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
    `wm_action_description` text COLLATE utf8mb3_unicode_ci,
    `wm_is_deleted` tinyint DEFAULT '0',
    `wm_deleted_by` int DEFAULT '0',
    PRIMARY KEY (`wm_id`),
    KEY `fk_wm_warehouse_id_idx` (`wm_warehouse_id`) USING BTREE,
    KEY `fk_wm_product_id_idx` (`wm_product_id`) USING BTREE,
    KEY `idx_wm_quantity` (`wm_quantity`) USING BTREE,
    CONSTRAINT `fk_wm_product_id` FOREIGN KEY (`wm_product_id`) REFERENCES `inventory_products` (`p_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_wm_warehouse_id` FOREIGN KEY (`wm_warehouse_id`) REFERENCES `inventory_warehouses` (`w_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


ALTER TABLE `billing_invoices`
    ADD COLUMN `bi_internal_invoice` TINYINT NULL DEFAULT 0 AFTER `bi_due_date`,
ADD COLUMN `bi_company_to` INT NULL DEFAULT 0 AFTER `bi_internal_invoice`;


ALTER TABLE `company_details`
    ADD COLUMN `cd_default_warehouse` SMALLINT NULL DEFAULT 0 AFTER `cd_register_number`;

ALTER TABLE `inventory_warehouses`
    ADD COLUMN `w_company_id` INT NULL DEFAULT 0 AFTER `fk_w_id`;

ALTER TABLE `inventory_stocks`
    ADD COLUMN `is_stock_unit` SMALLINT NULL DEFAULT 0 AFTER `is_stock_uid`;


ALTER TABLE `crm_lead_app_results`
    ADD COLUMN `ar_app_show_apt` TINYINT NULL DEFAULT 0 AFTER `ar_app_description`;


ALTER TABLE `srm_supplier_products`
    ADD COLUMN `sp_stock_unit` SMALLINT NULL DEFAULT 0 AFTER `sp_product_quantity`;


INSERT INTO `privileged_actions` (`pa_id`, `pa_code`, `pa_name`, `pa_description`, `pa_group`) VALUES ('212', 'erp_purchasing_module', 'Allow users to Access Purchasing Management Module', 'Allow users to Access Purchasing Management Module', 'Purchasing Module');
INSERT INTO `privileged_actions` (`pa_id`, `pa_code`, `pa_name`, `pa_description`, `pa_group`) VALUES ('213', 'erp_purchasing_request_status', 'Allow users to Management request Status', 'Allow users to Management request Status', 'Purchasing Module');
INSERT INTO `privileged_actions` (`pa_id`, `pa_code`, `pa_name`, `pa_description`, `pa_group`) VALUES ('214', 'erp_purchasing_quotation_status', 'Allow users to Management Quotation Status', 'Allow users to Management Quotation Status', 'Purchasing Module');
INSERT INTO `privileged_actions` (`pa_id`, `pa_code`, `pa_name`, `pa_description`, `pa_group`) VALUES ('215', 'erp_purchasing_manage_requisition', 'Allow users to manage Purchase Requisitions', 'Allow users to manage Purchase Requisitions', 'Purchasing Module');
INSERT INTO `privileged_actions` (`pa_id`, `pa_code`, `pa_name`, `pa_description`, `pa_group`) VALUES ('216', 'erp_purchasing_manage_orders', 'Allow users to manage Purchase Orders', 'Allow users to manage Purchase Requisitions', 'Purchasing Module');
INSERT INTO `privileged_actions` (`pa_id`, `pa_code`, `pa_name`, `pa_description`, `pa_group`) VALUES ('217', 'erp_accounting_expenses_module', 'Allow users to Manage Expenses Module', 'Allow users to Manahe Expenses Module', 'Expenses Module');
INSERT INTO `privileged_actions` (`pa_id`, `pa_code`, `pa_name`, `pa_description`, `pa_group`) VALUES ('218', 'erp_accounting_expense_categories', 'Allow users to  Manage Expense Categories', 'Allow users to  Manage Expense Categories', 'Expenses Module');
INSERT INTO `privileged_actions` (`pa_id`, `pa_code`, `pa_name`, `pa_description`, `pa_group`) VALUES ('219', 'erp_acc_expense_status', 'Allow users to Manage Expense Statuses', 'Allow users to Manage Expense Statuses', 'Expenses Module');
INSERT INTO `privileged_actions` (`pa_id`, `pa_code`, `pa_name`, `pa_description`, `pa_group`) VALUES ('220', 'erp_acc_expense', 'Allow users to Manage Expense', 'Allow users to Manage Expense', 'Expenses Module');
INSERT INTO `privileged_actions` (`pa_id`, `pa_code`, `pa_name`, `pa_description`, `pa_group`) VALUES ('221', 'erp_acc_expense_payment', 'Allow users to Manage Expenses Payment', 'Allow users to Manage Expenses Payment', 'Expenses Module');
INSERT INTO `privileged_actions` (`pa_id`, `pa_code`, `pa_name`, `pa_description`, `pa_group`) VALUES ('222', 'erp_closuresales_reports', 'Allow users to access to closure sales report', 'Allow users to access to closure sales report', 'Callcenter Management');
INSERT INTO `privileged_actions` (`pa_id`, `pa_code`, `pa_name`, `pa_description`, `pa_group`) VALUES ('223', 'erp_callback_reports', 'Allow users to access to callback report', 'Allow users to access to callback report', 'Callcenter Management');
INSERT INTO `privileged_actions` (`pa_id`, `pa_code`, `pa_name`, `pa_description`, `pa_group`) VALUES ('224', 'erp_cumulative_month_reports', 'Allow users to access to cumulative monthly leads', 'Allow users to access to cumulative monthly leads', 'Callcenter Management');


ALTER TABLE `inventory_stocks` ADD COLUMN `is_stock_expiry_date` DATE NULL DEFAULT NULL AFTER `is_stock_unit`;

ALTER TABLE `inventory_warehouse_movement`  CHANGE COLUMN `wm_action_type` `wm_action_type` VARCHAR(50) NULL DEFAULT NULL ;

ALTER TABLE `callcenter_calls_products` ADD COLUMN `cp_invoice_id` INT NULL DEFAULT 0 AFTER `cp_technician_id`,
    ADD COLUMN `cp_serial_number` VARCHAR(255) NULL DEFAULT NULL AFTER `cp_invoice_id`;


ALTER TABLE `callcenter_calls_products`
    ADD COLUMN `cp_warehouse_id` SMALLINT NULL DEFAULT 0 AFTER `cp_invoice_id`;



INSERT INTO `privileged_actions` (`pa_id`, `pa_code`, `pa_name`, `pa_description`, `pa_group`) VALUES ('225', 'erp_forcasting_leads_report', 'Allow users to access to forcasting leads report', 'Allow users to access to forcasting leads report', 'Callcenter Management');


ALTER TABLE `inventory_stocks`
    ADD COLUMN `is_production_date` DATE NULL DEFAULT NULL AFTER `is_discount`,
ADD COLUMN `is_expiry_date` DATE NULL DEFAULT NULL AFTER `is_production_date`;

ALTER TABLE `srm_supplier_products`
    ADD COLUMN `sp_production_date` DATE NULL DEFAULT NULL AFTER `sp_product_description`,
ADD COLUMN `sp_expiry_date` DATE NULL DEFAULT NULL AFTER `sp_production_date`;


ALTER TABLE `billing_invoices`
    ADD COLUMN `bi_official_invoice` TINYINT NULL DEFAULT 0 AFTER `bi_last_updated_by`;


INSERT INTO `privileged_actions` (`pa_id`, `pa_code`, `pa_name`, `pa_description`, `pa_group`) VALUES ('226', 'erp_telemarketing_report', 'Allow users to access to telemarketing Report', 'Allow users to access to telemarketing Report', 'Callcenter Management');
