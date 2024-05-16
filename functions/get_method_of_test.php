<?php
require_once('../database.php');
// Connect to the database 
$database = new Database();
$conn = $database->getConnection();
// Get the layout selected from the AJAX request
$selectedLayout = $_GET['layout'];

// Query the database to get the method of test
$query = "SELECT description FROM method_of_tests WHERE layouts_id = :layout";
$stmt = $conn->prepare($query);
$stmt->bindParam(':layout', $selectedLayout);
$stmt->execute();

// Fetch the method of test
$result = $stmt->fetch(PDO::FETCH_ASSOC);

// Return the method of test as JSON
header('Content-Type: application/json');
echo json_encode($result);
