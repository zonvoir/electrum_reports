<?php
require_once '../database.php'; // Include your database connection file
require_once '../functions/layout.php'; // Include your Layout class file

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $layouts = new Layout();

//     if (isset($_POST['action'])) {
//         $action = $_POST['action'];
//         // Construct the function name dynamically
//         $functionName = 'handle' . ucfirst($action);

//         if (method_exists($layouts, $functionName)) {
//             // Call the function with extracted parameters
//             $response = $layouts->$functionName($_POST);
//             echo json_encode($response);
//         } else {
//             echo json_encode(['status' => 'error', 'message' => 'Invalid action function: ' . $functionName]);
//         }
//     } else {
//         echo json_encode(['status' => 'error', 'message' => 'Action not provided.']);
//     }
// }


// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $layouts = new Layout();

//     if (isset($_POST['action'])) {
//         $action = $_POST['action'];
//         // Construct the function name dynamically
//         $functionName = 'handle' . ucfirst($action);

//         if (method_exists($layouts, $functionName)) {
//             // Call the function with extracted parameters
//             $response = $layouts->$functionName($_POST);
//             echo json_encode(['status' => 'success', 'result' => $response]);
//           //echo json_encode($response);
//         } else {
//             echo json_encode(['status' => 'error', 'message' => 'Invalid action function: ' . $functionName]);
//         }
//     } else {
//         echo json_encode(['status' => 'error', 'message' => 'Action not provided.']);
//     }
// }



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Initialize layouts object
    $layouts = new Layout();

    // Check if 'action' is set in the POST data
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        // Construct the function name dynamically
        $functionName = 'handle' . ucfirst($action);

        // Check if the method exists in the layouts object
        if (method_exists($layouts, $functionName)) {
            // Call the function with extracted parameters
            $response = $layouts->$functionName($_POST);
            // echo json_encode($response);

            $draw = (isset($response['draw']))? $response['draw']: null;
            $recordsTotal = (isset($response['recordsTotal'])) ? $response['recordsTotal']: null;
            $recordsFiltered = (isset($response['recordsFiltered'])) ? $response['recordsFiltered']: null;
            
            $dtResponse = array(
                "status" => "success",
                "result" => $response,
                "draw"=> $draw,
                "recordsTotal" =>  $recordsTotal,
                "recordsFiltered"=>$recordsFiltered,
            );
            echo json_encode($dtResponse);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid action function: ' . $functionName]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Action not provided.']);
    }
} else {
    // Handle other request methods if needed
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}

