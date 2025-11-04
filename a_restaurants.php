<?php

require_once 'config.php';
require_once './lib/lib.php';


$profiles = sql("SELECT * FROM profiles")




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
                                    <li class="breadcrumb-item"><a href="a_restaurants.php">Restoranlar</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Restoranlar Siyahısı</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <section class="section mt-2">

                    <div class="d-flex justify-content-end mb-3 rounded-2">
                        <a href="restaurants.php" class="btn btn-primary">Əlavə Et</a>
                    </div>


                 

                </section>
            </div>

            <!-- FOOTER -->
            <?php require_once './includes/footer.php' ?>

            <script>
                $(document).ready(function() {
                    $('#table1').DataTable({
                        language: {
                            url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/az.json'
                        },
                        pageLength: 10,
                        lengthMenu: [5, 10, 25, 50, 100],
                        ordering: true,
                        searching: true,
                        responsive: true,
                        autoWidth: false,
                        scrollX: false
                    });
                });
            </script>

</body>

</html>