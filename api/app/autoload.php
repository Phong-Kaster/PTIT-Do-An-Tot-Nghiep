<?php 
require_once APPPATH."/core/Autoloader.php";

// instantiate the loader
$loader = new Autoloader;

// register the autoloader
$loader->register();

// register the base directories for auto loading
$loader->addBaseDir(APPPATH.'/vendor');
$loader->addBaseDir(APPPATH.'/lib');
$loader->addBaseDir(APPPATH.'/core');
$loader->addBaseDir(APPPATH.'/controllers');
$loader->addBaseDir(APPPATH.'/models');


require_once APPPATH."/vendor/autoload.php";