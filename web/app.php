<?php
error_reporting (E_ALL);

require_once '../vendor/autoload.php';

session_start();

$loader = new \kernel\Loader();
$loader->run();