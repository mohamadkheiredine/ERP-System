

ALTER TABLE `prod_cycle_expenses` ADD COLUMN `ce_expense_category_id` SMALLINT NULL DEFAULT 0 AFTER `ce_voucher_id`;


CREATE TABLE `prod_farm_stock` (
      `fs_id` INT NOT NULL AUTO_INCREMENT,
      `fs_cycle_id` INT NULL DEFAULT 0,
      `fs_warehouse_id` SMALLINT NULL DEFAULT 0,
      `fs_product_id` INT NULL DEFAULT 0,
      `fs_quantity` DECIMAL NULL DEFAULT 0,
      `fs_creation_date` DATE NULL DEFAULT NULL,
      `fs_created_by` INT NULL DEFAULT 0,
      `fs_is_deleted` TINYINT NULL DEFAULT 0,
      `fs_deleted_by` INT NULL DEFAULT 0,
      PRIMARY KEY (`fs_id`),
      INDEX `idx_cycle_id` USING BTREE (`fs_cycle_id`) VISIBLE,
      INDEX `idx_warehouse_id` USING BTREE (`fs_warehouse_id`) VISIBLE,
      INDEX `idx_product_id` USING BTREE (`fs_product_id`) VISIBLE,
      INDEX `idx_fs_quantity` USING BTREE (`fs_quantity`) VISIBLE,
      CONSTRAINT `fk_cycle_id`
          FOREIGN KEY (`fs_cycle_id`)
              REFERENCES `prod_farm_cycles` (`fc_id`)
              ON DELETE CASCADE
              ON UPDATE CASCADE,
      CONSTRAINT `fs_fk_warehouse_id`
          FOREIGN KEY (`fs_warehouse_id`)
              REFERENCES `inventory_warehouses` (`w_id`)
              ON DELETE CASCADE
              ON UPDATE CASCADE,
      CONSTRAINT `fk_product_id`
          FOREIGN KEY (`fs_product_id`)
              REFERENCES `inventory_products` (`p_id`)
              ON DELETE CASCADE
              ON UPDATE CASCADE )
    ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

ALTER TABLE `prod_farm_stock` ADD COLUMN `fs_stock_label` VARCHAR(500) NULL DEFAULT NULL AFTER `fs_product_id`;



CREATE TABLE `prod_cycle_orders` (
     `co_id` int NOT NULL AUTO_INCREMENT,
     `co_company_id` int DEFAULT '0',
     `co_cycle_id` int DEFAULT '0',
     `co_customer_id` mediumint DEFAULT '0',
     `co_date_creation` datetime DEFAULT NULL,
     `co_order_id` mediumint DEFAULT '0',
     `co_order_amount` decimal(10,0) DEFAULT '0',
     `co_currency_id` smallint DEFAULT '0',
     `co_order_description` text COLLATE utf8mb3_unicode_ci,
     `co_is_paid` tinyint DEFAULT '0',
     `co_is_deleted` tinyint DEFAULT '0',
     `co_deleted_by` int DEFAULT '0',
     PRIMARY KEY (`co_id`),
     KEY `fk_co_company_id_idx` (`co_company_id`),
     KEY `fk_co_cycle_id_idx` (`co_cycle_id`),
     KEY `fk_co_customer_id_idx` (`co_customer_id`),
     KEY `fk_co_order_id_idx` (`co_order_id`),
     CONSTRAINT `fk_co_company_id` FOREIGN KEY (`co_company_id`) REFERENCES `company_details` (`cd_id`) ON DELETE CASCADE ON UPDATE CASCADE,
     CONSTRAINT `fk_co_customer_id` FOREIGN KEY (`co_customer_id`) REFERENCES `inventory_customers` (`ic_id`) ON DELETE CASCADE ON UPDATE CASCADE,
     CONSTRAINT `fk_co_cycle_id` FOREIGN KEY (`co_cycle_id`) REFERENCES `prod_farm_cycles` (`fc_id`) ON DELETE CASCADE ON UPDATE CASCADE,
     CONSTRAINT `fk_co_order_id` FOREIGN KEY (`co_order_id`) REFERENCES `sales_orders` (`so_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

