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

  header('Location: index.php');
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
  .col-sm-12 {
    padding: 7px;
  }
  
  /* Settings page style consistency */
  .card {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    border: none;
    border-radius: 1rem;
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
  
  .pagetitle h1 {
    color: #1f2937;
    font-weight: 600;
  }
  
  .table {
    border-radius: 0.5rem;
    overflow: hidden;
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
                      <td>
                        <span class="badge bg-primary">
                          <a href="restaurants.php?id=<?= $profile['id'] ?>" class="text-white text-decoration-none">
                            <?= htmlspecialchars($profile['username']) ?>
                          </a>
                        </span>
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
                        <a href="index.php?d=<?= base64_encode($profile['id']) ?>" class="btn btn-danger btn-sm">
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