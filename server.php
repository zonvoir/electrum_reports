<?php
require_once('database.php'); // Adjust the path as needed

$db = new Database();
$conn = $db->getConnection();

$start = $_POST['start'];
$length = $_POST['length'];

$query = "SELECT L.id, L.layout_name, L.layout_template_id, T.template_name FROM layouts L,layout_template T WHERE L.layout_template_id = T.id  LIMIT $start, $length";
$result = $conn->query($query);

$data = [];
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $data[] = $row;
}

// echo json_encode(["data" => $data]);



$totalRecords = 5;

$response = [
    "draw" => intval($_POST['draw']),
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $totalRecords, // For simplicity, assuming no filtering
    "data" => $data
    
];

echo json_encode($response);
