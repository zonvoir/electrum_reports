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
            <div class="col-md-3">
                <label for="equipment_id">Equipment ID</label>
                <input type="text" id="equipment_id" class="form-control getSplitData" />
            </div>
            <div class="col-md-3">
                <label for="sensor_id">Sensor ID</label>
                <input type="text" id="sensor_id" class="form-control getSplitData" />
            </div>
            <div class="col-md-3">
                <label for="cal_date">Cal date</label>
                <input type="date" id="cal_date" class="form-control getSplitData" />
            </div>
            <div class="col-md-3">
                <label for="res">Res</label>
                <input type="text" id="res" class="form-control getCertificateData" />
            </div>
            <div class="col-md-2 hide">
                <label for="range_min">Range</label>
                <input type="number" id="range_min" class="form-control getSplitData" placeholder="Min" value="0" />
            </div>
            <div class="col-md-2 hide">
                <label for="range_min"></label>
                <input type="number" id="range_max" class="form-control getSplitData" placeholder="Max" value="0" />
            </div>
            <div class="col-md-2 hide">
                <label for="x_split_no">X(split No.)</label>
                <input type="text" id="x_split_no" class="form-control getSplitData" />
            </div>
        </div>
        <div class="row mt-2 mb-4">
            <div class="col-md-8">&nbsp;</div>
            <div class="col-md-4">
                <table class="table table-borderd">
                    <thead>
                        <tr>
                            <th>Ref Uncert</th>
                            <th>Split Data</th>
                        </tr>
                    </thead>
                    <tbody id="split_table"></tbody>
                </table>
            </div>
        </div>
        <hr />
        <div class="row mt-2">
            <div class="col-md-4">
                <label for="equipment_name">Equipment Name</label>
                <input type="text" id="equipment_name" class="form-control" readonly />
            </div>
            <div class="col-md-4">
                <label for="brand">Brand</label>
                <input type="text" id="brand" class="form-control" readonly />
            </div>
            <div class="col-md-4">
                <label for="serial_no">Serial #</label>
                <input type="text" id="serial_no" class="form-control" readonly />
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-4">
                <label for="unit_ref">Unit ref</label>
                <input type="text" id="unit_ref" class="form-control" readonly />
            </div>
            <div class="col-md-4">
                <label for="resolution_ref">Resolution ref</label>
                <input type="text" id="resolution_ref" class="form-control" readonly />
            </div>
            <div class="col-md-4">
                <label for="cal_date_2">Cal Date</label>
                <input type="date" id="cal_date_2" class="form-control" readonly />
            </div>
        </div>
        <div class="row mt-2 mb-4">
            <div class="col-md-4">
                <label for="C1">C1</label>
                <input type="text" id="C1" class="form-control" readonly />
            </div>
            <div class="col-md-4">
                <label for="C2">C2</label>
                <input type="text" id="C2" class="form-control" readonly />
            </div>
            <div class="col-md-4">
                <label for="C3">C3</label>
                <input type="text" id="C3" class="form-control" readonly />
            </div>
            <div class="col-md-4">
                <label for="C4">C4</label>
                <input type="text" id="C4" class="form-control" readonly />
            </div>
            <div class="col-md-4">
                <label for="C5">C5</label>
                <input type="text" id="C5" class="form-control" readonly />
            </div>
        </div>
        <hr />
        <div class="row mt-2">
            <?php
            $querySiRefEqInfo = "SELECT * FROM si_ref_eq_info GROUP BY unit";
            $statementSiRefEqInfo = $conn->prepare($querySiRefEqInfo);
            // $statementTemplate->bindParam(':templateID', $layoutTemplateID, PDO::PARAM_INT);
            $statementSiRefEqInfo->execute();
            $siRefEqInfos = $statementSiRefEqInfo->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <div class="col-md-4">
                <label for="unit_uuc">Unit UUC</label>
                <select class="form-control" id="unit_uuc">
                    <option value="ft">Feet</option>
                    <?php
                    // foreach($siRefEqInfos as $siRefEqInfo)
                    // {
                    ?>
                    <!-- <option value="<?php echo $siRefEqInfo['unit']; ?>"><?php echo $siRefEqInfo['unit']; ?></option> -->
                    <?php
                    // }
                    ?>
                </select>
            </div>
            <div class="col-md-4">
                <label for="resolution_uuc">Resolution UUC</label>
                <select class="form-control" id="resolution_uuc">
                    <option value="m">Meter</option>
                    <?php
                    // foreach($siRefEqInfos as $siRefEqInfo)
                    // {
                    ?>
                    <!-- <option value="<?php echo $siRefEqInfo['unit']; ?>"><?php echo $siRefEqInfo['unit']; ?></option> -->
                    <?php
                    // }
                    ?>
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

            // echo "<pre>";
            // print_r($rows);
            // echo "</pre>";

            echo '<table id="template-table" class="table table-bordered">';
            $levelIndex = 1;
            $count = 0;
            $lastRow = [];
            foreach ($rows as $key => $row) {
                $count = 0;
                echo '<tr>';
                foreach ($row as $heading) {
                    $hide = "";
                    $hideChkBox = "hide";
                    if ($heading['column_function'] != "") {
                        $hide = "hide";
                        $hideChkBox = "";
                    }
                    echo '<th class="' . $hide . '" colspan="' . $heading['colspan'] . '" type="' . $heading['column_type'] . '" column_function="' . $heading['column_function'] . '"   >
                                    <label for="' . $heading['id'] . '">'
                        . $heading['title'];
                    if ($totalLevels == $heading['level']) {
                        echo ' <input class="heading_check ' . $hideChkBox . '" data-data="' . htmlspecialchars(json_encode($heading)) . '" id="' . $heading['id'] . '" type="checkbox" />';
                    }
                    echo '</label>';
                    echo '</th>';
                    $count++;
                }
                echo '<th>&nbsp;</th></tr>';
                if ($key == count($rows)) {
                    $lastRow = $row;
                }
            }

            echo '<tr>';
            for ($i = 0; $i <= $count - 1; $i++) {
                if ($i == $count - 1) {
                    echo '<td colspan="' . $row[$i]['colspan'] . '" type="' . $row[$i]['column_type'] . '" column_function="' . $row[$i]['column_function'] . '">
                                        <div class="d-flex">      
                                            <input type="number" class="hide form-control me-1 input_val_' . $row[$i]['id'] . '" readonly />                                          
                                            <button class="btnDeleteRow border-1" type="button" disabled>&times;</button>
                                        </div>';
                    echo '</td>';
                } else if ($lastRow[$i]['column_function'] != "") {
                    echo '<td class="hide" colspan="' . $row[$i]['colspan'] . '" type="' . $row[$i]['column_type'] . '" column_function="' . $row[$i]['column_function'] . '">
                                        <div class="d-flex">
                                            <input type="number" class="form-control me-1 input_val_' . $row[$i]['id'] . '" readonly />                                            
                                        </div>';
                    echo '</td>';
                } else {
                    echo '<td colspan="' . $row[$i]['colspan'] . '" type="' . $row[$i]['column_type'] . '" column_function="' . $row[$i]['column_function'] . '">';
                    if ($row[$i]['multi_line'] == 1) {
                        echo '<textarea class="form-control valueChange input_val_' . $row[$i]['id'] . '"></textarea>';
                    } else {
                        echo '<input type="number" class="form-control valueChange input_val_' . $row[$i]['id'] . '" />';
                    }
                    echo '</td>';
                }
            }
            echo '</tr>';
            echo '</table>';
            echo '<div style="border-left:0 !important; border-right:0 !important">
                    <button onclick="calculate();" class="btn btn-primary btnCalulate" type="button">Calculate & Save</button>
                    <button style="float:right" class="btn btn-primary btnAddRow" type="button"><i class="fa-solid fa-plus"></i> Add Row</button>                    
                    <!--button class="btn btn-primary" type="submit">Submit</button-->
                </div><p>&nbsp;</p><p>&nbsp;</p>';

            ?>
        </div>
    </div>
    <?php
    require 'modals.php';
    ?>

    <script>
        // $("body").on('keyup change', '.getSplitData', function() {
        //     var equipment_id = $("#equipment_id").val();
        //     var sensor_id = $("#sensor_id").val();
        //     var cal_date = $("#cal_date").val();
        //     var range_min = $("#range_min").val();
        //     var range_max = $("#range_max").val();
        //     //var x_split_no = $("#x_split_no").val();

        //     var splitTable = $('#split_table');
        //     var splitTableHTML = '';

        //     // [equipment_id, sensor_id, cal_date, range_min, range_max, x_split_no].every(value => value !== '')
        //     if (equipment_id != '' && sensor_id != '' && cal_date != '' && range_min != '' && range_max != '') { //&& x_split_no != ''
        //         $.ajax({
        //             type: 'POST',
        //             url: './functions/add-title.php',
        //             data: {
        //                 action: 'loadSplitData',
        //                 equipment_id: equipment_id,
        //                 sensor_id: sensor_id,
        //                 cal_date: cal_date,
        //                 range_min: range_min,
        //                 range_max: range_max,
        //                 //x_split_no: x_split_no,
        //             },
        //             success: function(dataJSON) {
        //                 var response = JSON.parse(dataJSON)
        //                 if (response.status === 'success') {
        //                     $.each(response.data, function(index, row) {
        //                         splitTableHTML += '<tr><td>' + row.uncert + '</td><td>' + row.split_no + '</td></tr>';
        //                     });
        //                     splitTable.html(splitTableHTML);
        //                 }
        //             }
        //         });
        //     } else {
        //         splitTable.html(splitTableHTML);
        //     }
        // });      

        $("body").on('input', '.getCertificateData', function() {
            var equipment_id = $("#equipment_id").val();
            var sensor_id = $("#sensor_id").val();
            var cal_date = $("#cal_date").val();
            var range_min = $("#range_min").val();
            var range_max = $("#range_max").val();
            //var x_split_no = $("#x_split_no").val();
            var res = $("#res").val();

            if (equipment_id != '' && sensor_id != '' && cal_date != '' && range_min != '' && range_max != '' && res !== '') { //&& x_split_no != ''
                $.ajax({
                    type: 'POST',
                    url: './functions/add-title.php',
                    data: {
                        action: 'loadCertificateData',
                        equipment_id: equipment_id,
                        sensor_id: sensor_id,
                        cal_date: cal_date,
                        range_min: range_min,
                        range_max: range_max,
                        //x_split_no: x_split_no,
                        res: res,
                    },
                    success: function(dataJSON) {
                        var response = JSON.parse(dataJSON)
                        if (response.status === 'success') {
                            $("#equipment_name").val(response.data.eq_name);
                            $("#brand").val(response.data.brand);
                            $("#serial_no").val(response.data.serial_no);
                            $("#unit_ref").val('Meter'); //response.data.unit
                            $("#resolution_ref").val('Feet'); //response.data.unit
                            $("#cal_date_2").val(changeDateFormat(response.data.cal_date));
                            $("#C1").val(response.data.c1);
                            $("#C2").val(response.data.c2);
                            $("#C3").val(response.data.c3);
                            $("#C4").val(response.data.c4);
                            $("#C5").val(response.data.c5);
                            $("#x_split_no").val(response.data.split_no);

                            var x_split_no = response.data.split_no;
                            var splitTable = $('#split_table');
                            var splitTableHTML = '';

                            $.ajax({
                                type: 'POST',
                                url: './functions/add-title.php',
                                data: {
                                    action: 'loadSplitData',
                                    equipment_id: equipment_id,
                                    sensor_id: sensor_id,
                                    cal_date: cal_date,
                                    range_min: range_min,
                                    range_max: range_max,
                                    x_split_no: x_split_no,
                                },
                                success: function(dataJSON) {
                                    var response = JSON.parse(dataJSON)
                                    if (response.status === 'success') {
                                        $.each(response.data, function(index, row) {
                                            splitTableHTML += '<tr><td>' + row.uncert + '</td><td>' + row.split_no + '</td></tr>';
                                        });
                                        splitTable.html(splitTableHTML);
                                    }
                                }
                            });



                        }
                    }
                });
            }
        });

        function changeDateFormat(dateString) {
            console.log('dateString', dateString);
            if (dateString != '' && dateString != undefined) {
                var parts = dateString.split('/');
                var formattedDate = parts[2] + '-' + parts[0].padStart(2, '0') + '-' + parts[1].padStart(2, '0');
                return formattedDate;
            } else {
                return '';
            }
        }

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
                        var refUnitConValue = 0;
                        var uUCMeanValue = 0;
                        var refUnitCon = false;
                        var uUCMean = false;
                        multipleArr.forEach(function(column) {
                            column.forEach(function(val) {

                                if (val == "Ref unit con") {
                                    refUnitCon = true;
                                } else if (isNaN(val)) {
                                    refUnitCon = false;
                                }
                                if (refUnitCon && val != "Ref unit con") {
                                    refUnitConValue = parseFloat(val);
                                }

                                if (val == "UUC Mean") {
                                    uUCMean = true;
                                } else if (isNaN(val)) {
                                    uUCMean = false;
                                }
                                if (uUCMean && val != "UUC Mean") {
                                    uUCMeanValue = parseFloat(val);
                                }

                            });
                        });

                        let x = 0;
                        $(".input_val_" + heading_id + "").each(function() {
                            let v = parseFloat(refUnitConValue - uUCMeanValue);
                            $(this).val(v);
                            singleInputHeadingWiseArray.push(v);
                        });
                        multipleArr.push(singleInputHeadingWiseArray);
                        console.log(multipleArr);
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
                        var RefReading = false;
                        var uUUCCovertArr = [];
                        var sampleStdev = 0;
                        multipleArr.forEach(function(column) {
                            column.forEach(function(val) {
                                if (val == "Ref reading") {
                                    RefReading = true;
                                } else if (isNaN(val)) {
                                    RefReading = false;
                                }
                                if (RefReading && val != "Ref reading") {
                                    uUUCCovertArr.push(val);
                                }
                                var data = uUUCCovertArr;

                                sampleStdev = sampleStandardDeviation(data);
                                console.log("Sample Standard Deviation:", sampleStdev);
                            });
                        });
                        let x = 0;
                        $(".input_val_" + heading_id + "").each(function() {
                            let v = parseFloat(sampleStdev);
                            $(this).val(v);
                            singleInputHeadingWiseArray.push(v);
                        });
                        multipleArr.push(singleInputHeadingWiseArray);
                        console.log(multipleArr);

                    }
                    if (column_function == "RC") {
                        //put here REF COUNT formula

                    }
                    if (column_function == "UC") {
                        //put here UUC convrt formula
                        var unit_ref = $("#unit_ref").val() == 'Meter' ? 'm' : 'm';
                        var unit_uuc = $("#unit_uuc").val();
                        var result = 0;
                        if (unit_uuc != "" && unit_ref != "") {
                            var UUCReading = false;
                            var UUCReadingVal = [];
                            multipleArr.forEach(function(column) {
                                column.forEach(function(val) {
                                    if (val == "UUC reading") {
                                        UUCReading = true;
                                    } else if (isNaN(val)) {
                                        UUCReading = false;
                                    }
                                    if (UUCReading && val != "UUC reading") {
                                        if (unit_uuc == "m" && unit_ref == "ft") {
                                            UUCReadingVal.push(metersToFeet(val));
                                        } else if (unit_uuc == "ft" && unit_ref == "m") {
                                            UUCReadingVal.push(feetToMeters(val));
                                        }
                                    }
                                });
                            });
                            let x = 0;
                            $(".input_val_" + heading_id + "").each(function() {
                                let v = UUCReadingVal[x++];
                                $(this).val(v);
                                singleInputHeadingWiseArray.push(v);
                            });
                            multipleArr.push(singleInputHeadingWiseArray);
                            console.log(multipleArr);
                        }
                    }
                    if (column_function == "RM") {
                        //put here Ref Mean formula
                        var RefReading = false;
                        var RefReadingSum = 0;
                        var RefReadingCount = 0;
                        multipleArr.forEach(function(column) {
                            column.forEach(function(val) {
                                if (val == "Ref reading") {
                                    RefReading = true;
                                } else if (isNaN(val)) {
                                    RefReading = false;
                                }
                                if (RefReading && val != "Ref reading") {
                                    RefReadingSum = RefReadingSum + parseFloat(val);
                                    RefReadingCount++;
                                }
                            });
                        });
                        let x = 0;
                        $(".input_val_" + heading_id + "").each(function() {
                            let v = parseFloat(RefReadingSum / RefReadingCount);
                            $(this).val(v);
                            singleInputHeadingWiseArray.push(v);
                        });
                        multipleArr.push(singleInputHeadingWiseArray);
                        console.log(multipleArr);
                    }

                    if (column_function == "CR") {
                        //put here CORRECTED REF formula
                        var xSplitNo = $("#x_split_no").val();
                        var C1 = $("#C1").val();
                        var C2 = $("#C2").val();
                        var C3 = $("#C3").val();
                        var C4 = $("#C4").val();
                        var C5 = $("#C5").val();

                        var correct_reference = C5 * Math.pow(xSplitNo, 4) + C4 * Math.pow(xSplitNo, 3) + C3 * Math.pow(xSplitNo, 2) + C2 * Math.pow(xSplitNo, 1) + C1;

                        let x = 0;
                        $(".input_val_" + heading_id + "").each(function() {
                            let v = parseFloat(correct_reference);
                            $(this).val(v);
                            singleInputHeadingWiseArray.push(v);
                        });
                        multipleArr.push(singleInputHeadingWiseArray);
                        console.log(multipleArr);
                    }

                    if (column_function == "UCM") {
                        //put here UUC CONVERT MEAN formula
                        var RefReading = false;
                        var RefReadingSum = 0;
                        var RefReadingCount = 0;
                        multipleArr.forEach(function(column) {
                            column.forEach(function(val) {
                                if (val == "UUC convert") {
                                    RefReading = true;
                                } else if (isNaN(val)) {
                                    RefReading = false;
                                }
                                if (RefReading && val != "UUC convert") {
                                    RefReadingSum = RefReadingSum + parseFloat(val);
                                    RefReadingCount++;
                                }
                            });
                        });
                        let x = 0;
                        $(".input_val_" + heading_id + "").each(function() {
                            let v = parseFloat(RefReadingSum / RefReadingCount);
                            $(this).val(v);
                            singleInputHeadingWiseArray.push(v);
                        });
                        multipleArr.push(singleInputHeadingWiseArray);
                        console.log(multipleArr);

                    }
                    if (column_function == "UM") {
                        //put here UUC MEAN formula
                        var RefReading = false;
                        var RefReadingSum = 0;
                        var RefReadingCount = 0;
                        multipleArr.forEach(function(column) {
                            column.forEach(function(val) {
                                if (val == "UUC reading") {
                                    RefReading = true;
                                } else if (isNaN(val)) {
                                    RefReading = false;
                                }
                                if (RefReading && val != "UUC reading") {
                                    RefReadingSum = RefReadingSum + parseFloat(val);
                                    RefReadingCount++;
                                }
                            });
                        });
                        let x = 0;
                        $(".input_val_" + heading_id + "").each(function() {
                            let v = parseFloat(RefReadingSum / RefReadingCount);
                            $(this).val(v);
                            singleInputHeadingWiseArray.push(v);
                        });
                        multipleArr.push(singleInputHeadingWiseArray);
                        console.log(multipleArr);
                    }
                    if (column_function == "RUC") {
                        //put here REF UNIT CON formula
                        var resolution_ref = $("#resolution_ref").val() == 'Feet' ? 'ft' : 'ft';
                        var resolution_uuc = $("#resolution_uuc").val();
                        var result = 0;
                        if (resolution_uuc != "" && resolution_ref != "") {
                            var refUnitCon = false;
                            var refUnitConVal = [];
                            multipleArr.forEach(function(column) {
                                column.forEach(function(val) {
                                    if (val == "Corrected Ref") {
                                        refUnitCon = true;
                                    } else if (isNaN(val)) {
                                        refUnitCon = false;
                                    }
                                    if (refUnitCon && val != "Corrected Ref") {
                                        if (resolution_uuc == "m" && resolution_ref == "ft") {
                                            refUnitConVal.push(metersToFeet(val));
                                        } else if (resolution_uuc == "ft" && resolution_ref == "m") {
                                            refUnitConVal.push(feetToMeters(val));
                                        }
                                    }
                                });
                            });
                            let x = 0;
                            $(".input_val_" + heading_id + "").each(function() {
                                let v = refUnitConVal[x++];
                                $(this).val(v);
                                singleInputHeadingWiseArray.push(v);
                            });
                            multipleArr.push(singleInputHeadingWiseArray);
                            console.log(multipleArr);
                        }

                    }
                    if (column_function == "CUS") {
                        //put here COVERTD UUC STDEV formula
                        var RefReading = false;
                        var uUUCCovertArr = [];
                        var sampleStdev = 0;
                        multipleArr.forEach(function(column) {
                            column.forEach(function(val) {
                                if (val == "UUC convert") {
                                    RefReading = true;
                                } else if (isNaN(val)) {
                                    RefReading = false;
                                }
                                if (RefReading && val != "UUC convert") {
                                    uUUCCovertArr.push(val);
                                }
                                var data = uUUCCovertArr;

                                sampleStdev = sampleStandardDeviation(data);
                                console.log("Sample Standard Deviation:", sampleStdev);
                            });
                        });
                        let x = 0;
                        $(".input_val_" + heading_id + "").each(function() {
                            let v = parseFloat(sampleStdev);
                            $(this).val(v);
                            singleInputHeadingWiseArray.push(v);
                        });
                        multipleArr.push(singleInputHeadingWiseArray);
                        console.log(multipleArr);

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

        function calculate() {
            $(".heading_check").trigger("click");
        }

        function sampleStandardDeviation(data) {
            const n = data.length;
            if (n === 0 || n === 1) return null;

            const mean = data.reduce((acc, val) => acc + val, 0) / n;
            const variance = data.reduce((acc, val) => acc + Math.pow(val - mean, 2), 0) / (n - 1);

            return Math.sqrt(variance);
        }

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

        function feetToMeters(feet) {
            return feet * 0.3048; // 1 foot = 0.3048 meters
        }
    </script>
    <?php
    require 'footer.php';
    ?>