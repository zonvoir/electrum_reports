<?php
require('./vendor/autoload.php');
require_once('database.php'); // Include the TCPDF library
require_once('Traceability.php'); // Include the TCPDF library
require_once('./vendor/tecnickcom/tcpdf/tcpdf.php');
$database = new Database();
$conn = $database->getConnection();

$traceability = new Traceability($conn);

// $row = 1;
// if (($handle = fopen("csv/data.csv", "r")) !== FALSE) {
//     while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
//         $num = count($data);
//         echo "<p> $num fields in line $row: <br /></p>\n";
//         $row++;
//         for ($c = 0; $c < $num; $c++) {
//             // echo $data[$c] . "<br />\n";
//             $traceability->insertTraceability($data);
//         }
//     }
//     fclose($handle);
// }

$row = 1;
if (($handle = fopen("csv/data.csv", "r")) !== FALSE) {
    $traceability = new Traceability($conn); // Create an instance of your Traceability class

    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $num = count($data);
        echo "<p> $num fields in line $row: <br /></p>\n";
        $row++;

        // Insert the entire row as a single record
        $traceability->insertTraceability($data);
    }

    fclose($handle);
}

