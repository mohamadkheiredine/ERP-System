DROP TABLE `lg_shipment_operations`;
CREATE TABLE `lg_shipment_operations` (
  `so_id` int NOT NULL AUTO_INCREMENT,
  `so_operation_reference` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `so_operation_label` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `so_operation_description` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `so_operation_type` tinyint DEFAULT '0',
  `so_owner_id` int DEFAULT '0',
  `so_creation_date` datetime DEFAULT NULL,
  `so_operation_date` date DEFAULT NULL,
  `so_operation_time` time DEFAULT NULL,
  `so_operation_status` tinyint DEFAULT '0',
  `so_country_source` smallint DEFAULT '0',
  `so_country_destination` smallint DEFAULT '0',
  `so_warehouse_source` smallint DEFAULT '0',
  `so_warehouse_destination` smallint DEFAULT '0',
  `so_operation_vehicule` smallint DEFAULT '0',
  `so_is_deleted` tinyint DEFAULT '0',
  `so_deleted_by` int DEFAULT '0',
  PRIMARY KEY (`so_id`),
  KEY `so_warehouse_source_idx` (`so_warehouse_source`),
  KEY `so_owner_id_idx` (`so_owner_id`),
  CONSTRAINT `so_owner_id` FOREIGN KEY (`so_owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `so_warehouse_source` FOREIGN KEY (`so_warehouse_source`) REFERENCES `inventory_warehouses` (`w_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


ALTER TABLE `crm_accounts` ADD COLUMN `ca_account_code` VARCHAR(5) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL AFTER `ca_account_rating`,
CHANGE COLUMN `ca_creation_date` `ca_creation_date` DATE NULL DEFAULT NULL ;




ALTER TABLE `crm_services` ADD COLUMN `cs_service_cost` DECIMAL NULL DEFAULT 0 AFTER `cs_service_description`;
ALTER TABLE `crm_services` ADD COLUMN `cs_validate_payment_type` TINYINT NULL DEFAULT 0 AFTER `cs_purchase_accounting_code`;
ALTER TABLE `users` ADD COLUMN `u_hourly_rate` FLOAT NULL DEFAULT 0 AFTER `u_user_sallary`,CHANGE COLUMN `u_employment_date` `u_employment_date` DATE NULL DEFAULT NULL ;

INSERT INTO `sys_appconfig` (`sa_config_index`, `sa_config_description`, `sa_config_value`, `sa_config_type`, `sa_is_active`) VALUES ('hourly_rate_sallary', 'Sallery By Hourly Rate without taxes', '1', '1', '1');


CREATE TABLE `sh_order_suppliers` (
  `fk_order_supplier_id` mediumint NOT NULL DEFAULT '0',
  `fk_ship_order_id` int NOT NULL DEFAULT '0',
  `os_ship_category_id` int NOT NULL DEFAULT '0',
  `os_order_weight` float NOT NULL DEFAULT '0',
  `os_weight_price` decimal(10,0) NOT NULL DEFAULT '0',
  `os_weight_unit` smallint NOT NULL DEFAULT '0',
  `os_currency_id` smallint NOT NULL DEFAULT '0',
  KEY `idx_os_weight_price` (`os_weight_price`) USING BTREE /*!80000 INVISIBLE */,
  KEY `idx_os_order_weight` (`os_order_weight`) USING BTREE /*!80000 INVISIBLE */,
  KEY `idx_os_category_id` (`os_ship_category_id`) USING BTREE /*!80000 INVISIBLE */,
  KEY `idx_fk_order_id` (`fk_ship_order_id`) /*!80000 INVISIBLE */,
  KEY `idx_fk_supplier_order_id` (`fk_order_supplier_id`) /*!80000 INVISIBLE */,
  CONSTRAINT `fk_order_supplier_id` FOREIGN KEY (`fk_order_supplier_id`) REFERENCES `srm_suppliers` (`ss_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


ALTER TABLE `lg_shipment_operations` 
ADD COLUMN `so_transportation_mode` TINYINT NULL DEFAULT 0 AFTER `so_operation_vehicule`;


CREATE TABLE `lg_transportation_mode` (
  `tm_id` TINYINT NOT NULL AUTO_INCREMENT,
  `tm_mode` VARCHAR(255) NULL DEFAULT NULL,
  `tm_is_deleted` TINYINT NULL DEFAULT 0,
  `tm_deleted_by` INT NULL DEFAULT 0,
  PRIMARY KEY (`tm_id`),
  INDEX `idx_tm_mode` USING BTREE (`tm_mode`) VISIBLE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;
INSERT INTO `lg_transportation_mode` (`tm_mode`) VALUES ('Road Transport');
INSERT INTO `lg_transportation_mode` (`tm_mode`) VALUES ('Rail Transport');
INSERT INTO `lg_transportation_mode` (`tm_mode`) VALUES ('Air Transport');
INSERT INTO `lg_transportation_mode` (`tm_mode`) VALUES ('Sea Transport');
INSERT INTO `lg_transportation_mode` (`tm_mode`) VALUES ('Intermodal Transport');
INSERT INTO `lg_transportation_mode` (`tm_mode`) VALUES ('Pipeline Transport');
INSERT INTO `lg_transportation_mode` (`tm_mode`) VALUES ('Multimodal Transport');


ALTER TABLE `lg_shipment_operations` Add COLUMN `so_transportation_mode` TINYINT NULL DEFAULT 0 AFTER `so_operation_description`;


CREATE TABLE `lg_operations_orders` (
  `fk_oo_operation_id` INT NULL DEFAULT 0,
  `fk_oo_order_id` INT NULL DEFAULT 0,
  INDEX `fk_oo_operation_id_idx` (`fk_oo_operation_id` ASC) VISIBLE,
  CONSTRAINT `fk_oo_operation_id`
    FOREIGN KEY (`fk_oo_operation_id`)
    REFERENCES `lg_shipment_operations` (`so_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


ALTER TABLE `sh_order_categories` 
ADD COLUMN `fk_oc_supplier_id` MEDIUMINT NULL DEFAULT 0 AFTER `fk_category_id`,
ADD INDEX `fk_oc_supplier_id_idx` (`fk_oc_supplier_id` ASC) VISIBLE;
;
ALTER TABLE `sh_order_categories` 
ADD CONSTRAINT `fk_oc_supplier_id`
  FOREIGN KEY (`fk_oc_supplier_id`)
  REFERENCES `srm_suppliers` (`ss_id`)
  ON DELETE CASCADE
  ON UPDATE CASCADE;


ALTER TABLE `srm_suppliers` 
ADD COLUMN `ss_supplier_code` VARCHAR(15) CHARACTER SET 'utf8' NULL DEFAULT NULL AFTER `fk_owner_id`;