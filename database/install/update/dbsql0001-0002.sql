ALTER TABLE `inventory_products` 
ADD COLUMN `p_product_expiry_date` VARCHAR(15) NULL DEFAULT NULL AFTER `p_product_profile_extention`,
ADD COLUMN `p_product_production_date` VARCHAR(15) NULL DEFAULT NULL AFTER `p_product_expiry_date`;


ALTER TABLE `inventory_product_categories` ADD COLUMN `pc_use_serial_number` TINYINT NULL DEFAULT 0 AFTER `pc_description`;
ALTER TABLE `inventory_product_categories` ADD COLUMN `pc_maintenance_category` TINYINT NULL DEFAULT 0 AFTER `pc_use_serial_number`;

ALTER TABLE `company_details` ADD COLUMN `cd_default_item` TINYINT NULL DEFAULT 0 AFTER `cd_company_tax`;
ALTER TABLE `company_details` ADD COLUMN `cd_company_homepage` TINYINT NULL DEFAULT 0 AFTER `cd_default_item`;


ALTER TABLE `inventory_stocks` ADD COLUMN `is_selling_price` DECIMAL(10,0) NULL DEFAULT 0 AFTER `is_stock_currency`;
ALTER TABLE `inventory_stocks` ADD COLUMN `is_discount` FLOAT NULL DEFAULT 0 AFTER `is_selling_price`;
ALTER TABLE `inventory_stocks` ADD COLUMN `is_vendor_price` DECIMAL(10,0) NULL DEFAULT 0 AFTER `is_discount`;


