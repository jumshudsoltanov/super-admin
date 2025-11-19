<?php

require_once 'config.php';
require_once './lib/lib.php';
require_once './lib/settings_helpers.php';

// Authentication check
if ($_SESSION['username'] == '') {
    header("Location: login.php");
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (isset($_POST['update_hero'])) {
        // Hero section update
        $heroSettings = [
            'hero_title' => $_POST['hero_title'] ?? '',
            'hero_description' => $_POST['hero_description'] ?? '',
            'hero_stat_1_number' => $_POST['hero_stat_1_number'] ?? '',
            'hero_stat_1_label' => $_POST['hero_stat_1_label'] ?? '',
            'hero_stat_2_number' => $_POST['hero_stat_2_number'] ?? '',
            'hero_stat_2_label' => $_POST['hero_stat_2_label'] ?? '',
            'hero_stat_3_number' => $_POST['hero_stat_3_number'] ?? '',
            'hero_stat_3_label' => $_POST['hero_stat_3_label'] ?? '',
        ];
        
        updateMultipleSettings($heroSettings, 'hero');
        $successMessage = "Hero section uğurla yeniləndi!";
        
        // Telegram log
        sendTelegramLog("✅ Settings yeniləndi\n👤 İstifadəçi: " . currentUser('username') . "\n📝 Bölmə: Hero Section");
    }
    
    elseif (isset($_POST['update_contact'])) {
        // Contact section update
        $contactSettings = [
            'contact_phone' => $_POST['contact_phone'] ?? '',
            'contact_email' => $_POST['contact_email'] ?? '',
            'contact_address' => $_POST['contact_address'] ?? '',
        ];
        
        updateMultipleSettings($contactSettings, 'contact');
        $successMessage = "Əlaqə məlumatları uğurla yeniləndi!";
        
        sendTelegramLog("✅ Settings yeniləndi\n👤 İstifadəçi: " . currentUser('username') . "\n📝 Bölmə: Contact Info");
    }
    
    elseif (isset($_POST['update_social'])) {
        // Social media update
        $socialSettings = [
            'social_facebook' => $_POST['social_facebook'] ?? '',
            'social_instagram' => $_POST['social_instagram'] ?? '',
            'social_linkedin' => $_POST['social_linkedin'] ?? '',
            'social_whatsapp' => $_POST['social_whatsapp'] ?? '',
        ];
        
        updateMultipleSettings($socialSettings, 'social');
        $successMessage = "Sosial media linkləri uğurla yeniləndi!";
        
        sendTelegramLog("✅ Settings yeniləndi\n👤 İstifadəçi: " . currentUser('username') . "\n📝 Bölmə: Social Media");
    }
    
    elseif (isset($_POST['update_products'])) {
        // Products section update
        $productSettings = [
            'product_pos_title' => $_POST['product_pos_title'] ?? '',
            'product_pos_description' => $_POST['product_pos_description'] ?? '',
            'product_qr_title' => $_POST['product_qr_title'] ?? '',
            'product_qr_description' => $_POST['product_qr_description'] ?? '',
            'product_integration_title' => $_POST['product_integration_title'] ?? '',
            'product_integration_description' => $_POST['product_integration_description'] ?? '',
        ];
        
        updateMultipleSettings($productSettings, 'products');
        $successMessage = "Məhsul məlumatları uğurla yeniləndi!";
        
        sendTelegramLog("✅ Settings yeniləndi\n👤 İstifadəçi: " . currentUser('username') . "\n📝 Bölmə: Products");
    }
}

// Load current settings
$heroSettings = getHeroSettings();
$contactSettings = getContactSettings();
$socialSettings = getSocialSettings();
$productSettings = getProductSettings();

?>

<!DOCTYPE html>
<html lang="az">

<head>
    <title>Sayt Parametrləri</title>
    <?php require_once './includes/head.php' ?>
</head>

