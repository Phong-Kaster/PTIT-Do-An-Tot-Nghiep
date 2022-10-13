<?php 
// Language slug
// 
// Will be used theme routes
$langs = [];
foreach (Config::get("applangs") as $l) {
    if (!in_array($l["code"], $langs)) {
        $langs[] = $l["code"];
    }

    if (!in_array($l["shortcode"], $langs)) {
        $langs[] = $l["shortcode"];
    }
}
$langslug = $langs ? "[".implode("|", $langs).":lang]" : "";


/**
 * Theme Routes
 */

// Index (Landing Page)
// 
// Replace "Index" with "Login" to completely disable Landing page 
// After this change, Login page will be your default landing page
// 
// This is useful in case of self use, or having different 
// landing page in different address. For ex: you can install the script
// to subdirectory or subdomain of your wordpress website.
App::addRoute("GET|POST", "/", "Index");
App::addRoute("GET|POST", "/".$langslug."?/?", "Index");

/************************** LOGIN******************************/
App::addRoute("GET|POST", "/".$langslug."?/api/login/?", "Login");

// 
//  Remove or comment following line to completely 
//  disable signup page. This might be useful in case 
//  of self use of the script
/************************** SIGN UP******************************/
App::addRoute("GET|POST", "/".$langslug."?/api/signup/?", "Signup");

// Logout
App::addRoute("GET", "/".$langslug."?/api/logout/?", "Logout");


/************************** RECOVERY & PASSWORD RESET******************************/
App::addRoute("POST", "/api/recovery/?", "Recovery");
App::addRoute("GET|POST", "/api/password-reset/[i:id]?", "PasswordReset");



/************************** SPECIALITY ******************************/
App::addRoute("GET|POST", "/api/specialities", "Specialities");
App::addRoute("GET|PUT|DELETE", "/api/specialities/[i:id]", "Speciality");


/************************** CLINIC ******************************/
App::addRoute("GET|POST", "/api/clinics", "Clinics");
App::addRoute("GET|PUT|DELETE", "/api/clinics/[i:id]", "Clinic");


/************************** DOCTOR ******************************/
App::addRoute("GET|POST", "/api/doctors", "Doctors");
App::addRoute("GET|PUT|DELETE", "/api/doctors/[i:id]", "Doctor");