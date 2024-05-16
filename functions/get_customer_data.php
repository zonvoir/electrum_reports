<?php
require_once('../database.php');
// Connect to the database 
$database = new Database();
$conn = $database->getConnection();
// Perform a query to retrieve customer data
$query = "SELECT id, company, address_1, address_2 FROM customers";
$stmt = $conn->prepare($query);
$stmt->execute();

// Fetch the data as an associative array
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return the data as JSON
header('Content-Type: application/json');
echo json_encode($data);
?>