<style>
    .nav-pills .nav-link.active {
        background: linear-gradient(135deg, #6366f1, #4f46e5);
    }
    
    .card {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border: none;
        border-radius: 1rem;
    }
    
    .form-label {
        font-weight: 600;
        color: #1f2937;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #6366f1, #4f46e5);
        border: none;
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, #4f46e5, #6366f1);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(99, 102, 241, 0.3);
    }
</style>

<body>

    <!-- ======= Header ======= -->
    <?php require_once './includes/header.php' ?>
    <!-- ====== Header =======-->

    <!-- ======= Sidebar ======= -->
    <?php require_once './includes/sidebar.php' ?>
    <!-- ====== Sidebar ======= -->

    <main id="main" class="main">

        <div class="pagetitle">
            <h1><i class="bi bi-gear me-2"></i>Sayt Parametrləri</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Ana səhifə</a></li>
                    <li class="breadcrumb-item active">Parametrlər</li>
                </ol>
            </nav>
        </div>

        <?php if (isset($successMessage)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            <?= $successMessage ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>

        <section class="section">
            <div class="row">
                <div class="col-lg-3">
                    <!-- Tabs Navigation -->
                    <div class="card">
                        <div class="card-body pt-3">
                            <ul class="nav nav-pills flex-column" id="settingsTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active w-100 text-start mb-2" id="hero-tab" data-bs-toggle="pill" data-bs-target="#hero" type="button">
                                        <i class="bi bi-house-door me-2"></i>
                                        Hero Section
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link w-100 text-start mb-2" id="products-tab" data-bs-toggle="pill" data-bs-target="#products" type="button">
                                        <i class="bi bi-box-seam me-2"></i>
                                        Məhsullar
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link w-100 text-start mb-2" id="contact-tab" data-bs-toggle="pill" data-bs-target="#contact" type="button">
                                        <i class="bi bi-envelope me-2"></i>
                                        Əlaqə Məlumatları
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link w-100 text-start" id="social-tab" data-bs-toggle="pill" data-bs-target="#social" type="button">
                                        <i class="bi bi-share me-2"></i>
                                        Sosial Media
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-lg-9">
                    <div class="tab-content">
                        
                        <!-- Hero Settings Tab -->
                        <div class="tab-pane fade show active" id="hero" role="tabpanel">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Hero Section Parametrləri</h5>
                                    <form method="POST" action="">
                                        <div class="mb-3">
                                            <label for="hero_title" class="form-label">Başlıq</label>
                                            <input type="text" class="form-control" id="hero_title" name="hero_title" 
                                                   value="<?= htmlspecialchars($heroSettings['hero_title'] ?? '') ?>" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="hero_description" class="form-label">Təsvir</label>
                                            <textarea class="form-control" id="hero_description" name="hero_description" 
                                                      rows="3" required><?= htmlspecialchars($heroSettings['hero_description'] ?? '') ?></textarea>
                                        </div>

                                        <hr class="my-4">
                                        <h6 class="mb-3">Statistika</h6>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="hero_stat_1_number" class="form-label">Stat 1 - Rəqəm</label>
                                                <input type="text" class="form-control" id="hero_stat_1_number" name="hero_stat_1_number" 
                                                       value="<?= htmlspecialchars($heroSettings['hero_stat_1_number'] ?? '') ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="hero_stat_1_label" class="form-label">Stat 1 - Label</label>
                                                <input type="text" class="form-control" id="hero_stat_1_label" name="hero_stat_1_label" 
                                                       value="<?= htmlspecialchars($heroSettings['hero_stat_1_label'] ?? '') ?>">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="hero_stat_2_number" class="form-label">Stat 2 - Rəqəm</label>
                                                <input type="text" class="form-control" id="hero_stat_2_number" name="hero_stat_2_number" 
                                                       value="<?= htmlspecialchars($heroSettings['hero_stat_2_number'] ?? '') ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="hero_stat_2_label" class="form-label">Stat 2 - Label</label>
                                                <input type="text" class="form-control" id="hero_stat_2_label" name="hero_stat_2_label" 
                                                       value="<?= htmlspecialchars($heroSettings['hero_stat_2_label'] ?? '') ?>">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="hero_stat_3_number" class="form-label">Stat 3 - Rəqəm</label>
                                                <input type="text" class="form-control" id="hero_stat_3_number" name="hero_stat_3_number" 
                                                       value="<?= htmlspecialchars($heroSettings['hero_stat_3_number'] ?? '') ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="hero_stat_3_label" class="form-label">Stat 3 - Label</label>
                                                <input type="text" class="form-control" id="hero_stat_3_label" name="hero_stat_3_label" 
                                                       value="<?= htmlspecialchars($heroSettings['hero_stat_3_label'] ?? '') ?>">
                                            </div>
                                        </div>

                                        <button type="submit" name="update_hero" class="btn btn-primary">
                                            <i class="bi bi-check-circle me-1"></i>
                                            Yadda saxla
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Products Settings Tab -->
                        <div class="tab-pane fade" id="products" role="tabpanel">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Məhsul Məlumatları</h5>
                                    <form method="POST" action="">
                                        
                                        <h6 class="mb-3"><i class="bi bi-cash-register me-2"></i>POS Sistemi</h6>
                                        <div class="mb-3">
                                            <label for="product_pos_title" class="form-label">Başlıq</label>
                                            <input type="text" class="form-control" id="product_pos_title" name="product_pos_title" 
                                                   value="<?= htmlspecialchars($productSettings['product_pos_title'] ?? '') ?>">
                                        </div>
                                        <div class="mb-4">
                                            <label for="product_pos_description" class="form-label">Təsvir</label>
                                            <textarea class="form-control" id="product_pos_description" name="product_pos_description" 
                                                      rows="2"><?= htmlspecialchars($productSettings['product_pos_description'] ?? '') ?></textarea>
                                        </div>

                                        <hr class="my-4">
                                        <h6 class="mb-3"><i class="bi bi-qr-code me-2"></i>QR Menu</h6>
                                        <div class="mb-3">
                                            <label for="product_qr_title" class="form-label">Başlıq</label>
                                            <input type="text" class="form-control" id="product_qr_title" name="product_qr_title" 
                                                   value="<?= htmlspecialchars($productSettings['product_qr_title'] ?? '') ?>">
                                        </div>
                                        <div class="mb-4">
                                            <label for="product_qr_description" class="form-label">Təsvir</label>
                                            <textarea class="form-control" id="product_qr_description" name="product_qr_description" 
                                                      rows="2"><?= htmlspecialchars($productSettings['product_qr_description'] ?? '') ?></textarea>
                                        </div>

                                        <hr class="my-4">
                                        <h6 class="mb-3"><i class="bi bi-gear-wide-connected me-2"></i>Tam İnteqrasiya</h6>
                                        <div class="mb-3">
                                            <label for="product_integration_title" class="form-label">Başlıq</label>
                                            <input type="text" class="form-control" id="product_integration_title" name="product_integration_title" 
                                                   value="<?= htmlspecialchars($productSettings['product_integration_title'] ?? '') ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="product_integration_description" class="form-label">Təsvir</label>
                                            <textarea class="form-control" id="product_integration_description" name="product_integration_description" 
                                                      rows="2"><?= htmlspecialchars($productSettings['product_integration_description'] ?? '') ?></textarea>
                                        </div>

                                        <button type="submit" name="update_products" class="btn btn-primary">
                                            <i class="bi bi-check-circle me-1"></i>
                                            Yadda saxla
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Settings Tab -->
                        <div class="tab-pane fade" id="contact" role="tabpanel">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Əlaqə Məlumatları</h5>
                                    <form method="POST" action="">
                                        <div class="mb-3">
                                            <label for="contact_phone" class="form-label">Telefon</label>
                                            <input type="text" class="form-control" id="contact_phone" name="contact_phone" 
                                                   value="<?= htmlspecialchars($contactSettings['contact_phone'] ?? '') ?>">
                                        </div>

                                        <div class="mb-3">
                                            <label for="contact_email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="contact_email" name="contact_email" 
                                                   value="<?= htmlspecialchars($contactSettings['contact_email'] ?? '') ?>">
                                        </div>

                                        <div class="mb-3">
                                            <label for="contact_address" class="form-label">Ünvan</label>
                                            <input type="text" class="form-control" id="contact_address" name="contact_address" 
                                                   value="<?= htmlspecialchars($contactSettings['contact_address'] ?? '') ?>">
                                        </div>

                                        <button type="submit" name="update_contact" class="btn btn-primary">
                                            <i class="bi bi-check-circle me-1"></i>
                                            Yadda saxla
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Social Media Settings Tab -->
                        <div class="tab-pane fade" id="social" role="tabpanel">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Sosial Media Linkləri</h5>
                                    <form method="POST" action="">
                                        <div class="mb-3">
                                            <label for="social_facebook" class="form-label">
                                                <i class="bi bi-facebook text-primary me-1"></i>
                                                Facebook
                                            </label>
                                            <input type="url" class="form-control" id="social_facebook" name="social_facebook" 
                                                   value="<?= htmlspecialchars($socialSettings['social_facebook'] ?? '') ?>" 
                                                   placeholder="https://facebook.com/...">
                                        </div>

                                        <div class="mb-3">
                                            <label for="social_instagram" class="form-label">
                                                <i class="bi bi-instagram text-danger me-1"></i>
                                                Instagram
                                            </label>
                                            <input type="url" class="form-control" id="social_instagram" name="social_instagram" 
                                                   value="<?= htmlspecialchars($socialSettings['social_instagram'] ?? '') ?>" 
                                                   placeholder="https://instagram.com/...">
                                        </div>

                                        <div class="mb-3">
                                            <label for="social_linkedin" class="form-label">
                                                <i class="bi bi-linkedin text-info me-1"></i>
                                                LinkedIn
                                            </label>
                                            <input type="url" class="form-control" id="social_linkedin" name="social_linkedin" 
                                                   value="<?= htmlspecialchars($socialSettings['social_linkedin'] ?? '') ?>" 
                                                   placeholder="https://linkedin.com/...">
                                        </div>

                                        <div class="mb-3">
                                            <label for="social_whatsapp" class="form-label">
                                                <i class="bi bi-whatsapp text-success me-1"></i>
                                                WhatsApp
                                            </label>
                                            <input type="text" class="form-control" id="social_whatsapp" name="social_whatsapp" 
                                                   value="<?= htmlspecialchars($socialSettings['social_whatsapp'] ?? '') ?>" 
                                                   placeholder="https://wa.me/...">
                                        </div>

                                        <button type="submit" name="update_social" class="btn btn-primary">
                                            <i class="bi bi-check-circle me-1"></i>
                                            Yadda saxla
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>

    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
    <?php require_once './includes/footer.php' ?>
    <!-- ======= Footer ======= -->

</body>

</html>
