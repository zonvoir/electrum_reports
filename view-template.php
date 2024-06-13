<?php require 'header.php'; ?>

<div class="container">
    <?php if ($role['name'] == 'analyst'): ?>
        <?php require '401.php'; ?>
    <?php else: ?>
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

            echo '<form id="calculation_template_form">';
            echo '<div class="table-responive">';
            echo '<table id="template-table" class="table table-bordered">';
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
                    echo '<th class="' . $hide . ' p-1 text-nowrap text-center" colspan="' . $heading['colspan'] . '" type="' . $heading['column_type'] . '" column_function="' . $heading['column_function'] . '">';
                    echo $heading['title'];
                    echo '<input class="hide heading_check ' . $hideChkBox . '" data-data="' . htmlspecialchars(json_encode($heading)) . '" id="' . $heading['id'] . '" type="checkbox" />';
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
                    echo '<td class="hide1 p-1" colspan="' . $row[$i]['colspan'] . '" type="' . $row[$i]['column_type'] . '" column_function="' . $row[$i]['column_function'] . '">';
                    echo '<textarea name="title[' . $row[$i]['title'] . '][]" class="form-control valueChange input_val_' . $row[$i]['id'] . '" style="resize:none;" readonly rows="3"></textarea>';
                    echo '</td>';
                } else {
                    echo '<td class="p-1" colspan="' . $row[$i]['colspan'] . '" type="' . $row[$i]['column_type'] . '" column_function="' . $row[$i]['column_function'] . '">';
                    echo '<textarea name="title[' . $row[$i]['title'] . '][]" class="input-field form-control valueChange input_val_' . $row[$i]['id'] . '" style="resize:none;" rows="3"></textarea>';
                    echo '</td>';
                }
            }
            echo '<td class="align-middle"><button class="btnDeleteRow border-1" type="button" disabled>&times;</button></td>';
            echo '</tr>';
            echo '</table>';
            echo '<div class="mb-5" style="border-left:0 !important; border-right:0 !important">
                        <button onclick="calculate();" class="btn btn-primary btnCalulate" type="button">Calculate & Save <i class="fa fa-spinner fa-spin" style="display:none;"></i></button>
                        <button class="btn btn-primary float-end btnAddRow" type="button"><i class="fa-solid fa-plus"></i> Add Row</button>                    
                    </div>';
            echo '</div>';
            echo '</form>';
            ?>
        </div>
    <?php endif; ?>
</div>

