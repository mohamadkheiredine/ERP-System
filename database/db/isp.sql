ALTER TABLE `crm_lead_files` CHANGE COLUMN `lf_file_mime_type` `lf_file_mime_type` VARCHAR(100) NULL DEFAULT NULL ;
CREATE TABLE `crm_approval_flow` (
  `of_department_id` TINYINT NULL DEFAULT 0,
  `of_approval_order` TINYINT NULL DEFAULT 0,
  INDEX `idx_of_department_id` USING BTREE (`of_department_id`),
  CONSTRAINT `fk_of_department_id`
    FOREIGN KEY (`of_department_id`)
    REFERENCES `samaflix_db`.`sys_departments` (`sd_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


ALTER TABLE `crm_lead_status` ADD COLUMN `ls_department_id` TINYINT NULL DEFAULT 0 AFTER `fk_parent_status`, ADD INDEX `idx_ls_department_id` USING BTREE (`ls_department_id`);