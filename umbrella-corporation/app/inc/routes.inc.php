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

// Login
App::addRoute("GET|POST", "/".$langslug."?/login/?", "Login");

// Signup
// 
//  Remove or comment following line to completely 
//  disable signup page. This might be useful in case 
//  of self use of the script
App::addRoute("GET|POST", "/".$langslug."?/signup/?", "Signup");

// Logout
App::addRoute("GET", "/".$langslug."?/logout/?", "Logout");

// Recovery
// App::addRoute("GET|POST", "/".$langslug."?/recovery/?", "Recovery");
// App::addRoute("GET|POST", "/".$langslug."?/recovery/[i:id].[a:hash]/?", "PasswordReset");

/******************************** REGISTER *********************************/
App::addRoute("GET", "/register/?", "Register");

/******************************** DASHBOARD *********************************/
App::addRoute("GET", "/dashboard/?", "Dashboard");

/******************************** RECOVERY *********************************/
App::addRoute("GET", "/recovery/?", "Recovery");

/******************************** APPOINTMENT *********************************/
App::addRoute("GET", "/appointments/?", "Appointments");
App::addRoute("GET", "/appointment/?[i:id]/?", "Appointment");
App::addRoute("GET", "/appointment/create/?", "Appointment");
App::addRoute("GET", "/appointment/arrange/?", "AppointmentArrange");

/******************************** BOOKING *********************************/
App::addRoute("GET", "/bookings/?", "Bookings");
App::addRoute("GET", "/booking/[i:id]/?", "Booking");

/******************************** Error 404 | 500 *********************************/
App::addRoute("GET", "/error/?", "Error");

/******************************** SPECIALITY *********************************/
App::addRoute("GET", "/specialities/?", "Specialities");
App::addRoute("GET", "/speciality/?[i:id]/?", "Speciality");
App::addRoute("GET", "/speciality/create/?", "Speciality");


/******************************** PATIENT *********************************/
App::addRoute("GET", "/patients/?", "Patients");
App::addRoute("GET", "/patient/?[i:id]/?", "Patient");
App::addRoute("GET", "/patient/create/?", "Patient");