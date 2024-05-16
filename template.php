<?php
require_once('database.php');
$database = new Database();
$conn = $database->getConnection();
require 'header.php';

?>
<body>
<?php
require 'navigation.php';
?>
<div class="container">
    <div class="row mt-4">
        <?php
        // $query = "SELECT * FROM layouts WHERE id = :layoutId";
        // $statement = $conn->prepare($query);
        // $statement->bindParam(':layoutId', $_GET['layout_id'], PDO::PARAM_INT);
        // $statement->execute();
        // $layout =  $statement->fetchAll(PDO::FETCH_ASSOC);

        $layoutTemplateID = $_GET['id'];

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

        echo ' <table id="template-table" class="table" style="width:100%">';

        $val = 0;
        if ($result) {
            $val =  $result['max_id'];
        }

        if ($headings) {
            if ($headings && $headings2) {
                echo '<tr>';
                foreach ($headings as $heading) {
                    echo '<th class="first-level-' . $heading['column_type'] . '" style="border:1px solid #7f7f7f;"  colspan="' . $heading['colspan'] . '" column_function="' . $heading['column_function'] . '" type="' . $heading['column_type'] . '">' . $heading['title'] . '</th>';
                }
                echo '</tr>';
            } else {
                echo '<tr>';
                foreach ($headings as $heading) {
                    echo '<th class="first-level-' . $heading['column_type'] . '" style="border:1px solid #7f7f7f;"  colspan="' . $heading['colspan'] . '"  column_function="' . $heading['column_function'] . '" type="' . $heading['column_type'] . '">' . $heading['title'] . ' <input type="checkbox"/></th>';
                }
                echo '</tr>';
            }

            if ($headings2 && $headings3) {
                echo '<tr>';
                foreach ($headings2 as $heading2) {
                    echo '<th style="border:1px solid #7f7f7f;" colspan="' . $heading2['colspan'] . '"  column_function="' . $heading['column_function'] . '" type="' . $heading['column_type'] . '">' . $heading2['title'] . '</th>';
                }
                echo '</tr>';
            } else {
                echo '<tr>';
                foreach ($headings2 as $heading2) {
                    echo '<th style="border:1px solid #7f7f7f;" colspan="' . $heading2['colspan'] . '"  column_function="' . $heading['column_function'] . '" type="' . $heading['column_type'] . '">' . $heading2['title'] . ' <input type="checkbox"/></th>';
                }
                echo '</tr>';
            }

            if ($headings3) {
                echo '<tr>';
                foreach ($headings3 as $heading3) {
                    echo '<th style="border:1px solid #7f7f7f;"  column_function="' . $heading['column_function'] . '" type="' . $heading['column_type'] . '">' . $heading3['title'] .  '</th>';
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
                    echo '<td style="border:1px solid #7f7f7f;" class="value-cell">' . $resultValue['value'] . ' </td>';
                }
                echo '</tr>';
            }
            echo '</table>';
        }
        ?>
    </div>
</div>
<?php
require 'modals.php';
?>

<script>
var headerCells = document.querySelectorAll("#template-table th");
var selectedColumnValues = [];
var selectedColumnIndex = -1;

headerCells.forEach(th => {
    th.addEventListener("click", function() {
        if (selectedColumnIndex === -1) {
            // Primary click
            selectedColumnIndex = this.cellIndex;
            selectedColumnValues = [];

            var rows = document.getElementById("template-table").getElementsByTagName("tr");
            for (var i = 1; i < rows.length; i++) {
                var cells = rows[i].getElementsByTagName("td");
                if (selectedColumnIndex < cells.length) {
                    var originalValue = parseFloat(cells[selectedColumnIndex].innerText.trim()) || 0;
                    selectedColumnValues.push(originalValue);
                }
            }

            console.log("Values under the clicked header:", selectedColumnValues);
        } else {
            // Secondary click
            if (selectedColumnValues.length > 0) {
                var columnIndex = this.cellIndex;
                var rows = document.getElementById("template-table").getElementsByTagName("tr");

                console.log("rows.length: " + rows.length);

                for (var i = 0; i < rows.length; i++) {
                    var cells = rows[i].getElementsByTagName("td");
                    if (columnIndex < cells.length && i - 2 < selectedColumnValues.length) {
                        cells[columnIndex].innerText = selectedColumnValues[i - 2];
                    }
                }

                console.log("Copied values to the secondary clicked header:", selectedColumnValues);
            } else {
                console.log("No values found for the primary clicked header.");
            }

            // Reset values and state for the next click
            selectedColumnIndex = -1;
            selectedColumnValues = [];
        }
    });
});
</script>
<?php
require 'footer.php';
?>