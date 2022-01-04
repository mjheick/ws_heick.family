<?php
/* Show me all the errors */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$path = '../lib/';
require_once($path . 'Family.php');
require_once($path . 'Data.php');