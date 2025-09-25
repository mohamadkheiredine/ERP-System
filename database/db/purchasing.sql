-- Purchase Requisition (Header)
CREATE TABLE  pruchase_purchase_requisitions (
pr_id   BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
pr_company_id       INT UNSIGNED default 0,
pr_requisition_number VARCHAR(30) DEFAULT NULL,
pr_requisition_date DATE DEFAULT NULL,
pr_requester_id     INT DEFAULT 0,
pr_department_id    TINYINT Default 0,
pr_cost_center_id   INT UNSIGNED default 0,
pr_project_id       INT UNSIGNED default 0,
pr_priority         TINYINT default 0,
pr_status_id           INT DEFAULT 0,
pr_remarks          TEXT default NULL,
pr_is_deleted   tinyint default 0,
pr_deleted_by int default 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Purchase Requisition Items
CREATE TABLE pruchase_purchase_requisition_items (
pi_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
pi_requisition_id   BIGINT UNSIGNED default 0,
pi_line_no          INT default 0,
pi_item_id          BIGINT UNSIGNED default 0,
pi_description      TEXT Default NULL,
pi_uom_code         VARCHAR(16) default NULL,
pi_qty_requested    DECIMAL(18,4) default 0,
pi_expected_date    DATE default NULL,
pi_cost_center_id   int UNSIGNED default 0,
pi_project_id       INT UNSIGNED default 0,
pi_status_id           int default 0,
pi_is_deleted   tinyint default 0,
pi_deleted_by int default 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE pruchase_pr_approval_policies (
ap_id       BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
ap_company_id      int UNSIGNED Default 0,
ap_policy_code     VARCHAR(40) default NULL,
ap_name            VARCHAR(255) default 0,
ap_applies_department_id INT default 0,
ap_min_amount      DECIMAL(18,4) DEFAULT 0,
ap_max_amount      DECIMAL(18,4) default 0,
ap_is_active       TINYINT(1) DEFAULT 1,
ap_is_deleted   tinyint default 0,
ap_deleted_by int default 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Approval Policy Steps
CREATE TABLE pruchase_pr_approval_policy_steps (
ps_id  BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
ps_policy_id       BIGINT UNSIGNED default 0,
ps_step_order      INT default 0,
ps_role_code       VARCHAR(60) default NULL,
ps_approval_type   ENUM('SEQUENTIAL','ANY_OF','ALL_OF') DEFAULT 'SEQUENTIAL',
ps_threshold_amount DECIMAL(18,4) default 0,
ps_is_deleted tinyint default 0,
ps_deleted_by int default 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Approval Workflow Instances
CREATE TABLE pruchase_pr_approval_workflows (
pw_id     BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
pw_requisition_id  BIGINT UNSIGNED NOT NULL,
pw_policy_id       BIGINT UNSIGNED NOT NULL,
pw_current_step_order INT NOT NULL DEFAULT 1,
pw_status          int default 0,
pw_started_at      DATETIME default NULL,
pw_finished_at     DATETIME default NULL,
pw_is_deleted tinyint default 0,
pw_deleted_by int default 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Workflow Steps (runtime)
CREATE TABLE pruchase_pr_approval_workflow_steps (
ws_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
ws_workflow_id     BIGINT default 0,
ws_step_order      INT default 0,
ws_approver_user_id BIGINT default 0,
ws_role_code       VARCHAR(60) default NULL,
ws_step_status     int default 0,
ws_acted_at        DATETIME default NULL,
ws_remarks         TEXT Default NULL,
ws_is_deleted tinyint default 0,
ws_deleted_by int default 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Approval Logs
CREATE TABLE pruchase_pr_approval_logs (
al_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
al_workflow_id     BIGINT default 0,
al_step_order      INT default 0,
al_action          ENUM('SUBMIT','APPROVE','REJECT','ESCALATE','CANCEL') NOT NULL,
al_actor_user_id   BIGINT default 0,
al_action_at       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
al_notes           VARCHAR(500) default NULL,
al_is_deleted tinyint default 0,
al_deleted_by int default 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- PR Status History
CREATE TABLE pruchase_purchase_requisition_status_history (
sh_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
sh_requisition_id BIGINT UNSIGNED default 0,
sh_old_status    INT default 0,
sh_new_status       INT default 0,
sh_changed_by    INT default 0,
sh_changed_at    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
sh_remarks       TEXT DEFAULT NULL,
sh_is_deleted tinyint default 0,
sh_deleted_by int default 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- PR to PO Links
CREATE TABLE pruchase_pr_to_po_links (
pl_id  BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
pl_item_id BIGINT UNSIGNED default 0,
pl_line_id     BIGINT UNSIGNED NOT NULL default 0,
pl_qty_converted  DECIMAL(18,4) NOT NULL default 0,
pl_created_at     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
pl_is_deleted tinyint default 0,
pl_deleted_by int default 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
