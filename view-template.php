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
            <div class="col-md-4">
                <label for="unit_uuc">Unit UUC : </label>
                <select class="form-control" id="unit_uuc">
                    <option value="">Select</option>
                    <option value="m">Meter (m)</option>
                    <option value="ft">Feet (ft)</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="unit_ref">Unit ref : </label>
                <select class="form-control" id="unit_ref">
                    <option value="">Select</option>
                    <option value="m">Meter (m)</option>
                    <option value="ft">Feet (ft)</option>
                </select>
            </div>
        </div>
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
                    ORDER BY h.id, h.parent_id";

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

            // foreach ($rows as $key => $row) {
            //     echo "<pre>";
            //     print_r($row);
            //     echo "</pre>";
            //     break;
            // }

            echo '<table id="template-table" class="table table-bordered">';
            $levelIndex = 1;
            $count = 0;
            $lastRow = [];
            foreach ($rows as $key => $row) {
                $count = 0;
                echo '<tr>';
                foreach ($row as $heading) {
                    echo '<th colspan="' . $heading['colspan'] . '" type="' . $heading['column_type'] . '" column_function="' . $heading['column_function'] . '"   >
                                    <label for="' . $heading['id'] . '">'
                        . $heading['title'];
                    if ($totalLevels == $heading['level']) {
                        echo ' <input class="heading_check" data-data="' . htmlspecialchars(json_encode($heading)) . '" id="' . $heading['id'] . '" type="checkbox" />';
                    }
                    echo '</label>';
                    echo '</th>';
                    $count++;
                }
                echo '</tr>';
                if ($key == count($rows)) {
                    $lastRow = $row;
                }
            }

            echo "<pre>";
            print_r($lastRow);
            echo "</pre>";
            // die;

            //if ($key == count($rows)) {
            //echo "count:" . count($row);
            echo '<tr>';
            for ($i = 0; $i <= $count - 1; $i++) {
                if ($i == $count - 1) {
                    echo '<td colspan="' . $row[$i]['colspan'] . '" type="' . $row[$i]['column_type'] . '" column_function="' . $row[$i]['column_function'] . '">
                                        <div class="d-flex">                                            
                                            <button class="btnDeleteRow border-1" type="button" disabled>&times;</button>
                                        </div>';
                    echo '</td>';
                } else if ($lastRow[$i]['column_function'] != "") {
                    echo '<td colspan="' . $row[$i]['colspan'] . '" type="' . $row[$i]['column_type'] . '" column_function="' . $row[$i]['column_function'] . '">
                                        <div class="d-flex">
                                            <input type="number" class="form-control me-1 input_val_' . $row[$i]['id'] . '" readonly />                                            
                                        </div>';
                    echo '</td>';
                } else {
                    echo '<td colspan="' . $row[$i]['colspan'] . '" type="' . $row[$i]['column_type'] . '" column_function="' . $row[$i]['column_function'] . '">
                                        <input type="number" class="form-control valueChange input_val_' . $row[$i]['id'] . '" />
                                    </td>';
                }
            }
            echo '</tr>';
            //}
            echo '</table>';
            echo '<div style="border-left:0 !important; border-right:0 !important">
                    <button class="btn btn-primary btnAddRow" type="button"><i class="fa-solid fa-plus"></i> Add Row</button>
                    <!--button class="btn btn-primary" type="submit">Submit</button-->
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
            rowCount--;
            if (rowCount <= 2) {
                $(document).find('.btnDeleteRow').prop('disabled', true);
            }
        });

        var multipleArr = [];

        $(".heading_check").on('click', function() {
            var heading = JSON.parse($(this).attr('data-data'));
            var heading_id = heading.id;
            var column_type = heading.column_type;
            var column_function = heading.column_function;
            var title = heading.title;
            var singleInputHeadingWiseArray = [title];
            var valueToRemove = title;

            if ($(this).is(':checked')) {
                multipleArr = removeRowByFirstValue(multipleArr, valueToRemove);
                if (column_type == 'DATA') {
                    $(".input_val_" + heading_id + "").each(function() {
                        if ($(this).val()) {
                            singleInputHeadingWiseArray.push($(this).val());
                        }
                    });
                    multipleArr.push(singleInputHeadingWiseArray);
                    console.log(multipleArr);

                } else {

                    var i = 1;

                    if (column_function == "CORRECTION") {
                        //put here CORRECTION formula

                    }
                    if (column_function == "TUC") {
                        //put here TEST UNIT CONVERTION formula

                    }
                    if (column_function == "TS") {
                        //put here TEST STDEV formula

                    }
                    if (column_function == "TC") {
                        //put here TEST COUNT formula

                    }
                    if (column_function == "RS") {
                        //put here REF STDEV formula

                    }
                    if (column_function == "RC") {
                        //put here REF COUNT formula

                    }
                    if (column_function == "UC") {
                        var unit_uuc = $("#unit_uuc").val();
                        var unit_ref = $("#unit_ref").val();
                        var result = 0;
                        if (unit_uuc == "m" && unit_ref == "ft") {
                            result = metersToFeet(10);
                            $(".input_val_" + heading_id + "").each(function() {
                                $(this).val(result);
                            });

                            multipleArr.forEach(function(column) {
                                column.forEach(function(val) {
                                    if (val == "UUC reading") {
                                        console.log(val);
                                    }
                                });
                            });
                        }
                    }
                    if (column_function == "RM") {
                        //put here REF MEAN formula

                    }
                    if (column_function == "UCM") {
                        //put here UUC CONVERT MEAN formula

                    }
                    if (column_function == "UM") {
                        //put here UUC MEAN formula

                    }
                    if (column_function == "RUC") {
                        //put here REF UNIT CON formula

                    }
                    if (column_function == "CUS") {
                        //put here COVERTD UUC STDEV formula

                    }
                    if (column_function == "VC") {
                        //this is testing function for Voltage Calculate formula V = IR
                        var removeFirstColumnArr = removeFirstColumn(multipleArr);
                        var transposedArray = transposeArray(removeFirstColumnArr);

                        var table = document.getElementById("template-table");
                        var rows = table.getElementsByTagName("tr");

                        transposedArray.forEach(function(column) {

                            var vc_cal_result = 1;
                            column.forEach(function(val) {
                                console.log('val', val);
                                vc_cal_result = vc_cal_result * parseFloat(val);
                            });

                            var cells = rows[i].getElementsByTagName("td");
                            var lastCell = cells[cells.length - 1];
                            var lastCellInput = cells[cells.length - 1].querySelector("input[type='number']");
                            if (lastCellInput) {
                                lastCellInput.value = result; // You can set any value here
                            }

                            i++;
                        });
                    }
                }
            } else if (column_type == 'FUNCTION') {
                $(".input_val_" + heading_id + "").each(function() {
                    $(this).val('');
                });
            }
        });

        function getLastCell(rows, i, result) {
            var cells = rows[i].getElementsByTagName("td");
            var lastCell = cells[cells.length - 1];
            var lastCellInput = cells[cells.length - 1].querySelector("input[type='number']");
            if (lastCellInput) {
                lastCellInput.value = result; // You can set any value here
            }
        }

        function removeRowByFirstValue(array, valueToRemove) {
            return array.filter(function(row) {
                return row[0] !== valueToRemove;
            });
        }

        function transposeArray(array) {
            return array[0].map((_, colIndex) => array.map(row => row[colIndex]));
        }

        function removeFirstColumn(array) {
            return array.map(function(row) {
                return row.slice(1); // Remove the first element from each row
            });
        }

        $(".valueChange").on("keyup mouseup", function() {
            $(".heading_check").prop('checked', false);
        });

        function metersToFeet($meters) {
            $feet = $meters * 3.28084;
            return $feet;
        }
    </script>
    <?php
    require 'footer.php';
    ?>