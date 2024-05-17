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
                echo '<tr>';
                    foreach ($row as $heading) {
                        echo '<th 
                                colspan="'.$heading['colspan'].'"  
                                type="'.$heading['column_type'].'"
                                column_function="'.$heading['column_function'].'"   
                            >
                                '.$heading['title'];
                                if($totalLevels==$heading['level']) {
                                    echo ' <input class="heading_check" data-data="'.htmlspecialchars(json_encode($heading)).'" type="checkbox"/>';
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
                                            <input type="number" class="form-control me-1 input_val_'.$row[$i]['id'].'" readonly />
                                            <button class="btnDeleteRow border-1" type="button" disabled>&times;</button>
                                        </div>
                                    </td>';
                            } else {
                                echo '<td  
                                        colspan="'.$row[$i]['colspan'].'"  
                                        type="'.$row[$i]['column_type'].'"
                                        column_function="'.$row[$i]['column_function'].'"
                                    >
                                        <input type="number" class="form-control input_val_'.$row[$i]['id'].'" />
                                    </td>';
                            }
                        }
                    echo '</tr>';
                }
            }
        echo '</table>';
        echo '<div style="border-left:0 !important; border-right:0 !important">
                <button class="btn btn-primary btnAddRow" type="button"><i class="fa-solid fa-plus"></i> Add Row</button>
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
    newRow.find('input').val('');
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

function removeRowByFirstValue(array, valueToRemove) {
    return array.filter(function(row) {
        return row[0] !== valueToRemove;
    });
}

var multidimensionalArr = [];
$(".heading_check").on('click', function(){
    var heading;
    if($(this).is(':checked')) {
        heading = JSON.parse($(this).attr('data-data'));

        var heading_id = heading.id;
        var column_type = heading.column_type;
        var column_function = heading.column_function;

        var singleArr = [];
        var functionArr = [];

        var valueToRemove = heading_id;
        multidimensionalArr = removeRowByFirstValue(multidimensionalArr, valueToRemove);

        if(column_type=='DATA')
        {
            $(".input_val_"+heading_id+"").each(function(index, element) {
                if($(this).val()){
                    singleArr.push($(this).val());
                }
            });
            multidimensionalArr.push(singleArr);
            console.log(multidimensionalArr);
        }else{
            $(".input_val_"+heading_id+"").each(function(index, element) {
                if($(this).val()){
                    functionArr.push($(this).val());
                }
            });
            console.log('functionArr', functionArr);
            for (var i = 0; i < multidimensionalArr.length; i++) {
                var innerArray = multidimensionalArr[i];
                console.log('innerArray', innerArray);
                for (var j = 0; j < innerArray.length; j++) {
                    var element = innerArray[j];
                    console.log('element', element);
                    console.log('Index:', i, 'Element:', element);
                }
            }
            if(column_function=="CORRECTION"){

            }
            if(column_function=="TUC"){

            }
            if(column_function=="TS"){

            }
            if(column_function=="TC"){

            }
            if(column_function=="RS"){

            }
            if(column_function=="RC"){

            }
            if(column_function=="VC"){
                var v = 2-2;
            }
        }
    } else {
        heading = '';
    }
});
</script>
<?php
require 'footer.php';
?>