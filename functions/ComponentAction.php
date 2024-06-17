<?php

require './Component.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $component = new Component();

    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'loadComponents':
                handleLoadComponents($component);
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
        echo json_encode(['status' => 'error', 'message' => 'Layout Name not provided.']);
    }
}
