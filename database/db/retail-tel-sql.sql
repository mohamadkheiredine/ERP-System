CREATE TABLE `ph_phone_line` (
  `pl_id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `pl_line_title` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL,
  `pl_line_number` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pl_phone_type` TINYINT NULL DEFAULT 0,
   `pl_total_units` FLOAT NOT NULL DEFAULT '0',
  `pl_is_deleted` tinyint(4) DEFAULT '0',
  `pl_deleted_by` int(11) DEFAULT '0',
  PRIMARY KEY (`pl_id`),
  KEY `idx_pl_line_number` (`pl_line_number`),
  KEY `idx_pl_total_units` (`pl_total_units`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


CREATE TABLE `ph_phone_transaction` (
  `pt_id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `fk_phone_line` tinyint(4) DEFAULT '0',
  `pt_phone_to` varchar(45) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pt_transaction_unit` int(11) DEFAULT '0',
  `pt_total_price` float DEFAULT '0',
  `pt_total_currency` smallint(6) DEFAULT '0',
  `pt_is_deleted` tinyint(4) DEFAULT '0',
  `pt_deleted_by` int(11) DEFAULT '0',
  PRIMARY KEY (`pt_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `ph_units_packages` (
  `up_id` TINYINT NOT NULL AUTO_INCREMENT,
  `up_package_label` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL,
  `up_number_urnits` FLOAT NULL DEFAULT 0,
  `up_units_price` FLOAT NULL DEFAULT 0,
  `up_price_currency` SMALLINT NULL DEFAULT 0,
  PRIMARY KEY (`up_id`),
  INDEX `idx_package_label` (`up_package_label` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE `ph_phone_units` (
  `pu_id` TINYINT NOT NULL AUTO_INCREMENT,
  `pu_unit_label` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL,
  `pu_units` FLOAT NULL DEFAULT 0,
  `pu_unit_amount` DECIMAL NULL DEFAULT 0,
  `pu_currency_id` SMALLINT NULL DEFAULT 0,
  PRIMARY KEY (`pu_id`),
  INDEX `idx_pu_units` (`pu_units` ASC),
  INDEX `idx_pu_unit_amount` (`pu_unit_amount` ASC),
  INDEX `idx_pu_currency_id` USING BTREE (`pu_currency_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

ALTER TABLE `sales_order_products` 
DROP FOREIGN KEY `fk_sp_product_id`;
ALTER TABLE `sales_order_products` 
ADD COLUMN `so_is_units` TINYINT NULL DEFAULT 0 AFTER `so_stock_id`,
DROP INDEX `idx_sp_product_id` ;


ALTER TABLE `sales_order_products` ADD COLUMN `so_unit_number` TINYINT NULL DEFAULT 0 AFTER `so_product_currency`,ADD COLUMN `so_unit_label` VARCHAR(255) NULL DEFAULT 0 AFTER `so_unit_number`;
ALTER TABLE `sales_order_products` ADD COLUMN `so_unit_amount` TINYINT NULL DEFAULT 0 AFTER `so_unit_label`;