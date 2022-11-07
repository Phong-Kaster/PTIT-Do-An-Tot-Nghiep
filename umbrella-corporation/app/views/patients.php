<!DOCTYPE html>
<!--
* CoreUI - Free Bootstrap Admin Template
* @version v4.2.1
* @link https://coreui.io
* Copyright (c) 2022 creativeLabs Łukasz Holeczek
* Licensed under MIT (https://coreui.io/license)
-->
<!-- Breadcrumb-->
<html lang="en">
  <head>
  <base href="./">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="CoreUI - Open Source Bootstrap Admin Template">
    <meta name="author" content="Łukasz Holeczek">
    <meta name="keyword" content="Bootstrap,Admin,Template,Open,Source,jQuery,CSS,HTML,RWD,Dashboard">
    <title> <?= WEBSITE_NAME ?></title>
  
    <link rel="apple-touch-icon" sizes="57x57" href="<?= APPURL."/assets/favicon/apple-icon-57x57.png?v=".VERSION ?>">
    <link rel="apple-touch-icon" sizes="60x60" href="<?= APPURL."/assets/favicon/apple-icon-60x60.png?v=".VERSION ?>">
    <link rel="apple-touch-icon" sizes="72x72" href="<?= APPURL."/assets/favicon/apple-icon-72x72.png?v=".VERSION ?>">
    <link rel="apple-touch-icon" sizes="76x76" href="<?= APPURL."/assets/favicon/apple-icon-76x76.png?v=".VERSION ?>">
    <link rel="apple-touch-icon" sizes="114x114" href="<?= APPURL."/assets/favicon/apple-icon-114x114.png?v=".VERSION ?>">
    <link rel="apple-touch-icon" sizes="120x120" href="<?= APPURL."/assets/favicon/apple-icon-120x120.png?v=".VERSION ?>">
    <link rel="apple-touch-icon" sizes="144x144" href="<?= APPURL."/assets/favicon/apple-icon-144x144.png?v=".VERSION ?>">
    <link rel="apple-touch-icon" sizes="152x152" href="<?= APPURL."/assets/favicon/apple-icon-152x152.png?v=".VERSION ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= APPURL."/assets/favicon/apple-icon-180x180.png?v=".VERSION ?>">
    
    <link rel="icon" type="image/png" sizes="192x192" href="<?= APPURL."/assets/img/umbrella-192x192.png?v=".VERSION ?>">
    <link rel="icon" type="image/png" sizes="192x192" href="<?= APPURL."/assets/img/umbrella-192x192.png?v=".VERSION ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= APPURL."/assets/img/umbrella-192x192.png?v=".VERSION ?>">
    <link rel="icon" type="image/png" sizes="96x96" href="<?= APPURL."/assets/img/umbrella-192x192.png?v=".VERSION ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= APPURL."/assets/img/umbrella-192x192.png?v=".VERSION ?>">
    
    <!-- <link rel="manifest" href="<?= APPURL."/assets/favicon/manifest.json?v=".VERSION ?>"> -->
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="<?= APPURL."/assets/favicon/ms-icon-144x144.png?v=".VERSION ?>" >
    <meta name="theme-color" content="#ffffff">
    <!-- Vendors styles-->
    <link rel="stylesheet" href="<?= APPURL."/assets/vendors/simplebar/css/simplebar.css?v=".VERSION ?>">
    <link rel="stylesheet" href="<?= APPURL."/assets/css/vendors/simplebar.css?v=".VERSION ?>">
    <!-- Main styles for this application-->
    <link href="<?= APPURL."/assets/css/style.css?v=".VERSION ?>" rel="stylesheet">
    <!-- We use those styles to show code examples, you should remove them in your application.-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/prismjs@1.23.0/themes/prism.css">
    <link href="<?= APPURL."/assets/css/examples.css?v=".VERSION ?>" rel="stylesheet">

    <!-- CoreUI icons -->
    <link rel="stylesheet" href="https://unpkg.com/@coreui/icons/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/@coreui/icons/css/free.min.css">
    <link rel="stylesheet" href="https://unpkg.com/@coreui/icons/css/brand.min.css">
    <link rel="stylesheet" href="https://unpkg.com/@coreui/icons/css/flag.min.css">

    <!-- chart js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>

    <!-- Global site tag (gtag.js) - Google Analytics-->
    <script async="" src="https://www.googletagmanager.com/gtag/js?id=UA-118965717-3"></script>
    <script>
      window.dataLayer = window.dataLayer || [];

      function gtag() {
        dataLayer.push(arguments);
      }
      gtag('js', new Date());
      // Shared ID
      gtag('config', 'UA-118965717-3');
      // Bootstrap ID
      gtag('config', 'UA-118965717-5');
    </script>
    <link href="<?= APPURL."/assets/vendors/@coreui/chartjs/css/coreui-chartjs.css?v=".VERSION ?>" rel="stylesheet">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <style>
      div.form
      {
          display: block;
          text-align: center;
      }
      form
      {
          display: inline-block;
          margin-left: auto;
          margin-right: auto;
          text-align: left;
      }
    </style>
  </head>
  <body>
    
    <!-- LEFT NAVIGATION -->
    <?php 
          $Nav = new stdClass;
          $Nav->activeMenu = "patient";
          require_once(APPPATH.'/views/fragments/navleft.fragment.php');
    ?>
    <!-- end LEFT NAVIGATION -->

    <!-- CONTENT -->
    <div class="wrapper d-flex flex-column min-vh-100 bg-light">
      <!-- TOP NAVIGATION -->
      <?php require_once(APPPATH.'/views/fragments/navtop.fragment.php'); ?>
      <!-- end NAVIGATION -->
      
      <!-- CONTENT -->
      <?php require_once(APPPATH.'/views/fragments/patients.fragment.php'); ?>
      <!-- end CONTENT -->

      <!-- FOOTER -->
      <?php require_once(APPPATH.'/views/fragments/footer.fragment.php'); ?>
      <!-- end FOOTER -->
    </div>
    <!-- end CONTENT -->


    <!-- GENERAL JS -->
    <?php require_once(APPPATH.'/views/fragments/javascript.fragment.php'); ?>
    <!-- PRIVATE JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script><!-- date picker -->
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script><!-- date picker -->
    <script src="<?= APPURL."/assets/js/customized/patients.js?v=".VERSION ?>"></script>
    <script>
        let order = { column:"id", dir:"asc"}
        let params = {
            order: order,
            length: DEFAULT_LENGTH
        }
        setupPatientTable(params);
        setupButton();
    </script>
  </body>
</html>