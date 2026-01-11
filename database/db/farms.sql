CREATE TABLE `prod_cycle_orders` (
 `co_id` INT NOT NULL AUTO_INCREMENT,
 `co_company_id` INT NULL DEFAULT 0,
 `co_cycle_id` INT NULL DEFAULT 0,
 `co_customer_id` MEDIUMINT NULL DEFAULT 0,
 `co_date_creation` DATETIME NULL DEFAULT NULL,
 `co_order_id` MEDIUMINT NULL DEFAULT 0,
 `co_order_amount` DECIMAL NULL DEFAULT 0,
 `co_currency_id` SMALLINT NULL DEFAULT 0,
 `co_order_description` TEXT NULL DEFAULT NULL,
 PRIMARY KEY (`co_id`),
 INDEX `fk_co_company_id_idx` (`co_company_id` ASC) VISIBLE,
 INDEX `fk_co_cycle_id_idx` (`co_cycle_id` ASC) VISIBLE,
 INDEX `fk_co_customer_id_idx` (`co_customer_id` ASC) VISIBLE,
 INDEX `fk_co_order_id_idx` (`co_order_id` ASC) VISIBLE,
 CONSTRAINT `fk_co_company_id`
     FOREIGN KEY (`co_company_id`)
         REFERENCES `company_details` (`cd_id`)
         ON DELETE CASCADE
         ON UPDATE CASCADE,
 CONSTRAINT `fk_co_cycle_id`
     FOREIGN KEY (`co_cycle_id`)
         REFERENCES `prod_farm_cycles` (`fc_id`)
         ON DELETE CASCADE
         ON UPDATE CASCADE,
 CONSTRAINT `fk_co_customer_id`
     FOREIGN KEY (`co_customer_id`)
         REFERENCES `inventory_customers` (`ic_id`)
         ON DELETE CASCADE
         ON UPDATE CASCADE,
 CONSTRAINT `fk_co_order_id`
     FOREIGN KEY (`co_order_id`)
         REFERENCES `sales_orders` (`so_id`)
         ON DELETE CASCADE
         ON UPDATE CASCADE)
    ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


ALTER TABLE `prod_cycle_expenses` ADD COLUMN `ce_expense_category_id` SMALLINT NULL DEFAULT 0 AFTER `ce_voucher_id`;
