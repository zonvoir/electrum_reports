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
        $layoutTemplateID = $_GET['id'];
        
        $queryTemplate = "SELECT levels FROM layout_template WHERE id = :templateID";
        $statementTemplate = $conn->prepare($queryTemplate);
        $statementTemplate->bindParam(':templateID', $layoutTemplateID, PDO::PARAM_INT);
        $statementTemplate->execute();
        $template = $statementTemplate->fetch(PDO::FETCH_ASSOC);
        
        $totalLevels = $template ? $template['levels'] : 1;
        $hLevel = range(1, $totalLevels);
        $in  = str_repeat('?,', count($hLevel) - 1) . '?';
        
        $query1 = "SELECT h.*, c.column_function FROM headings h 
                    LEFT JOIN column_functions c ON h.id = c.headings_id 
                    WHERE  h.level IN ($in) AND h.layout_template_id = ? 
                    ORDER BY h.parent_id";
        
        $statement1 = $conn->prepare($query1);
        $statement1->execute([...$hLevel, $layoutTemplateID]);
        $headings = $statement1->fetchAll(PDO::FETCH_ASSOC);

        // Initialize the result array
        $rows = [];

        //Group elements by their level
        foreach ($headings as $key => $heading) {
            $level = $heading['level'];
            if (!array_key_exists($level, $rows)) {
                $rows[$level] = [];
            }
            $rows[$level][] = $heading;
        }

        echo '<table id="template-table" class="table table-bordered">';
            $levelIndex = 1;
            foreach ($rows as $key => $row) {
                echo '<tr class="11">';
                    foreach ($row as $heading) {
                        echo '<th 
                                class="first-level-'.$heading['column_type'].'" 
                                colspan="'.$heading['colspan'].'"  
                                type="'.$heading['column_type'].'"
                                column_function="'.$heading['column_function'].'" 
                                style=""  
                            >
                                '.$heading['title'];
                            if($totalLevels==$heading['level']) {
                                echo ' <input type="checkbox"/>';
                            }
                        echo '</th>';
                    }
                echo '</tr>';

                if($key == count($rows)) {
                    echo '<tr>';
                        for ($i=0; $i <= count($row) - 1; $i++) { 
                            if ($i == count($row) - 1) {
                                echo '<td  
                                        colspan="'.$row[$i]['colspan'].'"  
                                        type="'.$row[$i]['column_type'].'"
                                        column_function="'.$row[$i]['column_function'].'"
                                    >
                                        <div class="d-flex">
                                            <input type="number" class="form-control me-1" readonly />
                                            <button class="btnDeleteRow border-1" type="button" disabled>&times;</button>
                                        </div>
                                    </td>';
                            } else {
                                echo '<td  
                                        colspan="'.$row[$i]['colspan'].'"  
                                        type="'.$row[$i]['column_type'].'"
                                        column_function="'.$row[$i]['column_function'].'"
                                    >
                                        <input type="number" class="form-control" />
                                    </td>';
                            }
                        }
                        // echo '<td class="text-end"><a class="btn btn-primary "><i class="fa fa-plus"></i></a></td>';
                    echo '</tr>';
                }
            }
            // echo '<tfoot>
            //     <tr style="border:0 !important;">
            //         <di style="border-left:0 !important; border-right:0 !important">
            //             <button class="btn btn-primary btnAddRow" type="button"><i class="fa fa-plus"></i> Add Row</button>
            //             <button class="btn btn-primary" type="submit">Submit</button>
            //         </di>
            //     </tr>
            // </tfoot>';
        echo '</table>';
        echo '<div style="border-left:0 !important; border-right:0 !important">
                <button class="btn btn-primary btnAddRow" type="button"><i class="fa fa-plus"></i> Add Row</button>
                <button class="btn btn-primary" type="submit">Submit</button>
            </div>';
        
        ?>
    </div>
</div>
<?php
require 'modals.php';
?>

<script>
//Add table row
$(".btnAddRow").click(function() { 
    var table = $("#template-table").closest('table');
    var lastRow = table.find('tbody tr').last();
    var newRow = lastRow.clone(true, true); 
    // newRow.find('input, textarea, select').val('');
    // newRow.find('.growTextarea').css('height','auto');
    newRow.insertAfter(lastRow);
    table.find('.btnDeleteRow').removeAttr("disabled");
});

//Delete table row
$(".btnDeleteRow").click(function() {
    var rowCount = $(this).closest('table').find('tbody tr').length;
    if (rowCount > 2) {
        $(this).closest('tbody tr').remove(); 
    } 
    rowCount --; 
    if (rowCount <= 2) { 
        $(document).find('.btnDeleteRow').prop('disabled', true);  
    }
});


// var headerCells = document.querySelectorAll("#template-table th");
// var selectedColumnValues = [];
// var selectedColumnIndex = -1;

// headerCells.forEach(th => {
//     th.addEventListener("click", function() {
//         if (selectedColumnIndex === -1) {
//             // Primary click
//             selectedColumnIndex = this.cellIndex;
//             selectedColumnValues = [];

//             var rows = document.getElementById("template-table").getElementsByTagName("tr");
//             for (var i = 1; i < rows.length; i++) {
//                 var cells = rows[i].getElementsByTagName("td");
//                 if (selectedColumnIndex < cells.length) {
//                     var originalValue = parseFloat(cells[selectedColumnIndex].innerText.trim()) || 0;
//                     selectedColumnValues.push(originalValue);
//                 }
//             }

//             console.log("Values under the clicked header:", selectedColumnValues);
//         } else {
//             // Secondary click
//             if (selectedColumnValues.length > 0) {
//                 var columnIndex = this.cellIndex;
//                 var rows = document.getElementById("template-table").getElementsByTagName("tr");

//                 console.log("rows.length: " + rows.length);

//                 for (var i = 0; i < rows.length; i++) {
//                     var cells = rows[i].getElementsByTagName("td");
//                     if (columnIndex < cells.length && i - 2 < selectedColumnValues.length) {
//                         cells[columnIndex].innerText = selectedColumnValues[i - 2];
//                     }
//                 }

//                 console.log("Copied values to the secondary clicked header:", selectedColumnValues);
//             } else {
//                 console.log("No values found for the primary clicked header.");
//             }

//             // Reset values and state for the next click
//             selectedColumnIndex = -1;
//             selectedColumnValues = [];
//         }
//     });
// });
</script>
<?php
require 'footer.php';
?>