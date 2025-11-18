CREATE TABLE fnb_floor (
   fl_id    smallint PRIMARY KEY AUTO_INCREMENT,
   fl_branch_id   int Default 0,
   fl_floor_name  VARCHAR(255) Default NULL,
   fl_sort_order  INT DEFAULT 0,
   fl_is_deleted TINYINT default 0,
   fl_deleted_by int default 0,
   UNIQUE KEY uq_floor_branch_name (fl_branch_id, fl_floor_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE fnb_table (
   ft_id           INT PRIMARY KEY AUTO_INCREMENT,
   ft_floor_id     INT DEFAULT 0,
   ft_label        VARCHAR(255) DEFAULT NULL,
   ft_capacity     INT DEFAULT 2,
   ft_status_id    TINYINT default 0,
   ft_pos_code     VARCHAR(255) DEFAULT NULL,         -- Optional code/QR
   ft_x_pos        DECIMAL(8,2) DEFAULT 0.00,         -- X coordinate on floor map
   ft_y_pos        DECIMAL(8,2) DEFAULT 0.00,         -- Y coordinate on floor map
   ft_rotation     DECIMAL(5,2) DEFAULT 0.00,         -- Angle in degrees
   ft_shape        tinyint  DEFAULT 0,
   ft_color        VARCHAR(20) DEFAULT '#00bfa5',     -- Optional table color (teal default)
   ft_active       TINYINT(1) DEFAULT 1,              -- Enable/Disable for use
   ft_created_at   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
   ft_updated_at   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
   ft_is_deleted TINYINT default 0,
   ft_deleted_by int default 0,
   INDEX (ft_floor_id),
   INDEX (ft_status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `fnb_kitchen_stations` (
    `ks_id` smallint NOT NULL AUTO_INCREMENT,
    `ks_code` varchar(5) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
    `ks_name` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
    `ks_description` text COLLATE utf8mb3_unicode_ci,
    `ks_branch_id` int DEFAULT '0',
    `ks_is_active` tinyint DEFAULT '1',
    `ks_is_deleted` tinyint DEFAULT '0',
    `ks_deleted_by` int DEFAULT '0',
    PRIMARY KEY (`ks_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


CREATE TABLE fnb_orders (
fo_id BIGINT AUTO_INCREMENT PRIMARY KEY,
fo_order_code VARCHAR(30) UNIQUE DEFAULT NULL,
fo_order_type ENUM('dine_in','takeaway','delivery','online') DEFAULT 'dine_in',
fo_branch_id INT default 0,
fo_store_id INT Default 0,
fo_table_id INT default 0,
fo_customer_id INT default 0,
fo_order_status INT Default 0,
fo_order_datetime DATETIME DEFAULT CURRENT_TIMESTAMP,
fo_subtotal DECIMAL(10,2) DEFAULT 0.00,
fo_discount DECIMAL(10,2) DEFAULT 0.00,
fo_tax DECIMAL(10,2) DEFAULT 0.00,
fo_service_charge DECIMAL(10,2) DEFAULT 0.00,
fo_total_amount DECIMAL(10,2) DEFAULT 0.00,
fo_paid_amount DECIMAL(10,2) DEFAULT 0.00,
fo_currency_id smallint default 0,
fo_payment_status ENUM('unpaid','partial','paid') DEFAULT 'unpaid',
fo_created_by INT default 0,
fo_creation_date date default NULL,
fo_notes TEXT default NULL
);


CREATE TABLE fnb_order_items (
oi_id BIGINT AUTO_INCREMENT PRIMARY KEY,
oi_order_id BIGINT NOT NULL,
oi_item_id INT NOT NULL,
oi_quantity DECIMAL(10,2) DEFAULT 1.00,
oi_unit_price DECIMAL(10,2) DEFAULT 0.00,
oi_item_discount DECIMAL(10,2) DEFAULT 0.00,
oi_total_price DECIMAL(10,2) GENERATED ALWAYS AS ((oi_quantity * oi_unit_price) - oi_item_discount) STORED,
oi_kitchen_status int default 0,
oi_station_id SMALLINT default 0,
oi_notes TEXT default NULL,
oi_currency_id smallint default 0,
oi_is_deleted tinyint default 0,
oi_deleted_by int default 0
);


CREATE TABLE fnb_order_item_modifiers (
      im_id BIGINT AUTO_INCREMENT PRIMARY KEY,
      im_item_id BIGINT default 0,
      im_modifier_id INT default 0,
      im_modifier_name VARCHAR(255) default 0,
      im_modifier_type ENUM('add','remove','option') DEFAULT 'add',
      im_modifier_cost DECIMAL(10,2) DEFAULT 0.00,
      im_currency_id smallint default 0
);

CREATE TABLE fnb_order_payments (
op_id BIGINT AUTO_INCREMENT PRIMARY KEY,
op_order_id BIGINT default 0,
op_payment_method ENUM('cash','card','wallet','voucher','split','other') DEFAULT 'cash',
op_total_amount DECIMAL(10,2) DEFAULT 0.00,
op_payment_datetime DATETIME DEFAULT CURRENT_TIMESTAMP,
op_transaction_ref VARCHAR(100) default NULL,
op_processed_by INT default 0,
op_processed_date datetime default NULL,
op_invoice_id INT default 0
);


CREATE TABLE fnb_order_tables (
ot_order_id BIGINT default 0,
ot_table_id INT default 0
);

CREATE TABLE fnb_order_delivery (
delivery_id BIGINT AUTO_INCREMENT PRIMARY KEY,
od_order_id BIGINT default 0,
od_driver_id INT default 0,
od_delivery_address TEXT default NULL,
od_delivery_status int default 0,
od_delivery_datetime DATETIME default NULL
);


CREATE TABLE fnb_order_audit_log (
 oa_log_id BIGINT AUTO_INCREMENT PRIMARY KEY,
 oa_order_id BIGINT default 0,
 oa_action VARCHAR(255) default NULL,
 oa_old_status int default 0,
 oa_new_status int default 0,
 oa_changed_by INT default 0,
 oa_changed_at DATETIME DEFAULT CURRENT_TIMESTAMP
);


