<?php

// Conncect DB
require_once 'config.php';

// Lib
require_once './lib/lib.php';


// --- RATE LIMIT PARAMETRLƏRİ ---
define('MAX_LOGIN_ATTEMPTS', 3); // Maksimal cəhd sayı
define('LOGIN_BLOCK_DURATION', 60); // Blok müddəti (saniyə ilə), yəni 1 dəqiqə

/**
 * Ziyarətçinin real IP adresini almaq üçün köməkçi funksiya
 */
function getClientIP()
{
  $ipaddress = '';
  if (isset($_SERVER['HTTP_CLIENT_IP']))
    $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
  else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
    $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
  else if (isset($_SERVER['HTTP_X_FORWARDED']))
    $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
  else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
    $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
  else if (isset($_SERVER['HTTP_FORWARDED']))
    $ipaddress = $_SERVER['HTTP_FORWARDED'];
  else if (isset($_SERVER['REMOTE_ADDR']))
    $ipaddress = $_SERVER['REMOTE_ADDR'];
  else
    $ipaddress = 'UNKNOWN';

  if (strpos($ipaddress, ',') !== false) {
    $ipaddress = trim(explode(',', $ipaddress)[0]);
  }
  return $ipaddress;
}

// --- RATE LIMIT MƏNTİQİ BAŞLADI ---

$clientIP = getClientIP();
$currentTime = time();
$blockWindowStart = $currentTime - LOGIN_BLOCK_DURATION;
$login_error_message = ''; // Xəta mesajı üçün boş dəyişən

// 1. Köhnə cəhdləri təmizləyək (1 dəqiqədən köhnə)
sql("DELETE FROM login_attempts WHERE attempt_time < $blockWindowStart");

// 2. Bu IP üçün son 1 dəqiqədə olan cəhdləri sayaq
$failedAttemptsResult = sql("SELECT COUNT(*) as count FROM login_attempts WHERE ip_address = '$clientIP'");
$failedAttempts = $failedAttemptsResult[0]['count'];

// 3. IP-nin blokda olub-olmadığını yoxlayaq
$isBlocked = ($failedAttempts >= MAX_LOGIN_ATTEMPTS);

if ($isBlocked) {
  // Əgər IP blokludursa, heç bir girişə icazə vermə
  $login_error_message = "Çox sayda uğursuz cəhd. Zəhmət olmasa 1 dəqiqə sonra yenidən yoxlayın.";
}
// IP bloklu DEYİLSƏ və POST sorğusu gəlibsə
else if (isset($_POST['login'])) {
  $name = $_POST['name'];
  $password = $_POST['password'];
  $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'UNKNOWN';

  // İstifadəçi məlumatlarını çəkək
  $requestData = sql("SELECT * FROM super_admin WHERE username = '$name'");
  $adminRecord = !empty($requestData) ? $requestData[0] : null;

  // Yoxlama: İstifadəçi mövcuddur VƏ şifrə düzgündür
  if ($adminRecord && password_verify($password, $adminRecord['password'])) {

    // --- UĞURLU GİRİŞ ---

    // 1. Bu IP üçün köhnə cəhdləri təmizlə
    sql("DELETE FROM login_attempts WHERE ip_address = '$clientIP'");

    // 2. Sessiyanı başlat
    $_SESSION['username'] = $adminRecord['username'];

    // 3. Telegram Logu Göndər (Uğurlu)
    try {
      $logMessage = "👑 <b>SUPER ADMIN GİRİŞİ (Uğurlu)</b>\n";
      $logMessage .= "--------------------------------------\n";
      $logMessage .= "<b>Username:</b> " . htmlspecialchars($name) . "\n";
      $logMessage .= "<b>IP Adres:</b> " . htmlspecialchars($clientIP) . "\n";
      $logMessage .= "<b>Tarix:</b> " . date('d-m-Y H:i:s');
      sendTelegramLog($logMessage);
    } catch (Exception $e) { /* error_log(...) */
    }

    // 4. Əsas səhifəyə yönləndir
    header("Location: dashboard.php");
    exit;

  } else {

    // --- UĞURSUZ GİRİŞ ---

    // 1. Uğursuz cəhdi bazaya yaz
    sql("INSERT INTO login_attempts (ip_address, attempt_time) VALUES ('$clientIP', $currentTime)");

    // 2. Telegram Logu Göndər (Uğursuz)
    try {
      $logMessage = "🚫 <b>SUPER ADMIN CƏHDİ (Uğursuz)</b>\n";
      $logMessage .= "--------------------------------------\n";
      $logMessage .= "<b>Daxil edilən ad:</b> " . htmlspecialchars($name) . "\n";
      $logMessage .= "<b>IP Adres:</b> " . htmlspecialchars($clientIP) . "\n";
      $logMessage .= "<b>Cihaz:</b> " . htmlspecialchars($userAgent);
      sendTelegramLog($logMessage);
    } catch (Exception $e) { /* error_log(...) */
    }

    // 3. Xəta mesajını istifadəçiyə göstər
    $remaining_attempts = (MAX_LOGIN_ATTEMPTS - 1) - $failedAttempts;
    if ($remaining_attempts <= 0) {
      $login_error_message = "Səhv istifadəçi adı və ya şifrə. IP-niz 1 dəqiqəlik bloklandı.";
    } else {
      $login_error_message = "Səhv istifadəçi adı və ya şifrə. Qalan cəhd sayı: $remaining_attempts";
    }
  }
}

