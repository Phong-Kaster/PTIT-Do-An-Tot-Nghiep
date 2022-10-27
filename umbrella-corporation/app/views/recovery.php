<!DOCTYPE html>
<!--
* CoreUI - Free Bootstrap Admin Template
* @version v4.2.1
* @link https://coreui.io
* Copyright (c) 2022 creativeLabs Łukasz Holeczek
* Licensed under MIT (https://coreui.io/license)
-->
<html lang="en">
  <head>
    <base href="./">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content=" <?= WEBSITE_NAME ?> ">
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
    <link href="<?= APPURL."/assets/css/examples.css?v=".VERSION ?> rel="stylesheet">
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
  </head>


    <!-- CONTENT -->
    <?php require_once(APPPATH.'/views/fragments/recovery.fragment.php'); ?>
    <!-- end CONTENT -->

    <!-- JAVA SCRIPT -->
    <?php require_once(APPPATH.'/views/fragments/javascript.fragment.php'); ?>
    <!-- PRIVATE JS -->
    <script>
        /**
         * @author Phong-Kaster
         * @since 27-10-2022
         * @params email is the email address of user
         * @params id is the id of user in DOCTOR table
         * redirect to password reset page
         */
        function redirectToPasswordReset(email, id)
        {
            let data = {
                email: email,
                id: id
            };
        }

        /**
         * @author Phong-Kaster
         * @since 27-10-2022
         * jQuery $(document). ready() is a basic part of using jQuery. The jQuery document ready function
         * executes when the DOM (Document Object Model) is completely loaded in the browser
         */
        $(document).ready(function() {
            /**This is global variable - it represents the ID of user who are try recovery password
             * This ID is used at "2. BUTTON RESET PASSWORD" to make AJAX call accurately.
             */
            let id = "";

            /**1 - BUTTON CONFIRM SEND EMAIL RECOVERY */
            $("#submitButton").click(function(){
                /**1 - Step 1 - declare */
                let email = $("#email").val();
                
                let data = {
                    email: email
                };


                /**1 - Step 2 - show loading */
                Swal.fire({
                title: 'Hệ thống đang xử lý yêu cầu của bạn',
                html: 'Xin vui lòng đợi trong giây lát...',
                allowEscapeKey: false,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading()
                }
                });


                /**1 - Step 3 - make AJAX request */
                let title = 'success';
                let msg = "";
                $.ajax({
                    type: "POST",
                    url: "<?= API_URL ?>/recovery",
                    data: data,
                    dataType: "JSON",
                    success: function(resp) {
                        Swal.close();// close loading screen
                        msg = resp.msg;
                        if(resp.result == 1)// result = 1
                        {
                            id = resp.id;
                            Swal
                            .fire({
                                title: 'Success',
                                text: "Mã xác thực đã được gửi tới, hãy kiểm tra Gmail để lấy mã xác thực. Nếu không tìm thấy mã xác thực, hãy gửi yêu cầu lại",
                                icon: 'success',
                                confirmButtonText: 'Xác nhận',
                                confirmButtonColor: '#FF0000',
                                reverseButtons: false
                            })
                            .then((result) => 
                                {
                                    if (result.isConfirmed) 
                                    {
                                        Swal.close();
                                    } 
                                });
                        }
                        else// result = 0
                        {
                            title = 'error';
                            Swal.fire({
                            position: 'center',
                            icon: 'warning',
                            title: 'Warning',
                            text: msg,
                            showConfirmButton: false,
                            timer: 1500
                            });
                        }
                    },
                    error: function(err) {
                        Swal.fire('Oops...', "Oops! An error occured. Please try again later!", 'error');
                    }
                });
            });
            /**end 1 - BUTTON CONFIRM SEND EMAIL RECOVERY */


            /**2 - BUTTON RESET PASSWORD */
            $("#resetButton").click(function(){
                /**2 - Step 1 - declare */
                let recoveryToken = $("#recoveryToken").val();
                let password = $("#password").val();
                let passwordConfirm = $("#passwordConfirm").val();


                let data = {
                    recovery_token: recoveryToken,
                    password: password,
                    passwordConfirm: passwordConfirm
                };

                /**2 - Step 2 - show loading screen */
                Swal.fire({
                    title: 'Hệ thống đang xử lý yêu cầu của bạn',
                    html: 'Xin vui lòng đợi trong giây lát...',
                    allowEscapeKey: false,
                    allowOutsideClick: false,
                    didOpen: () => {
                    Swal.showLoading()
                    }
                });

                $.ajax({
                    type: "POST",
                    url: "<?= API_URL ?>/password-reset/"+id,
                    data: data,
                    dataType: "JSON",
                    success: function(resp) {
                    Swal.close();// close loading screen
                    msg = resp.msg;
                    if(resp.result == 1)
                    {
                        Swal.fire({
                            title: 'Success',
                            text: "Cập nhật mật khẩu thành công. Ấn xác nhận để quay về trang đăng nhập !",
                            icon: 'Success',
                            showCancelButton: true,
                            confirmButtonText: 'Xác nhận',
                            cancelButtonText: 'Đóng',
                            confirmButtonColor: '#FF0000',
                            cancelButtonColor: '#0000FF',
                            reverseButtons: false
                            }).then((result) => 
                                {
                                if (result.isConfirmed) 
                                {
                                    window.location.replace('<?=APPURL?>/login');
                                } 
                                else
                                {
                                    Swal.close();
                                }
                                });
                    }
                    else
                    {
                        title = 'error';
                        Swal.fire({
                            position: 'center',
                            icon: title,
                            title: 'Warning',
                            text: msg,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                    },
                    error: function(err) {
                    Swal.fire('Oops...', "Oops! An error occured. Please try again later!", 'error');
                    }
                });
            });
            /**end 2.BUTTON CHANGE PASSWORD */
        });
    </script>
</html>