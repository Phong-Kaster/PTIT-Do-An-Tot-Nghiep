<?php
/**
 * Define database credentials
 */
define("DB_HOST", "localhost"); 
define("DB_NAME", "doantotnghiep"); 
define("DB_USER", "root"); 
define("DB_PASS", ""); 
define("DB_ENCODING", "utf8"); // DB connnection charset


/**
 * Define DB tables
 */
define("TABLE_PREFIX", "tn_");

// Set table names without prefix
define("TABLE_SPECIALITIES", "specialities");
define("TABLE_DOCTORS", "doctors");
// define("TABLE_CLINICS", "clinic");

define("TABLE_BOOKINGS", "booking");
define("TABLE_APPOINTMENTS", "appointments");
define("TABLE_PATIENTS", "patients");

define("TABLE_TREATMENTS", "treatments");
define("TABLE_APPOINTMENT_RECORDS", "appointment_records");
define("TABLE_SERVICES", "services");

define("TABLE_DOCTOR_AND_SERVICE", "doctor_and_service");
define("TABLE_NOTIFICATIONS", "notifications");
define("TABLE_ROOMS", "rooms");

define("TABLE_BOOKING_PHOTOS", "booking_photo");
define("TABLE_DRUGS", "drugs");