ALTER TABLE `inventory_products` ADD COLUMN `p_product_quantity` MEDIUMINT NULL DEFAULT 0 AFTER `p_product_stock_alert`;
ALTER TABLE `sales_orders` ADD COLUMN `so_whole_sale` TINYINT NULL DEFAULT 0 AFTER `so_order_paied`;

ALTER TABLE `billing_invoices` ADD COLUMN `bi_created_by` INT NULL DEFAULT 0 AFTER `bi_number_payments`, CHANGE COLUMN `bi_invoice_date` `bi_invoice_date` DATE NULL DEFAULT NULL , CHANGE COLUMN `bi_due_date` `bi_due_date` DATE NULL DEFAULT NULL ;
ALTER TABLE `billing_invoices` ADD COLUMN `bi_second_currency` SMALLINT NULL DEFAULT 0 AFTER `bi_invoice_currency`;
ALTER TABLE `billing_invoices` ADD COLUMN `bi_exchange_rate` FLOAT NULL DEFAULT 0 AFTER `bi_second_currency`;
ALTER TABLE `billing_invoice_items` ADD COLUMN `ii_payment_type` TINYINT NULL DEFAULT 0 AFTER `ii_item_label`;
ALTER TABLE `billing_invoice_payments` ADD COLUMN `ip_payment_type` TINYINT NULL DEFAULT 0 AFTER `ip_payment_label`;
ALTER TABLE `inventory_vendors` ADD COLUMN `iv_vendor_account_id` SMALLINT NULL DEFAULT 0 AFTER `iv_vendor_tax_id`,CHANGE COLUMN `iv_date_creation` `iv_date_creation` DATE NULL DEFAULT NULL ;


