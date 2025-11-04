<?php

// Conncect DB
require_once 'config.php';

// Lib
require_once './lib/lib.php';



if (isset($_POST['login'])) {
  $name = $_POST['name'];
  $password = $_POST['password'];


  $request = sql("SELECT * FROM super_admin WHERE username = '$name'")[0];


  if (password_verify($password, $request['password'])) {
    $_SESSION['username'] = $request['username'];
    header("Location: index.php");
    exit;
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

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

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