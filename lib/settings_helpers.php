<?php

/**
 * Settings Helper Functions
 * Sayt parametrlərini idarə etmək üçün helper funksiyalar
 */

/**
 * Tək setting məlumatını gətir
 * 
 * @param string $key Setting key (məs: 'hero_title')
 * @param mixed $default Default value əgər tapılmazsa
 * @return string|null Setting value
 */
function getSetting($key, $default = null)
{
    global $conn;
    
    $key = mysqli_real_escape_string($conn, $key);
    $result = sql("SELECT setting_value FROM settings WHERE setting_key = '$key' LIMIT 1");
    
    if (!empty($result)) {
        return $result[0]['setting_value'];
    }
    
    return $default;
}

/**
 * Setting-i yenilə və ya yarat
 * 
 * @param string $key Setting key
 * @param string $value Yeni value
 * @param string $group Setting qrupu (optional)
 * @return bool Success status
 */
function updateSetting($key, $value, $group = null)
{
    global $conn;
    
    $key = mysqli_real_escape_string($conn, $key);
    $value = mysqli_real_escape_string($conn, $value);
    
    // Check if exists
    $exists = sql("SELECT id FROM settings WHERE setting_key = '$key' LIMIT 1");
    
    if (!empty($exists)) {
        // Update
        $query = "UPDATE settings SET setting_value = '$value' WHERE setting_key = '$key'";
        sql($query);
        return true;
    } else {
        // Insert
        $groupSql = $group ? "'" . mysqli_real_escape_string($conn, $group) . "'" : "NULL";
        $query = "INSERT INTO settings (setting_key, setting_value, setting_group) 
                  VALUES ('$key', '$value', $groupSql)";
        sql($query);
        return true;
    }
}

/**
 * Qrup üzrə bütün settings-ləri gətir
 * 
 * @param string $group Group name (məs: 'hero', 'contact')
 * @return array Settings array
 */
function getSettingsByGroup($group)
{
    global $conn;
    
    $group = mysqli_real_escape_string($conn, $group);
    $results = sql("SELECT setting_key, setting_value FROM settings WHERE setting_group = '$group'");
    
    $settings = [];
    foreach ($results as $row) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }
    
    return $settings;
}

/**
 * Bütün settings-ləri group-lanmış şəkildə gətir
 * 
 * @return array Grouped settings
 */
function getAllSettingsGrouped()
{
    $results = sql("SELECT setting_key, setting_value, setting_group FROM settings ORDER BY setting_group, setting_key");
    
    $grouped = [];
    foreach ($results as $row) {
        $group = $row['setting_group'] ?? 'other';
        $grouped[$group][$row['setting_key']] = $row['setting_value'];
    }
    
    return $grouped;
}

/**
 * Çoxlu settings-i bir anda yenilə
 * 
 * @param array $settings Key-value array
 * @param string $group Default group (optional)
 * @return bool Success status
 */
function updateMultipleSettings($settings, $group = null)
{
    $success = true;
    
    foreach ($settings as $key => $value) {
        if (!updateSetting($key, $value, $group)) {
            $success = false;
        }
    }
    
    return $success;
}

/**
 * Setting-i sil
 * 
 * @param string $key Setting key
 * @return bool Success status
 */
function deleteSetting($key)
{
    global $conn;
    
    $key = mysqli_real_escape_string($conn, $key);
    sql("DELETE FROM settings WHERE setting_key = '$key'");
    
    return true;
}

/**
 * Hero section məlumatlarını gətir
 * 
 * @return array Hero settings
 */
function getHeroSettings()
{
    return getSettingsByGroup('hero');
}

/**
 * Contact məlumatlarını gətir
 * 
 * @return array Contact settings
 */
function getContactSettings()
{
    return getSettingsByGroup('contact');
}

/**
 * Social media linklərini gətir
 * 
 * @return array Social media settings
 */
function getSocialSettings()
{
    return getSettingsByGroup('social');
}

/**
 * Product məlumatlarını gətir
 * 
 * @return array Product settings
 */
function getProductSettings()
{
    return getSettingsByGroup('products');
}
