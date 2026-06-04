-- Initialize Coin System
-- This SQL file initializes the coin system settings

-- Insert or update coin_to_taka setting (new format: just the taka amount, meaning 1000 coins = this amount)
INSERT INTO settings (`group`, name, payload, created_at, updated_at)
VALUES ('coin', 'coin_to_taka', '"7"', NOW(), NOW())
ON DUPLICATE KEY UPDATE 
    payload = '"7"',
    updated_at = NOW();

-- Update existing users to have referral codes if they don't have one
UPDATE users 
SET referral_code = UPPER(SUBSTRING(MD5(CONCAT(id, email, created_at)), 1, 8))
WHERE referral_code IS NULL OR referral_code = '';

-- Ensure all users have coins defaulted to 0 if NULL
UPDATE users SET coins = 0 WHERE coins IS NULL;

-- Ensure all users have total_refer defaulted to 0 if NULL
UPDATE users SET total_refer = 0 WHERE total_refer IS NULL;

-- Ensure all orders have claimed defaulted to false if NULL
UPDATE orders SET claimed = 0 WHERE claimed IS NULL;

