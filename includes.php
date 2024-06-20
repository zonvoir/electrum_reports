<?php
require_once('database.php');
require 'check-login.php';
require 'access.php';

$database = new Database();
$conn = $database->getConnection();
?>