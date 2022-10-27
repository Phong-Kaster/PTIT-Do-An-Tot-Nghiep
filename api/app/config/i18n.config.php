<?php
$langs = [];

// US English
$langs[] = [
    "code" => "en-US",
    "shortcode" => "en",
    "name" => "English",
    "localname" => "English"
];

// German
$langs[] = [
    "code" => "de-DE",
    "shortcode" => "de",
    "name" => "German",
    "localname" => "Deutsch"
];

// Spanish
$langs[] = [
    "code" => "es-ES",
    "shortcode" => "es",
    "name" => "Spanish",
    "localname" => "Español (España)"
];

// French
/*$langs[] = [
    "code" => "fr-FR",
    "shortcode" => "fr",
    "name" => "French",
    "localname" => "Français (France)"
];*/

// Hindi
$langs[] = [
    "code" => "hi-IN",
    "shortcode" => "hi",
    "name" => "Hindi",
    "localname" => "हिन्दी"
];

// Indonesian
$langs[] = [
    "code" => "id-ID",
    "shortcode" => "id",
    "name" => "Indonesian",
    "localname" => "Bahasa Indonesia"
];

// Italian
$langs[] = [
    "code" => "it-IT",
    "shortcode" => "it",
    "name" => "Italian",
    "localname" => "Italiano"
];

// Dutch
/*$langs[] = [
    "code" => "nl-NL",
    "shortcode" => "nl",
    "name" => "Dutch",
    "localname" => "Nederlands"
];*/

// Portuguese (Brazil)
$langs[] = [
    "code" => "pt-BR",
    "shortcode" => "pt-BR",
    "name" => "Portuguese (Brazil)",
    "localname" => "Português (Brasil)"
];

// Russian
$langs[] = [
    "code" => "ru-RU",
    "shortcode" => "ru",
    "name" => "Russian",
    "localname" => "Русский"
];

// Turkish
$langs[] = [
    "code" => "tr-TR",
    "shortcode" => "tr",
    "name" => "Turkish",
    "localname" => "Türkçe"
];


Config::set("applangs", $langs);
Config::set("default_applang", "en-US");
