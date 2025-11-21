<?php

require_once 'config.php';
require_once './lib/lib.php';


$profiles = sql("SELECT * FROM profiles");


if ($_SESSION['username'] == '') {
  header("Location: login.php");
  exit;
}


if (isset($_GET['d']) && $_GET['d'] !== '') {

  $id = base64_decode($_GET['d']);

  sql("DELETE FROM profiles WHERE id = '$id'");

  header('Location: restaurantslist.php');
  exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>Restoranlar Siyahısı</title>
  <?php require_once './includes/head.php' ?>
  <!-- DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
</head>

<style>
  /* Modern Design System */
  :root {
    --primary-gradient: linear-gradient(135deg, #2578f6ff, #165ff0ff);
    --primary-gradient-hover: linear-gradient(135deg, #3228f0ff, #316bdeff);
    --success-gradient: linear-gradient(135deg, #10b981, #059669);
    --danger-gradient: linear-gradient(135deg, #ef4444, #dc2626);
    --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.05);
    --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.08);
    --shadow-lg: 0 10px 25px rgba(0, 0, 0, 0.1);
    --shadow-xl: 0 20px 40px rgba(0, 0, 0, 0.15);
    --border-radius: 1rem;
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  }

  /* Page Layout */
  .col-sm-12 {
    padding: 7px;
  }

  /* Page Title */
  .pagetitle {
    margin-bottom: 2rem;
    animation: fadeInDown 0.6s ease-out;
  }

  .pagetitle h1 {
    color: #1f2937;
    font-weight: 700;
    font-size: 2rem;
    letter-spacing: -0.5px;
    margin-bottom: 0.5rem;
  }

  .breadcrumb {
    background: transparent;
    padding: 0;
    margin: 0;
  }

  /* Card Styling */
  .card {
    background: linear-gradient(to bottom right, #ffffff, #f9fafb);
    border: 1px solid rgba(226, 232, 240, 0.8);
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-md);
    transition: var(--transition);
    overflow: hidden;
    position: relative;
  }

  .card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: var(--primary-gradient);
    transform: scaleX(0);
    transform-origin: left;
    transition: transform 0.5s ease;
  }

  .card:hover {
    box-shadow: var(--shadow-lg);
    transform: translateY(-2px);
  }

  .card:hover::before {
    transform: scaleX(1);
  }

  .card-body {
    padding: 1.5rem;
  }

  /* Buttons */
  .btn {
    border-radius: 2rem;
    padding: 0.625rem 1.5rem;
    font-weight: 600;
    transition: var(--transition);
    border: none;
    position: relative;
    overflow: hidden;
  }

  .btn::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.3);
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
  }

  .btn:hover::before {
    width: 300px;
    height: 300px;
  }

  .btn-primary {
    background: var(--primary-gradient);
    color: white;
    box-shadow: 0 4px 10px rgba(99, 102, 241, 0.2);
  }

  .btn-primary:hover {
    background: var(--primary-gradient-hover);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(99, 102, 241, 0.35);
    color: white;
  }

  .btn-primary:active {
    transform: translateY(0);
  }

  .btn-sm {
    padding: 0.375rem 0.875rem;
    font-size: 0.875rem;
  }

  .btn-danger {
    background: var(--danger-gradient);
    box-shadow: 0 4px 10px rgba(239, 68, 68, 0.2);
  }

  .btn-danger:hover {
    background: linear-gradient(135deg, #dc2626, #ef4444);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(239, 68, 68, 0.35);
  }

  /* Badges */
  .badge {
    padding: 0.5rem 1rem;
    border-radius: 1.5rem;
    font-weight: 600;
    font-size: 0.75rem;
    letter-spacing: 0.25px;
    transition: var(--transition);
    border: none;
  }

  .badge.bg-primary {
    background: var(--primary-gradient) !important;
    box-shadow: 0 2px 8px rgba(99, 102, 241, 0.25);
  }

  .badge.bg-primary:hover {
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
    transform: translateY(-1px);
  }

  .badge.bg-success {
    background: var(--success-gradient) !important;
    box-shadow: 0 2px 8px rgba(16, 185, 129, 0.25);
  }

  .badge.bg-danger {
    background: var(--danger-gradient) !important;
    box-shadow: 0 2px 8px rgba(239, 68, 68, 0.25);
  }

  .badge a {
    text-decoration: none;
    color: white;
    transition: opacity 0.2s;
  }

  .badge a:hover {
    opacity: 0.9;
  }

  /* DataTable Enhancements */
  .table {
    border-collapse: separate;
    border-spacing: 0;
    border-radius: var(--border-radius);
    overflow: hidden;
    background: white;
  }

  .table thead th {
    background: linear-gradient(135deg, #4463ebff 0%, #2361f0ff 100%);
    color: white;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.75rem;
    padding: 1rem 0.75rem;
    border: none;
    position: relative;
  }

  .table thead th::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 2px;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.5), transparent);
  }

  .table tbody tr {
    transition: var(--transition);
    border-bottom: 1px solid #f1f5f9;
  }

  .table tbody tr:hover {
    background: linear-gradient(to right, #f8fafc, #f1f5f9);
    transform: scale(1.01);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
  }

  .table tbody td {
    padding: 1rem 0.75rem;
    vertical-align: middle;
    color: #475569;
    font-size: 0.875rem;
    border: none;
  }

  .table tbody tr:last-child {
    border-bottom: none;
  }

  /* DataTables Custom Controls */
  .dataTables_wrapper {
    padding: 1rem;
  }

  .dataTables_wrapper .dataTables_length,
  .dataTables_wrapper .dataTables_filter {
    margin-bottom: 1rem;
  }

  .dataTables_wrapper .dataTables_length select,
  .dataTables_wrapper .dataTables_filter input {
    border: 2px solid #e2e8f0;
    border-radius: 0.75rem;
    padding: 0.5rem 1rem;
    transition: var(--transition);
    background: white;
  }

  .dataTables_wrapper .dataTables_length select:focus,
  .dataTables_wrapper .dataTables_filter input:focus {
    border-color: #6366f1;
    outline: none;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
  }

  .dataTables_wrapper .dataTables_paginate .paginate_button {
    border-radius: 0.5rem;
    margin: 0 0.125rem;
    transition: var(--transition);
  }

  .dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: var(--primary-gradient) !important;
    border: none !important;
    color: white !important;
  }

  .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background: var(--primary-gradient) !important;
    border: none !important;
    color: white !important;
  }

  /* Animations */
  @keyframes fadeInDown {
    from {
      opacity: 0;
      transform: translateY(-20px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  @keyframes fadeInUp {
    from {
      opacity: 0;
      transform: translateY(20px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .dashboard {
    animation: fadeInUp 0.6s ease-out;
  }

  /* Responsive */
  @media (max-width: 768px) {
    .pagetitle h1 {
      font-size: 1.5rem;
    }

    .btn {
      padding: 0.5rem 1rem;
      font-size: 0.875rem;
    }
  }

  /* Custom scrollbar for table */
  .dataTables_wrapper {
    scrollbar-width: thin;
    scrollbar-color: #cbd5e1 #f1f5f9;
  }

  .dataTables_wrapper::-webkit-scrollbar {
    height: 8px;
    width: 8px;
  }

  .dataTables_wrapper::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 10px;
  }

  .dataTables_wrapper::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #6366f1, #4f46e5);
    border-radius: 10px;
  }

  .dataTables_wrapper::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #4f46e5, #6366f1);
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

    <div class="pagetitle d-flex justify-content-between align-items-center">
      <div>
        <h1>Restoranlar Siyahısı</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Restoranlar Siyahısı</a></li>
            <li class="breadcrumb-item active">Restoranlar</li>
          </ol>
        </nav>
      </div>

      <!-- Əlavə et düyməsi -->
      <a href="restaurants.php" class="btn btn-primary btn-md rounded-pill">
        <i class="bi bi-plus-circle"></i> Əlavə et
      </a>
    </div>
    <!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">

        <div class="col-12">
          <div class="card p-2">
            <div class="card-body recent-sales">
              <table class="table table-striped" id="restaurantsTable">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Logo</th>
                    <th>İstifadəçi Adı</th>
                    <th>Bizimlə Çalışır</th>
                    <th>Yaranma Tarixi</th>
                    <th>Restoran Adı</th>
                    <th>Bitmə Vaxtı</th>
                    <th>Sahibkar Nömrəsi</th>
                    <th>Satış Tipi</th>
                    <th>Ödəniş</th>
                    <th>Pos</th>
                    <th>Redaktə</th>
                    <th>Sil</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($profiles as $profile) : ?>
                    <tr>
                      <td><?= $profile['id'] ?></td>
                      <?php if($profile['logo'] !== ''): ?>
                      <td>
                        <img src="../<?= $profile['logo'] ?>" alt="" width="70px" height="70px"> 
                      </td>
                      <?php else: ?>
                      <td></td>
                      <?php endif ?>
                      <td>
                        <s
                        n class="badge bg-primary">
                          <a href="restaurants.php?id=<?= $profile['id'] ?>" class="text-white text-decoration-none">
                            <?= htmlspecialchars($profile['username']) ?>
                          </a>
                        </s>
                      </td>
                      <td>
                        <span class="badge bg-<?= $profile['is_working'] ? 'success' : 'danger'  ?>">
                          <?= htmlspecialchars($profile['is_working'] ? 'Aktiv' : 'Deaktiv') ?>
                        </span>
                      </td>
                      <td><?= $profile['sale_date'] ?></td>
                      <td><?= htmlspecialchars($profile['restaurant_name']) ?></td>
                      <td><?= htmlspecialchars($profile['next_payment_date']) ?></td>
                      <td><?= htmlspecialchars($profile['boss_phone_number']) ?></td>
                      <td><?= $profile['sale_type'] == 1 ? 'İllik' : 'Aylıq' ?></td>
                      <td><?= htmlspecialchars($profile['payment']) ?></td>
                      <td>
                        <span class="badge bg-<?= $profile['is_pos'] ? 'success' : 'danger'  ?>">
                          <?= htmlspecialchars($profile['is_pos'] ? 'Aktiv' : 'Deaktiv') ?>
                        </span>
                      </td>
                      <td>
                        <a href="restaurants.php?e=<?= base64_encode($profile['id']) ?>" class="btn btn-primary btn-sm">
                          <i class="bi bi-pencil"></i>
                        </a>
                      </td>
                      <td>
                        <a href="restaurantslist.php?d=<?= base64_encode($profile['id']) ?>" class="btn btn-danger btn-sm">
                          <i class="bi bi-trash"></i>
                        </a>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>


      </div>
    </section>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <?php require_once './includes/footer.php' ?>
  <!-- ======= Footer ======= -->

  <!-- jQuery (DataTables üçün mütləq lazımdır) -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>


  <!-- DataTables JS -->
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>


  <script>
    $(document).ready(function() {
      $('#restaurantsTable').DataTable({
        language: {
          url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/az.json'
        },
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],
        responsive: true,
        autoWidth: false,
        scrollX: true,
        createdRow: function(row, data, dataIndex) {
          $(row).addClass('pt-3 pb-3');
        }
      });
    });
  </script>
</body>

</html>