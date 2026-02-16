CREATE TABLE crm_quotations (
    cq_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    cq_quotation_no VARCHAR(50) default NULL,
    cq_lead_id INT UNSIGNED default 0,
    cq_opportunity_id BIGINT default 0,
    cq_customer_id INT default 0,

    cq_quotation_date DATE default NULL,
    cq_expiry_date DATE default NULL,

    cq_status_id INT Default 0,

    cq_currency_id smallint default 0,
    cq_payment_term_id BIGINT UNSIGNED NULL,
    cq_subtotal DECIMAL(18,2) NOT NULL DEFAULT 0.00,
    cq_discount_total DECIMAL(18,2) NOT NULL DEFAULT 0.00,
    cq_tax_total DECIMAL(18,2) NOT NULL DEFAULT 0.00,
    cq_grand_total DECIMAL(18,2) NOT NULL DEFAULT 0.00,

    cq_note_public TEXT default NULL,
    cq_note_internal TEXT default NULL,

    cq_created_by INT UNSIGNED default NULL,
    cq_approved_by INT UNSIGNED default NULL,

    cq_created_at DATETIME default NULL,
    cq_updated_at DATETIME default NULL,
    cq_is_deleted tinyint default 0,
    cq_deleted_by int default 0,
    PRIMARY KEY (cq_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
