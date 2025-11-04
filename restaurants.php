    <?php

    require_once 'config.php';


    require_once './lib/lib.php';






    // Insert
    if (isset($_POST['send']) && !empty($_POST['send'])) {

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
        $is_contract = $_POST['is_contract'] ?? 0;
        $is_working = $_POST['is_working'] ?? 0;
        $is_pos = $_POST['is_pos'] ?? 0;
        $is_menu = $_POST['is_menu'] ?? 0;
        $is_tax = $_POST['is_tax'] ?? 0;
        $is_telegram_notify = $_POST['is_telegram_notify'] ?? 0;
        $is_whatsapp_notify = $_POST['is_whatsapp_notify'] ?? 0;
        $is_pos_block = $_POST['is_pos_block'] ?? 0;
        $is_menu_block = $_POST['is_menu_block'] ?? 0;
        $terminal_id = $_POST['terminal_id'] ?? 0;
        $terminal_password = $_POST['terminal_code'] ?? 0;
        $tax_percent = $_POST['tax_percent'] ?? 0;
        $tax_merchant = $_POST['tax_merchant'];
        $tax_type = $_POST['tax_type'];
        $tax_cash_type = $_POST['tax_cash_type'];
        $port_addr = $_POST['port_addr'];
        $ip_addr = $_POST['ip_addr'];


        // Insert Profile
        $insertNewProfile = sql("INSERT INTO `profiles`(`logo`, `restaurant_name`, `telegram_chat_id`, `kitchen_receipt`, `customer_receipt`, `username`, `password`, `sale_date`, `sale_type`, 
    `payment`, `next_payment_date`, `boss_name`, `boss_phone_number`, 
    `production_name`, `production_phone_number`, `region`, 
    `is_contract`, `is_working`, `is_pos`, `is_menu`, `is_tax`, 
    `is_telegram_notify`, `is_whatsapp_notify`, `is_pos_block`, `is_menu_block`,
    `tax_percent`, `tax_merchant`, `tax_type`, `ip_addr`, `port_addr`, `tax_cash_type`
) VALUES (
    '$imgUrl','$restaurant_name', '$telegram_chat_id', '$kitchen_receipt', '$customer_receipt' '$username','$password','$sale_date','$sale_type',
    '$payment','$next_payment_date','$boss_name','$boss_phone_number',
    '$production_name','$production_phone_number','$region',
    '$is_contract','$is_working','$is_pos','$is_menu','$is_tax',
    '$is_telegram_notify','$is_whatsapp_notify','$is_pos_block','$is_menu_block',
    '$tax_percent','$tax_merchant','$tax_type','$ip_addr','$port_addr','$tax_cash_type'
)");

        $lastId = $conn->insert_id;

        //  Insert Terminal
        if (!empty($terminal_id) && !empty($terminal_password) && count($terminal_id) === count($terminal_password)) {
            foreach ($terminal_id as $i => $terminal) {
                $password = $terminal_password[$i];

                sql("INSERT INTO terminal_groups (profile_id, terminal_id, terminal_password) 
                VALUES ('$lastId', '$terminal', '$password')");
            }
        }


        header('Location: index.php');
        exit;
    }


    // Update 

    if (isset($_GET['e']) && !empty($_GET['e'])) {
        $id = base64_decode($_GET['e']);
        $profiles = sql("SELECT * FROM profiles WHERE id = $id")[0] ?? null;
        $getTerminals = sql("SELECT * FROM terminal_groups WHERE profile_id = '$id' ");


        if (isset($_POST['update'])) {

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
            $is_contract = $_POST['is_contract'] ?? 0;
            $is_working = $_POST['is_working'] ?? 0;
            $is_pos = $_POST['is_pos'] ?? 0;
            $is_menu = $_POST['is_menu'] ?? 0;
            $is_tax = $_POST['is_tax'] ?? 0;
            $is_telegram_notify = $_POST['is_telegram_notify'] ?? 0;
            $is_whatsapp_notify = $_POST['is_whatsapp_notify'] ?? 0;
            $is_pos_block = $_POST['is_pos_block'] ?? 0;
            $is_menu_block = $_POST['is_menu_block'] ?? 0;
            $terminal_id = $_POST['terminal_id'] ?? 0;
            $terminal_password = $_POST['terminal_code'] ?? 0;
            $tax_percent = $_POST['tax_percent'] ?? 0;
            $tax_merchant = $_POST['tax_merchant'];
            $tax_type = $_POST['tax_type'];
            $tax_cash_type = $_POST['tax_cash_type'];
            $port_addr = $_POST['port_addr'];
            $ip_addr = $_POST['ip_addr'];


            $updateSql = sql("UPDATE `profiles` 
    SET 
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
        `port_addr` = '$port_addr'
    WHERE id = '$id'
");



            sql("DELETE FROM terminal_groups WHERE profile_id='$id' ");
            if (!empty($terminal_id) && !empty($terminal_password) && count($terminal_id) === count($terminal_password)) {
                foreach ($terminal_id as $i => $terminal) {
                    $password = $terminal_password[$i];
                    sql("INSERT INTO terminal_groups (profile_id, terminal_id, terminal_password) 
                        VALUES ('$id', '$terminal', '$password')");
                }
            }


            header('Location: index.php');
            exit;
        }
    }






    ?>


    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Restoranlar Siyahısı</title>
        <?php require_once './includes/head.php' ?>

    </head>

    <body>
        <div id="app">
            <?php require_once './includes/sidebar.php' ?>
            <div id="main">
                <?php require_once './includes/header.php' ?>
                <div class="page-heading">
                    <div class="page-title">
                        <div class="row">
                            <div class="col-12 col-md-6 order-md-1 order-last">
                                <h3>Restoranlar Siyahısı</h3>
                            </div>
                            <div class="col-12 col-md-6 order-md-2 order-first">
                                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="#"><?= isset($_GET['e']) ? 'Redaktə Et' : 'Əlavə Et' ?></a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Restoranlar Siyahısı</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                    <section class="section mt-3">

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-content">
                                        <div class="card-body p-3">
                                            <ul class="nav nav-tabs d-flex" id="myTabjustified" role="tablist">
                                                <li class="nav-item flex-fill" role="presentation">
                                                    <button class="nav-link w-100 active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-justified" type="button" role="tab" aria-controls="home" aria-selected="true">Müəssisə məlumatları</button>
                                                </li>
                                                <li class="nav-item flex-fill" role="presentation">
                                                    <button class="nav-link w-100 " id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-justified" type="button" role="tab" aria-controls="profile" aria-selected="false">Əlavə detallar</button>
                                                </li>
                                            </ul>
                                            <form class="form form-vertical" method="post" action="" enctype="multipart/form-data">
                                                <div class="form-body mt-4">

                                                    <div class="row">
                                                        <div class="tab-content pt-2" id="myTabjustifiedContent">
                                                            <div class="tab-pane fade  active show" id="home-justified" role="tabpanel" aria-labelledby="home-tab">
                                                                <div class="text-center">
                                                                    <h5 class="fw-bold text-primary pt-4">
                                                                        <i class="bi bi-info-circle me-2"></i> Müəsissə Məlumatları
                                                                    </h5>
                                                                    <hr class="w-25 mx-auto opacity-50">
                                                                </div>

                                                                <div class="col-12 mb-1">
                                                                    <label for="first-name-icon">Müəsissə Loqosu</label>
                                                                    <div class="position-relative mt-2">
                                                                        <input type="file" class="form-control" id="inputGroupFile01" name="logo" value="">
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="form-group has-icon-left mt-2">
                                                                        <label for="first-name-icon">Müəsissə Adı</label>
                                                                        <div class="position-relative mt-2">
                                                                            <input type="text" class="form-control" placeholder="Sizin Cavabınız" id="first-name-icon" name="restaurant_name" value="<?= $profiles['restaurant_name'] ?? '' ?>">

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="form-group has-icon-left mt-2">
                                                                        <label for="first-name-icon">İstifadəçi Adı</label>
                                                                        <div class="position-relative mt-2">
                                                                            <input type="text" class="form-control" placeholder="Sizin Cavabınız" id="first-name-icon" name="username" value="<?= $profiles['username'] ?? '' ?>">

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="form-group has-icon-left mt-2">
                                                                        <label for="first-name-icon">Telegram Chat Id</label>
                                                                        <div class="position-relative mt-2">
                                                                            <input type="text" class="form-control" placeholder="Sizin Cavabınız" id="first-name-icon" name="telegram_chat_id" value="<?= $profiles['telegram_chat_id'] ?? '' ?>">

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="form-group has-icon-left mt-2">
                                                                        <label for="email-id-icon">Şifrə</label>
                                                                        <div class="position-relative mt-2">
                                                                            <input type="text" class="form-control" placeholder="Sizin Cavabınız" id="email-id-icon" name="password" value="<?= $profiles['password'] ?? '' ?>">

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="form-group has-icon-left mt-2">
                                                                        <label for="password-id-icon">Satış Tarixi</label>
                                                                        <div class="position-relative mt-2">
                                                                            <input type="date" class="form-control" placeholder="Password" id="password-id-icon" name="sale_date" value="<?= $profiles['sale_date'] ?? '' ?>">

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="form-group has-icon-left mt-2">
                                                                        <label for="mobile-id-icon">Satış Növü</label>
                                                                        <div class="position-relative mt-2">
                                                                            <select class="form-select" name="sale_type">
                                                                                <option>-- Birini Seçin --</option>
                                                                                <?php foreach (['İllik', 'Aylıq'] as $key => $value) : ?>
                                                                                    <option value="<?= $value ?>"
                                                                                        <?= (($profiles['sale_type'] ?? null) == $key && isset($_GET['e'])) ? 'selected' : '' ?>>
                                                                                        <?= $value ?>
                                                                                    </option>
                                                                                <?php endforeach; ?>

                                                                            </select>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="form-group has-icon-left mt-2">
                                                                        <label for="mobile-id-icon">Ödəniş</label>
                                                                        <div class="position-relative mt-2">
                                                                            <input type="text" class="form-control" placeholder="Sizin Cavabınız" id="mobile-id-icon" name="payment" value="<?= $profiles['payment'] ?? '' ?>">

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="form-group has-icon-left mt-2">
                                                                        <label for="password-id-icon">Növbəti Ödəniş Tarixi</label>
                                                                        <div class="position-relative mt-2">
                                                                            <input type="date" class="form-control" placeholder="Password" id="password-id-icon" name="next_payment_date" value="<?= $profiles['next_payment_date'] ?? '' ?>">

                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="text-center mt-4">
                                                                    <h5 class="fw-bold text-primary">
                                                                        <i class="bi bi-info-circle me-2"></i> Müəsissə Sahibinin Məlumatları
                                                                    </h5>
                                                                    <hr class="w-25 mx-auto opacity-50">
                                                                </div>
                                                                

                                                                <div class="col-12">
                                                                    <div class="form-group has-icon-left mt-2">
                                                                        <label for="first-name-icon">Müəsissə Sahibinin Adı</label>
                                                                        <div class="position-relative mt-2">
                                                                            <input type="text" class="form-control" placeholder="Sizin Cavabınız" id="first-name-icon" name="boss_name" value="<?= $profiles['boss_name'] ?? '' ?>">

                                                                        </div>
                                                                    </div>
                                                                </div>


                                                                <div class="col-12">
                                                                    <div class="form-group has-icon-left mt-2">
                                                                        <label for="first-name-icon">Müəsissə Sahibinin Nömrəsi</label>
                                                                        <div class="position-relative mt-2">
                                                                            <input type="text" class="form-control" placeholder="Sizin Cavabınız" id="first-name-icon" name="boss_phone_number" value="<?= $profiles['boss_phone_number'] ?? '' ?>">

                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-12">
                                                                    <div class="form-group has-icon-left mt-2">
                                                                        <label for="first-name-icon">Məhsul şəxs</label>
                                                                        <div class="position-relative mt-2">
                                                                            <input type="text" class="form-control" placeholder="Sizin Cavabınız" id="first-name-icon" name="production_name" value="<?= $profiles['production_name'] ?? '' ?>">

                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-12">
                                                                    <div class="form-group has-icon-left mt-2">
                                                                        <label for="first-name-icon">Məhsul şəxsin nömrəsi</label>
                                                                        <div class="position-relative mt-2">
                                                                            <input type="text" class="form-control" placeholder="Sizin Cavabınız" id="first-name-icon" name="production_phone_number" value="<?= $profiles['production_phone_number'] ?? '' ?>">

                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-12">
                                                                    <div class="form-group has-icon-left mt-2">
                                                                        <label for="first-name-icon">Şəhər, Region</label>
                                                                        <div class="position-relative mt-2">
                                                                            <input type="text" class="form-control" placeholder="Sizin Cavabınız" id="first-name-icon" name="region" value="<?= $profiles['region'] ?? '' ?>">

                                                                        </div>
                                                                    </div>
                                                                </div>


                                                                <div class="col-12 mt-2">
                                                                    <div class="form-check form-switch mt-2">
                                                                        <div class="checkbox mt-2">
                                                                            <input type="checkbox" id="document-me-v" class="form-check-input" value="1" name="is_contract" <?= (isset($profiles['is_contract']) && $profiles['is_contract'] === '1') ? 'checked' : '' ?>>
                                                                            <label for="document-me-v">Müqavilə Var?</label>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-12 mt-2">
                                                                    <div class="form-check form-switch mt-2">
                                                                        <div class="checkbox mt-2">
                                                                            <input type="checkbox" id="is_working" class="form-check-input" value="1" name="is_working" <?= (isset($profiles['is_working']) && $profiles['is_working'] === '1') ? 'checked' : '' ?>>
                                                                            <label for="is_working">Bizimlə Çalışır?</label>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                  <div class="text-center mt-4">
                                                                    <h5 class="fw-bold text-primary">
                                                                        <i class="bi bi-info-circle me-2"></i> Çek Məlumatları
                                                                    </h5>
                                                                    <hr class="w-25 mx-auto opacity-50">
                                                                </div>

                                                                <div class="col-12">
                                                                    <div class="form-group has-icon-left mt-2">
                                                                        <label for="first-name-icon">Müştəri çeki ölçüsü</label>
                                                                        <div class="position-relative mt-2">
                                                                            <input type="text" class="form-control" placeholder="Sizin Cavabınız" id="first-name-icon" name="customer_receipt" value="<?= $profiles['customer_receipt'] ?? '' ?>">

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                 <div class="col-12">
                                                                    <div class="form-group has-icon-left mt-2">
                                                                        <label for="first-name-icon">Mətbəx çeki ölçüsü</label>
                                                                        <div class="position-relative mt-2">
                                                                            <input type="text" class="form-control" placeholder="Sizin Cavabınız" id="first-name-icon" name="kitchen_receipt" value="<?= $profiles['kitchen_receipt'] ?? '' ?>">

                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="text-center mt-5">
                                                                    <h5 class="fw-bold text-primary">
                                                                        <i class="bi bi-info-circle me-2"></i> Sistem Məlumatları
                                                                    </h5>
                                                                    <hr class="w-25 mx-auto opacity-50">
                                                                </div>


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

                                                                                            <div class="col-md-5 col-12">
                                                                                                <label class="form-label">Terminal ID</label>
                                                                                                <input type="text" class="form-control" placeholder="Terminal ID" name="terminal_id[]" value="<?= $value['terminal_id'] ?? '' ?>" readonly>
                                                                                            </div>


                                                                                            <div class="col-md-5 col-12">
                                                                                                <label class="form-label">Terminal Kodu</label>
                                                                                                <input type="text" class="form-control" placeholder="Terminal Kodu" name="terminal_code[]" value="<?= $value['terminal_password'] ?? '' ?>">
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


                                                                    <div class="col-12 mt-3">
                                                                        <div class="form-check form-switch">
                                                                            <div class="checkbox mt-1">
                                                                                <input type="checkbox" id="pos-me-v" class="form-check-input" value="1" name="is_pos" <?= (isset($profiles['is_pos']) && $profiles['is_pos'] === '1') ? 'checked' : '' ?>>
                                                                                <label for="pos-me-v">Pos Aktivdir?</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>


                                                                    <div class="col-12 mt-3">
                                                                        <div class="form-check form-switch">
                                                                            <div class="checkbox mt-1">
                                                                                <input type="checkbox" id="menu-me-v" class="form-check-input" value="1" name="is_menu" <?= (isset($profiles['is_menu']) && $profiles['is_menu'] === '1') ? 'checked' : '' ?>>
                                                                                <label for="menu-me-v">Qr Menyu Aktivdir?</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-12 mt-3">
                                                                        <div class="form-check form-switch">
                                                                            <div class="checkbox mt-2">
                                                                                <input type="checkbox" id="telegram-me-v" class="form-check-input" value="1" name="is_telegram_notify" <?= (isset($profiles['is_telegram_notify']) && $profiles['is_telegram_notify'] === '1') ? 'checked' : '' ?>>
                                                                                <label for="telegram-me-v">Telegram Ödəniş Xatırlatma?</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>


                                                                    <div class="col-12 mt-3">
                                                                        <div class="form-check form-switch">
                                                                            <div class="checkbox mt-3">
                                                                                <input type="checkbox" id="whatsapp-me-v" class="form-check-input" value="1" name="is_whatsapp_notify" <?= (isset($profiles['is_whatsapp_notify']) && $profiles['is_whatsapp_notify'] === '1') ? 'checked' : '' ?>>
                                                                                <label for="whatsapp-me-v">Whatsapp Ödəniş Xatırlatma?</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>


                                                                    <div class="text-center mt-4">
                                                                        <h5 class="fw-bold text-primary">
                                                                            <i class="bi bi-info-circle me-2"></i> Sistem Bloklama Məlumatları
                                                                        </h5>
                                                                        <hr class="w-25 mx-auto opacity-50">
                                                                    </div>



                                                                    <div class="col-12 mb-2">
                                                                        <div class="form-check form-switch mb-2">
                                                                            <div class="checkbox mt-3">
                                                                                <input type="checkbox" id="pos-block-me-v" class="form-check-input" value="1" name="is_pos_block" <?= (isset($profiles['is_pos_block']) && $profiles['is_pos_block'] === '1') ? 'checked' : '' ?>>
                                                                                <label for="pos-block-me-v">POS Blokla?</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>


                                                                    <div class="col-12 mb-2">
                                                                        <div class="form-check form-switch mt-2">
                                                                            <div class="checkbox mt-3">
                                                                                <input type="checkbox" id="menu-block-me-v" class="form-check-input" value="1" name="is_menu_block" <?= (isset($profiles['is_menu_block']) && $profiles['is_menu_block'] === '1') ? 'checked' : '' ?>>
                                                                                <label for="menu-block-me-v">QR Menyu Blokla?</label>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                            <div class="tab-pane fade" id="profile-justified" role="tabpanel" aria-labelledby="profile-tab">
                                                                <div class="col-12">
                                                                    <div class="card">
                                                                        <div class="card-body">
                                                                            <h5 class="card-title mb-3">Vergi</h5>
                                                                            <div class="row mb-3">
                                                                                <label for="inputText" class="col-sm-2 col-form-label"></label>
                                                                                <div class="col-sm-10">
                                                                                    <div class="form-check form-switch">
                                                                                        <input class="form-check-input" type="checkbox" id="is_tax" value="1" name="is_tax" <?= (isset($profiles['is_tax']) && $profiles['is_tax'] === '1') ? 'checked' : '' ?>>
                                                                                        <label class="form-check-label" for="is_tax">Vergi</label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row mb-3">
                                                                                <label for="tax_percent" class="col-sm-2 col-form-label">Vergi faizi</label>
                                                                                <div class="col-sm-10">
                                                                                    <div class="input-group mb-3">
                                                                                        <span class="input-group-text">%</span>
                                                                                        <input type="text" class="form-control" id="tax_percent" name="tax_percent" value="<?= $profiles['tax_percent'] ?? '' ?>">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row mb-3">
                                                                                <label for="tax_merchant" class="col-sm-2 col-form-label">Vergi Merchant id</label>
                                                                                <div class="col-sm-10">
                                                                                    <div class="input-group mb-3">
                                                                                        <span class="input-group-text">ID</span>
                                                                                        <input type="text" class="form-control" id="tax_merchant" name="tax_merchant" value="<?= $profiles['tax_merchant'] ?? '' ?>">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row mb-3">
                                                                                <label for="type" class="col-sm-2 col-form-label">Vergi Növü</label>
                                                                                <div class="col-sm-10">
                                                                                    <input type="text" class="form-control" name="tax_type" value="<?= $profiles['tax_type'] ?? '' ?>">
                                                                                </div>
                                                                            </div>
                                                                            <div class="row mb-3">
                                                                                <label for="ip_addr" class="col-sm-2 col-form-label">Kassa Ip</label>
                                                                                <div class="col-sm-10">
                                                                                    <input type="text" class="form-control" name="ip_addr" value="<?= $profiles['ip_addr'] ?? '' ?>">
                                                                                </div>
                                                                            </div>
                                                                            <div class="row mb-3">
                                                                                <label for="port_addr" class="col-sm-2 col-form-label">Kassa Port</label>
                                                                                <div class="col-sm-10">
                                                                                    <input type="text" class="form-control" name="port_addr" value="<?= $profiles['port_addr'] ?? '' ?>">
                                                                                </div>
                                                                            </div>
                                                                            <div class="row mb-3">
                                                                                <label class="col-sm-2 col-form-label">Vergi integrasiyası</label>
                                                                                <div class="col-sm-10">
                                                                                    <select class="form-select" name="tax_cash_type">
                                                                                        <option selected="">-- Birini Seçin --</option>
                                                                                        <option value="azsmart" <?= $profiles['tax_cash_type'] == 'azsmart' ? "SELECTED" : '' ?>>Azsmart</option>
                                                                                        <option value="omnitech" <?= $profiles['tax_cash_type'] == 'omnitech' ? "SELECTED" : '' ?>>Omnitech</option>
                                                                                        <option value="sunmi" <?= $profiles['tax_cash_type'] == 'sunmi' ? "SELECTED" : '' ?>>Sunmi</option>
                                                                                        <option value="nba" <?= $profiles['tax_cash_type'] == 'nba' ? "SELECTED" : '' ?>>Nba</option>
                                                                                        <option value="vizar" <?= $profiles['tax_cash_type'] == 'vizar' ? "SELECTED" : '' ?>>Vizar</option>
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class=" col-12 d-flex justify-content-center mt-5">
                                                                <button type="submit" name="<?= isset($_GET['e']) ? 'update' : 'send' ?>" value="1" class="btn btn-primary btn-md me-2 mb-1">Göndər</button>
                                                                <button type="reset" class="btn btn-light-secondary me-1 mb-1">Sıfırla</button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>

                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </section>
                </div>

                <!-- FOOTER -->
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
                            <div class="col-md-5 col-12">
                            <label class="form-label">Terminal ID</label>
                                <input type="text" class="form-control" placeholder="Terminal ID" name="terminal_id[]" value="${terminalID}" readonly>
                            </div>

                    <!-- Terminal Kodu -->
                <div class="col-md-5 col-12">
                    <label class="form-label">Terminal Kodu</label>
                    <input type="text" class="form-control" placeholder="Terminal Kodu" name="terminal_code[]">
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