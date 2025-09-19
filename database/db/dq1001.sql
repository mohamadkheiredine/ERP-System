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






