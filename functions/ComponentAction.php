<?php

require './Component.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $component = new Component();

    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'loadComponents':
                handleLoadComponents($component);
                break;
            case 'manageComponent':
                handleManageComponent($component);
                break;
            case 'deleteComponent':
                handleDeleteComponent($component);
                break;
            case 'loadTable2Data':
                handleTable2Data($component);
                break;
            default:
                echo json_encode(['status' => 'error', 'message' => 'Invalid action.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => $_POST['action'] . ' Action not provided.']);
    }
}

function handleLoadComponents($component)
{
    if (isset($_POST['action']) && $_POST['action'] == 'loadComponents') {
        $layput_id = $_POST['layout_id'];
        $template_id = $_POST['template_id'];
        $result = $component->loadComponents($layput_id, $template_id);
        echo json_encode($result);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Something want wrong.']);
    }
}

function handleManageComponent($component)
{
    if (isset($_POST['action']) && $_POST['action'] == 'manageComponent') {

        $response = $component->storeAndUpdateComponent($_POST);
        if ($response) {
            echo json_encode($response);
        } else {
            echo 'Error retreaving data';
        }
    }
}

function handleDeleteComponent($component)
{
    if (isset($_POST['action']) && $_POST['action'] == 'deleteComponent') {

        $response = $component->deleteComponent($_POST);
        if ($response) {
            echo json_encode($response);
        } else {
            echo 'Error retreaving data';
        }
    }
}

function handleTable2Data($component)
{
    if (isset($_POST['action']) && $_POST['action'] == 'loadTable2Data') {
        $result = $component->loadTable2($_POST);
        echo json_encode($result);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Something want wrong.']);
    }
}
