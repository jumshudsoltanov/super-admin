<?php
require_once 'config.php';
require_once './lib/lib.php';

// Insert
if (isset($_POST['send'])) {

    $imgUrl = singleImg('logo');
    $restaurant_name = $_POST['restaurant_name'];
    $telegram_chat_id = $_POST['telegram_chat_id'];
    $kitchen_receipt = $_POST['kitchen_receipt'];
    $customer_receipt = $_POST['customer_receipt'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $sale_date = $_POST['sale_date'];
    $sale_type = $_POST['sale_type'];
    $payment = $_POST['payment'];
    $next_payment_date = $_POST['next_payment_date'];
    $boss_name = $_POST['boss_name'];
    $boss_phone_number = $_POST['boss_phone_number'];
    $production_name = $_POST['production_name'];
    $production_phone_number = $_POST['production_phone_number'];
    $region = $_POST['region'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    // Checkboxes & Optional fields (Fixing Warnings)
    $is_contract = $_POST['is_contract'] ?? 0;
    $is_working = $_POST['is_working'] ?? 0;
    $is_pos = $_POST['is_pos'] ?? 0;
    $is_menu = $_POST['is_menu'] ?? 0;
    $is_tax = $_POST['is_tax'] ?? 0;
    $is_telegram_notify = $_POST['is_telegram_notify'] ?? 0;
    $is_whatsapp_notify = $_POST['is_whatsapp_notify'] ?? 0;
    $is_pos_block = $_POST['is_pos_block'] ?? 0;
    $is_menu_block = $_POST['is_menu_block'] ?? 0;
    $is_scroll = $_POST['is_scroll'] ?? 0;
    $is_timer = $_POST['is_timer'] ?? 0;

    $terminal_id = $_POST['terminal_id'] ?? [];
    $terminal_password = $_POST['terminal_code'] ?? [];
    $is_master = $_POST['is_master'] ?? [];

    $tax_percent = $_POST['tax_percent'] ?? 0;
    $tax_merchant = $_POST['tax_merchant'] ?? '';
    $tax_type = $_POST['tax_type'] ?? '';
    $tax_cash_type = $_POST['tax_cash_type'] ?? '';
    $port_addr = $_POST['port_addr'] ?? '';
    $ip_addr = $_POST['ip_addr'] ?? '';


    // Insert Profile (Fixed SQL Syntax Error: added comma before username)
    $insertNewProfile = sql("INSERT INTO `profiles`(`logo`, `restaurant_name`, `telegram_chat_id`, `kitchen_receipt`, `customer_receipt`, `username`, `password`, `sale_date`, `sale_type`, 
    `payment`, `next_payment_date`, `boss_name`, `boss_phone_number`, 
    `production_name`, `production_phone_number`, `region`, `start_time`, `end_time`,
    `is_contract`, `is_working`, `is_pos`, `is_menu`, `is_tax`, 
    `is_telegram_notify`, `is_whatsapp_notify`, `is_pos_block`, `is_menu_block`,
    `tax_percent`, `tax_merchant`, `tax_type`, `ip_addr`, `port_addr`, `tax_cash_type`, `is_scroll`, `is_timer`
) VALUES (
    '$imgUrl','$restaurant_name', '$telegram_chat_id', '$kitchen_receipt', '$customer_receipt', '$username','$password','$sale_date','$sale_type',
    '$payment','$next_payment_date','$boss_name','$boss_phone_number',
    '$production_name','$production_phone_number','$region',
    '$start_time', '$end_time',
    '$is_contract','$is_working','$is_pos','$is_menu','$is_tax',
    '$is_telegram_notify','$is_whatsapp_notify','$is_pos_block','$is_menu_block',
    '$tax_percent','$tax_merchant','$tax_type','$ip_addr','$port_addr','$tax_cash_type', '$is_scroll', '$is_timer'
)");

    $lastId = $conn->insert_id;

    //  Insert Terminal
    if (!empty($terminal_id) && !empty($terminal_password) && count($terminal_id) === count($terminal_password)) {
        foreach ($terminal_id as $i => $terminal) {
            $password = $terminal_password[$i];
            $master = $is_master[$i] ?? 0;
            sql("INSERT INTO terminal_groups (profile_id, terminal_id, terminal_password, is_master) 
                VALUES ('$lastId', '$terminal', '$password', '$master')");
        }
    }

    header('Location: restaurantslist.php');
    exit;
}


// Update 
if (isset($_GET['e']) && !empty($_GET['e'])) {
    $id = base64_decode($_GET['e']);
    $profiles = sql("SELECT * FROM profiles WHERE id = $id")[0] ?? null;
    $getTerminals = sql("SELECT * FROM terminal_groups WHERE profile_id = '$id' ");
    $logoUrl = $profiles['logo'];



    if (isset($_POST['update'])) {

        $logo = !empty(singleImg('logo')) ? singleImg('logo') : $logoUrl;
        $restaurant_name = $_POST['restaurant_name'];
        $telegram_chat_id = $_POST['telegram_chat_id'];
        $kitchen_receipt = $_POST['kitchen_receipt'];
        $customer_receipt = $_POST['customer_receipt'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $sale_date = $_POST['sale_date'];
        $sale_type = $_POST['sale_type'];
        $payment = $_POST['payment'];
        $next_payment_date = $_POST['next_payment_date'];
        $boss_name = $_POST['boss_name'];
        $boss_phone_number = $_POST['boss_phone_number'];
        $production_name = $_POST['production_name'];
        $production_phone_number = $_POST['production_phone_number'];
        $region = $_POST['region'];
        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];

        $is_contract = $_POST['is_contract'] ?? 0;
        $is_working = $_POST['is_working'] ?? 0;
        $is_pos = $_POST['is_pos'] ?? 0;
        $is_menu = $_POST['is_menu'] ?? 0;
        $is_tax = $_POST['is_tax'] ?? 0;
        $is_telegram_notify = $_POST['is_telegram_notify'] ?? 0;
        $is_whatsapp_notify = $_POST['is_whatsapp_notify'] ?? 0;
        $is_pos_block = $_POST['is_pos_block'] ?? 0;
        $is_menu_block = $_POST['is_menu_block'] ?? 0;
        $is_scroll = $_POST['is_scroll'] ?? 0;
        $is_timer = $_POST['is_timer'] ?? 0;

        $terminal_id = $_POST['terminal_id'] ?? [];
        $terminal_password = $_POST['terminal_code'] ?? [];
        $is_master = $_POST['is_master'] ?? [];

        $tax_percent = $_POST['tax_percent'] ?? 0;
        $tax_merchant = $_POST['tax_merchant'] ?? '';
        $tax_type = $_POST['tax_type'] ?? '';
        $tax_cash_type = $_POST['tax_cash_type'] ?? '';
        $port_addr = $_POST['port_addr'] ?? '';
        $ip_addr = $_POST['ip_addr'] ?? '';

        $updateSql = sql("UPDATE `profiles` 
        SET 
        `logo` = '$logo',
        `restaurant_name` = '$restaurant_name',
        `telegram_chat_id` = '$telegram_chat_id',
        `kitchen_receipt` = '$kitchen_receipt',
        `customer_receipt` = '$customer_receipt',
        `username` = '$username',
        `password` = '$password',
        `sale_date` = '$sale_date',
        `sale_type` = '$sale_type',
        `payment` = '$payment',
        `next_payment_date` = '$next_payment_date',
        `boss_name` = '$boss_name',
        `boss_phone_number` = '$boss_phone_number',
        `production_name` = '$production_name',
        `production_phone_number` = '$production_phone_number',
        `region` = '$region',
        `start_time` = '$start_time',
        `end_time` = '$end_time',
        `is_contract` = '$is_contract',
        `is_working` = '$is_working',
        `is_pos` = '$is_pos',
        `is_menu` = '$is_menu',
        `is_tax` = '$is_tax',
        `is_telegram_notify` = '$is_telegram_notify',
        `is_whatsapp_notify` = '$is_whatsapp_notify',
        `is_pos_block` = '$is_pos_block',
        `is_menu_block` = '$is_menu_block',
        `tax_percent` = '$tax_percent',
        `tax_merchant` = '$tax_merchant',
        `tax_type` = '$tax_type',
        `tax_cash_type` = '$tax_cash_type',
        `ip_addr` = '$ip_addr',
        `port_addr` = '$port_addr',
        `is_scroll` = '$is_scroll',
        `is_timer` = '$is_timer'
    WHERE id = '$id'");

        sql("DELETE FROM terminal_groups WHERE profile_id='$id' ");
        if (!empty($terminal_id) && !empty($terminal_password) && count($terminal_id) === count($terminal_password)) {
            foreach ($terminal_id as $i => $terminal) {
                $password = $terminal_password[$i];
                $master = $is_master[$i] ?? 0;
                sql("INSERT INTO terminal_groups (profile_id, terminal_id, terminal_password, is_master) 
                        VALUES ('$id', '$terminal', '$password', '$master')");
            }
        }

        header('Location: restaurantslist.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="az">

<head>
    <title>Restoran İdarə Et</title>
    <?php require_once './includes/head.php' ?>
    <style>
        /* Settings page style consistency */
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
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.25);
        }

        .text-primary {
            color: #6366f1 !important;
        }

        .card-title {
            color: #1f2937;
            font-weight: 600;
        }

        .nav-pills .nav-link {
            color: #6b7280;
            transition: all 0.3s ease;
        }

        .nav-pills .nav-link:hover {
            background-color: rgba(99, 102, 241, 0.1);
        }

        .form-check-input:checked {
            background-color: #6366f1;
            border-color: #6366f1;
        }
    </style>
</head>

<body>
    <?php require_once './includes/header.php' ?>
    <?php require_once './includes/sidebar.php' ?>

    <main id="main" class="main">
        <div class="pagetitle">
            <h1><i class="bi bi-building me-2"></i>Restoran İdarə Et</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Ana səhifə</a></li>
                    <li class="breadcrumb-item active"><?= isset($_GET['e']) ? 'Redaktə Et' : 'Əlavə Et' ?></li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-3">
                    <div class="card">
                        <div class="card-body pt-3">
                            <ul class="nav nav-pills flex-column" id="restaurantTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active w-100 text-start mb-2" id="company-tab" data-bs-toggle="pill" data-bs-target="#company" type="button">
                                        <i class="bi bi-building me-2"></i> Müəssisə Məlumatları
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link w-100 text-start mb-2" id="terminal-tab" data-bs-toggle="pill" data-bs-target="#terminal" type="button">
                                        <i class="bi bi-display-fill"></i> Terminallar siyahısı
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link w-100 text-start" id="additional-tab" data-bs-toggle="pill" data-bs-target="#additional" type="button">
                                        <i class="bi bi-gear me-2"></i> Əlavə Detallar
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-lg-9">
                    <form class="form form-vertical" method="post" action="" enctype="multipart/form-data">
                        <div class="tab-content">

                            <div class="tab-pane fade show active" id="company" role="tabpanel">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title"><i class="bi bi-info-circle me-2"></i>Müəssisə Məlumatları</h5>
                                        <div class="row mt-3">
                                            <div class="col-md-12 mb-3">
                                                <label for="logo" class="form-label">Müəsissə Loqosu</label>
                                                <input type="file" class="form-control" id="logo" name="logo">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="restaurant_name" class="form-label">Müəsissə Adı</label>
                                                <input type="text" class="form-control" id="restaurant_name" name="restaurant_name" placeholder="Müəsissə adını daxil edin" value="<?= $profiles['restaurant_name'] ?? '' ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="username" class="form-label">İstifadəçi Adı</label>
                                                <input type="text" class="form-control" id="username" name="username" placeholder="İstifadəçi adını daxil edin" value="<?= $profiles['username'] ?? '' ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="password" class="form-label">Şifrə</label>
                                                <input type="text" class="form-control" id="password" name="password" placeholder="Şifrəni daxil edin" value="<?= $profiles['password'] ?? '' ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="telegram_chat_id" class="form-label">Telegram Chat ID</label>
                                                <input type="text" class="form-control" id="telegram_chat_id" name="telegram_chat_id" placeholder="Chat ID" value="<?= $profiles['telegram_chat_id'] ?? '' ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="sale_date" class="form-label">Satış Tarixi</label>
                                                <input type="date" class="form-control" id="sale_date" name="sale_date" value="<?= $profiles['sale_date'] ?? '' ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="sale_type" class="form-label">Satış Növü</label>
                                                <select class="form-select" id="sale_type" name="sale_type">
                                                    <option value="">-- Birini Seçin --</option>
                                                    <option value="İllik" <?= (($profiles['sale_type'] ?? '') == 'İllik') ? 'selected' : '' ?>>İllik</option>
                                                    <option value="Aylıq" <?= (($profiles['sale_type'] ?? '') == 'Aylıq') ? 'selected' : '' ?>>Aylıq</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="payment" class="form-label">Ödəniş</label>
                                                <input type="text" class="form-control" id="payment" name="payment" placeholder="Ödəniş məbləği" value="<?= $profiles['payment'] ?? '' ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="next_payment_date" class="form-label">Növbəti Ödəniş Tarixi</label>
                                                <input type="date" class="form-control" id="next_payment_date" name="next_payment_date" value="<?= $profiles['next_payment_date'] ?? '' ?>">
                                            </div>
                                        </div>
                                        <hr class="my-4">
                                        <h6 class="text-primary mb-3"><i class="bi bi-person me-2"></i>Müəsissə Sahibinin Məlumatları</h6>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="boss_name" class="form-label">Müəsissə Sahibinin Adı</label>
                                                <input type="text" class="form-control" id="boss_name" name="boss_name" placeholder="Sahib adı" value="<?= $profiles['boss_name'] ?? '' ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="boss_phone_number" class="form-label">Müəsissə Sahibinin Nömrəsi</label>
                                                <input type="tel" class="form-control" id="boss_phone_number" name="boss_phone_number" placeholder="+994XX XXX XX XX" value="<?= $profiles['boss_phone_number'] ?? '' ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="production_name" class="form-label">Məhsul Şəxs</label>
                                                <input type="text" class="form-control" id="production_name" name="production_name" placeholder="İstehsalçı adı" value="<?= $profiles['production_name'] ?? '' ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="production_phone_number" class="form-label">Məhsul Şəxsin Nömrəsi</label>
                                                <input type="tel" class="form-control" id="production_phone_number" name="production_phone_number" placeholder="+994XX XXX XX XX" value="<?= $profiles['production_phone_number'] ?? '' ?>">
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label for="region" class="form-label">Şəhər, Region</label>
                                                <input type="text" class="form-control" id="region" name="region" placeholder="Şəhər və ya region" value="<?= $profiles['region'] ?? '' ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="start_time" class="form-label">Başlama Vaxtı</label>
                                                <input type="time" class="form-control" id="start_time" name="start_time" value="<?= $profiles['start_time'] ?? '' ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="end_time" class="form-label">Bitmə Vaxtı</label>
                                                <input type="time" class="form-control" id="end_time" name="end_time" value="<?= $profiles['end_time'] ?? '' ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <div class="form-check form-switch"><input class="form-check-input" type="checkbox" id="is_contract" name="is_contract" value="1" <?= (isset($profiles['is_contract']) && $profiles['is_contract'] === '1') ? 'checked' : '' ?>><label class="form-check-label" for="is_contract">Müqavilə Var?</label></div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <div class="form-check form-switch"><input class="form-check-input" type="checkbox" id="is_working" name="is_working" value="1" <?= (isset($profiles['is_working']) && $profiles['is_working'] === '1') ? 'checked' : '' ?>><label class="form-check-label" for="is_working">Bizimlə Çalışır?</label></div>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-12 d-flex justify-content-end">
                                                <button type="submit" name="<?= isset($_GET['e']) ? 'update' : 'send' ?>" class="btn btn-primary"><i class="bi bi-check-circle me-2"></i>Yadda Saxla</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="additional" role="tabpanel">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title"><i class="bi bi-gear me-2"></i>Əlavə Detallar</h5>
                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <h6 class="text-primary mb-3"><i class="bi bi-receipt me-2"></i>Çek Məlumatları</h6>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="customer_receipt" class="form-label">Müştəri Çeki Ölçüsü</label>
                                                        <input type="text" class="form-control" id="customer_receipt" name="customer_receipt" placeholder="58mm" value="<?= $profiles['customer_receipt'] ?? '' ?>">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="kitchen_receipt" class="form-label">Mətbəx Çeki Ölçüsü</label>
                                                        <input type="text" class="form-control" id="kitchen_receipt" name="kitchen_receipt" placeholder="80mm" value="<?= $profiles['kitchen_receipt'] ?? '' ?>">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12 mt-3">
    <h6 class="text-primary mb-3"><i class="bi bi-hdd-network me-2"></i>Vergi və Şəbəkə Məlumatları</h6>
    
    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="tax_cash_type" class="form-label">Vergi inteqrasiyası</label>
            <select class="form-select" id="tax_cash_type" name="tax_cash_type">
                <option value="" selected>-- Birini Seçin --</option>
                <option value="azsmart" <?= ($profiles['tax_cash_type'] ?? '') == 'azsmart' ? "selected" : '' ?>>Azsmart</option>
                <option value="omnitech" <?= ($profiles['tax_cash_type'] ?? '') == 'omnitech' ? "selected" : '' ?>>Omnitech</option>
                <option value="sunmi" <?= ($profiles['tax_cash_type'] ?? '') == 'sunmi' ? "selected" : '' ?>>Sunmi</option>
                <option value="nba" <?= ($profiles['tax_cash_type'] ?? '') == 'nba' ? "selected" : '' ?>>Nba</option>
                <option value="vizar" <?= ($profiles['tax_cash_type'] ?? '') == 'vizar' ? "selected" : '' ?>>Vizar</option>
            </select>
        </div>
        <div class="col-md-4 mb-3">
            <label for="tax_percent" class="form-label">Vergi faizi</label>
            <div class="input-group">
                <span class="input-group-text">%</span>
                <input type="text" class="form-control" id="tax_percent" name="tax_percent" value="<?= $profiles['tax_percent'] ?? '' ?>">
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <label for="tax_merchant" class="form-label">Vergi Merchant ID</label>
            <div class="input-group">
                <span class="input-group-text">ID</span>
                <input type="text" class="form-control" id="tax_merchant" name="tax_merchant" value="<?= $profiles['tax_merchant'] ?? '' ?>">
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <label for="tax_type" class="form-label">Vergi Növü</label>
            <input type="text" class="form-control" id="tax_type" name="tax_type" value="<?= $profiles['tax_type'] ?? '' ?>">
        </div>

        <div class="col-md-4 mb-3">
            <label for="ip_addr" class="form-label">Kassa IP</label>
            <input type="text" class="form-control" id="ip_addr" name="ip_addr" value="<?= $profiles['ip_addr'] ?? '' ?>">
        </div>

        <div class="col-md-4 mb-3">
            <label for="port_addr" class="form-label">Kassa Port</label>
            <input type="text" class="form-control" id="port_addr" name="port_addr" value="<?= $profiles['port_addr'] ?? '' ?>">
        </div>

        
    </div>
</div>

                                            <div class="col-md-12 mt-3">
                                                <h6 class="text-primary mb-3"><i class="bi bi-toggles me-2"></i>Sistem Parametrləri</h6>
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <div class="form-check form-switch"><input class="form-check-input" type="checkbox" id="is_pos" name="is_pos" value="1" <?= (isset($profiles['is_pos']) && $profiles['is_pos'] === '1') ? 'checked' : '' ?>><label class="form-check-label" for="is_pos">POS Aktivdir?</label></div>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <div class="form-check form-switch"><input class="form-check-input" type="checkbox" id="is_menu" name="is_menu" value="1" <?= (isset($profiles['is_menu']) && $profiles['is_menu'] === '1') ? 'checked' : '' ?>><label class="form-check-label" for="is_menu">Menyu Aktivdir?</label></div>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <div class="form-check form-switch"><input class="form-check-input" type="checkbox" id="is_tax" name="is_tax" value="1" <?= (isset($profiles['is_tax']) && $profiles['is_tax'] === '1') ? 'checked' : '' ?>><label class="form-check-label" for="is_tax">Vergi Aktivdir?</label></div>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <div class="form-check form-switch"><input class="form-check-input" type="checkbox" id="is_scroll" name="is_scroll" value="1" <?= (isset($profiles['is_scroll']) && $profiles['is_scroll'] === '1') ? 'checked' : '' ?>><label class="form-check-label" for="is_scroll">Scroll Aktivdir?</label></div>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <div class="form-check form-switch"><input class="form-check-input" type="checkbox" id="is_telegram_notify" name="is_telegram_notify" value="1" <?= (isset($profiles['is_telegram_notify']) && $profiles['is_telegram_notify'] === '1') ? 'checked' : '' ?>><label class="form-check-label" for="is_telegram_notify">Telegram Bildiriş</label></div>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <div class="form-check form-switch"><input class="form-check-input" type="checkbox" id="is_whatsapp_notify" name="is_whatsapp_notify" value="1" <?= (isset($profiles['is_whatsapp_notify']) && $profiles['is_whatsapp_notify'] === '1') ? 'checked' : '' ?>><label class="form-check-label" for="is_whatsapp_notify">WhatsApp Bildiriş</label></div>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <div class="form-check form-switch"><input class="form-check-input" type="checkbox" id="is_pos_block" name="is_pos_block" value="1" <?= (isset($profiles['is_pos_block']) && $profiles['is_pos_block'] === '1') ? 'checked' : '' ?>><label class="form-check-label" for="is_pos_block">POS Blok?</label></div>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <div class="form-check form-switch"><input class="form-check-input" type="checkbox" id="is_menu_block" name="is_menu_block" value="1" <?= (isset($profiles['is_menu_block']) && $profiles['is_menu_block'] === '1') ? 'checked' : '' ?>><label class="form-check-label" for="is_menu_block">Menyu Blok?</label></div>
                                                    </div>
                                                     <div class="col-md-6 mb-3">
                                                        <div class="form-check form-switch"><input class="form-check-input" type="checkbox" id="is_timer" name="is_timer" value="1" <?= (isset($profiles['is_timer']) && $profiles['is_timer'] === '1') ? 'checked' : '' ?>><label class="form-check-label" for="is_tax">Timer Aktivdir?</label></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-12 d-flex justify-content-end">
                                                <button type="submit" name="<?= isset($_GET['e']) ? 'update' : 'send' ?>" class="btn btn-primary"><i class="bi bi-check-circle me-2"></i>Yadda Saxla</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="tab-pane fade" id="terminal" role="tabpanel">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title"><i class="bi bi-display-fill"></i> Terminallar siyahısı</h5>

                                        <div class="col-12">
                                            <div class="card shadow border-0 rounded-3">
                                                <div class="card-header d-flex flex-wrap justify-content-between align-items-center bg-white border-0 pb-0">
                                                    <h4 class="card-title mb-0 text-primary fw-bold">Terminallar</h4>
                                                    <button type="button" class="btn btn-sm btn-outline-primary btn-md mt-2 mt-md-0" onclick="addForm(this)">
                                                        <i class="bi bi-plus-circle me-1"></i> Əlavə Et
                                                    </button>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row g-3 mb-2 mt-2" id="terminal_list">
                                                        <?php if (isset($_GET['e']) && !empty($_GET['e'])): ?>

                                                            <?php foreach ($getTerminals as $key => $value) : ?>
                                                                <div class="row g-3 align-items-end mb-2 terminal_item">

                                                                    <div class="col-md-4 col-12">
                                                                        <label class="form-label">Terminal ID</label>
                                                                        <input type="text" class="form-control" placeholder="Terminal ID" name="terminal_id[]" value="<?= htmlspecialchars($value['terminal_id'] ?? '') ?>" readonly>
                                                                    </div>

                                                                    <div class="col-md-4 col-12">
                                                                        <label class="form-label">Terminal Kodu</label>
                                                                        <input type="text" class="form-control" placeholder="Terminal Kodu" name="terminal_code[]" value="<?= htmlspecialchars($value['terminal_password'] ?? '') ?>">
                                                                    </div>

                                                                    <!-- Master sahəsi: Bootstrap toggle switch -->
                                                                    <div class="col-md-2 col-12">
                                                                        <label class="form-label d-block">Master</label>

                                                                        <div class="form-check form-switch d-inline-block">
                                                                            <input
                                                                                class="form-check-input"
                                                                                type="checkbox"
                                                                                id="terminalMaster<?= $key ?>"
                                                                                name="is_master[]"
                                                                                value="1"
                                                                                <?= (!empty($value['is_master']) && $value['is_master'] == 1) ? 'checked' : '' ?>>
                                                                            <label class="form-check-label" for="terminalMaster<?= $key ?>">
                                                                                <?= (!empty($value['is_master']) && $value['is_master'] == 1) ? 'Bəli' : 'Xeyr' ?>
                                                                            </label>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-2 col-12 d-flex align-items-end">
                                                                        <button type="button" class="btn btn-outline-danger w-100" onclick="this.closest('.terminal_item').remove()">
                                                                            <i class="bi bi-trash"></i> Sil
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            <?php endforeach; ?>


                                                        <?php endif ?>
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="row mt-3">
                                                <div class="col-12 d-flex justify-content-end">
                                                    <button type="submit" name="<?= isset($_GET['e']) ? 'update' : 'send' ?>" class="btn btn-primary"><i class="bi bi-check-circle me-2"></i>Yadda Saxla</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                    </form>
                </div>
            </div>
        </section>
    </main>
    <?php require_once './includes/footer.php' ?>

    <script>
        function generateTerminalID() {
            return Math.floor(10000000 + Math.random() * 90000000);
        }

        function addForm() {
            const terminal_list = document.getElementById('terminal_list');
            const terminalID = generateTerminalID(); // Yeni ID

            let html = `
  <div class="row g-3 align-items-end mb-2 terminal_item">
      
      <!-- Terminal ID -->
      <div class="col-md-4 col-12">
          <label class="form-label">Terminal ID</label>
          <input type="text" class="form-control" placeholder="Terminal ID" name="terminal_id[]" value="${terminalID}" readonly>
      </div>

      <!-- Terminal Kodu -->
      <div class="col-md-4 col-12">
          <label class="form-label">Terminal Kodu</label>
          <input type="text" class="form-control" placeholder="Terminal Kodu" name="terminal_code[]">
      </div>

      <!-- Master Switch -->
      <div class="col-md-2 col-12">
          <label class="form-label d-block">Master</label>
          <div class="form-check form-switch d-inline-block">
              <input class="form-check-input" type="checkbox" name="is_master[]" value="1" id="masterSwitch${Date.now()}">
              <label class="form-check-label" for="masterSwitch${Date.now()}">Bəli</label>
          </div>
      </div>

      <!-- Sil Butonu -->
      <div class="col-md-2 col-12 d-flex align-items-end">
          <button type="button" class="btn btn-outline-danger w-100" onclick="this.closest('.terminal_item').remove()">
              <i class="bi bi-trash"></i> Sil
          </button>
      </div>
  </div>
`;

            terminal_list.innerHTML += html;
        }
    </script>

</body>

</html>