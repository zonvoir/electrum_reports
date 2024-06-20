<?php

require './layout.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $layouts = new Layout();

    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'addNewTemplate':
                handleAddNewTemplate($layouts);
                break;
            case 'addNewLayout':
                handleAddNewLayout($layouts);
                break;
            case 'loadTemplates':
                handleLoadTemplates($layouts);
                break;
            case 'removeTemplate':
                handleDeleteTemplate($layouts);
                break;
            case 'loadLayouts':
                handleLoadLayouts($layouts);
                break;
            case 'removeLayout':
                handleDeleteLayout($layouts);
                break;
            case 'getHeadings':
                handleLoadHeadings($layouts);
                break;
            case 'getMaxLevel':
                handleGetMaxLevel($layouts);
                break;
            case 'addHeading':
                handleAddHeading($layouts);
                break;
            case 'updateHeading':
                handleUpdateHeading($layouts);
                break;
            case 'deleteHeading':
                handleDeleteHeading($layouts);
                break;
            case 'addSubTitle':
                handleAddSubTitle($layouts);
                break;
            case 'remove':
                handleRemove($layouts);
                break;
            case 'linkTemplate':
                handleLinkTemplateWithLayout($layouts);
                break;
            case 'edit':
                handleEdit($layouts);
                break;
            case 'addHedingValues':
                handleAddValues($layouts);
                break;
            case 'removeHedingValues':
                handleRemoveValues($layouts);
                break;
            case 'allLayouts':
                handleAllLayouts($layouts);
                break;
            case 'allTemplates':
                handleAllTemplates($layouts);
                break;
            case 'loadSplitData':
                handleSplitData($layouts);
                break;
            case 'loadCertificateData':
                handleCertificateData($layouts);
                break;
            case 'storeCalculationFormData':
                handleStoreCalculationFormData($layouts);
                break;
            case 'userSignUp':
                handleUserSignUp($layouts);
                break;
            case 'userSignIn':
                handleUserSignIn($layouts);
                break;
            case 'multipleValues':
                handleInsertMultipleValues($layouts);
                break;
            case 'addResultValue':
                handleResultValues($layouts);
                break;
            case 'getCellValues':
                handleGetCellValues($layouts);
                break;
            case 'updateCellValue':
                handleUpdateCellValues($layouts);
                break;
            case 'getMultipleValueCount':
                getMultipleValueCount($layouts);
                break;
            case 'getValueofValues':
                getValueOfValuesIds($layouts);
                break;
            case 'getHeadingData':
                getHeadingData($layouts);
                break;
            default:
                echo json_encode(['status' => 'error', 'message' => 'Invalid action.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => $_POST['action'] . ' Action not provided.']);
    }
}

function handleAddNewTemplate($layouts)
{

    if (isset($_POST['template_name'])) {
        $templateName = $_POST['template_name'];
        $template_id = $_POST['template_id'];
        $result = $layouts->addOrUpdateTemplate($templateName, $template_id);
        echo json_encode($result);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Layout Name not provided.']);
    }
}

function handleAddNewLayout($layouts)
{

    if (isset($_POST['layout_name'])) {
        $layoutName = $_POST['layout_name'];
        $layout_template_id = $_POST['layout_template_id'];
        $result = $layouts->addNewLayout($layoutName, $layout_template_id);
        echo json_encode($result);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Layout Name not provided.']);
    }
}

function handleLoadTemplates($layouts)
{
    $result = $layouts->loadTemplates($layouts);
    if ($result) {
        echo json_encode(['status' => 'success', 'templates' => $result]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error loading templates.']);
    }
}

function handleDeleteTemplate($layouts)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $template_id = $_POST['template_id'];
        $result = $layouts->removeTemplate($template_id);
        echo json_encode($result);
    }
}

function handleLoadLayouts($layouts)
{
    $result = $layouts->loadLayouts($layouts);
    if ($result) {
        echo json_encode(['status' => 'success', 'templates' => $result]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error loading templates.']);
    }
}

function handleDeleteLayout($layouts)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $layout_id = $_POST['layout_id'];
        $result = $layouts->removeLayout($layout_id);
        echo json_encode($result);
    }
}

