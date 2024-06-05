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
    <div class="container1 p-3">
        <div class="row mt-4">
            <div class="col-md-2">
                <label for="equipment_id">Equipment ID</label>
                <input type="text" id="equipment_id" value="ECAL/WS/E02" class="form-control getSplitData" />
            </div>
            <div class="col-md-2">
                <label for="sensor_id">Sensor ID</label>
                <input type="text" id="sensor_id" value="ECAL/WS/E02-DCV" class="form-control getSplitData" />
            </div>
            <div class="col-md-2">
                <label for="cal_date">Cal date</label>
                <input type="date" id="cal_date" class="form-control getSplitData" value="2024-06-05" />
            </div>
            <div class="col-md-2">
                <label for="res">Res</label>
                <input type="text" id="res" class="form-control getCertificateData" />
            </div>
            <div class="col-md-2">
                <label for="x">X</label>
                <input type="text" id="x" class="form-control getSplitData" />
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
                <label for="x_split_no">X (split no)</label>
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

            echo '<form id="calculation_template_form">';
            echo '<table id="template-table" class="table table-bordered">';
                $levelIndex = 1;
                $count = 0;
                $lastRow = [];
                foreach ($rows as $key => $row) {
                    $count = 0;
                    echo '<tr>';
                        foreach ($row as $heading) {
                            $hide = "";
                            $hideChkBox = "hide1";
                            if ($heading['column_function'] != "") {
                                $hide = "hide1";
                                $hideChkBox = "";
                            }
                            echo '<th class="' . $hide . '" colspan="' . $heading['colspan'] . '" type="' . $heading['column_type'] . '" column_function="' . $heading['column_function'] . '"   >
                                    <label for="' . $heading['id'] . '">'
                                        . $heading['title'];
                                        if ($totalLevels == $heading['level']) {
                                            echo ' <input class="hide1 heading_check ' . $hideChkBox . '" data-data="' . htmlspecialchars(json_encode($heading)) . '" id="' . $heading['id'] . '" type="checkbox" />';
                                        }
                            echo '</label>';
                            echo '</th>';
                            $count++;
                        }
                        echo '<th>&nbsp;</th>';
                    echo '</tr>';
                    if ($key == count($rows)) {
                        $lastRow = $row;
                    }
                }

                echo '<tr>';
                    for ($i = 0; $i <= $count - 1; $i++) {
                        if ($lastRow[$i]['column_function'] != "") {
                            echo '<td class="hide1" colspan="' . $row[$i]['colspan'] . '" type="' . $row[$i]['column_type'] . '" column_function="' . $row[$i]['column_function'] . '">
                                    <div class="d-flex">
                                        <!--input name="title['.$row[$i]['title'].'][]" type="number" class="form-control me-1 input_val_' . $row[$i]['id'] . '" readonly /-->   
                                        <textarea name="title['.$row[$i]['title'].'][]" class="input-field form-control me-1 valueChange input_val_' . $row[$i]['id'] . '" style="resize:none;" readonly></textarea>                                         
                                    </div>';
                            echo '</td>';
                        } else {
                            echo '<td colspan="' . $row[$i]['colspan'] . '" type="' . $row[$i]['column_type'] . '" column_function="' . $row[$i]['column_function'] . '">';
                                echo '<textarea name="title['.$row[$i]['title'].'][]" class="input-field form-control valueChange input_val_' . $row[$i]['id'] . '" style="resize:none;"></textarea>';
                                // if ($row[$i]['multi_line'] == 1) {
                                //     echo '<textarea name="title['.$row[$i]['title'].'][]" class="input-field form-control valueChange input_val_' . $row[$i]['id'] . '" rows="4" style="resize:none;"></textarea>';
                                // } else {
                                //     echo '<input name="title['.$row[$i]['title'].'][]" type="number" class="input-field form-control valueChange input_val_' . $row[$i]['id'] . '" />';
                                // }
                            echo '</td>';
                        }
                    }
                    echo '<td class="align-middle"><button class="btnDeleteRow border-1" type="button" disabled>&times;</button></td>';
                echo '</tr>';
            echo '</table>';
            echo '<div class="mb-5" style="border-left:0 !important; border-right:0 !important">
                    <button onclick="calculate();" class="btn btn-primary btnCalulate" type="button">Calculate & Save <i class="fa fa-spinner fa-spin" style="display:none;"></i></button>
                    <button class="btn btn-primary float-end btnAddRow" type="button"><i class="fa-solid fa-plus"></i> Add Row</button>                    
                    <!--button class="btn btn-primary" type="submit">Submit</button-->
                  </div>';
            echo '</form>';
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
        
        const url = new URL(window.location.href);
        const layout_template_id = url.searchParams.get('id');
        var function_fields = [];
        $.ajax({
            type: 'POST',
            url: './functions/add-title.php',
            data: {
                action: 'getHeadingData',
                layout_template_id: layout_template_id,
            },
            success: function(dataJSON) {
                var response = JSON.parse(dataJSON);
                if (response.status === 'success') {
                    $.each(response.data, function(index, row) {
                        function_fields[row.id] = row.function_fields;
                    });
                    // console.log('function_fields',function_fields);
                }
            }
        });

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

                            if (x_split_no) {
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
                    }
                });
            }
        });

        function changeDateFormat(dateString) {
            //console.log('dateString', dateString);
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
            newRow.find('input, textarea').val('');
            // newRow.find('.growTextarea').css('height','auto');
            newRow.insertAfter(lastRow);
            table.find('.btnDeleteRow').removeAttr("disabled");
        });

        //for checl validation click on calculate & save
        let isValid = false;

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
            isValid = false;
        });

        var multipleArr = [];

        $(".heading_check").on('click', function() {
            var heading = JSON.parse($(this).attr('data-data'));
            var heading_id = heading.id;
            var title = heading.title;
            var column_type = heading.column_type;
            var multi_line = heading.multi_line;
            var column_function = heading.column_function;
            var singleInputHeadingWiseArray = [heading_id + "@@@"];
            var valueToRemove = heading_id + "@@@";

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
                                if (val == function_fields[heading_id] + '@@@') {
                                    RefReading = true;
                                } else if (isNaN(val)) {
                                    RefReading = false;
                                }
                                if (RefReading && val != function_fields[heading_id] + '@@@') {
                                    uUUCCovertArr.push(val);
                                }
                                //console.log(uUUCCovertArr);
                                var data = uUUCCovertArr;

                                sampleStdev = sampleStandardDeviation(data);
                                //console.log("Sample Standard Deviation:", sampleStdev);
                            });
                        });
                        let x = 0;
                        $(".input_val_" + heading_id + "").each(function() {
                            let v = parseFloat(sampleStdev);
                            $(this).val(v);
                            singleInputHeadingWiseArray.push(v);
                        });
                        multipleArr.push(singleInputHeadingWiseArray);
                        //console.log(multipleArr);

                    }
                    if (column_function == "RC") {
                        //put here REF COUNT formula

                    }
                    if (column_function == "UC") {
                        //put here UUC convrt formula
                        var unit_ref = $("#unit_ref").val() == 'Meter' ? 'm' : 'm';
                        var unit_uuc = $("#unit_uuc").val();
                        var UUCReading = false;
                        var UUCReadingVal = [];

                        if (unit_uuc != "" && unit_ref != "") {
                            multipleArr.forEach(function(column) {
                                column.forEach(function(val) {
                                    if (val == function_fields[heading_id] + '@@@') {
                                        UUCReading = true;
                                    } else if (isNaN(val) && val.indexOf('@@@') > 0) {
                                        UUCReading = false;
                                    }
                                    if (UUCReading && val != function_fields[heading_id] + '@@@') {
                                        let uUCReadingArr = val.split('\n');
                                        
                                        if (unit_uuc == "m" && unit_ref == "ft") {
                                            var feetsArray = $.map(uUCReadingArr, function(meters) {
                                                return convertMetersToFeet(meters);
                                            });
                                            var feetsString = feetsArray.join('\n')
                                            UUCReadingVal.push(feetsString);
                                            
                                        } else if (unit_uuc == "ft" && unit_ref == "m") {

                                            var metersArray = $.map(uUCReadingArr, function(feets) {
                                                return convertFeetToMeters(feets);
                                            });
                                            var metersString = metersArray.join('\n');
                                            UUCReadingVal.push(metersString);
                                            
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
                        var refMeanValue = 0;
                        var refMeanValueArr = [];
                        multipleArr.forEach(function(column) {
                            column.forEach(function(val) {
                                if (val == function_fields[heading_id] + '@@@') {
                                    RefReading = true;
                                } else if (isNaN(val) && val.indexOf('@@@') > 0) {
                                    RefReading = false;
                                }
                                if (RefReading && val != function_fields[heading_id] + '@@@') {
                                    let refReadingArr = val.split('\n');
                                    let refReadingCount = refReadingArr.length;
                                    var refReadingSum = refReadingArr.reduce(function(sum, value) {
                                        return sum + parseInt(value, 10);
                                    }, 0);

                                    refMeanValue = refReadingSum/refReadingCount;
                                    refMeanValueArr.push(refMeanValue);
                                }
                            });
                        });
                        let x = 0;
                        $(".input_val_" + heading_id + "").each(function() {
                            let v = parseFloat(refMeanValueArr[x]);
                            $(this).val(v);
                            singleInputHeadingWiseArray.push(v);
                            x++;
                        });
                        multipleArr.push(singleInputHeadingWiseArray);
                        console.log(multipleArr);
                    }

                    if (column_function == "CR") {
                        //put here CORRECTED REF formula
                        var xSplitNo = $("#x").val();
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
                        //console.log(multipleArr);
                    }

                    if (column_function == "UCM") {
                        //put here UUC CONVERT MEAN formula
                        var RefReading = false;
                        var RefReadingSum = 0;
                        var RefReadingCount = 0;
                        multipleArr.forEach(function(column) {
                            column.forEach(function(val) {
                                if (val == function_fields[heading_id] + '@@@') {
                                    RefReading = true;
                                } else if (isNaN(val)) {
                                    RefReading = false;
                                }
                                if (RefReading && val != function_fields[heading_id] + '@@@') {
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
                        //console.log(multipleArr);

                    }
                    if (column_function == "UM") {
                        //put here UUC MEAN formula
                        var RefReading = false;
                        var RefReadingSum = 0;
                        var RefReadingCount = 0;
                        multipleArr.forEach(function(column) {
                            column.forEach(function(val) {
                                if (val == function_fields[heading_id] + '@@@') {
                                    RefReading = true;
                                } else if (isNaN(val)) {
                                    RefReading = false;
                                }
                                if (RefReading && val != function_fields[heading_id] + '@@@') {
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
                        //console.log(multipleArr);
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
                                    if (val == function_fields[heading_id] + '@@@') {
                                        refUnitCon = true;
                                    } else if (isNaN(val)) {
                                        refUnitCon = false;
                                    }
                                    if (refUnitCon && val != function_fields[heading_id] + '@@@') {
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
                            //console.log(multipleArr);
                        }

                    }

                    if (column_function == "CORRECTION") {
                        //put here CORRECTION formula
                        var refUnitConId = 0;
                        var uUCMeanId = 0;
                        var refUnitConValue = 0;
                        var uUCMeanValue = 0;
                        var refUnitCon = false;
                        var uUCMean = false;

                        multipleArr.forEach(function(column) {
                            column.forEach(function(val) {
                                if (function_fields[heading_id] !== undefined && function_fields[heading_id].includes(",")) {

                                    split = function_fields[heading_id].split(',');
                                    uUCMeanId = parseFloat(split[0]);
                                    refUnitConId = parseFloat(split[1]);

                                    if (val == uUCMeanId + '@@@') {
                                        uUCMean = true;
                                    } else if (isNaN(val)) {
                                        uUCMean = false;
                                    }
                                    if (uUCMean && val != uUCMeanId + '@@@') {
                                        uUCMeanValue = parseFloat(val);
                                    }

                                    if (val == refUnitConId + '@@@') {
                                        refUnitCon = true;
                                    } else if (isNaN(val)) {
                                        refUnitCon = false;
                                    }
                                    if (refUnitCon && val != refUnitConId + '@@@') {
                                        refUnitConValue = parseFloat(val);
                                    }
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

                    if (column_function == "CUS") {
                        //put here COVERTD UUC STDEV formula
                        var RefReading = false;
                        var uUUCCovertArr = [];
                        var sampleStdev = 0;
                        multipleArr.forEach(function(column) {
                            column.forEach(function(val) {
                                if (val == function_fields[heading_id] + '@@@') {
                                    RefReading = true;
                                } else if (isNaN(val)) {
                                    RefReading = false;
                                }
                                if (RefReading && val != function_fields[heading_id] + '@@@') {
                                    uUUCCovertArr.push(val);
                                }
                                var data = uUUCCovertArr;
                                sampleStdev = sampleStandardDeviation(data);
                                //console.log("Sample Standard Deviation:", sampleStdev);
                            });
                        });
                        let x = 0;
                        $(".input_val_" + heading_id + "").each(function() {
                            let v = parseFloat(sampleStdev);
                            $(this).val(v);
                            singleInputHeadingWiseArray.push(v);
                        });
                        multipleArr.push(singleInputHeadingWiseArray);
                        //console.log(multipleArr);

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
            var equipment_id = $("#equipment_id").val();
            var sensor_id = $("#sensor_id").val();
            var cal_date = $("#cal_date").val();
            var res = $("#res").val();
            var x = $("#x").val();
            if (equipment_id=='') {
                toastr.error('Equipment field is required!', 'Error!')
                return false;
            }
            if (sensor_id=='') {
                toastr.error('Sensor field is required!', 'Error!')
                return false;
            }
            if (cal_date=='') {
                toastr.error('Cal date field is required!', 'Error!')
                return false;
            }
            if (res=='') {
                toastr.error('Res field is required!', 'Error!')
                return false;
            }
            if (x=='') {
                toastr.error('X field is required!', 'Error!')
                return false;
            }
            
            $('.input-field').each(function () {
                isValid = validateInput($(this));
            });

            if (isValid==false) {
                toastr.error('Please fill data entry fields is required!', 'Opps!')
            }else{
                $(".heading_check").trigger("click");

                const urlParams = new URLSearchParams(window.location.search);
                const templateId = urlParams.get('id');

                var formData = new FormData($("#calculation_template_form")[0]);
                formData.append('action', 'storeCalculationFormData');
                formData.append('template_id', templateId);
                $.ajax({
                    beforeSend:function(){
                        $(".btnCalulate").attr('disabled',true);
                        $(".fa-spinner").show();
                    },
                    type: 'POST',
                    url: './functions/add-title.php',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(dataJSON) {
                        var response = JSON.parse(dataJSON)
                        if (response.status=='success') {
                            toastr.success(response.message, 'Success!', {
                                timeOut: 3000,
                                extendedTimeOut: 2000,
                                progressBar: true,
                                closeButton: true,
                                tapToDismiss: false,
                                positionClass: "toast-top-right",
                            });
                            // $('#calculation_template_form')[0].reset();
                        }else{
                            toastr.error('Someing want wrong!', 'Opps!')
                        }                        
                    },
                    complete:function(){
                        $(".btnCalulate").attr('disabled',false);
                        $(".fa-spinner").hide();
                    },
                });
            }
        }

        function validateInput(input) {
            const value = input.val().trim();
            if (value === '') {
                return false;
            } else {
                return true;
            }
        }

        function sampleStandardDeviation(data) {
            const n = data.length;
            if (n === 0 || n === 1) return 0;

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

        function metersToFeet(meter) {
            meter = math.unit(meter + ' m');
            var feet = parseFloat(meter.to('ft'));
            return feet;
        }

        function feetToMeters(feet) {
            feet = math.unit(feet + ' ft');
            var meter = parseFloat(feet.to('m'));
            console.log('meter',meter);
            return meter;
        }

        function convertFeetToMeters(feets) {
            return (parseFloat(feets) * 0.3048).toFixed(4);
        }

        function convertMetersToFeet(meters) {
            return (parseFloat(meters) * 3.28084).toFixed(4);
        }
    </script>
    <?php
    require 'footer.php';
    ?>