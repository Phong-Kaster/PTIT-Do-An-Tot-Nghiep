<?php 
require_once APPPATH.'/config/common.config.php'; // Common configuration
require_once APPPATH.'/config/db.config.php'; // Database configuration
require_once APPPATH.'/config/i18n.config.php'; // i18n configuration

// ASCII Secure random crypto key
define("CRYPTO_KEY", "def00000696dcbac44167211cb0ae542ac9d5001a06d45c0d487f4309f403bfcc2694f99fa081ebd69096a18237a96010b9b9b8aa8be7a00d222b8ba100d496b293ba488");

// General purpose salt
define("EC_SALT", "ImINZ0B8kD2PmWuU");


// Path to instagram sessions directory
define("SESSIONS_PATH", APPPATH . "/sessions");
// Path to temporary files directory
define("TEMP_PATH", ROOTPATH . "/assets/uploads/temp");


// Path to themes directory
define("THEMES_PATH", ROOTPATH . "/inc/themes");
// URI of themes directory
define("THEMES_URL", APPURL . "/inc/themes");


// Path to plugins directory
define("PLUGINS_PATH", ROOTPATH . "/inc/plugins");
// URI of plugins directory
define("PLUGINS_URL", APPURL . "/inc/plugins");

// Path to ffmpeg binary executable
// NULL means it's been installed on global path
// If you set the value other than null, then it will only be 
// validated during posting the videos
define("FFMPEGBIN", NULL);

// Path to ffprobe binary executable
// NULL means it's been installed on global path
// If you set the value other than null, then it will only be 
// validated during posting the videos
define("FFPROBEBIN", NULL);

// information
define("TITLE", "UMBRELLA CORPORATION");
define("VIDEO", "https://www.youtube.com/watch?v=lcSPUceWcGg&ab_channel=GalaxyStudio");
define("COPYRIGHT", htmlspecialchars("© 2022 ".TITLE.", Heinzstraße 8, Berlin, Germany"));

define("UPLOAD_PATH", ROOTPATH . "/assets/uploads");