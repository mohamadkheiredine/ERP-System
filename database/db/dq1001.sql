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


ALTER TABLE `billing_invoice_items` ADD COLUMN `ii_warehouse_id` SMALLINT NULL DEFAULT 0 AFTER `fk_invoice_id`;

