-- APX Bank Schema Definition with Native AUTO_INCREMENT Primary Keys

CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `firstname` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `lastname` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `image` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `admin_email` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `admin_password` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `audit_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `device` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `ipAddress` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '127.0.0.1',
  `datenow` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `card` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `seria_key` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `card_number` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `card_name` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `card_expiration` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `card_security` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `card_limit` double NOT NULL DEFAULT 5000,
  `card_limit_remain` double NOT NULL DEFAULT 5000,
  `card_status` int(11) NOT NULL DEFAULT 2 COMMENT '1=Active,2=Process,3=hold, 4=PAUSE',
  `createdAt` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `card_request` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `reference_id` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `card_type` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `card_reason` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `card_request_status` int(11) NOT NULL DEFAULT 2,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `crypto_currency` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `crypto_name` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `wallet_address` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `deposit` (
  `d_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `refrence_id` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `image` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `amount` double NOT NULL DEFAULT 0,
  `wallet_address` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `crypto_id` int(11) NOT NULL DEFAULT 0,
  `crypto_status` int(15) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `domestic_transfer` (
  `dom_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `acct_id` int(11) DEFAULT NULL,
  `refrence_id` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `amount` double NOT NULL DEFAULT 0,
  `bank_name` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `acct_name` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `acct_number` varchar(50) DEFAULT '',
  `trans_type` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'domestic transfer',
  `acct_type` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `acct_remarks` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `dom_status` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `loan` (
  `loan_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `loan_reference_id` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `acct_id` int(11) NOT NULL DEFAULT 0,
  `amount` double DEFAULT 0,
  `loan_remarks` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `loan_status` int(11) NOT NULL DEFAULT 0,
  `loan_message` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `FullName` text DEFAULT NULL,
  `PhoneNumber` text DEFAULT NULL,
  `locationcus` text DEFAULT NULL,
  `Addresscus` text DEFAULT NULL,
  `City` text DEFAULT NULL,
  `Customer` text DEFAULT NULL,
  `Messagecus` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `image` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `about_us` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `url_name` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `url_tel` varchar(15) DEFAULT NULL,
  `url_email` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `trans_limit_min` double DEFAULT 500,
  `trans_limit_max` double DEFAULT 500000,
  `livechat` text DEFAULT NULL COMMENT 'Live Widget',
  `twillio_status` int(11) NOT NULL DEFAULT 0 COMMENT '0=off,1=on',
  `billing_code` int(11) NOT NULL DEFAULT 0 COMMENT '0=off,1=on',
  `transfer` int(11) NOT NULL DEFAULT 1 COMMENT '0=off,1=on',
  `bank_deposit` int(11) NOT NULL DEFAULT 0 COMMENT '0=off,1=on'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `temp_trans` (
  `wire_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `acct_id` int(11) DEFAULT NULL,
  `trans_id` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `amount` double NOT NULL DEFAULT 0,
  `bank_name` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `acct_name_id` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `acct_number` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `trans_type` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'wire transfer',
  `acct_type` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `acct_country` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `acct_swift` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `acct_routing` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `acct_remarks` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `wire_status` int(11) NOT NULL DEFAULT 0,
  `trans_otp` int(11) DEFAULT NULL,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `transactions` (
  `trans_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `refrence_id` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `amount` double NOT NULL DEFAULT 0,
  `trans_type` int(15) NOT NULL DEFAULT 1,
  `sender_name` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `trans_status` int(11) NOT NULL DEFAULT 0,
  `created_at` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `time_created` text COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `acct_username` varchar(200) DEFAULT NULL,
  `firstname` varchar(200) DEFAULT NULL,
  `lastname` varchar(200) DEFAULT NULL,
  `image` text DEFAULT NULL,
  `acct_no` varchar(50) DEFAULT NULL,
  `billing_code` int(11) NOT NULL DEFAULT 0 COMMENT '0=deactive,1=active',
  `transfer` int(11) NOT NULL DEFAULT 1 COMMENT '0=deactive,1=active',
  `acct_balance` double DEFAULT 0,
  `avail_balance` double DEFAULT 0,
  `loan_balance` double DEFAULT 0,
  `acct_limit` double DEFAULT 500000,
  `limit_remain` double DEFAULT 500000,
  `acct_type` varchar(200) DEFAULT 'Savings',
  `acct_gender` text DEFAULT NULL,
  `marital_status` text DEFAULT NULL,
  `acct_currency` varchar(50) DEFAULT 'USD',
  `acct_status` varchar(50) DEFAULT 'active' COMMENT 'active, hold',
  `acct_email` varchar(200) DEFAULT NULL,
  `acct_phone` varchar(20) DEFAULT NULL,
  `acct_occupation` text DEFAULT NULL,
  `acct_dob` text DEFAULT NULL,
  `ssn` varchar(200) DEFAULT NULL,
  `frontID` text DEFAULT NULL,
  `backID` text DEFAULT NULL,
  `country` text DEFAULT NULL,
  `state` text DEFAULT NULL,
  `acct_password` text DEFAULT NULL,
  `acct_pin` varchar(4) DEFAULT NULL,
  `acct_otp` int(11) DEFAULT 0,
  `acct_cot` varchar(15) DEFAULT '1234',
  `acct_imf` varchar(15) DEFAULT '1234',
  `acct_tax` varchar(15) DEFAULT '1234',
  `mgr_name` text DEFAULT NULL,
  `mgr_no` text DEFAULT NULL,
  `mgr_email` text DEFAULT NULL,
  `mgr_id` text DEFAULT NULL,
  `mgr_image` text DEFAULT NULL,
  `acct_address` text DEFAULT NULL,
  `tx_freeze_until` datetime DEFAULT NULL,
  `tx_freeze_reason` text DEFAULT NULL,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `user_devices` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `device_fingerprint` varchar(64) NOT NULL,
  `device_name` text DEFAULT NULL,
  `ip_address` varchar(200) NOT NULL DEFAULT '127.0.0.1',
  `first_login` datetime NOT NULL DEFAULT current_timestamp(),
  `last_login` datetime NOT NULL DEFAULT current_timestamp(),
  `is_trusted` tinyint(1) NOT NULL DEFAULT 1,
  INDEX (`user_id`),
  INDEX (`device_fingerprint`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `v_bank` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `bank_name` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `routine_no` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `acct_no` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `swift_code` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `wire_transfer` (
  `wire_id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `acct_id` int(11) NOT NULL DEFAULT 0,
  `refrence_id` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `amount` double NOT NULL DEFAULT 0,
  `bank_name` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `acct_name` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `acct_number` varchar(200) COLLATE utf8_unicode_ci DEFAULT '',
  `trans_type` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'wire transfer',
  `acct_type` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `acct_country` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `acct_swift` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `acct_routing` varchar(50) COLLATE utf8_unicode_ci DEFAULT '',
  `acct_remarks` text COLLATE utf8_unicode_ci DEFAULT NULL,
  `wire_status` int(11) NOT NULL DEFAULT 0,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `withdrawal` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `reference_id` varchar(200) NOT NULL DEFAULT '',
  `user_id` int(11) NOT NULL DEFAULT 0,
  `amount` float NOT NULL DEFAULT 0,
  `withdraw_method` varchar(200) NOT NULL DEFAULT 'Bank',
  `trans_type` int(11) NOT NULL DEFAULT 1,
  `wallet_address` text DEFAULT NULL,
  `bankname` text DEFAULT NULL,
  `account_number` text DEFAULT NULL,
  `routineno` text DEFAULT NULL,
  `acctname` text DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `admin` (`id`, `firstname`, `lastname`, `image`, `admin_email`, `admin_password`) VALUES
(1, 'Admin', 'Admin', 'jamesavatar.png', 'support@dirtyscripts.shop', '$2y$10$Ysp7iiUXB4O.p.vm/H.V5eya846d4sHiBlpkc23JPMCbwEnFVddIC')
ON DUPLICATE KEY UPDATE `id`=`id`;

INSERT INTO `crypto_currency` (`id`, `crypto_name`, `wallet_address`, `created_at`) VALUES
(1, 'Bitcoin', '8rtknjbhgfgvsnkjhgxfgxjhkx', '2022-10-21 17:33:59')
ON DUPLICATE KEY UPDATE `id`=`id`;

INSERT INTO `settings` (`id`, `image`, `about_us`, `url_name`, `url_tel`, `url_email`, `trans_limit_min`, `trans_limit_max`, `livechat`, `twillio_status`, `billing_code`, `transfer`, `bank_deposit`) VALUES
(1, 'logo.png', 'APX Bank provides modern, secure, and intuitive digital banking solutions for individuals and enterprises worldwide.', 'APX Bank', '18002792265', 'contact@apxbank.com', 500, 500000, '', 0, 1, 1, 0)
ON DUPLICATE KEY UPDATE `id`=`id`;

INSERT INTO `users` (`id`, `acct_username`, `firstname`, `lastname`, `image`, `acct_no`, `billing_code`, `transfer`, `acct_balance`, `avail_balance`, `loan_balance`, `acct_limit`, `limit_remain`, `acct_type`, `acct_gender`, `marital_status`, `acct_currency`, `acct_status`, `acct_email`, `acct_phone`, `acct_occupation`, `acct_dob`, `ssn`, `frontID`, `backID`, `country`, `state`, `acct_password`, `acct_pin`, `acct_otp`, `acct_cot`, `acct_imf`, `acct_tax`, `mgr_name`, `mgr_no`, `mgr_email`, `mgr_id`, `mgr_image`, `acct_address`, `createdAt`) VALUES
(9, 'ofofonobs', 'Oluwaseun', 'Ikuesan', 'Testprofile-10.jpeg', '0022521726', 1, 1, 795064.44, 0, 866, 1100, -222506, 'Savings', 'male', 'single', 'USD', 'active', 'ofofonobs@gmail.com', '+2349035669201', 'Test Test', '2022-03-08', NULL, NULL, NULL, 'Albania', 'Test', '$2y$10$UaoEfbSOLJBmb.tIgmJaGuyJT0oSoNyI6Ehgq08ZJ7AKqeloxWwh.', '1234', 197600, '1234', '1234', '1234', 'Test Manager', '1234567890', 'manager1@gmail.com', '000000', 'manager1', 'Test Test', '2022-03-27 18:29:40')
ON DUPLICATE KEY UPDATE `id`=`id`;

-- Seed Sandra Bullock Account & 20 Transactions for the Last 12 Months
INSERT INTO `users` (
    `acct_username`, `firstname`, `lastname`, `image`, `acct_no`, `billing_code`, `transfer`,
    `acct_balance`, `avail_balance`, `loan_balance`, `acct_limit`, `limit_remain`,
    `acct_type`, `acct_gender`, `marital_status`, `acct_currency`, `acct_status`,
    `acct_email`, `acct_phone`, `acct_occupation`, `acct_dob`, `country`, `state`,
    `acct_password`, `acct_pin`, `acct_otp`, `acct_cot`, `acct_imf`, `acct_tax`,
    `mgr_name`, `mgr_no`, `mgr_email`, `mgr_id`, `mgr_image`, `acct_address`, `createdAt`
) VALUES (
    'sandrabullock', 'Sandra', 'Bullock', 'jamesavatar.png', '9909301376', 1, 1,
    523051.00, 523051.00, 0, 1000000, 1000000,
    'Savings', 'female', 'single', 'USD', 'active',
    'sandra.bullock@example.com', '+1 (310) 555-0199', 'Executive Producer & Actress', '1964-07-26', 'United States', 'California',
    '$2y$10$UaoEfbSOLJBmb.tIgmJaGuyJT0oSoNyI6Ehgq08ZJ7AKqeloxWwh.', '1234', 123456, '1234', '1234', '1234',
    'Relationship Manager', '18002792265', 'manager@apxbank.com', 'RM-9901', 'manager1', '1004 Benedict Canyon Dr, Beverly Hills, CA 90210', '2025-10-01 08:00:00'
) ON DUPLICATE KEY UPDATE
    `firstname` = 'Sandra',
    `lastname` = 'Bullock',
    `acct_balance` = 523051.00,
    `avail_balance` = 523051.00,
    `acct_status` = 'active';

DELETE FROM `transactions` 
WHERE `user_id` IN (SELECT `id` FROM `users` WHERE `acct_no` = '9909301376')
  AND `refrence_id` LIKE 'TXN-SB-%';

INSERT INTO `transactions` (`user_id`, `refrence_id`, `amount`, `trans_type`, `sender_name`, `description`, `trans_status`, `created_at`, `time_created`)
SELECT `id`, 'TXN-SB-804192', 125000.00, 1, 'Warner Bros Entertainment', 'Direct Deposit - Royalty & Production Distribution', 1, '2025-11-12', '10:15:22' FROM `users` WHERE `acct_no` = '9909301376'
UNION ALL
SELECT `id`, 'TXN-SB-819402', 38450.00, 1, 'Fortis Global Investment', 'Q4 Portfolio Dividend Yield Payout', 1, '2025-11-28', '14:30:45' FROM `users` WHERE `acct_no` = '9909301376'
UNION ALL
SELECT `id`, 'TXN-SB-823910', 24650.00, 2, 'Beverly Hills Luxury Realty', 'Residential Estate Maintenance & Security', 1, '2025-12-05', '16:30:10' FROM `users` WHERE `acct_no` = '9909301376'
UNION ALL
SELECT `id`, 'TXN-SB-831954', 82500.00, 1, 'Highland Capital Partners', 'Asset Liquidation Proceeds & Distribution', 1, '2025-12-15', '09:20:04' FROM `users` WHERE `acct_no` = '9909301376'
UNION ALL
SELECT `id`, 'TXN-SB-840281', 145000.00, 1, 'Netflix Media Production', 'Executive Producer Residuals & Licensing', 1, '2026-01-08', '11:45:19' FROM `users` WHERE `acct_no` = '9909301376'
UNION ALL
SELECT `id`, 'TXN-SB-851940', 58200.00, 2, 'NetJets Aviation LLC', 'Private Flight Share Charter Membership', 1, '2026-01-22', '13:10:50' FROM `users` WHERE `acct_no` = '9909301376'
UNION ALL
SELECT `id`, 'TXN-SB-860392', 54200.00, 1, 'Vanguard Wealth Mgmt', 'Semi-Annual Portfolio Return Disbursement', 1, '2026-02-02', '15:10:33' FROM `users` WHERE `acct_no` = '9909301376'
UNION ALL
SELECT `id`, 'TXN-SB-871029', 62500.00, 2, 'Christie\'s Auction House', 'Contemporary Sculpture Fine Art Acquisition', 1, '2026-02-18', '11:00:15' FROM `users` WHERE `acct_no` = '9909301376'
UNION ALL
SELECT `id`, 'TXN-SB-882941', 95000.00, 1, 'Creative Artists Agency', 'Talent Representation Endorsement Contract', 1, '2026-03-14', '13:25:40' FROM `users` WHERE `acct_no` = '9909301376'
UNION ALL
SELECT `id`, 'TXN-SB-894018', 85000.00, 2, 'Morgan Stanley Wealth', 'Municipal Bond Portfolio Reinvestment', 1, '2026-03-29', '15:45:22' FROM `users` WHERE `acct_no` = '9909301376'
UNION ALL
SELECT `id`, 'TXN-SB-901842', 22500.00, 1, 'Beverly Hills Escrow', 'Commercial Property Lease Rental Yield', 1, '2026-04-18', '16:40:08' FROM `users` WHERE `acct_no` = '9909301376'
UNION ALL
SELECT `id`, 'TXN-SB-912850', 72800.00, 2, 'Internal Revenue Service (IRS)', 'Q1 Estimated Federal Tax Settlement', 1, '2026-05-04', '14:20:30' FROM `users` WHERE `acct_no` = '9909301376'
UNION ALL
SELECT `id`, 'TXN-SB-923841', 110000.00, 1, 'Universal Pictures Inc', 'International Syndication Rights Settlement', 1, '2026-05-20', '10:05:14' FROM `users` WHERE `acct_no` = '9909301376'
UNION ALL
SELECT `id`, 'TXN-SB-934910', 48750.00, 1, 'Fidelity Treasury Fund', 'US T-Bill Maturity Disbursement', 1, '2026-06-12', '12:50:41' FROM `users` WHERE `acct_no` = '9909301376'
UNION ALL
SELECT `id`, 'TXN-SB-945028', 36420.00, 2, 'American Express Centurion', 'Executive Travel & Hospitality Statement', 1, '2026-06-28', '10:40:18' FROM `users` WHERE `acct_no` = '9909301376'
UNION ALL
SELECT `id`, 'TXN-SB-956102', 65000.00, 1, 'Paramount Pictures', 'Global Theatrical Screening Royalty Fee', 1, '2026-07-25', '14:15:55' FROM `users` WHERE `acct_no` = '9909301376'
UNION ALL
SELECT `id`, 'TXN-SB-967204', 22779.00, 2, 'California State Franchise Tax', 'CA State Quarterly Estimated Tax Payment', 1, '2026-08-14', '12:15:02' FROM `users` WHERE `acct_no` = '9909301376'
UNION ALL
SELECT `id`, 'TXN-SB-978310', 75000.00, 1, 'Sotheby\'s International', 'Private Fine Art Collection Sale Proceeds', 1, '2026-08-30', '09:35:48' FROM `users` WHERE `acct_no` = '9909301376'
UNION ALL
SELECT `id`, 'TXN-SB-989421', 18000.00, 2, 'Cartier North America', 'Custom High Jewelry Commission Order', 1, '2026-09-22', '17:05:39' FROM `users` WHERE `acct_no` = '9909301376'
UNION ALL
SELECT `id`, 'TXN-SB-990532', 42000.00, 1, 'Apple Studios LLC', 'Digital Streaming Production Consultation', 1, '2026-10-10', '11:20:25' FROM `users` WHERE `acct_no` = '9909301376';

