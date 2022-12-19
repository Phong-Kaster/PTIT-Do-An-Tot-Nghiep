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
App::addRoute("GET|POST", "/".$langslug."?/login/?", "Login");

/************************** LOGIN******************************/
App::addRoute("GET|POST", "/".$langslug."?/login/google/?", "LoginWithGoogle");

// 
//  Remove or comment following line to completely 
//  disable signup page. This might be useful in case 
//  of self use of the script
/************************** SIGN UP******************************/
App::addRoute("GET|POST", "/".$langslug."?/signup/?", "Signup");

// Logout
App::addRoute("GET", "/".$langslug."?/logout/?", "Logout");


/************************** RECOVERY & PASSWORD RESET******************************/
App::addRoute("POST", "/recovery/?", "Recovery");
App::addRoute("GET|POST", "/password-reset/[i:id]?", "PasswordReset");



/************************** SPECIALITY ******************************/
App::addRoute("GET|POST", "/specialities", "Specialities");
App::addRoute("GET|PUT|DELETE|POST", "/specialities/[i:id]", "Speciality");


/************************** CLINIC ******************************/
App::addRoute("GET|POST", "/clinics", "Clinics");
App::addRoute("GET|PUT|DELETE", "/clinics/[i:id]", "Clinic");


/************************** DOCTOR ******************************/
App::addRoute("GET|POST", "/doctors", "Doctors");
App::addRoute("GET|PUT|DELETE|POST", "/doctors/[i:id]", "Doctor");


/************************** DOCTOR PROFILE ******************************/
// this controller is used by doctor to update personal information.
App::addRoute("GET|POST", "/doctor/profile", "DoctorProfile");



/************************** PATIENT ******************************/
App::addRoute("GET|POST", "/patients", "Patients");
App::addRoute("GET|PUT|DELETE", "/patients/[i:id]", "Patient");


/************************** PATIENT PROFILE ******************************/
// this controller is used by patient to update personal information.
App::addRoute("GET|POST", "/patient/profile", "PatientProfile");


/************************** SERVICE ******************************/
App::addRoute("GET|POST", "/services", "Services");
App::addRoute("GET|PUT|DELETE|POST", "/services/[i:id]", "Service");


/************************** DOCTOR AND SERVICE ******************************/
App::addRoute("GET|POST|DELETE", "/doctors-and-services/[i:id]", "DoctorsAndServices");
App::addRoute("GET", "/doctors-and-services-ready/[i:id]", "DoctorsAndServicesReady");


/************************** PATIENT BOOKING ******************************/
App::addRoute("GET|POST", "/patient/booking/?", "PatientBookings");
App::addRoute("GET|DELETE", "/patient/booking/[i:id]/?", "PatientBooking");

/************************** BOOKING ******************************/
App::addRoute("GET|POST", "/bookings/?", "Bookings");
App::addRoute("GET|PUT|PATCH", "/bookings/[i:id]/?", "Booking");

/************************** APPOINTMENTS ******************************/
App::addRoute("GET|POST", "/appointments/?", "Appointments");
App::addRoute("GET|PUT|PATCH|DELETE", "/appointments/[i:id]/?", "Appointment");

/************************** TREATMENT ******************************/
App::addRoute("GET|POST", "/treatments/?", "Treatments");
App::addRoute("GET|PUT|PATCH|DELETE", "/treatments/[i:id]/?", "Treatment");

/************************** APPOINTMENT RECORDS ******************************/
App::addRoute("GET|POST", "/appointment-records/?", "AppointmentRecords");
App::addRoute("GET|PUT|PATCH|DELETE", "/appointment-records/[i:id]/?", "AppointmentRecord");

/************************** ROOMS ******************************/
App::addRoute("GET|POST", "/rooms/?", "Rooms");
App::addRoute("GET|PUT|DELETE", "/rooms/[i:id]/?", "Room");

/************************** APPOINTMENTS ******************************/
App::addRoute("GET|POST", "/appointment-queue/?", "AppointmentQueue");
App::addRoute("GET|POST", "/appointment-queue-now/?", "AppointmentQueueNow");

/************************** CHART ******************************/
App::addRoute("GET", "/charts/?", "Charts");


/*************************** PATIENT NOTIFICATION ************************** */
App::addRoute("GET|POST|PUT", "/patient/notifications/?", "PatientNotifications");
App::addRoute("GET|POST", "/patient/notifications/mark-as-read/[i:id]/?", "PatientNotification");


/*************************** PATIENT APPOINTMENT ************************** */
App::addRoute("GET", "/patient/appointments/?", "PatientAppointments");
App::addRoute("GET", "/patient/appointments/[i:id]/?", "PatientAppointment");

/*************************** PATIENT APPOINTMENT ************************** */
App::addRoute("GET", "/patient/appointments/records/?", "PatientRecords");
App::addRoute("GET", "/patient/appointments/records/[i:id]/?", "PatientRecord");

/*************************** PATIENT TREATMENT ************************** */
App::addRoute("GET", "/patient/treatments/[i:id]?", "PatientTreatments");// id is the appointment's ID
App::addRoute("GET", "/patient/treatment/[i:id]/?", "PatientTreatment");// id is the treatment's ID

/*************************** BOOKING PHOTO ************************** */
App::addRoute("GET", "/booking/photos/[i:id]/?", "BookingPhotos");// id is the the booking's ID
App::addRoute("POST", "/booking/upload-photo/?", "BookingPhotoUpload");// used to upload photo of booking id
App::addRoute("GET|DELETE", "/booking/photo/[i:id]/?", "BookingPhoto");// id IS the photo'ID

/*************************** DRUGS ************************** */
App::addRoute("GET|POST", "/drugs/?", "Drugs");
App::addRoute("GET", "/drugs/[i:id]/?", "Drug");


/*************************** is doctor busy ************************** */
App::addRoute("GET", "/is-doctor-busy/[i:id]?", "isDoctorBusy");