function handleLoadHeadings($layouts)
{
    if (isset($_POST['layout_template_id']) && isset($_POST['layout_id'])) {
        $layoutTemplateID = $_POST['layout_template_id'];
        $layoutID = $_POST['layout_id'];
        $result = $layouts->loadTitles($layoutID, $layoutTemplateID);
        echo json_encode($result);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Layout Name not provided.']);
    }
}

function handleGetMaxLevel($layouts)
{
    if (isset($_POST['template_id'])) {
        $template_id = $_POST['template_id'];
        $result = $layouts->maxLevel($template_id);
        echo json_encode($result);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid Value']);
    }
}

function handleAddHeading($layouts)
{
    if (isset($_POST['title'])) {
        $data = $_POST;
        $result = $layouts->addTitle($data);
        echo json_encode($result);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Title not provided.']);
    }
}

function handleUpdateHeading($layouts)
{
    if (isset($_POST['title'])) {
        $result = $layouts->updateHeading($_POST);
        echo json_encode($result);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Title not provided.']);
    }
}

function handleDeleteHeading($layouts)
{
    if (isset($_POST['action']) && $_POST['action'] == 'deleteHeading') {
        $headingId = $_POST['heading_id'];
        $result = $layouts->deleteHeading($headingId);
        echo json_encode($result);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error while deleted.']);
    }
}

function handleAddSubTitle($layouts)
{
    if (isset($_POST['action']) && $_POST['action'] == 'addSubTitle') {
        $result = $layouts->storeSubTitle($_POST);
        echo json_encode($result);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Sub Title not provided.']);
    }
}

function handleResultValues($layouts)
{
    if (isset($_POST['value_id']) && isset($_POST['value'])) {
        $valueId = $_POST['value_id'];
        $value = $_POST['value'];
        $template_id = $_POST['template_id'];
        $header_id = $_POST['header_id'];
        $rowId = $_POST['row_id'];
        $result = $layouts->updateResultValues($valueId, $value, $template_id, $header_id, $rowId);
        echo json_encode($result);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid Value']);
    }
}
function handleGetCellValues($layouts)
{
    if (isset($_POST['cell_id'])) {
        $valueId = $_POST['cell_id'];
        $result = $layouts->getCellValues($valueId);
        echo json_encode($result);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid Value']);
    }
}
function handleUpdateCellValues($layouts)
{
    if (isset($_POST['cell_id']) && isset($_POST['cell_value'])) {
        $id = $_POST['cell_id'];
        $value = $_POST['cell_value'];
        $result = $layouts->updateCellValue($id, $value);
        echo json_encode($result);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid Value']);
    }
}


function handleInsertMultipleValues($layouts)
{
    if (isset($_POST['value_id']) && isset($_POST['value'])) {
        $valueId = $_POST['value_id'];
        $value = $_POST['value'];
        $result = $layouts->insertMultipleValues($valueId, $value);
        echo json_encode($result);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid Value']);
    }
}

function handleLinkTemplateWithLayout($layouts)
{
    if (isset($_POST['layout_id']) && isset($_POST['template_id'])) {
        $layoutID = $_POST['layout_id'];
        $templateID = $_POST['template_id'];
        $result = $layouts->linkTemplate($layoutID, $templateID);
        echo json_encode($result);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Template couldnt link.']);
    }
}

// function handleAddSecondSubTitle($layouts)
// {

//     if (isset($_POST['title']) && isset($_POST['heddings_id'])) {
//         $title = $_POST['title'];
//         $heddings_id = $_POST['heddings_id'];
//         $level = $_POST['level'];
//         $templateID = $_POST['template_id'];
//         $result = $layouts->addSubTitle($heddings_id, $title, $level, $templateID);
//         echo json_encode($result);
//     } else {
//         echo json_encode(['status' => 'error', 'message' => 'Sub Title not provided.']);
//     }
// }

function handleRemove($layouts)
{
    // Implement code to remove a title
}

function handleEdit($layouts)
{
    // Implement code to edit a title
}


function makeTable()
{
}


