<?php

require __DIR__ . "/../vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

require "funciones.php";
require "database.php";

use Model\ActiveRecord;
ActiveRecord::setDB($db);