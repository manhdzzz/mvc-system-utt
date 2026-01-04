<?php
session_start();
ini_set('display_errors',1);
error_reporting(E_ALL);



require_once __DIR__."/../Core/config.php";
require_once __DIR__."/../Core/Database.php";
require_once __DIR__."/../Core/Controller.php";
require_once __DIR__."/../Core/App.php";

new App();
