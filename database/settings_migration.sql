-- Unipos Az - Settings Table
-- Bu fayl database-ə import edilməlidir

CREATE TABLE IF NOT EXISTS `settings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `setting_key` VARCHAR(100) UNIQUE NOT NULL,
  `setting_value` TEXT,
  `setting_group` VARCHAR(50),
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Default values
INSERT INTO `settings` (`setting_key`, `setting_value`, `setting_group`) VALUES
-- Hero Section
('hero_title', 'Restoranınızı Rəqəmsallaşdırın', 'hero'),
('hero_description', 'Müasir POS sistemi və QR Menu həlləri ilə biznesinizi növbəti səviyyəyə çıxarın. Sürətli, etibarlı və asan istifadə.', 'hero'),
('hero_stat_1_number', '500+', 'hero'),
('hero_stat_1_label', 'Müştəri', 'hero'),
('hero_stat_2_number', '99.9%', 'hero'),
('hero_stat_2_label', 'Uptime', 'hero'),
('hero_stat_3_number', '24/7', 'hero'),
('hero_stat_3_label', 'Dəstək', 'hero'),

-- Contact Information
('contact_phone', '+994 XX XXX XX XX', 'contact'),
('contact_email', 'info@unipos.az', 'contact'),
('contact_address', 'Bakı, Azərbaycan', 'contact'),

-- Social Media
('social_facebook', '#', 'social'),
('social_instagram', '#', 'social'),
('social_linkedin', '#', 'social'),
('social_whatsapp', '#', 'social'),

-- Products
('product_pos_title', 'POS Sistemi', 'products'),
('product_pos_description', 'Tam funksional POS sistemi ilə satışlarınızı idarə edin, anbar və hesabatları izləyin.', 'products'),
('product_qr_title', 'QR Menu', 'products'),
('product_qr_description', 'Müştəriləriniz üçün əlçatan, interaktiv və müasir rəqəmsal menyu həlli.', 'products'),
('product_integration_title', 'Tam İnteqrasiya', 'products'),
('product_integration_description', 'POS və QR Menu sistemləri birlikdə tam avtomatlaşdırılmış həll yaradır.', 'products')

ON DUPLICATE KEY UPDATE 
  `setting_value` = VALUES(`setting_value`),
  `updated_at` = CURRENT_TIMESTAMP;