<script>
    $('.input-field').on('input', function() {
        var sanitizedValue = $(this).val().replace(/[^0-9.\n]/g, '');
        $(this).val(sanitizedValue);
    });

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
            }
        }
    });

    $("body").on('input', '.getCertificateData', function() {
        var equipment_id = $("#equipment_id").val();
        var sensor_id = $("#sensor_id").val();
        var cal_date = $("#cal_date").val();
        var range_min = $("#range_min").val();
        var range_max = $("#range_max").val();
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
        newRow.find('input, textarea').val('');;
        newRow.insertAfter(lastRow);
        table.find('.btnDeleteRow').removeAttr("disabled");
    });

    //for check validation click on calculate & save
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

                //put here TEST UNIT CONVERTION formula
                if (column_function == "TUC") {}

                //put here TEST STDEV formula
                if (column_function == "TS") {}

                //put here TEST COUNT formula
                if (column_function == "TC") {}

                //put here REF COUNT formula
                if (column_function == "RC") {}

                // Apply here UUC convrt formula
                if (column_function == "UC") {
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
                        let index = 0;
                        $(".input_val_" + heading_id + "").each(function() {
                            let v = UUCReadingVal[index];
                            $(this).val(v);
                            singleInputHeadingWiseArray.push(v);
                            index++;
                        });
                        multipleArr.push(singleInputHeadingWiseArray);
                        console.log(multipleArr);
                    }
                }

                // Apply here Ref Mean formula
                if (column_function === "RM") {
                    var isValueMatch = false;
                    var calculatedValue = 0;
                    var calculatedValues = [];

                    multipleArr.forEach(function(column) {
                        column.forEach(function(value) {
                            if (value === function_fields[heading_id] + '@@@') {
                                isValueMatch = true;
                            } else if (isNaN(value) && value.indexOf('@@@') > 0) {
                                isValueMatch = false;
                            }

                            if (isValueMatch && value !== function_fields[heading_id] + '@@@') {
                                let previousValues = value.split('\n');
                                let previousValuesCount = previousValues.length;
                                var previousValuesSum = previousValues.reduce(function(sum, currentValue) {
                                    return sum + parseFloat(currentValue);
                                }, 0);

                                calculatedValue = previousValuesSum / previousValuesCount;
                                calculatedValues.push(calculatedValue);
                            }
                        });
                    });

                    let index = 0;
                    $(".input_val_" + heading_id).each(function() {
                        let formattedValue = parseFloat(calculatedValues[index]).toFixed(2);
                        $(this).val(formattedValue);
                        singleInputHeadingWiseArray.push(formattedValue);
                        index++;
                    });
                    multipleArr.push(singleInputHeadingWiseArray);
                    console.log(multipleArr);
                }

                // Apply here CORRECTED REF formula
                if (column_function == "CR") {
                    var xSplitNo = $("#x").val();
                    var C1 = $("#C1").val();
                    var C2 = $("#C2").val();
                    var C3 = $("#C3").val();
                    var C4 = $("#C4").val();
                    var C5 = $("#C5").val();

                    var correctRef = C5 * Math.pow(xSplitNo, 4) + C4 * Math.pow(xSplitNo, 3) + C3 * Math.pow(xSplitNo, 2) + C2 * Math.pow(xSplitNo, 1) + C1;

                    let index = 0;
                    $(".input_val_" + heading_id + "").each(function() {
                        let v = parseFloat(correctRef).toFixed(2);
                        $(this).val(v);
                        singleInputHeadingWiseArray.push(v);
                    });
                    multipleArr.push(singleInputHeadingWiseArray);
                    console.log(multipleArr);
                }

                // Apply here UUC CONVERT MEAN formula
                if (column_function == "UCM") {
                    var isValueMatch = false;
                    var calculatedValue = 0;
                    var calculatedValues = [];

                    multipleArr.forEach(function(column) {
                        column.forEach(function(value) {
                            if (value === function_fields[heading_id] + '@@@') {
                                isValueMatch = true;
                            } else if (isNaN(value) && value.indexOf('@@@') > 0) {
                                isValueMatch = false;
                            }

                            if (isValueMatch && value !== function_fields[heading_id] + '@@@') {
                                let previousValues = value.split('\n');
                                let previousValuesCount = previousValues.length;
                                var previousValuesSum = previousValues.reduce(function(sum, currentValue) {
                                    return sum + parseFloat(currentValue);
                                }, 0);

                                calculatedValue = previousValuesSum / previousValuesCount;
                                calculatedValues.push(calculatedValue);
                            }
                        });
                    });

                    let index = 0;
                    $(".input_val_" + heading_id).each(function() {
                        let formattedValue = parseFloat(calculatedValues[index]).toFixed(2);
                        $(this).val(formattedValue);
                        singleInputHeadingWiseArray.push(formattedValue);
                        index++;
                    });
                    multipleArr.push(singleInputHeadingWiseArray);
                    console.log(multipleArr);
                }

                // Apply here UUC MEAN formula
                if (column_function == "UM") {
                    var isValueMatch = false;
                    var calculatedValue = 0;
                    var calculatedValues = [];

                    multipleArr.forEach(function(column) {
                        column.forEach(function(value) {
                            if (value === function_fields[heading_id] + '@@@') {
                                isValueMatch = true;
                            } else if (isNaN(value) && value.indexOf('@@@') > 0) {
                                isValueMatch = false;
                            }

                            if (isValueMatch && value !== function_fields[heading_id] + '@@@') {
                                let previousValues = value.split('\n');
                                let previousValuesCount = previousValues.length;
                                var previousValuesSum = previousValues.reduce(function(sum, currentValue) {
                                    return sum + parseFloat(currentValue);
                                }, 0);

                                calculatedValue = previousValuesSum / previousValuesCount;
                                calculatedValues.push(calculatedValue);
                            }
                        });
                    });

                    let index = 0;
                    $(".input_val_" + heading_id).each(function() {
                        let formattedValue = parseFloat(calculatedValues[index]).toFixed(2);
                        $(this).val(formattedValue);
                        singleInputHeadingWiseArray.push(formattedValue);
                        index++;
                    });
                    multipleArr.push(singleInputHeadingWiseArray);
                    console.log(multipleArr);
                }

                // Apply here REF UNIT CON formula
                if (column_function == "RUC") {
                    var resolution_ref = $("#resolution_ref").val() == 'Feet' ? 'ft' : 'ft';
                    var resolution_uuc = $("#resolution_uuc").val();
                    var refUnitCon = false;
                    var refUnitConVal = [];
                    if (resolution_uuc != "" && resolution_ref != "") {
                        multipleArr.forEach(function(column) {
                            column.forEach(function(val) {
                                if (val == function_fields[heading_id] + '@@@') {
                                    refUnitCon = true;
                                } else if (isNaN(val) && val.indexOf('@@@') > 0) {
                                    refUnitCon = false;
                                }
                                if (refUnitCon && val != function_fields[heading_id] + '@@@') {
                                    if (resolution_uuc == "m" && resolution_ref == "ft") {
                                        refUnitConVal.push(convertMetersToFeet(val));
                                    } else if (resolution_uuc == "ft" && resolution_ref == "m") {
                                        refUnitConVal.push(convertFeetToMeters(val));
                                    }
                                }
                            });
                        });
                        let index = 0;
                        $(".input_val_" + heading_id + "").each(function() {
                            let v = refUnitConVal[index];
                            $(this).val(v);
                            singleInputHeadingWiseArray.push(v);
                            index++;
                        });
                        multipleArr.push(singleInputHeadingWiseArray);
                        console.log(multipleArr);
                    }
                }

                // Apply here CORRECTION formula
                if (column_function == "CORRECTION") {
                    var refUnitConId = 0;
                    var uUCMeanId = 0;
                    var refUnitCon = false;
                    var uUCMean = false;
                    var uUCMeanValue = 0;
                    var uUCMeanValues = [];
                    var refUnitConValue = 0;
                    var refUnitConValues = [];
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
                                    uUCMeanValues.push(uUCMeanValue);
                                }

                                if (val == refUnitConId + '@@@') {
                                    refUnitCon = true;
                                } else if (isNaN(val)) {
                                    refUnitCon = false;
                                }
                                if (refUnitCon && val != refUnitConId + '@@@') {
                                    refUnitConValue = parseFloat(val);
                                    refUnitConValues.push(refUnitConValue);
                                }
                            }
                        });
                    });

                    let index = 0;
                    $(".input_val_" + heading_id + "").each(function() {
                        let v = parseFloat(refUnitConValues[index] - uUCMeanValues[index]).toFixed(2);
                        $(this).val(v);
                        singleInputHeadingWiseArray.push(v);
                        index++;
                    });
                    multipleArr.push(singleInputHeadingWiseArray);
                    console.log(multipleArr);
                }

                // Apply here COVERTD UUC STDEV formula
                if (column_function == "CUS") {
                    var RefReading = false;
                    var uUUCCovertArr = [];
                    multipleArr.forEach(function(column) {
                        column.forEach(function(val) {
                            if (val == function_fields[heading_id] + '@@@') {
                                RefReading = true;
                            } else if (isNaN(val) && val.indexOf('@@@') > 0) {
                                RefReading = false;
                            }
                            if (RefReading && val != function_fields[heading_id] + '@@@') {

                                let data = val.split('\n');
                                var sampleStdev = sampleStandardDeviation(data);
                                uUUCCovertArr.push(sampleStdev);
                            }
                        });
                    });
                    let index = 0;
                    $(".input_val_" + heading_id + "").each(function() {
                        let v = parseFloat(uUUCCovertArr[index]).toFixed(2);
                        $(this).val(v);
                        singleInputHeadingWiseArray.push(v);
                        index++;
                    });
                    multipleArr.push(singleInputHeadingWiseArray);
                    console.log(multipleArr);
                }

                // Apply here REF STDEV formula
                if (column_function == "RS") {
                    var RefReading = false;
                    var uUUCCovertArr = [];
                    multipleArr.forEach(function(column) {
                        column.forEach(function(val) {
                            if (val == function_fields[heading_id] + '@@@') {
                                RefReading = true;
                            } else if (isNaN(val) && val.indexOf('@@@') > 0) {
                                RefReading = false;
                            }
                            if (RefReading && val != function_fields[heading_id] + '@@@') {
                                let data = val.split('\n');
                                var sampleStdev = sampleStandardDeviation(data);
                                uUUCCovertArr.push(sampleStdev);
                            }
                        });
                    });
                    let index = 0;
                    $(".input_val_" + heading_id + "").each(function() {
                        let v = parseFloat(uUUCCovertArr[index]).toFixed(2);
                        $(this).val(v);
                        singleInputHeadingWiseArray.push(v);
                        index++;
                    });
                    multipleArr.push(singleInputHeadingWiseArray);
                    console.log(multipleArr);
                }
            }
        } else if (column_type == 'FUNCTION') {
            $(".input_val_" + heading_id + "").each(function() {
                $(this).val('');
            });
        }
    });

    function sampleStandardDeviation(data) {
        const n = data.length;
        if (n === 0 || n === 1) return 0;
        const numericData = data.map(Number);
        const mean = numericData.reduce((acc, val) => acc + val, 0) / n;
        const variance = numericData.reduce((acc, val) => acc + Math.pow(val - mean, 2), 0) / (n - 1);
        return Math.sqrt(variance);
    }

    function removeRowByFirstValue(array, valueToRemove) {
        return array.filter(function(row) {
            return row[0] !== valueToRemove;
        });
    }

    function convertFeetToMeters(feets) {
        return (parseFloat(feets) * 0.3048).toFixed(2);
    }

    function convertMetersToFeet(meters) {
        return (parseFloat(meters) * 3.28084).toFixed(2);
    }

    function calculate() {
        var equipment_id = $("#equipment_id").val();
        var sensor_id = $("#sensor_id").val();
        var cal_date = $("#cal_date").val();
        var res = $("#res").val();
        var x = $("#x").val();
        if (equipment_id == '') {
            toastrErrorMessage('Equipment field is required!');
            return false;
        }
        if (sensor_id == '') {
            toastrErrorMessage('Sensor field is required!');
            return false;
        }
        if (cal_date == '') {
            toastrErrorMessage('Cal date field is required!');
            return false;
        }
        if (res == '') {
            toastrErrorMessage('Res field is required!');
            return false;
        }
        if (x == '') {
            toastrErrorMessage('X field is required!');
            return false;
        }

        $('.input-field').each(function() {
            isValid = validateInput($(this));
        });

        if (isValid == false) {
            toastrErrorMessage('Please fill data entry fields is required!');
        } else {
            $(".heading_check").trigger("click");

            const urlParams = new URLSearchParams(window.location.search);
            const templateId = urlParams.get('id');

            var formData = new FormData($("#calculation_template_form")[0]);
            formData.append('action', 'storeCalculationFormData');
            formData.append('template_id', templateId);
            $.ajax({
                beforeSend: function() {
                    $(".btnCalulate").attr('disabled', true);
                    $(".fa-spinner").show();
                },
                type: 'POST',
                url: './functions/add-title.php',
                data: formData,
                processData: false,
                contentType: false,
                success: function(dataJSON) {
                    var response = JSON.parse(dataJSON)
                    if (response.status == 'success') {
                        toastr.success(response.message, 'Success!', {
                            timeOut: 3000,
                            extendedTimeOut: 2000,
                            progressBar: true,
                            closeButton: true,
                            tapToDismiss: false,
                            positionClass: "toast-top-right",
                        });
                        // $('#calculation_template_form')[0].reset();
                        $(".heading_check").prop('checked', false);
                    } else {
                        toastrErrorMessage('Someing want wrong!');
                    }
                },
                complete: function() {
                    $(".btnCalulate").attr('disabled', false);
                    $(".fa-spinner").hide();
                },
            });
        }
    }

    function toastrErrorMessage(message) {
        toastr.error(message, 'Opps!', {
            timeOut: 3000,
            extendedTimeOut: 2000,
            progressBar: true,
            closeButton: true,
            tapToDismiss: false,
            positionClass: "toast-top-right",
        });
    }

    function validateInput(input) {
        const value = input.val().trim();
        if (value === '') {
            return false;
        } else {
            return true;
        }
    }

    // $(".valueChange").on("keyup mouseup", function() 
    // {
    //     $(".heading_check").prop('checked', false);
    // });
</script>

<?php require 'footer.php'; ?>