CREATE TABLE `inventory_stock_ids` (
  `si_stock_id` INT NOT NULL DEFAULT 0,
  `si_stock_uid` INT NOT NULL DEFAULT 0);
  
  
  ALTER TABLE `inventory_stock_ids` CHANGE COLUMN `si_stock_uid` `si_stock_uid` VARCHAR(255) NULL DEFAULT NULL ;
  
  
  DROP TABLE `prod_production_plan`;
  CREATE TABLE `prod_production_plan` (
  `pp_id` int NOT NULL AUTO_INCREMENT,
  `pp_plan_code` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `pp_plan_label` varchar(500) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `pp_plan_description` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `pp_production_manager` int DEFAULT '0',
  `pp_plan_status` tinyint DEFAULT '0',
  `pp_prepare_date` date DEFAULT NULL,
  `pp_end_date` date DEFAULT NULL,
  `pp_start_date` date DEFAULT NULL,
  `pp_finish_date` date DEFAULT NULL,
  `pp_customer_id` mediumint DEFAULT '0',
  `pp_estimation_time` time DEFAULT NULL,
  `pp_real_duration` time DEFAULT NULL,
  `pp_assign_to` int DEFAULT '0',
  `pp_is_approved` tinyint DEFAULT '0',
  `pp_approval_date` date DEFAULT NULL,
  `pp_approve_note` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `pp_approval_user` int DEFAULT '0',
  `pp_created_by` int DEFAULT '0',
  `pp_creation_date` date DEFAULT NULL,
  `pp_run_production` tinyint DEFAULT '0',
  `pp_start_production` tinyint DEFAULT '0',
  `pp_production_pause` tinyint DEFAULT '0',
  `pp_production_block` tinyint DEFAULT '0',
  `pp_is_deleted` tinyint DEFAULT '0',
  `pp_deleted_by` int DEFAULT '0',
  PRIMARY KEY (`pp_id`),
  KEY `idx_pp_plan_code` (`pp_plan_code`),
  KEY `idx_pp_plan_status` (`pp_plan_status`),
  KEY `idx_pp_customer_id` (`pp_customer_id`) USING BTREE,
  KEY `idx_pp_production_manager_idx` (`pp_production_manager`) USING BTREE,
  CONSTRAINT `fk_pp_customer_id` FOREIGN KEY (`pp_customer_id`) REFERENCES `inventory_customers` (`ic_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_pp_plan_status` FOREIGN KEY (`pp_plan_status`) REFERENCES `prod_plan_status` (`ps_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_pp_production_manager` FOREIGN KEY (`pp_production_manager`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;



DROP TABLE mrp_bill_material;
CREATE TABLE `mrp_bill_material` (
  `bm_id` MEDIUMINT NOT NULL AUTO_INCREMENT,
  `bm_bom_label` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL,
  `fk_finish_product_id` INT NULL DEFAULT 0,
  `bm_bom_version` FLOAT NULL DEFAULT 0,
  `bm_bom_description` TEXT CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL,
  `bm_effective_date` DATE NULL DEFAULT NULL,
  `bm_status` TINYINT NULL DEFAULT 0,
  `bm_responsible_id` INT NULL DEFAULT 0,
  `fk_currency_id` SMALLINT NULL DEFAULT 0,
  `bm_type` SMALLINT NULL DEFAULT 0,
  `bm_lead_time` VARCHAR(8) NULL DEFAULT NULL,
  `bm_production_rate` INT NULL DEFAULT 0,
  `bm_comments` TEXT NULL DEFAULT NULL,
  `bm_unit_of_measure` SMALLINT NULL DEFAULT 0,
  `bm_is_deleted` TINYINT NULL DEFAULT 0,
  `bm_deleted_by` INT NULL DEFAULT 0,
  PRIMARY KEY (`bm_id`),
  INDEX `idx_fk_finish_product_id` (`fk_finish_product_id` ASC) INVISIBLE,
  INDEX `idx_bm_bom_label` (`bm_bom_label` ASC) INVISIBLE,
  INDEX `idx_bm_responsible_id` USING BTREE (`bm_responsible_id`) VISIBLE,
  CONSTRAINT `fk_finish_product_id`
    FOREIGN KEY (`fk_finish_product_id`)
    REFERENCES `inventory_products` (`p_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `bm_responsible_id`
    FOREIGN KEY (`bm_responsible_id`)
    REFERENCES `users` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


CREATE TABLE`bom_line_items` (
  `bl_id` INT NOT NULL AUTO_INCREMENT,
  `fk_bom_id` MEDIUMINT NULL DEFAULT 0,
  `bl_item_label` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL,
  `bl_quantity_required` DECIMAL NULL DEFAULT 0,
  `bl_units` VARCHAR(5) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL,
  `bl_scrap_percentage` FLOAT NULL DEFAULT 0,
  `bl_sequence_number` SMALLINT NULL DEFAULT 0,
  `bl_component_id` VARCHAR(5) NULL DEFAULT NULL,
  `bl_component_description` TEXT CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL,
  `bl_effective_date` DATETIME NULL DEFAULT NULL,
  `bl_lead_time` VARCHAR(5) NULL DEFAULT NULL,
  `bl_component_status` TINYINT NULL DEFAULT 0,
  `bl_is_deleted` TINYINT NULL DEFAULT 0,
  `bl_deleted_by` INT NULL DEFAULT 0,
  PRIMARY KEY (`bl_id`));


  
  CREATE TABLE `mrp_bom_routine` (
  `br_id` INT NOT NULL AUTO_INCREMENT,
  `br_routinelabel` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL,
  `br_bom_id` MEDIUMINT NULL DEFAULT 0,
  `br_operation_sequence` MEDIUMINT NULL DEFAULT 0,
  `br_work_center_id` TINYINT NULL DEFAULT 0,
  `br_machine_id` SMALLINT NULL DEFAULT 0,
  `br_setup_time` TIMESTAMP NULL DEFAULT NULL,
  `br_run_time` TIMESTAMP NULL DEFAULT NULL,
  `br_cycle_time` TIMESTAMP NULL DEFAULT NULL,
  `br_operation_status` TINYINT NULL DEFAULT 0,
  `br_effective_date` DATETIME NULL DEFAULT NULL,
  `br_comments` TEXT CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL,
  `br_work_instructions` TEXT CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL,
  `br_tooling_fixtures` TEXT CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL,
  `br_is_deleted` TINYINT NULL DEFAULT 0,
  `br_deleted_by` INT NULL DEFAULT 0,
  PRIMARY KEY (`br_id`),
  INDEX `idx_br_bom_id` USING BTREE (`br_bom_id`) VISIBLE,
  INDEX `idx_br_operation_sequence` USING BTREE (`br_operation_sequence`) VISIBLE,
  CONSTRAINT `fk_br_bom_id`
    FOREIGN KEY (`br_bom_id`)
    REFERENCES `retailerp_db`.`mrp_bill_material` (`bm_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;



CREATE TABLE `mrp_bom_status` (
  `mb_id` TINYINT NOT NULL AUTO_INCREMENT,
  `fk_dependancy_id` TINYINT NULL DEFAULT 0,
  `mb_status_label` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL,
  `mb_status_description` TEXT CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL,
  `mb_is_deleted` TINYINT NULL DEFAULT 0,
  `mb_deleted_by` INT NULL DEFAULT 0,
  PRIMARY KEY (`mb_id`),
  INDEX `idx_fk_dependancy_id` (`fk_dependancy_id` ASC) INVISIBLE)
ENGINE = InnoDB DEFAULT CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;



INSERT INTO `sys_appconfig` (`sa_id`, `sa_config_index`, `sa_config_description`, `sa_config_value`, `sa_config_type`, `sa_is_active`) VALUES ('3', 'stock_expiry_date', 'Products has an expiry date', '0', '1', '1');
INSERT INTO `sys_appconfig` (`sa_id`, `sa_config_index`, `sa_config_description`, `sa_config_value`, `sa_config_type`, `sa_is_active`) VALUES ('4', 'max_rows_per_page', 'Max Number of Rows per page', '20', '1', '1');
INSERT INTO `sys_appconfig` (`sa_config_index`, `sa_config_description`, `sa_config_value`, `sa_config_type`, `sa_is_active`) VALUES ('enable_tax_rate', 'Enable Tax Rate Field', '1', '1', '1');
INSERT INTO `sys_appconfig` (`sa_config_index`, `sa_config_description`, `sa_config_value`, `sa_config_type`, `sa_is_active`) VALUES ('price_by_supplier', 'get main prices by supplier', '0', '1', '1');

ALTER TABLE `inventory_product_categories` ADD COLUMN `pc_use_serial_number` TINYINT NULL DEFAULT 0 AFTER `pc_avatar_extension`,ADD COLUMN `pc_maintenance_category` TINYINT NULL DEFAULT 0 AFTER `pc_use_serial_number`;



CREATE TABLE `inventory_stock_ids` (
  `si_stock_id` INT UNSIGNED NULL DEFAULT 0,
  `si_stock_uid` VARCHAR(255) NULL DEFAULT NULL,
  INDEX `fk_si_stock_id_idx` (`si_stock_id` ASC) VISIBLE,
  INDEX `idx_si_stock_uid` USING BTREE (`si_stock_uid`) VISIBLE,
  CONSTRAINT `fk_si_stock_id`
    FOREIGN KEY (`si_stock_id`)
    REFERENCES `inventory_stocks` (`is_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

ALTER TABLE `srm_suppliers` CHANGE COLUMN `ss_date_creation` `ss_date_creation` DATE NULL DEFAULT NULL ;
ALTER TABLE `srm_suppliers` DROP FOREIGN KEY `ss_fk_category_id`;
ALTER TABLE `srm_suppliers` DROP INDEX `idx_ss_category_id` ;



ALTER TABLE `sales_orders` 
ADD COLUMN `so_vendor_id` SMALLINT NULL DEFAULT 0 AFTER `so_assign_to`,
CHANGE COLUMN `so_creation_date` `so_creation_date` DATE NULL DEFAULT NULL ,
CHANGE COLUMN `so_order_date` `so_order_date` DATE NULL DEFAULT NULL ,
CHANGE COLUMN `so_delivery_date` `so_delivery_date` DATE NULL DEFAULT NULL ,
CHANGE COLUMN `so_pay_date` `so_pay_date` DATE NULL DEFAULT NULL ;


ALTER TABLE `sales_orders` 
DROP FOREIGN KEY `so_fk_vat_id`;
ALTER TABLE `sales_orders` 
DROP INDEX `idx_so_vat_id` ;
;


ALTER TABLE `inventory_product_categories` 
DROP FOREIGN KEY `fk_pc_id`;
ALTER TABLE `inventory_product_categories` 
DROP INDEX `fk_pc_id` ;
;


ALTER TABLE `sales_orders` 
ADD COLUMN `so_extra_charges` FLOAT NULL DEFAULT 0 AFTER `so_pos_order`,
ADD COLUMN `so_delivery_fees` FLOAT NULL DEFAULT 0 AFTER `so_extra_charges`;


ALTER TABLE `sales_orders` 
CHANGE COLUMN `so_order_date` `so_order_date` DATETIME NULL DEFAULT NULL ,
CHANGE COLUMN `so_delivery_date` `so_delivery_date` DATETIME NULL DEFAULT NULL ;


ALTER TABLE `inventory_stocks` ADD COLUMN `is_selling_price` DECIMAL NULL DEFAULT 0 AFTER `is_price_item`;
ALTER TABLE `inventory_stocks` ADD COLUMN `is_discount` DECIMAL(10,0) NULL DEFAULT 0 AFTER `is_selling_price`;
ALTER TABLE `inventory_stocks` ADD COLUMN `is_vendor_price` DECIMAL(10,0) NULL DEFAULT 0 AFTER `is_selling_price`;


ALTER TABLE `inventory_stock_ids` ADD COLUMN `si_stock_sold` TINYINT NULL DEFAULT 0 AFTER `si_stock_uid`;

ALTER TABLE `acc_transaction_movements` ADD COLUMN `tm_transaction_date` DATE NULL DEFAULT NULL AFTER `tm_credit`, CHANGE COLUMN `tm_creation_date` `tm_creation_date` DATE NULL DEFAULT NULL ;

ALTER TABLE `inventory_stock_ids` 
DROP FOREIGN KEY `fk_si_stock_id`;
ALTER TABLE `inventory_stock_ids` 
ADD COLUMN `fk_product_id` INT NULL DEFAULT 0 AFTER `si_id`,
ADD COLUMN `fk_stock_id` INT NULL DEFAULT 0 AFTER `fk_product_id`,
CHANGE COLUMN `si_stock_id` `si_id` INT UNSIGNED NULL DEFAULT '0' ,
ADD INDEX `idx_fk_product_id` USING BTREE (`fk_product_id`) INVISIBLE,
ADD INDEX `idx_fk_stock_id` USING BTREE (`fk_stock_id`) VISIBLE;
;
ALTER TABLE `inventory_stock_ids` 
ADD CONSTRAINT `fk_si_stock_id`
  FOREIGN KEY (`si_id`)
  REFERENCES `inventory_stocks` (`is_id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;
  
  
  
  CREATE TABLE `inventory_warehouse_floors` (
  `wf_id` MEDIUMINT NOT NULL AUTO_INCREMENT,
  `fk_warehouse_id` SMALLINT NULL DEFAULT 0,
  `fk_zone_id` SMALLINT NULL DEFAULT 0,
  `wf_floor_title` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL,
  `wf_is_deleted` TINYINT NULL DEFAULT 0,
  `wf_deleted_by` INT NULL DEFAULT 0,
  PRIMARY KEY (`wf_id`),
  INDEX `idx_wf_fk_warehouse_id` (`fk_warehouse_id` ASC) INVISIBLE,
  INDEX `idx_wf_fk_zone_id` USING BTREE (`fk_zone_id`) VISIBLE,
  CONSTRAINT `fk_wf_warehouse_id`
    FOREIGN KEY (`fk_warehouse_id`)
    REFERENCES =`inventory_warehouses` (`w_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_wf_zone_id`
    FOREIGN KEY (`fk_zone_id`)
    REFERENCES `inventory_warehouse_zones` (`wz_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE);
    
    
    ALTER TABLE `srm_supplier_quotations` CHANGE COLUMN `sq_date_submit` `sq_date_submit` DATE NULL DEFAULT NULL ,CHANGE COLUMN `sq_due_date` `sq_due_date` DATE NULL DEFAULT NULL ;
    
    ALTER TABLE `srm_supplier_quotations` DROP FOREIGN KEY `fk_bid_id`;
	ALTER TABLE `srm_supplier_quotations` DROP COLUMN `fk_bid_id`,DROP INDEX `fk_bid_id_idx`;

	
	ALTER TABLE `srm_supplier_quotations` ADD COLUMN `sq_warehouse_id` SMALLINT NULL DEFAULT 0 AFTER `sq_user_id`;
	ALTER TABLE `srm_supplier_products` ADD COLUMN `sp_product_serial` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL AFTER `sp_id`;
	ALTER TABLE `srm_supplier_products` ADD COLUMN `fk_product_id` INT NULL DEFAULT 0 AFTER `sp_id`;
	ALTER TABLE `srm_supplier_products` ADD COLUMN `fk_quotation_id` INT NULL DEFAULT 0 AFTER `fk_product_id`;
	ALTER TABLE `inventory_stocks` ADD COLUMN `fk_floor_id` SMALLINT NULL DEFAULT 0 AFTER `is_supplier_id`;
	ALTER TABLE `srm_supplier_products` ADD COLUMN `sp_product_pruchase_price` DECIMAL NULL DEFAULT 0 AFTER `sp_product_price`,ADD COLUMN `sp_product_selling_price` DECIMAL NULL DEFAULT 0 AFTER `sp_product_pruchase_price`,ADD COLUMN `sp_product_wholesale_price` DECIMAL NULL DEFAULT 0 AFTER `sp_product_selling_price`,ADD COLUMN `sp_product_vendor_price` DECIMAL NULL DEFAULT 0 AFTER `sp_product_wholesale_price`,ADD COLUMN `sp_product_discount` DECIMAL NULL DEFAULT 0 AFTER `sp_product_vendor_price`;
	ALTER TABLE `srm_supplier_products` ADD COLUMN `sp_main_currency` SMALLINT NULL DEFAULT 0 AFTER `sp_product_currency`, CHANGE COLUMN `sp_product_serial` `sp_product_serial` VARCHAR(5000) CHARACTER SET 'utf8mb3' NULL DEFAULT NULL ;
	ALTER TABLE `srm_supplier_products` ADD COLUMN `sp_product_quantity` SMALLINT NULL DEFAULT 0 AFTER `sp_product_discount`;
	
	ALTER TABLE `inventory_stock_ids` DROP FOREIGN KEY `fk_si_stock_id`;
	ALTER TABLE `inventory_stock_ids` CHANGE COLUMN `si_id` `si_id` INT NOT NULL AUTO_INCREMENT ,ADD PRIMARY KEY (`si_id`),DROP INDEX `fk_si_stock_id_idx` ;
	ALTER TABLE `srm_supplier_quotations` ADD COLUMN `sq_invoice_number` VARCHAR(55) NULL DEFAULT NULL AFTER `sq_user_id`,ADD COLUMN `sq_container_number` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL AFTER `sq_invoice_number`;
	
	
	ALTER TABLE `srm_supplier_products` ADD COLUMN `sp_warehouse_id` SMALLINT NULL DEFAULT 0 AFTER `sp_id`,ADD COLUMN `sp_zone_id` MEDIUMINT NULL DEFAULT 0 AFTER `sp_warehouse_id`,ADD COLUMN `sp_floor_id` MEDIUMINT NULL DEFAULT 0 AFTER `sp_zone_id`,ADD INDEX `idx_sp_warehouse_id` (`sp_warehouse_id` ASC) INVISIBLE,ADD INDEX `idx_sp_zone_id` (`sp_zone_id` ASC) INVISIBLE,ADD INDEX `idx_sp_floor_id` USING BTREE (`sp_floor_id`) VISIBLE;
	ALTER TABLE `inventory_products` ADD COLUMN `fk_warehouse_id` SMALLINT NULL DEFAULT 0 AFTER `fk_pc_id`, ADD COLUMN `fk_zone_id` MEDIUMINT NULL DEFAULT '0' AFTER `fk_warehouse_id`, ADD COLUMN `fk_floor_id` MEDIUMINT NULL DEFAULT '0' AFTER `fk_zone_id`, ADD INDEX `idx_fk_zone_id` USING BTREE (`fk_zone_id`) VISIBLE, ADD INDEX `idx_fk_floor_id` USING BTREE (`fk_floor_id`) VISIBLE;
	
	ALTER TABLE `srm_supplier_quotations` ADD COLUMN `sq_trans_id` INT NULL DEFAULT 0 AFTER `sq_currency_id`,ADD COLUMN `sq_mov_id` INT NULL DEFAULT 0 AFTER `sq_trans_id`;
