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
    header("Location: index.php");
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
<html lang="en">

<head>
  <title>Giriş - Restoranlar Siyahısı</title>
  <!-- ======= Header ======= -->
  <?php require_once './includes/head.php' ?>
</head>

<body>

  <main>
    <div class="container">

      <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

              <div class="d-flex justify-content-center py-4">
                <a href="index.html" class="logo d-flex align-items-center w-auto">
                  <img src="assets/img/logo.png" alt="">
                  <span class="d-none d-lg-block">UniPOS</span>
                </a>
              </div><!-- End Logo -->

              <div class="card mb-3">

                <div class="card-body">

                  <div class="pt-4 pb-2">
                    <h5 class="card-title text-center pb-0 fs-4">Giriş</h5>
                    <p class="text-center small">Restoranlar Siyahısı</p>
                  </div>

                  <?php if (!empty($login_error_message)): ?>
                    <div class="alert alert-danger" role="alert" style="border-radius: 15px;">
                      <?php echo $login_error_message; ?>
                    </div>
                  <?php endif; ?>

                  <form class="row g-3 needs-validation" method="post" novalidate>
                    <div class="col-12">
                      <label for="yourName" class="form-label">İstifadəçi Adı:</label>
                      <input type="text" name="name" class="form-control" id="yourName" required>
                      <div class="invalid-feedback">Please, enter your name!</div>
                    </div>

                    <div class="col-12">
                      <label for="yourEmail" class="form-label">Şifrə</label>
                      <input type="password" name="password" class="form-control" id="password" required>
                      <div class="invalid-feedback">Please enter a valid Email adddress!</div>
                    </div>

                    <div class="col-12 mt-5 mb-5">
                      <button class="btn btn-primary w-100" type="submit" name="login">Giriş</button>
                    </div>
                  </form>

                </div>
              </div>

              <div class="credits">
                Designed by <a href="">Jumshud</a>
              </div>

            </div>
          </div>
        </div>

      </section>

    </div>
  </main><!-- End #main -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/chart.js/chart.min.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="assets/vendor/quill/quill.min.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>