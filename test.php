<?php
require_once('database.php');
require_once('Traceability.php'); // Include the TCPDF library
$database = new Database();
$conn = $database->getConnection();
$traceability = new Traceability($conn);

if (isset($_POST['given_date'])) {
    $post_date = $_POST['given_date'];
    $date = $traceability->getLatestCalDate($post_date);
}








// $givenDate = new DateTime($post_date); // Replace with your given date
// $givenDate = new DateTime('2023-07-21'); // Replace with your given date
// $dateList = array('2023-07-05', '2023-07-06', '2023-07-08', '2023-07-20', '2023-07-22', '2023-07-25');

// $latestDate = null;

// foreach ($dateList as $dateString) {
//     $date = new DateTime($dateString);

//     if ($date <= $givenDate) {
//         if ($latestDate === null || $date > $latestDate) {
//             $latestDate = $date;
//         }
//     }
// }

// if ($latestDate !== null) {
//     $latestDateString = $latestDate->format('Y-m-d'); // Format the date as needed
//     echo "Latest Date: $latestDateString";
// } else {
//     echo "No date found within the given criteria.";
// }

?>

<form action="#" method="post">
    <table>
        <tr>
            <td>Date</td>
            <td><input type="date" name="given_date" /></td>
            <td><button type="submit">Get Date</button></td>
        </tr>
        <tr>
            <td colspan="2"><?= $date; ?></td>
        </tr>
    </table>
</form>
