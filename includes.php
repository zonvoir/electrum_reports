<?php
require_once('database.php');
require 'check-login.php';

$database = new Database();
$conn = $database->getConnection();

require 'access.php';
require 'navigation.php';

?>