# Unipos Az - Admin Panel Integration

## Quraşdırma Təlimatları

### 1. Database Setup

Database-ə `settings` cədvəlini əlavə edin:

```bash
# MySQL database-ə daxil olun
mysql -u root -p tampos

# SQL migration faylım import edin
source database/settings_migration.sql
```

Və ya phpMyAdmin-dən:
- `database/settings_migration.sql` faylını açın
- Kodu kopyalayıb phpMyAdmin-də SQL tab-da işə salın

### 2. Faylların Yerləşdirilməsi 

Bütün faylların düzgün yerləşdiyindən əmin olun:
```
Unipos/
├── config.php                    # Database konfiqurasiyası
├── index.php                     # Admin panel (Restoranlar)
├── settings.php                  # [YENİ] Settings idarəetməsi
├── login.php
├── lib/
│   ├── lib.php                   # Əsas helper funksiyalar
│   └── settings_helpers.php      # [YENİ] Settings helpers
├── includes/
│   ├── sidebar.php               # [YENİLƏNDİ] Settings linki
│   ├── header.php
│   ├── footer.php
│   └── head.php
├── database/
│   └── settings_migration.sql    # [YENİ] Settings table
├── public_index.php              # [YENİ] Dinamik sayt
├── index.html                    # Static backup 
├── css/
├── js/
└── images/
```

### 3. Public Website

Public website üçün `public_index.php` istifadə edin. Bu fayl database-dən məlumatları çəkir və dinamik göstərir.

**Localhost təsti:**
```bash
# PHP built-in server
php -S localhost:8000 public_index.php

# və ya http-server
npx -y http-server ./ -p 8080
```

**Production:**
- Apache/Nginx konfiqurasiyasında `public_index.php`-ni index faylı kimi təyin edin
- Və ya `.htaccess` ilə redirect:
```apache
DirectoryIndex public_index.php
```

### 4. Admin Panel İstifadə

#### Settings Səhifəsi

`settings.php` səhifəsində 4 tab var:

1. **Hero Section**
   - Başlıq və təsvir
   - 3 statistika (rəqəm və label)

2.  **Məhsullar**
   - POS Sistemi başlıq və təsvir
   - QR Menu başlıq və təsvir
   - İnteqrasiya başlıq və təsvir

3. **Əlaqə Məlumatları**
   - Telefon
   - Email
   - Ünvan

4. **Sosial Media**
   - Facebook link
   - Instagram link
   - LinkedIn link
   - WhatsApp link

#### Settings Helpers API

```php
// Tək setting gətir
$value = getSetting('hero_title', 'Default value');

// Setting yenilə
updateSetting('hero_title', 'Yeni başlıq', 'hero');

// Qrup üzrə settings gətir
$heroSettings = getSettingsByGroup('hero');
// Returns: ['hero_title' => '...', 'hero_description' => '...', ...]

// Bütün settings grouped
$allSettings = getAllSettingsGrouped();
// Returns: ['hero' => [...], 'contact' => [...], 'social' => [...]]

// Çoxlu settings yenilə
updateMultipleSettings([
    'hero_title' => 'Yeni başlıq',
    'hero_description' => 'Yeni təsvir'
], 'hero');
```

## Xüsusiyyətlər

### ✅ Tamamlanmış

- [x] Database structure (settings table)
- [x] Settings helper functions
- [x] Admin panel Settings page with tabs
- [x] Hero section dinamik
- [x] Products section dinamik
- [x] Contact info dinamik
- [x] Social media links dinamik
- [x] Sidebar updated with Settings link
- [x] Success messages və Telegram notifications

### 🎯 Gələcək Təkmilləşdirmələr

- [ ] Image upload üçün settings
- [ ] Features section dinamik
- [ ] About section dinamik
- [ ] Logo upload
- [ ] Settings import/export
- [ ] Settings  history/versioning

## Texniki Detallar

**Database:**
- Table: `settings`
- Fields: `id`, `setting_key`, `setting_value`, `setting_group`, `updated_at`

**Helper Functions:**
- `getSetting($key, $default)`
- `updateSetting($key, $value, $group)`
- `getSettingsByGroup($group)`
- `getAllSettingsGrouped()`
- `updateMultipleSettings($settings, $group)`

**Settings Groups:**
- `hero` - Hero section settings
- `products` - Product information
- `contact` - Contact information
- `social` - Social media links

## Təhlükəsizlik

- ✅ XSS protection (`htmlspecialchars()` istifadə edilir)
- ✅ SQL injection protection (`mysqli_real_escape_string()`)
- ✅ Session authentication check
- ✅ Telegram logging aktivdir

## Dəstək

Suallarınız olarsa və ya problem yaranarsa, Telegram vasitəsilə log göndərilir.

---

**Version:** 1.0.0  
**Last Updated:** 2024-11-19  
**Developed by:** Unipos Az Team
