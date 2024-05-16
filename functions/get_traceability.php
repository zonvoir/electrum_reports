<?php
require_once('../database.php');
// Connect to the database 
$database = new Database();
$conn = $database->getConnection();
// Get the layout selected from the AJAX request
$equipmentId = $_GET['equipment_id'];
$calDate = (string)$_GET['cal_date'];

// Query the database to get thetraceability_table
// $query = "SELECT id, statement FROM traceability_table WHERE equipment_id = :equipment_id AND cal_date = :cal_date";
$query = "SELECT id, statement, equipment_id FROM traceability_table WHERE equipment_id = :equipment_id AND cal_date = (SELECT MAX(cal_date) FROM traceability_table WHERE equipment_id = :equipment_id AND cal_date <= :cal_date);";

$stmt = $conn->prepare($query);
$stmt->bindParam(':equipment_id', $equipmentId, PDO::PARAM_STR); // Assuming equipment_id is an integer
$stmt->bindParam(':cal_date', $calDate, PDO::PARAM_STR);
// echo $stmt->queryString;
if (!$stmt->execute()) {
    $errorInfo = $stmt->errorInfo();
    echo "SQL Error: " . $errorInfo[2];
    exit;
}
// Fetch the traceability_table
$result = $stmt->fetch(PDO::FETCH_ASSOC);

// Return the traceability_table description as JSON
header('Content-Type: application/json');
echo json_encode($result);
