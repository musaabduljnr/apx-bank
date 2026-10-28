-- =====================================================================
-- Seed Sandra Bullock Account & 20 Transactions for the Last 12 Months
-- Account Number: 9909301376
-- Target Net Balance: $523,051.00 USD
-- Total Credits: $903,400.00 (12 transactions)
-- Total Debits:  $380,349.00 (8 transactions)
-- Net Balance:   $523,051.00 (20 transactions)
-- =====================================================================

-- 1. Insert or Update User: Sandra Bullock
INSERT INTO `users` (
    `acct_username`,
    `firstname`,
    `lastname`,
    `image`,
    `acct_no`,
    `billing_code`,
    `transfer`,
    `acct_balance`,
    `avail_balance`,
    `loan_balance`,
    `acct_limit`,
    `limit_remain`,
    `acct_type`,
    `acct_gender`,
    `marital_status`,
    `acct_currency`,
    `acct_status`,
    `acct_email`,
    `acct_phone`,
    `acct_occupation`,
    `acct_dob`,
    `country`,
    `state`,
    `acct_password`,
    `acct_pin`,
    `acct_otp`,
    `acct_cot`,
    `acct_imf`,
    `acct_tax`,
    `mgr_name`,
    `mgr_no`,
    `mgr_email`,
    `mgr_id`,
    `mgr_image`,
    `acct_address`,
    `createdAt`
) VALUES (
    'sandrabullock',
    'Sandra',
    'Bullock',
    'Testprofile-10.jpeg',
    '9909301376',
    1,
    1,
    523051.00,
    523051.00,
    0,
    1000000,
    1000000,
    'Checking',
    'female',
    'single',
    'USD',
    'active',
    'sandra.bullock@example.com',
    '+1 (310) 555-0199',
    'Executive Producer & Actress',
    '1964-07-26',
    'United States',
    'California',
    '$2y$10$UaoEfbSOLJBmb.tIgmJaGuyJT0oSoNyI6Ehgq08ZJ7AKqeloxWwh.',
    '1234',
    123456,
    '1234',
    '1234',
    '1234',
    'Relationship Manager',
    '18002792265',
    'manager@apxbank.com',
    'RM-9901',
    'manager1',
    '1004 Benedict Canyon Dr, Beverly Hills, CA 90210',
    '2025-10-01 08:00:00'
) ON DUPLICATE KEY UPDATE
    `firstname` = 'Sandra',
    `lastname` = 'Bullock',
    `acct_balance` = 523051.00,
    `avail_balance` = 523051.00,
    `acct_status` = 'active';

-- 2. Clean previous seed transactions for this user
DELETE FROM `transactions` 
WHERE `user_id` IN (SELECT `id` FROM `users` WHERE `acct_no` = '9909301376')
  AND `refrence_id` LIKE 'TXN-SB-%';

-- 3. Insert 20 transactions from the last 12 months totaling exactly $523,051.00
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
