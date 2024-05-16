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

        $query1 = "SELECT column_function FROM column_functions WHERE headings_id = :headings_id";
        $statement1 = $conn->prepare($query1);
        $statement1->bindValue(':headings_id', $heading['id'], PDO::PARAM_INT);
        $statement1->execute();
        $result1 = $statement1->fetch(PDO::FETCH_ASSOC);
        $columnFunction1 = '';

        if ($result1) {
            $columnFunction1  = $result1['column_function'];
        }

        // echo '<th  class="header-cell" style="border:1px solid #7f7f7f;" level="1" data-template-id="' . $layoutTemplateID . '" data-id="' . $heading['id'] . '"  colspan="' . $heading['colspan'] . '" column_function="' . ($columnFunction1 ? $columnFunction1 : 'null') . '" column_type="' . $heading['column_type'] . '">' . $heading['title'] . ' <button class="btn btn-sm btn-warning">FN</button></th>';
        echo '<th class="header-cell" style="border:1px solid #7f7f7f;" level="1" data-template-id="' . $layoutTemplateID . '" data-id="' . $heading['id'] . '" colspan="' . $heading['colspan'] . '" column_function="' . ($columnFunction1 ? $columnFunction1 : 'null') . '" column_type="' . $heading['column_type'] . '">'. $heading['title'] ;

        // Check if $columnFunction1 value exists
        if ($columnFunction1) {
            echo ' <button class="btn btn-sm btn-warning function-btn">FN</button>';
        }

        echo '</th>';
        
        
    }
    echo '<th class="header-cell" style="border: 1px solid #7f7f7f; position: relative; text-align: right;">UB</th>';
    echo '</tr>';

    if ($headings2) {
        echo '<tr>';
        foreach ($headings2 as $heading2) {
            $query2 = "SELECT column_function FROM column_functions WHERE headings_id = :headings_id";
            $statement2 = $conn->prepare($query2);
            $statement2->bindValue(':headings_id', $heading2['id'], PDO::PARAM_INT);
            $statement2->execute();
            $result2 = $statement2->fetch(PDO::FETCH_ASSOC);
            $columnFunction2 = '';
            if ($result2) {
                $columnFunction2  = $result2['column_function'];
            }
            // echo '<th class="header-cell" style="border:1px solid #7f7f7f;" level="2" data-template-id="' . $layoutTemplateID . '" data-id="' . $heading2['id'] . '"   colspan="' . $heading2['colspan'] . '"  column_function="' . ($columnFunction2 ? $columnFunction2 : 'null') . '" column_type="' . $heading2['column_type'] . '">' . $heading2['title'] . '</th>';
            echo '<th class="header-cell" style="border:1px solid #7f7f7f;" level="2" data-template-id="' . $layoutTemplateID . '" data-id="' . $heading2['id'] . '" colspan="' . $heading2['colspan'] . '" column_function="' . ($columnFunction2 ? $columnFunction2 : 'null') . '" column_type="' . $heading2['column_type'] . '">' . $heading2['title'];

            // Check if $columnFunction2 value exists
            if ($columnFunction2) {
                echo '<button class="btn btn-sm btn-warning function-btn">FN</button>';
            }

            echo '</th>';
            
        }
        echo '<th class="header-cell" style="border: 1px solid #7f7f7f; position: relative; text-align: right;">UB</th>';
        echo '</tr>';
    }

    if ($headings3) {
        echo '<tr>';
        foreach ($headings3 as $heading3) {
            $query3 = "SELECT column_function FROM column_functions WHERE headings_id = :headings_id";
            $statement3 = $conn->prepare($query3);
            $statement3->bindValue(':headings_id', $heading3['id'], PDO::PARAM_INT);
            $statement3->execute();
            $result3 = $statement3->fetch(PDO::FETCH_ASSOC);
            $columnFunction3 = '';

            if ($result3) {
                $columnFunction3  = $result3['column_function'];
            }
            // echo '<th class="header-cell" style="border:1px solid #7f7f7f;" level="3"  data-template-id="' . $layoutTemplateID . '" data-id="' . $heading3['id'] . '"    column_function="' . ($columnFunction3 ? $columnFunction3 : 'null') . '" column_type="' . $heading3['column_type'] . '">' . $heading3['title'] .  '</th>';
            echo '<th class="header-cell" style="border: 1px solid #7f7f7f; position: relative; text-align: right;" level="3" data-template-id="' . $layoutTemplateID . '" data-id="' . $heading3['id'] . '" column_function="' . ($columnFunction3 ? $columnFunction3 : 'null') . '" column_type="' . $heading3['column_type'] . '">' . $heading3['title'];

            // Check if $columnFunction3 value exists
            if ($columnFunction3) {
                echo '<button class="btn btn-sm btn-warning  function-btn" style="position: absolute; top: 50%; transform: translateY(-50%); right: 5px;">FN</button>';
            }

            echo '</th>';

            
        }
        echo '<th class="header-cell" style="border: 1px solid #7f7f7f; position: relative; text-align: right;">UB</th>';
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


        echo '<tr class="data-row">';
        foreach ($resultValues as $resultValue) {

            $valueCountQuery = "SELECT COUNT(id) AS idcount FROM `multiple_values` WHERE value_id = :id";
            $statementValueCount = $conn->prepare($valueCountQuery);
            $statementValueCount->bindParam(':id', $resultValue['id']);
            if ($statementValueCount->execute()) {
                $valueCountResult = $statementValueCount->fetch(PDO::FETCH_ASSOC);
            }


            $hedNamequery = "SELECT column_function FROM column_functions WHERE headings_id = :headings_id";
            $hedNameValue = $conn->prepare($hedNamequery);
            $hedNameValue->bindParam(':headings_id', $resultValue['headings_id'], PDO::PARAM_INT);
            $hedNameValue->execute();
            $resultHeadName =  $hedNameValue->fetch(PDO::FETCH_ASSOC);
            $columnFunctionhn = '';
            if ($resultHeadName) {
                $columnFunctionhn  = $resultHeadName['column_function'];
            }


            echo '<td class="td-text ' . strtolower($columnFunctionhn) . '" style="border:1px solid #7f7f7f;" data-row-id="' . $i . '" data-value-id="' . $resultValue['id'] . '" data-value-count="' . $valueCountResult['idcount'] . '" data-td-class="' . strtolower($columnFunctionhn) . '">' . $resultValue['value'] . ' </td>';
        
        }
        echo '<td style="border:1px solid #7f7f7f;"><i class="fa-solid fa-gear ub-table-process" data-rowid="'.$resultValue['row_id'].'" data-template-id="'.$layoutTemplateID.'"></i><i class="fa-solid fa-eye table_2_preview" data-rowid="'.$resultValue['row_id'].'" data-headings-id="'.$resultValue['headings_id'].'" data-value-id="' . $resultValue['id'] . '" data-template-id="' . $resultValue['layout_template_id'] . '"></i></td>';
        echo '</tr>';
    }

    if (count($headings) > 0 && count($headings2) === 0) {
        echo '<tr class="data-row">';
        foreach ($headings as $heading) {
            echo '<td  style="border:1px solid #7f7f7f;"  colspan="' . $heading['colspan'] . '" ><input type="text" class="input_1 value-cell" cell-value-str="" name="heading_id[]" data-id="' . $heading['id'] . '" value=""> </td>';
        }
        echo '<td style="border:1px solid #7f7f7f;"></td>';
        // add button
        echo '</tr>';
        echo '<tr>';
        echo '<td style="border:none;padding-left:0px;">';
        echo '<button style="margin-top:10px;width:100px" class="btn btn-primary btn-sm" type="button" id="addRaw" data-level="1" data-layout="' . $_GET['layout_id'] . '" data-template="' . $layoutTemplateID . '"> <i class="fa-solid fa-floppy-disk"></i> Save</button>';
        echo '</td>';
        // remove button
        echo '<td style="border:none;padding-left:0px;">';
        echo '<button style="margin-top:10px;width:150px" class="btn btn-danger btn-sm" type="button" id="clearRaws" data-level="1" data-layout="' . $_GET['layout_id'] . '" data-template="' . $layoutTemplateID . '"> <i class="fa-solid fa-trash"></i> Clear All</button>';
        echo '</td>';
        echo '<td style="border:none"></td>';
        echo '</tr>';
    }

    if (count($headings2) > 0 && count($headings3) === 0) {

        echo '<tr>';
        foreach ($headings2 as $heading2) {

            echo '<td style="border:1px solid #7f7f7f;" onChange="processVal()"  colspan="' . $heading2['colspan'] . '"><input type="text" class="input_2 value-cell"  cell-value-str=""  name="heading_id[]" data-id="' . $heading2['id'] . '" value=""> </td>';
        }
        echo '<td style="border:1px solid #7f7f7f;"></td>';
        echo '</tr>';

        // add button
        echo '</tr>';
        echo '<tr>';
        echo '<td style="border:none;padding-left:0px;">';
        echo '<button style="margin-top:10px;width:100px" class="btn btn-primary btn-sm" type="button" id="addRaw" data-level="2" data-layout="' . $_GET['layout_id'] . '" data-template="' . $layoutTemplateID . '"> <i class="fa-solid fa-floppy-disk"></i> Save</button>';
        echo '</td>';
        // remove button
        echo '<td style="border:none;padding-left:0px;">';
        echo '<button style="margin-top:10px;width:100px" class="btn btn-warning btn-sm" type="button" id="clearRaws" data-level="3" data-layout="' . $_GET['layout_id'] . '" data-template="' . $layoutTemplateID . '"> <i class="fa-solid fa-trash"></i> Clear All</button>';
        echo '</td>';
        echo '<td></td>';
        echo '</tr>';
    }

    if ($headings3) {
        echo '<tr>';
        foreach ($headings3 as $heading3) {

            echo '<td style="border:1px solid #7f7f7f;"  colspan="' . $heading3['colspan'] . '"><input type="text" class="input_3 value-cell"  cell-value-str=""  name="heading_id[]" data-id="' . $heading3['id'] . '" value=""> </td>';
        }
        echo '<td style="border:1px solid #7f7f7f;"></td>';
        echo '</tr>';

        // add button
        echo '</tr>';
        echo '<tr>';
        echo '<td style="border:none;padding-left:0px;">';
        echo '<button style="margin-top:10px;width:100px" class="btn btn-primary btn-sm" type="button" id="addRaw" data-level="3" data-layout="' . $_GET['layout_id'] . '" data-template="' . $layoutTemplateID . '"> <i class="fa-solid fa-floppy-disk"></i> Save</button>';
        echo '</td>';
        // remove button
        echo '<td style="border:none;padding-left:0px;">';
        echo '<button style="margin-top:10px;width:100px" class="btn btn-warning btn-sm" type="button" id="clearRaws" data-level="3" data-layout="' . $_GET['layout_id'] . '" data-template="' . $layoutTemplateID . '"> <i class="fa-solid fa-trash"></i> Clear All</button>';
        echo '</td>';
        echo '<td></td>';
        echo '</tr>';
    }
}