CREATE TABLE `billing_journal_vouchers` (
  `pj_id` int NOT NULL AUTO_INCREMENT,
  `pj_transaction_id` int DEFAULT '0',
  `pj_movement_id` int DEFAULT '0',
  `pj_user_id` int DEFAULT '0',
  `pj_code` varchar(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `pj_voucher_label` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `pj_voucher_description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `pj_journal_id` smallint DEFAULT '0',
  `pj_account_credit` smallint DEFAULT '0',
  `pj_account_debit` smallint DEFAULT '0',
  `pj_creation_date` date DEFAULT NULL,
  `pj_payment_amount` float DEFAULT '0',
  `pj_extra_amount` decimal(10,2) DEFAULT '0.00',
  `pj_currency_id` smallint DEFAULT '0',
  `pj_sec_currency_id` smallint DEFAULT '0',
  `pj_exchange_rate` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT '0',
  `pj_is_deleted` tinyint DEFAULT '0',
  `pj_deleted_by` int DEFAULT '0',
  PRIMARY KEY (`pj_id`),
  KEY `idx_pj_code` (`pj_code`),
  KEY `idx_pj_account_payable` (`pj_account_credit`),
  KEY `idx_pj_account_receivable` (`pj_account_debit`),
  KEY `fk_pj_user_id_idx` (`pj_user_id`),
  KEY `idx_fk_pj_currency_id` (`pj_currency_id`) USING BTREE,
  CONSTRAINT `fk_pj_currency_id` FOREIGN KEY (`pj_currency_id`) REFERENCES `currency` (`cc_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_pj_user_id` FOREIGN KEY (`pj_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `acc_internal_notes` (
  `in_id` mediumint NOT NULL AUTO_INCREMENT,
  `in_transfer_code` varchar(15) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `in_account_sender` smallint DEFAULT '0',
  `in_account_receivable` smallint DEFAULT '0',
  `fk_trans_id` int DEFAULT '0',
  `fk_mov_id` int DEFAULT '0',
  `in_transfer_date` date DEFAULT NULL,
  `in_created_by` int DEFAULT '0',
  `in_transfert_label` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `in_transfer_notes` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `in_credit_value` decimal(10,0) DEFAULT '0',
  `in_debit_value` decimal(10,0) DEFAULT '0',
  `in_credit_currency` smallint DEFAULT '0',
  `in_second_currency` smallint DEFAULT '0',
  `in_exchange_rate` float DEFAULT '0',
  `in_is_deleted` tinyint DEFAULT '0',
  `in_deleted_by` int DEFAULT '0',
  PRIMARY KEY (`in_id`),
  KEY `idx_in_transfer_label` (`in_transfert_label`),
  KEY `idx_in_transfer_value` (`in_credit_value`),
  KEY `idx_in_transfer_date` (`in_transfer_date`),
  KEY `idx_in_transfer_by` (`in_created_by`),
  KEY `fk_in_account_sender_idx` (`in_account_sender`),
  KEY `fk_in_account_receivable_idx` (`in_account_receivable`),
  KEY `idx_in_credit_code` (`in_transfer_code`) USING BTREE,
  CONSTRAINT `fk_in_account_receivable` FOREIGN KEY (`in_account_receivable`) REFERENCES `acc_accounting_accounts` (`aa_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_in_account_sender` FOREIGN KEY (`in_account_sender`) REFERENCES `acc_accounting_accounts` (`aa_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_in_created_by` FOREIGN KEY (`in_created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


ALTER TABLE `billing_receipts` ADD COLUMN `br_is_deleted` TINYINT NULL DEFAULT 0 AFTER `br_receipt_paid`, ADD COLUMN `br_deleted_by` INT NULL DEFAULT 0 AFTER `br_is_deleted`,
CHANGE COLUMN `br_receipt_date` `br_receipt_date` DATE NULL DEFAULT NULL , CHANGE COLUMN `br_creation_date` `br_creation_date` DATE NULL DEFAULT NULL ;
ALTER TABLE `inventory_products` ADD COLUMN `fk_psupplier_id` MEDIUMINT NULL DEFAULT 0 AFTER `fk_floor_id`;


CREATE TABLE `main_job_status` (
  `js_id` TINYINT NOT NULL AUTO_INCREMENT,
  `js_status_title` VARCHAR(75) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL,
  `js_status_color` VARCHAR(7) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL,
  `js_status_order` TINYINT NULL DEFAULT 0,
  `js_is_deleted` TINYINT NULL DEFAULT 0,
  `js_deleted_by` INT NULL DEFAULT 0,
  PRIMARY KEY (`js_id`),
  INDEX `idx_js_status_title` (`js_status_title` ASC) INVISIBLE)
ENGINE = InnoDB DEFAULT CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;



CREATE TABLE `main_jobs` (
  `j_id` INT NOT NULL AUTO_INCREMENT,
  `j_job_code` VARCHAR(15) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL,
  `j_job_barecode` VARCHAR(25) NULL DEFAULT NULL,
  `j_barecode_img` BLOB NULL DEFAULT NULL,
  `j_job_title` VARCHAR(255) CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL,
  `j_job_description` TEXT CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL,
  `j_job_total_cost` FLOAT NULL DEFAULT 0,
  `j_due_date` DATE NULL DEFAULT NULL,
  `j_job_status_id` TINYINT NULL DEFAULT 0,
  `j_currency_id` SMALLINT NULL DEFAULT 0,
  `j_user_id` INT NULL DEFAULT 0,
  `j_customer_id` MEDIUMINT NULL DEFAULT 0,
  `j_vendor_id` MEDIUMINT NULL DEFAULT 0,
  `j_is_deleted` TINYINT NULL DEFAULT 0,
  `j_deleted_by` INT NULL DEFAULT 0,
  PRIMARY KEY (`j_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;

ALTER TABLE `main_jobs` ADD COLUMN `j_date_creation` DATE NULL DEFAULT NULL AFTER `j_job_total_cost`;


CREATE TABLE `main_job_items` (
  `ji_id` INT NOT NULL AUTO_INCREMENT,
  `fk_job_id` INT NULL DEFAULT 0,
  `ji_item_id` INT NULL DEFAULT 0,
  `ji_item_type` TINYINT NULL DEFAULT 0,
  `ji_item_cost` FLOAT NULL DEFAULT 0,
  `ji_currency_id` SMALLINT NULL DEFAULT 0,
  `ji_quantity` SMALLINT NULL DEFAULT 0,
  `ji_price_item` FLOAT NULL DEFAULT 0,
  PRIMARY KEY (`ji_id`),
  INDEX `idx_fk_job_id` (`fk_job_id` ASC) INVISIBLE,
  INDEX `idx_ji_item_id` USING BTREE (`ji_item_id`) VISIBLE,
  CONSTRAINT `fk_job_id`
    FOREIGN KEY (`fk_job_id`)
    REFERENCES `main_jobs` (`j_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB DEFAULT CHARACTER SET = utf8 COLLATE = utf8_unicode_ci;