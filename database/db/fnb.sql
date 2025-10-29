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