?>


<!DOCTYPE html>
<html lang="az">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>UniPOS - Giriş</title>
  
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
      overflow-x: hidden;
      position: relative;
    }

    /* Animated Background Shapes */
    body::before,
    body::after {
      content: '';
      position: fixed;
      border-radius: 50%;
      opacity: 0.3;
      z-index: 0;
      animation: float 6s ease-in-out infinite;
    }

    body::before {
      width: 300px;
      height: 300px;
      background: radial-gradient(circle, #64b5f6, #42a5f5);
      top: 10%;
      left: 5%;
      animation-delay: 0s;
    }

    body::after {
      width: 200px;
      height: 200px;
      background: radial-gradient(circle, #90caf9, #64b5f6);
      bottom: 15%;
      right: 10%;
      animation-delay: 2s;
    }

    @keyframes float {
      0%, 100% {
        transform: translateY(0) translateX(0);
      }
      33% {
        transform: translateY(-30px) translateX(30px);
      }
      66% {
        transform: translateY(20px) translateX(-20px);
      }
    }

    .login-container {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem 1rem;
      position: relative;
      z-index: 1;
    }

    .login-card {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(20px);
      border-radius: 2rem;
      padding: 3rem 2.5rem;
      max-width: 480px;
      width: 100%;
      box-shadow: 
        0 20px 60px rgba(0, 0, 0, 0.1),
        0 0 0 1px rgba(255, 255, 255, 0.5) inset;
      position: relative;
      overflow: hidden;
      animation: slideUp 0.6s ease-out;
    }

    @keyframes slideUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* Glass effect decoration */
    .login-card::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
      transform: rotate(45deg);
      animation: shine 3s infinite;
    }

    @keyframes shine {
      0%, 100% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
      50% { transform: translateX(100%) translateY(100%) rotate(45deg); }
    }

    .login-header {
      text-align: center;
      margin-bottom: 2.5rem;
      position: relative;
      z-index: 1;
    }

    .logo-container {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 1rem;
      margin-bottom: 2rem;
    }

    .logo-container img {
      width: 60px;
      height: 60px;
      object-fit: contain;
      filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.1));
      animation: logoFloat 3s ease-in-out infinite;
    }

    @keyframes logoFloat {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-10px); }
    }

    .logo-text {
      font-family: 'Outfit', sans-serif;
      font-size: 2.5rem;
      font-weight: 800;
      color: #1976d2;
      letter-spacing: -0.5px;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
    }

    .login-title {
      font-family: 'Outfit', sans-serif;
      font-size: 1.75rem;
      font-weight: 700;
      color: #1f2937;
      margin-bottom: 0.5rem;
    }

    .login-subtitle {
      color: #6b7280;
      font-size: 0.95rem;
    }

    /* Alert Styles */
    .alert {
      background: #ffebee;
      color: #c62828;
      padding: 1rem 1.25rem;
      border-radius: 1rem;
      margin-bottom: 1.5rem;
      display: flex;
      align-items: center;
      font-size: 0.9rem;
      font-weight: 500;
      border: 1px solid #ef9a9a;
      animation: alertSlide 0.3s ease-out;
      position: relative;
      z-index: 1;
    }

    @keyframes alertSlide {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* Form Styles */
    .login-form {
      position: relative;
      z-index: 1;
    }

    .form-group {
      margin-bottom: 1.75rem;
      position: relative;
    }

    .form-label {
      display: block;
      margin-bottom: 0.75rem;
      color: #374151;
      font-weight: 600;
      font-size: 0.95rem;
      transition: color 0.3s ease;
    }

    .input-wrapper {
      position: relative;
    }

    .input-icon {
      position: absolute;
      left: 1.25rem;
      top: 50%;
      transform: translateY(-50%);
      color: #9ca3af;
      font-size: 1.25rem;
      transition: all 0.3s ease;
      pointer-events: none;
      z-index: 1;
    }

    .form-control {
      width: 100%;
      padding: 1rem 1.25rem 1rem 3.5rem;
      border: 2px solid #e5e7eb;
      border-radius: 1rem;
      font-size: 1rem;
      color: #1f2937;
      background: #ffffff;
      transition: all 0.3s ease;
      outline: none;
    }

    .form-control::placeholder {
      color: #9ca3af;
    }

    .form-control:focus {
      border-color: #2196f3;
      box-shadow: 0 0 0 4px rgba(33, 150, 243, 0.1);
      transform: translateY(-2px);
    }

    .form-control:focus ~ .input-icon {
      color: #2196f3;
      transform: translateY(-50%) scale(1.1);
    }

    .form-control:focus + .form-label {
      color: #2196f3;
    }

    /* Show/Hide Password Toggle */
    .password-toggle {
      position: absolute;
      right: 1.25rem;
      top: 50%;
      transform: translateY(-50%);
      color: #9ca3af;
      cursor: pointer;
      font-size: 1.25rem;
      transition: color 0.3s ease;
      z-index: 2;
    }

    .password-toggle:hover {
      color: #2196f3;
    }

    /* Submit Button */
    .btn-submit {
      width: 100%;
      padding: 1rem 1.5rem;
      background: #1976d2;
      color: #ffffff;
      border: none;
      border-radius: 1rem;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
      margin-top: 1rem;
      box-shadow: 0 4px 12px rgba(25, 118, 210, 0.3);
    }

    .btn-submit:hover {
      background: #1565c0;
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(25, 118, 210, 0.4);
    }

    .btn-submit:active {
      transform: translateY(0);
    }

    .btn-submit::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
      transition: left 0.5s ease;
    }

    .btn-submit:hover::before {
      left: 100%;
    }

    /* Footer */
    .login-footer {
      text-align: center;
      margin-top: 2rem;
      color: #6b7280;
      font-size: 0.9rem;
      position: relative;
      z-index: 1;
    }

    .login-footer a {
      color: #1976d2;
      text-decoration: none;
      font-weight: 600;
      transition: color 0.3s ease;
    }

    .login-footer a:hover {
      color: #1565c0;
      text-decoration: underline;
    }

    /* Responsive Design */
    @media (max-width: 576px) {
      .login-card {
        padding: 2rem 1.5rem;
        border-radius: 1.5rem;
      }

      .logo-text {
        font-size: 2rem;
      }

      .login-title {
        font-size: 1.5rem;
      }

      .logo-container img {
        width: 50px;
        height: 50px;
      }
    }

    /* Invalid Feedback */
    .invalid-feedback {
      display: none;
      color: #dc2626;
      font-size: 0.875rem;
      margin-top: 0.5rem;
      font-weight: 500;
    }

    .form-control:invalid:not(:placeholder-shown) {
      border-color: #fca5a5;
    }

    .form-control:invalid:not(:placeholder-shown) ~ .invalid-feedback {
      display: block;
    }

    /* Loading State */
    .btn-submit.loading {
      pointer-events: none;
      opacity: 0.7;
    }

    .btn-submit.loading span {
      visibility: hidden;
    }

    .btn-submit.loading::after {
      content: '';
      position: absolute;
      width: 16px;
      height: 16px;
      top: 50%;
      left: 50%;
      margin-left: -8px;
      margin-top: -8px;
      border: 2px solid transparent;
      border-top-color: #ffffff;
      border-radius: 50%;
      animation: spin 0.6s linear infinite;
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }
  </style>