function  handleAddValues()
{

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $level = $_POST['level'];
        $values = $_POST['values'];
        $ids = $_POST['ids'];
        $templateID = $_POST['template_id'];
        $strs = $_POST['strs'];

        $layout = new Layout();
        $success = $layout->insertHeddingValues($level, $values, $ids, $templateID, $strs);

        if ($success) {
            echo 'Data inserted successfully';
        } else {
            echo 'Error inserting data';
        }
    }
}
function  handleRemoveValues()
{

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $templateID = $_POST['template_id'];

        $layout = new Layout();
        $success = $layout->removeHeddingValues($templateID);

        if ($success) {
            echo 'Data Cleared successfully';
        } else {
            echo 'Error delete data';
        }
    }
}

function handleAllLayouts()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $layout = new Layout();

        // Assuming you're using POST for DataTables, adjust if needed
        $draw = isset($_POST['draw']) ? intval($_POST['draw']) : 1;
        $start = isset($_POST['start']) ? intval($_POST['start']) : 1;
        $length = isset($_POST['length']) ? intval($_POST['length']) : 10; // Adjust as needed
        $response = $layout->allLayouts($draw, $start, $length);

        if ($response) {
            echo json_encode($response);
        } else {
            echo 'Error retreaving data';
        }
    }
}


function handleAllTemplates()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $layout = new Layout();

        // Assuming you're using POST for DataTables, adjust if needed
        $draw = isset($_POST['draw']) ? intval($_POST['draw']) : 1;
        $start = isset($_POST['start']) ? intval($_POST['start']) : 1;
        $length = isset($_POST['length']) ? intval($_POST['length']) : 10; // Adjust as needed
        $response = $layout->allTemplates($draw, $start, $length);

        if ($response) {
            echo json_encode($response);
        } else {
            echo 'Error retreaving data';
        }
    }
}

function handleSplitData($layouts)
{
    if (isset($_POST['action']) && $_POST['action'] == 'loadSplitData') {

        $response = $layouts->getSplitData($_POST);

        if ($response) {
            echo json_encode($response);
        } else {
            echo 'Error retreaving data';
        }
    }
}

function getHeadingData($layouts)
{
    if (isset($_POST['action']) && $_POST['action'] == 'getHeadingData') {

        $response = $layouts->getHeadingData($_POST);

        if ($response) {
            echo json_encode($response);
        } else {
            echo 'Error retreaving data';
        }
    }
}

function handleCertificateData($layouts)
{
    if (isset($_POST['action']) && $_POST['action'] == 'loadCertificateData') {

        $response = $layouts->getCertificateData($_POST);
        if ($response) {
            echo json_encode($response);
        } else {
            echo 'Error retreaving data';
        }
    }
}

function handleStoreCalculationFormData($layouts)
{
    if (isset($_POST['action']) && $_POST['action'] == 'storeCalculationFormData') {
        $response = $layouts->storeCalculationData($_POST);
        if ($response) {
            echo json_encode($response);
        } else {
            echo 'Error retreaving data';
        }
    }
}

function handleUserSignUp($layouts)
{
    if (isset($_POST['action']) && $_POST['action'] == 'userSignUp') {

        $response = $layouts->userStore($_POST);
        if ($response) {
            echo json_encode($response);
        } else {
            echo 'Error retreaving data';
        }
    }
}

function handleUserSignIn($layouts)
{
    if (isset($_POST['action']) && $_POST['action'] == 'userSignIn') {

        $response = $layouts->userLogin($_POST);
        if ($response) {
            echo json_encode($response);
        } else {
            echo 'Error retreaving data';
        }
    }
}

function getValuesUnderHeading()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $headingID = $_POST['heding_id'];
        $templateID = $_POST['template_id'];
    }
}


function getMultipleValueCount($layout)
{
    if (isset($_POST['value_id'])) {
        $valueId = $_POST['value_id'];
        $response = $layout->getValuesCount($valueId);
    }

    if ($response) {
        echo json_encode($response);
    } else {
        echo 'Error retreaving data';
    }
}


function getValueOfValuesIds($layout)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idlist'])) {
        // Decode JSON data into PHP array
        $idlist = json_decode($_POST['idlist']);

        // Check if decoding was successful
        if ($idlist === null) {
            // Error handling for invalid JSON
            $response = array('error' => 'Invalid JSON data');
            echo json_encode($response);
        } else {
            $response = $layout->getValueOfValues($idlist);
            echo json_encode($response);
        }
    } else {
        // Error handling for invalid request
        $response = array('error' => 'Invalid request');
        echo json_encode($response);
    }
}
