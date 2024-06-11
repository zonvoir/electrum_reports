<?php
require_once('database.php');
require 'check-login.php';
$database = new Database();
$conn = $database->getConnection();

$loggedInUser = isset($_SESSION['user']) ? $_SESSION['user'] : '';
?>