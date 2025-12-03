<?php
require_once 'config.php';
require_once './lib/lib.php';

if ($_SESSION['username'] == '') {
  header("Location: login.php");
  exit;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$profile = [];

if ($id > 0) {
    $result = sql("SELECT * FROM profiles WHERE id = '$id'");
    if (count($result) > 0) {
        $profile = $result[0];
    }
}

if (empty($profile)) {
    header("Location: restaurantslist.php");
    exit;
}

$menuUrl = "https://qr.tamteam.net/?r=" . $profile['username'];
?>

<!DOCTYPE html>
<html lang="az">

<head>
  <title>QR Generator - <?= htmlspecialchars($profile['restaurant_name']) ?></title>
  <?php require_once './includes/head.php' ?>
  <!-- QR Code Library -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <style>
    .qr-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        background: white;
        border-radius: 1rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        max-width: 500px;
        margin: 0 auto;
    }
    .qr-wrapper {
        position: relative;
        display: inline-block;
        padding: 10px;
        background: white;
        border-radius: 8px;
    }
    #qrcode {
        margin: 0;
    }
    #qrcode img {
        margin: 0 auto;
        display: block;
    }
    .qr-logo-overlay {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 60px;
        height: 60px;
        background: white;
        padding: 4px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        object-fit: contain;
    }
    .print-btn {
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        color: white;
        border: none;
        padding: 10px 25px;
        border-radius: 20px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .print-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
        color: white;
    }
    @media print {
        body * {
            visibility: hidden;
        }
        .qr-wrapper, .qr-wrapper * {
            visibility: visible;
        }
        .qr-wrapper {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            margin: 0;
            padding: 0;
            border: none;
        }
    }
  </style>
</head>

<body>

  <?php require_once './includes/header.php' ?>
  <?php require_once './includes/sidebar.php' ?>

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>QR Generator</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.php">Ana səhifə</a></li>
          <li class="breadcrumb-item"><a href="restaurantslist.php">Restoranlar</a></li>
          <li class="breadcrumb-item active">QR Generator</li>
        </ol>
      </nav>
    </div>

    <section class="section qr-section">
      <div class="row">
        <div class="col-12">
            <div class="qr-container">
                <h3 class="text-center mb-3"><?= htmlspecialchars($profile['restaurant_name']) ?></h3>
                
                <div class="qr-wrapper">
                    <div id="qrcode"></div>
                    <?php if($profile['logo'] !== ''): ?>
                        <img src="../<?= $profile['logo'] ?>" class="qr-logo-overlay" alt="Logo">
                    <?php endif; ?>
                </div>
                
                <p class="text-muted mt-3 text-center small"><?= $menuUrl ?></p>

                <div class="mt-4 d-flex gap-2 no-print">
                    <button onclick="window.print()" class="print-btn">
                        <i class="bi bi-printer me-2"></i> Çap Et
                    </button>
                    <a href="restaurantslist.php" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="bi bi-arrow-left me-2"></i> Geri
                    </a>
                </div>
            </div>
        </div>
      </div>
    </section>

  </main>

  <?php require_once './includes/footer.php' ?>

  <script>
    var qrcode = new QRCode(document.getElementById("qrcode"), {
        text: "<?= $menuUrl ?>",
        width: 256,
        height: 256,
        colorDark : "#000000",
        colorLight : "#ffffff",
        correctLevel : QRCode.CorrectLevel.H
    });
  </script>

</body>
</html>
