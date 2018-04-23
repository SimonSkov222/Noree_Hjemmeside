<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//header("Content-Type: text/plain");

ob_start();
require_once __DIR__."/Config.php";
require_once __DIR__."/System/Utils/Autoload.php";
require_once __DIR__."/System/Networking/Routing.php";
