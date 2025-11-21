<?php

require_once 'config.php';
require_once './lib/lib.php';

// Authentication check
if ($_SESSION['username'] == '') {
    header("Location: login.php");
    exit;
}

// Fetch all restaurants
$profiles = sql("SELECT * FROM profiles ORDER BY id DESC");

// Calculate statistics
$total_restaurants = count($profiles);
$active_restaurants = count(array_filter($profiles, function($p) { return $p['is_working'] == 1; }));
$inactive_restaurants = $total_restaurants - $active_restaurants;
$pos_enabled = count(array_filter($profiles, function($p) { return $p['is_pos'] == 1; }));

// Calculate monthly and yearly subscriptions
$monthly_subs = count(array_filter($profiles, function($p) { return $p['sale_type'] == 0; }));
$yearly_subs = count(array_filter($profiles, function($p) { return $p['sale_type'] == 1; }));

?>

<!DOCTYPE html>
<html lang="az">

<head>
    <title>Dashboard - UniPOS</title>
    <?php require_once './includes/head.php' ?>
</head>

<style>
    /* Dashboard Specific Styles */
    .stat-card {
        border-radius: 1rem;
        padding: 1.5rem;
        background: white;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--card-color, #2578f6);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.4s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
    }

    .stat-card:hover::before {
        transform: scaleX(1);
    }

    .stat-icon {
        width: 64px;
        height: 64px;
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }

    .stat-card:hover .stat-icon {
        transform: scale(1.1) rotate(5deg);
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 800;
        line-height: 1;
        margin-bottom: 0.5rem;
        background: linear-gradient(135deg, #2578f6, #165ff0);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .stat-label {
        color: #64748b;
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-trend {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 2rem;
        font-size: 0.75rem;
        font-weight: 600;
        margin-top: 0.5rem;
    }

    .stat-trend.up {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .stat-trend.down {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }

    .restaurant-card {
        border-radius: 1rem;
        padding: 1.25rem;
        background: white;
        border: 2px solid #e2e8f0;
        margin-bottom: 1rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
    }

    .restaurant-card:hover {
        border-color: #2578f6;
        box-shadow: 0 8px 20px rgba(37, 120, 246, 0.15);
        transform: translateX(5px);
    }

    .restaurant-name {
        font-size: 1.125rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }

    .restaurant-info {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        font-size: 0.875rem;
        color: #64748b;
    }

    .restaurant-info-item {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .restaurant-actions {
        margin-top: 1rem;
        display: flex;
        gap: 0.5rem;
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 1.5rem;
        position: relative;
        padding-left: 1rem;
    }

    .section-title::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: #2578f6;
        border-radius: 2px;
    }

    .quick-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .welcome-banner {
        background: linear-gradient(135deg, #2578f6 0%, #165ff0 100%);
        border-radius: 1rem;
        padding: 2rem;
        color: white;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }

    .welcome-banner::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .welcome-banner h2 {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
        position: relative;
        z-index: 1;
    }

    .welcome-banner p {
        font-size: 1rem;
        opacity: 0.9;
        position: relative;
        z-index: 1;
    }
</style>

<body>

    <!-- Header -->
    <?php require_once './includes/header.php' ?>

    <!-- Sidebar -->
    <?php require_once './includes/sidebar.php' ?>

    <main id="main" class="main">

        <!-- Welcome Banner -->
        <div class="welcome-banner">
            <h2>👋 Xoş gəldiniz, <?= currentUser('username') ?>!</h2>
            <p>UniPOS Admin Panel - Restoranlarınızı idarə edin</p>
        </div>

        <!-- Page Title -->
        <div class="pagetitle">
            <h1><i class="bi bi-speedometer2 me-2"></i>Dashboard</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="dashboard.php">Ana səhifə</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </nav>
        </div>

        <!-- Statistics Cards -->
        <div class="quick-stats">
            <div class="stat-card" style="--card-color: #2578f6;">
                <div class="stat-icon" style="background: rgba(37, 120, 246, 0.1); color: #2578f6;">
                    <i class="bi bi-shop"></i>
                </div>
                <div class="stat-number"><?= $total_restaurants ?></div>
                <div class="stat-label">Ümumi Restoranlar</div>
            </div>

            <div class="stat-card" style="--card-color: #10b981;">
                <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div class="stat-number"><?= $active_restaurants ?></div>
                <div class="stat-label">Aktiv Restoranlar</div>
                <span class="stat-trend up">
                    <i class="bi bi-arrow-up"></i> Aktiv
                </span>
            </div>

            <div class="stat-card" style="--card-color: #ef4444;">
                <div class="stat-icon" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;">
                    <i class="bi bi-x-circle"></i>
                </div>
                <div class="stat-number"><?= $inactive_restaurants ?></div>
                <div class="stat-label">Deaktiv Restoranlar</div>
            </div>

            <div class="stat-card" style="--card-color: #8b5cf6;">
                <div class="stat-icon" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6;">
                    <i class="bi bi-credit-card"></i>
                </div>
                <div class="stat-number"><?= $pos_enabled ?></div>
                <div class="stat-label">POS Aktivləşdirilib</div>
            </div>
        </div>

        <!-- Recent Restaurants -->
        <section class="section">
            <div class="row">
                <div class="col-12">
                    <h3 class="section-title">Son Əlavə Edilən Restoranlar</h3>
                    
                    <?php 
                    $recent_restaurants = array_slice($profiles, 0, 10);
                    foreach ($recent_restaurants as $restaurant): 
                    ?>
                    <div class="restaurant-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <div class="restaurant-name">
                                    <i class="bi bi-shop-window me-2" style="color: #2578f6;"></i>
                                    <?= htmlspecialchars($restaurant['restaurant_name']) ?>
                                </div>
                                <div class="restaurant-info">
                                    <div class="restaurant-info-item">
                                        <i class="bi bi-person"></i>
                                        <span><?= htmlspecialchars($restaurant['username']) ?></span>
                                    </div>
                                    <div class="restaurant-info-item">
                                        <i class="bi bi-telephone"></i>
                                        <span><?= htmlspecialchars($restaurant['boss_phone_number']) ?></span>
                                    </div>
                                    <div class="restaurant-info-item">
                                        <i class="bi bi-calendar"></i>
                                        <span><?= htmlspecialchars($restaurant['sale_date']) ?></span>
                                    </div>
                                    <div class="restaurant-info-item">
                                        <i class="bi bi-currency-dollar"></i>
                                        <span><?= htmlspecialchars($restaurant['payment']) ?> AZN</span>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <span class="badge bg-<?= $restaurant['is_working'] ? 'success' : 'danger' ?>">
                                    <?= $restaurant['is_working'] ? 'Aktiv' : 'Deaktiv' ?>
                                </span>
                                <?php if ($restaurant['is_pos']): ?>
                                <span class="badge" style="background: #8b5cf6; margin-left: 0.5rem;">
                                    <i class="bi bi-credit-card me-1"></i>POS
                                </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="restaurant-actions">
                            <a href="restaurants.php?e=<?= base64_encode($restaurant['id']) ?>" class="btn btn-sm btn-primary">
                                <i class="bi bi-pencil me-1"></i>Redaktə
                            </a>
                            <a href="restaurantslist.php" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye me-1"></i>Ətraflı
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>

                    <div class="text-center mt-4">
                        <a href="restaurantslist.php" class="btn btn-primary">
                            <i class="bi bi-list me-2"></i>Bütün Restoranları Gör
                        </a>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <!-- Footer -->
    <?php require_once './includes/footer.php' ?>

</body>

</html>
