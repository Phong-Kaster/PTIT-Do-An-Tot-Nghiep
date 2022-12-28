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


    <!-- DATETIME PICKER -->
    <link rel="stylesheet" href="https://www.jqueryscript.net/demo/Clean-jQuery-Date-Time-Picker-Plugin-datetimepicker/jquery.datetimepicker.css"/>

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
    <!-- <link rel="stylesheet" href="/resources/demos/style.css"> -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /><!-- SELECT 2 -->
  </head>
  <body>
    
    <!-- LEFT NAVIGATION -->
    <?php 
          $Nav = new stdClass;
          $Nav->activeMenu = "appointment";
          require_once(APPPATH.'/views/fragments/navleft.fragment.php');
    ?>
    <!-- end LEFT NAVIGATION -->

    <!-- CONTENT -->
    <div class="wrapper d-flex flex-column min-vh-100 bg-light">
      <!-- TOP NAVIGATION -->
      <?php require_once(APPPATH.'/views/fragments/navtop.fragment.php'); ?>
      <!-- end NAVIGATION -->
      
      <!-- CONTENT -->
      <?php require_once(APPPATH.'/views/fragments/appointment.fragment.php'); ?>
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

    <!-- PRIVATE JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script><!-- DATEPICKER -->
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script><!-- DATEPICKER -->

    <script src="https://www.jqueryscript.net/demo/Clean-jQuery-Date-Time-Picker-Plugin-datetimepicker/jquery.datetimepicker.js"></script><!-- DATETIME PICKER -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js" integrity="sha512-AIOTidJAcHBH2G/oZv9viEGXRqDNmfdPVPYOYKGy3fti0xIplnlgMHUGfuNRzC6FkzIo0iIxgFnr9RikFxK+sw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script><!-- SELECT 2 -->
    <script src="<?= APPURL."/assets/js/customized/appointment.js?v=".VERSION ?>"></script>
    <script>
      /**Step 1 - prepare parameters */
      let paramsSpeciality = {length: 100};
      let paramsDoctor = {length: 100};
      let paramsService = {length: 100};

      /**Step 2 - setup necessary filter dropdown */
      setupDropdownService(paramsService);
      setupDropdownSpeciality(paramsSpeciality);
      setupDropdownDoctor(paramsDoctor);
      setupChooseSpeciality();

      setupDatePicker();
      setupDatePickerForPatientBirthday()
      setupTimePicker();




      /**Step 3 */
      let id = <?= $id  ?>;
      if( id > 0)
      {
        setupAppointmentInfo(id);
      }
      else
      {

          let patientId = "<?= $patientId  ?>";
          let patientName = "<?= $patientName  ?>";
          let patientPhone = "<?= $patientPhone  ?>";
          let patientBirthday = "<?= $patientBirthday  ?>";
          let patientReason = "<?= $patientReason  ?>";
          let appointmentTime = "<?= $appointmentTime  ?>";
          let appointmentDate = "<?= $appointmentDate  ?>";
          let bookingId = "<?= $bookingId  ?>";
          let serviceId = "<?= $serviceId  ?>";
          let doctorId = "<?= $doctorId ?>";
          // console.log("appointment.php");
          // console.log("booking id: " + bookingId);
          // console.log("service Id: " + serviceId);

          let params = {
            patientId: patientId,
            patientName: patientName,
            patientPhone: patientPhone,
            patientBirthday: patientBirthday,
            patientReason: patientReason,
            appointmentTime: appointmentTime,
            appointmentDate: appointmentDate,
            bookingId: bookingId,
            serviceId: serviceId,
            doctorId: doctorId
          }
          setupAppointmentInfoWithParameter(params);
      }
      setupButton(id);


      /**Step 4- When ADMIN or SUPPORTER enters patient_id => website automatically finds out patient information */
      $(document).on('keypress',function(e) {
          if(e.which == 13) 
          {
             let patient_id = $("#patient-id").val();
             if( patient_id )
             {
                setupPatientInformation(patient_id);
             }
          }
      });


      /**Step 5 - set up dropdown service & dropdown doctor with Select2 */
      // $("#service").select2();
      // $("#doctor").select2();
    </script>
  </body>
</html>