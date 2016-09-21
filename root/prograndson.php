<?php

error_reporting(E_ALL);

date_default_timezone_set('Europe/Zurich');

define("EVE_APP", true);

require 'Library/autoload.php';
require 'Library/errorHandler.php';

$app = new Applications\Prograndson\PrograndsonApplication(__DIR__);
$app->run();

?>