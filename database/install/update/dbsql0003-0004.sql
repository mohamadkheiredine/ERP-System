ALTER TABLE `inventory_warehouses` 
ADD COLUMN `w_warehouse_location` TEXT CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL AFTER `w_material_warehouse`,
ADD COLUMN `w_owner_id` INT NULL DEFAULT 0 AFTER `w_warehouse_location`,
ADD COLUMN `w_opening_time` VARCHAR(8) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL AFTER `w_owner_id`,
ADD COLUMN `w_closing_time` VARCHAR(8) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL AFTER `w_opening_time`,
ADD COLUMN `w_operation_days` VARCHAR(25) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL AFTER `w_closing_time`;


ALTER TABLE `inventory_warehouses` 
ADD COLUMN `w_security_levels` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL AFTER `w_operation_days`,
ADD COLUMN `w_access_control` VARCHAR(255) NULL DEFAULT NULL AFTER `w_security_levels`,
ADD COLUMN `w_securoty_policy` TEXT CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL AFTER `w_access_control`;




CREATE TABLE `sh_category_prices` (
  `cp_id` INT NOT NULL,
  `fk_category_id` INT NULL DEFAULT 0,
  `cp_range_label` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL,
  `cp_weight_from` FLOAT NULL DEFAULT 0,
  `cp_weight_to` FLOAT NULL DEFAULT 0,
  `cp_weight_unit` TINYINT NULL DEFAULT 0,
  `cp_price_range` DECIMAL(10,2) NULL DEFAULT 0,
  `cp_price_currency` SMALLINT NULL DEFAULT 0,
  `cp_is_deleted` TINYINT NULL DEFAULT 0,
  `cp_deleted_by` INT NULL DEFAULT 0,
  PRIMARY KEY (`cp_id`),
  INDEX `idx_shp_fk_category_id` (`fk_category_id` ASC) INVISIBLE,
  INDEX `idx_cp_weight_from` USING BTREE (`cp_weight_from`) INVISIBLE,
  INDEX `idx_cp_weight_to` (`cp_weight_to` ASC) INVISIBLE,
  INDEX `idx_cp_price_range` (`cp_price_range` ASC) INVISIBLE,
  CONSTRAINT `fk_category_id`
    FOREIGN KEY (`fk_category_id`)
    REFERENCES `inventory_product_categories` (`pc_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;



CREATE TABLE `sh_order_status` (
  `ss_id` TINYINT NOT NULL AUTO_INCREMENT,
  `ss_parent_id` TINYINT NULL DEFAULT 0,
  `ss_order_title` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL,
  `ss_is_deleted` TINYINT NULL DEFAULT 0,
  `ss_deleted_by` INT NULL DEFAULT 0,
  PRIMARY KEY (`ss_id`),
  INDEX `idx_ss_parent_id` (`ss_parent_id` ASC) INVISIBLE,
  INDEX `idx_ss_order_title` USING BTREE (`ss_order_title`) VISIBLE);

CREATE TABLE `sh_shipping_orders` (
  `so_id` int NOT NULL AUTO_INCREMENT,
  `so_order_code` varchar(5) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `fk_status_id` tinyint DEFAULT '0',
  `so_order_label` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `so_order_note` text COLLATE utf8mb3_unicode_ci,
  `so_customer_id` mediumint DEFAULT '0',
  `so_supplier_id` mediumint DEFAULT '0',
  `so_invoice_id` int DEFAULT '0',
  `so_creation_date` date DEFAULT NULL,
  `so_paied_date` date DEFAULT NULL,
  `so_delivery_date` date DEFAULT NULL,
  `so_payment_type` tinyint DEFAULT '0',
  `so_total_weight` float DEFAULT '0',
  `so_total_unit` tinyint DEFAULT '0',
  `so_total_price` float DEFAULT '0',
  `so_currency_id` smallint DEFAULT '0',
  `so_is_deleted` tinyint DEFAULT '0',
  `so_deleted_by` int DEFAULT '0',
  PRIMARY KEY (`so_id`),
  KEY `idx_so_order_code` (`so_order_code`) /*!80000 INVISIBLE */,
  KEY `idx_fk_status_id` (`fk_status_id`) /*!80000 INVISIBLE */,
  KEY `idx_so_customer_id` (`so_customer_id`) /*!80000 INVISIBLE */,
  KEY `idx_so_supplier_id` (`so_supplier_id`) /*!80000 INVISIBLE */,
  KEY `idx_so_total_weight` (`so_total_weight`) /*!80000 INVISIBLE */,
  KEY `idx_so_total_price` (`so_total_price`) USING BTREE,
  KEY `idx_so_invoice_id` (`so_invoice_id`),
  CONSTRAINT `fk_so_customer_id` FOREIGN KEY (`so_customer_id`) REFERENCES `inventory_customers` (`ic_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_so_invoice_id` FOREIGN KEY (`so_invoice_id`) REFERENCES `billing_invoices` (`bi_id`) ON DELETE SET NULL ON UPDATE SET NULL,
  CONSTRAINT `fk_so_supplier_id` FOREIGN KEY (`so_supplier_id`) REFERENCES `srm_suppliers` (`ss_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

CREATE TABLE `sh_order_products` (
  `fk_order_id` int DEFAULT '0',
  `fk_category_id` int DEFAULT '0',
  `so_package_weight` float DEFAULT '0',
  `so_package_cost` float DEFAULT '0',
  `so_package_price` float DEFAULT '0',
  `so_package_currency` smallint DEFAULT '0',
  KEY `idx_sp_order_id` (`fk_order_id`),
  KEY `idx_fk_category_id` (`fk_category_id`),
  KEY `idx_so_package_weight` (`so_package_weight`),
  KEY `idx_so_package_price` (`so_package_price`) USING BTREE,
  CONSTRAINT `fk_ssp_category_id` FOREIGN KEY (`fk_category_id`) REFERENCES `inventory_product_categories` (`pc_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;