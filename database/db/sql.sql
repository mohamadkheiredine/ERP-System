ALTER TABLE `crm_services` ADD COLUMN `cs_validate_payment_type` TINYINT NULL DEFAULT 0 COMMENT 'validate payment type whern add it to invoice' AFTER `cs_currency_id`;
ALTER TABLE `billing_invoice_items` ADD COLUMN `ii_payment_type_id` TINYINT NULL DEFAULT 0 AFTER `ii_purchase_account_id`;


CREATE TABLE `crm_services_payment_types` (
  `st_id` MEDIUMINT NOT NULL AUTO_INCREMENT,
  `st_service_id` MEDIUMINT NULL DEFAULT 0,
  `st_payment_type_id` TINYINT NULL DEFAULT 0,
  `st_account_income_id` SMALLINT NULL DEFAULT 0,
  `st_account_purchase_Id` SMALLINT NULL DEFAULT 0,
  PRIMARY KEY (`st_id`),
  INDEX `fk_st_service_id_idx` (`st_service_id` ASC),
  INDEX `fk_st_payment_type_id_idx` (`st_payment_type_id` ASC),
  INDEX `fk_st_account_income_id_idx` (`st_account_income_id` ASC),
  INDEX `fk_st_account_purchase_Id_idx` (`st_account_purchase_Id` ASC),
  CONSTRAINT `fk_st_service_id`
    FOREIGN KEY (`st_service_id`)
    REFERENCES `crm_services` (`cs_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_st_payment_type_id`
    FOREIGN KEY (`st_payment_type_id`)
    REFERENCES `billing_payment_types` (`pt_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_st_account_income_id`
    FOREIGN KEY (`st_account_income_id`)
    REFERENCES `acc_accounting_accounts` (`aa_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_st_account_purchase_Id`
    FOREIGN KEY (`st_account_purchase_Id`)
    REFERENCES `acc_accounting_accounts` (`aa_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;