</head>

<body>
  <div class="login-container">
    <div class="login-card">
      <div class="login-header">
        <div class="logo-container">
          <img src="assets/img/unipos_logo.png" alt="UniPOS Logo">
          <span class="logo-text">UniPOS</span>
        </div>
        <h1 class="login-title">Xoş Gəlmisiniz</h1>
        <p class="login-subtitle">Daxil olmaq üçün məlumatlarınızı daxil edin</p>
      </div>

      <?php if (!empty($login_error_message)): ?>
        <div class="alert" role="alert">
          <i class="bi bi-exclamation-triangle-fill" style="margin-right: 0.5rem;"></i>
          <?php echo $login_error_message; ?>
        </div>
      <?php endif; ?>

      <form class="login-form" method="post" novalidate>
        <div class="form-group">
          <label for="yourName" class="form-label">
            <i class="bi bi-person-fill" style="margin-right: 0.25rem;"></i>
            İstifadəçi Adı
          </label>
          <div class="input-wrapper">
            <i class="bi bi-person-circle input-icon"></i>
            <input 
              type="text" 
              name="name" 
              class="form-control" 
              id="yourName" 
              placeholder="İstifadəçi adınızı daxil edin"
              required
              autocomplete="username"
            >
          </div>
          <div class="invalid-feedback">Zəhmət olmasa istifadəçi adınızı daxil edin!</div>
        </div>

        <div class="form-group">
          <label for="password" class="form-label">
            <i class="bi bi-shield-lock-fill" style="margin-right: 0.25rem;"></i>
            Şifrə
          </label>
          <div class="input-wrapper">
            <i class="bi bi-lock-fill input-icon"></i>
            <input 
              type="password" 
              name="password" 
              class="form-control" 
              id="password" 
              placeholder="Şifrənizi daxil edin"
              required
              autocomplete="current-password"
            >
            <i class="bi bi-eye password-toggle" id="togglePassword"></i>
          </div>
          <div class="invalid-feedback">Zəhmət olmasa şifrənizi daxil edin!</div>
        </div>

        <button class="btn-submit" type="submit" name="login">
          <span>Daxil Ol</span>
        </button>
      </form>

      <div class="login-footer">
        Designed by <a href="">Jumshud</a>
      </div>
    </div>
  </div>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <script>
    // Password Toggle Functionality
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    if (togglePassword && passwordInput) {
      togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        
        // Toggle eye icon
        this.classList.toggle('bi-eye');
        this.classList.toggle('bi-eye-slash');
      });
    }

    // Form Submit Loading State
    const loginForm = document.querySelector('.login-form');
    const submitBtn = document.querySelector('.btn-submit');

    if (loginForm && submitBtn) {
      loginForm.addEventListener('submit', function(e) {
        if (loginForm.checkValidity()) {
          submitBtn.classList.add('loading');
        }
      });
    }
  </script>
</body>

</html>