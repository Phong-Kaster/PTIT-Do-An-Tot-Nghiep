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
  </head>
  <body>
    
    <!-- LEFT NAVIGATION -->
    <?php 
          $Nav = new stdClass;
          $Nav->activeMenu = "dashboard";
          require_once(APPPATH.'/views/fragments/navleft.fragment.php');
    ?>
    <!-- end LEFT NAVIGATION -->

    <!-- CONTENT -->
    <div class="wrapper d-flex flex-column min-vh-100 bg-light">
      <!-- TOP NAVIGATION -->
      <?php require_once(APPPATH.'/views/fragments/navtop.fragment.php'); ?>
      <!-- end NAVIGATION -->
      
      <!-- CONTENT -->
      <?php require_once(APPPATH.'/views/fragments/dashboard.fragment.php'); ?>
      <!-- end CONTENT -->

      <!-- FOOTER -->
      <?php require_once(APPPATH.'/views/fragments/footer.fragment.php'); ?>
      <!-- end FOOTER -->
    </div>
    <!-- end CONTENT -->


    <!-- GENERAL JS -->
    <?php require_once(APPPATH.'/views/fragments/javascript.fragment.php'); ?>
    <!-- PRIVATE JS -->
    <script src="<?= APPURL."/assets/vendors/chart.js/js/chart.min.js?v=".VERSION ?>"></script>
    <script src="<?= APPURL."/assets/vendors/@coreui/chartjs/js/coreui-chartjs.js?v=".VERSION ?>"></script>
    <script src="<?= APPURL."/assets/vendors/@coreui/utils/js/coreui-utils.js?v=".VERSION ?>"></script>
    <script src="<?= APPURL."/assets/js/customized/dashboard.js?v=".VERSION ?>"></script>
    <script>
        // get number of all appointment include NORMAL and BOOKING 
        createChartWithAJAX("GET", "<?= API_URL ?>/charts", "appointmentsinlast7days");
        

        //get a comparable chart between BOOKING and ALL APPOINTMENTS
        createChartWithAJAX("GET", "<?= API_URL ?>/charts", "appointmentandbookinginlast7days");


        //get quantity of doctor
        let doctorParams = {
           search: 1
        }
        getQuantityWithAJAX("doctor-quantity", "<?= API_URL ?>/doctors", doctorParams);


        //get the number of appointments today
        let date = getCurrentDate();
        let appointmentParams = {
            date: date
        }
        getQuantityWithAJAX("current-appointment-quantity", "<?= API_URL ?>/appointments", appointmentParams);


        //get the number of booking appointment today
        let bookingParams = {
          appointment_date: date
        }
        getQuantityWithAJAX("current-booking-quantity", "<?= API_URL ?>/bookings", bookingParams);

        
        //get the number of booking appointment today
        let cancelledAppointmentParams = {
            status: "cancelled",
            date: date
        }
        getQuantityWithAJAX("current-cancelled-appointment", "<?= API_URL ?>/appointments", cancelledAppointmentParams);


        //get doctor info table
        getDoctorInfoWithAJAX("<?= API_URL ?>/doctors", doctorParams);
    </script>
  </body>
</html>