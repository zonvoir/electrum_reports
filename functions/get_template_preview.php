<?php
require_once('../database.php');
// Connect to the database 
$database = new Database();
$conn = $database->getConnection();
    // Get the layout selected from the AJAX request
;

$query = "SELECT * FROM layouts WHERE id = :layoutId";
$statement = $conn->prepare($query);
$statement->bindParam(':layoutId', $_GET['layout_id'], PDO::PARAM_INT);
$statement->execute();
$layout =  $statement->fetchAll(PDO::FETCH_ASSOC);

$layoutTemplateID = $layout[0]['layout_template_id'];
//load headings
$query1 = "SELECT * FROM headings WHERE parent_id = 0 AND layout_template_id = :layoutTemplateId order by parent_id";
$statement = $conn->prepare($query1);
$statement->bindParam(':layoutTemplateId', $layoutTemplateID, PDO::PARAM_INT);
$statement->execute();
$headings =  $statement->fetchAll(PDO::FETCH_ASSOC);

$query2 = "SELECT * FROM headings WHERE level = 2 AND layout_template_id = :layoutTemplateId order by parent_id";
$statement2 = $conn->prepare($query2);
$statement2->bindParam(':layoutTemplateId', $layoutTemplateID, PDO::PARAM_INT);
$statement2->execute();
$headings2 =  $statement2->fetchAll(PDO::FETCH_ASSOC);

$query3 = "SELECT * FROM headings WHERE level = 3 AND layout_template_id = :layoutTemplateId order by parent_id";
$statement3 = $conn->prepare($query3);
$statement3->bindParam(':layoutTemplateId', $layoutTemplateID, PDO::PARAM_INT);
$statement3->execute();
$headings3 =  $statement3->fetchAll(PDO::FETCH_ASSOC);

//get max row id from values table 
$maxquery = "SELECT MAX(row_id) max_id FROM value WHERE layout_template_id = :layoutTemplateId";
$statementMax = $conn->prepare($maxquery);
$statementMax->bindParam(':layoutTemplateId', $layoutTemplateID, PDO::PARAM_INT);
$statementMax->execute();
$result =  $statementMax->fetch(PDO::FETCH_ASSOC);

$val = 0;
if ($result) {
    $val =  $result['max_id'];
}


if ($headings) {


    echo '<tr>';
    foreach ($headings as $heading) {
        echo '<th style="border:1px solid #7f7f7f;"  colspan="' . $heading['colspan'] . '">' . $heading['title'] . '</th>';
    }
    echo '</tr>';

    if ($headings2) {
        echo '<tr>';
        foreach ($headings2 as $heading2) {
            echo '<th style="border:1px solid #7f7f7f;" colspan="' . $heading2['colspan'] . '">' . $heading2['title'] . '</th>';
        }
        echo '</tr>';
    }

    if ($headings3) {
        echo '<tr>';
        foreach ($headings3 as $heading3) {
            echo '<th style="border:1px solid #7f7f7f;" >' . $heading3['title'] .  '</th>';
        }
        echo '</tr>';
    }

    //get values from values table 

    for ($i = 1; $i <= $val; $i++) {
        $valuequery = "SELECT * FROM value WHERE layout_template_id = :layoutTemplateId AND row_id = :rowId";
        $statementValue = $conn->prepare($valuequery);
        $statementValue->bindParam(':layoutTemplateId', $layoutTemplateID, PDO::PARAM_INT);
        $statementValue->bindParam(':rowId', $i, PDO::PARAM_INT);
        $statementValue->execute();
        $resultValues =  $statementValue->fetchAll(PDO::FETCH_ASSOC);

        echo '<tr>';
        foreach ($resultValues as $resultValue) {
            echo '<td style="border:1px solid #7f7f7f;" >' . $resultValue['value'] . ' </td>';
        }
        echo '</tr>';
    }

    // if (count($headings) > 0 && count($headings2) === 0) {
    //     echo '<tr>';
    //     foreach ($headings as $heading) {
    //         echo '<td style="border:1px solid #7f7f7f;"  colspan="' . $heading['colspan'] . '" ><input type="text" name="heading_id[]" data-id="' . $heading['id'] . '" value=""> </td>';
    //     }
    // }

    // if (count($headings2) > 0 && count($headings3) === 0) {
    //     echo '<tr>';
    //     foreach ($headings2 as $heading2) {
    //         echo '<td style="border:1px solid #7f7f7f;"  colspan="' . $heading2['colspan'] . '"><input type="text" class="input_2" name="heading_id[]" data-id="' . $heading2['id'] . '" value=""> </td>';
    //     }
    //     echo '</tr>';
    // }

    // if ($headings3) {
    //     echo '<tr>';
    //     foreach ($headings3 as $heading3) {
    //         echo '<td style="border:1px solid #7f7f7f;"  colspan="' . $heading3['colspan'] . '"><input type="text" class="input_3" name="heading_id[]" data-id="' . $heading3['id'] . '" value=""> </td>';
    //     }
    //     echo '</tr>';
    // }
}
