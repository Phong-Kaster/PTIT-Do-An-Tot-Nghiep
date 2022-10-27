<!DOCTYPE html>
<html lang="<?= ACTIVE_LANG ?>">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
        <meta name="theme-color" content="#fff">

        <meta name="description" content="<?= site_settings("site_description") ?>">
        <meta name="keywords" content="<?= site_settings("site_keywords") ?>">

        <link rel="icon" href="<?= site_settings("logomark") ? site_settings("logomark") : APPURL."/assets/img/logomark.png" ?>" type="image/x-icon">
        <link rel="shortcut icon" href="<?= site_settings("logomark") ? site_settings("logomark") : APPURL."/assets/img/logomark.png" ?>" type="image/x-icon">

        <link rel="stylesheet" type="text/css" href="<?= APPURL."/assets/css/plugins.css?v=".VERSION ?>">
        <link rel="stylesheet" type="text/css" href="<?= APPURL."/assets/css/filemanager.css?v=".VERSION ?>">
        <link rel="stylesheet" type="text/css" href="<?= APPURL."/assets/css/core.css?v=".VERSION ?>">

        <title><?= $Post->isAvailable() ? __("Edit Post") : __("New Post") ?></title>
    </head>

    <body>
        <?php 
            $Nav = new stdClass;
            $Nav->activeMenu = "post";
            require_once(APPPATH.'/views/fragments/navigation.fragment.php');
        ?>

        <?php 
            $TopBar = new stdClass;
            $TopBar->title = __("Dashboard");
            require_once(APPPATH.'/views/fragments/topbar.fragment.php'); 
        ?>
        
        <?php require_once(APPPATH.'/views/fragments/post.fragment.php'); ?>
        
        <?php if ($Integrations->get("data.dropbox.api_key") && $AuthUser->get("settings.file_pickers.dropbox")): ?>
            <script id="dropboxjs" 
                    data-app-key="<?= htmlchars($Integrations->get("data.dropbox.api_key")) ?>"
                    type="text/javascript" 
                    src="https://www.dropbox.com/static/api/2/dropins.js"></script>
        <?php endif; ?>

        <?php if (SSL_ENABLED && $Integrations->get("data.onedrive.client_id") && $AuthUser->get("settings.file_pickers.onedrive")): ?>
            <script type="text/javascript" src="https://js.live.net/v7.0/OneDrive.js"></script>
        <?php endif; ?>
    
        <script type="text/javascript" src="<?= APPURL."/assets/js/plugins.js?v=".VERSION ?>"></script>
        <script type="text/javascript" src="<?= APPURL."/assets/js/filemanager.js?v=".VERSION ?>"></script>
        <?php require_once(APPPATH.'/inc/js-locale.inc.php'); ?>
        <script type="text/javascript" src="<?= APPURL."/assets/js/core.js?v=".VERSION ?>"></script>
        <script type="text/javascript" src="<?= APPURL."/assets/js/post.js?v=".VERSION ?>"></script>
        <script type="text/javascript" charset="utf-8">
            $(function(){
                NextPost.Post();
            });
        </script>
        <?php if ($Integrations->get("data.google.api_key") && $Integrations->get("data.google.client_id") && $AuthUser->get("settings.file_pickers.google_drive")): ?>
            <script src="https://www.google.com/jsapi?key=<?= htmlchars($Integrations->get("data.google.api_key")) ?>"></script>
            <script src="https://apis.google.com/js/client.js?onload=GoogleDrivePickerInitializer"></script>
        <?php endif; ?>

        <?php require_once(APPPATH.'/views/fragments/google-analytics.fragment.php'); ?>
    </body>
</html>