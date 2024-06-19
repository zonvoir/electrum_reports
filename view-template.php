<?php require 'header.php'; ?>

<div class="container">
    <?php if ($role['name'] == 'analyst'): ?>
        <?php require '401.php'; ?>
    <?php else: ?>
        <form id="calculation_template_form">
            <?php

            $layout_id = $_GET['layout_id'];

            $query = "SELECT * FROM layouts WHERE id = :layoutID";
            $statement = $conn->prepare($query);
            $statement->bindParam(':layoutID', $layout_id, PDO::PARAM_INT);
            $statement->execute();
            $layout = $statement->fetch(PDO::FETCH_ASSOC);

            $layoutTemplateID = $layout['layout_template_id'];

            $checkTemplateIdQuery  = "SELECT * FROM calculation_template_header WHERE template_id = :templateId";
            $checkStatement  = $conn->prepare($checkTemplateIdQuery );
            $checkStatement ->bindParam(':templateId', $layoutTemplateID);
            $checkStatement ->execute();
            $result = $checkStatement ->fetch();
            ?>
            <div class="row mt-4">
                <div class="col-md-2">
                    <label for="equipment_id">Equipment ID</label>
                    <input type="text" name="equipment_id" id="equipment_id" value="ECAL/WS/E02" class="form-control" />
                </div>
                <div class="col-md-2">
                    <label for="sensor_id">Sensor ID</label>
                    <input type="text" name="sensor_id" id="sensor_id" value="ECAL/WS/E02-DCV" class="form-control" />
                </div>
                <div class="col-md-2">
                    <label for="cal_date">Cal date</label>
                    <input type="date" name="cal_date" id="cal_date" value="2024-06-05" class="form-control" />
                </div>
                <div class="col-md-2">
                    <label for="res">Res</label>
                    <input type="text" name="res" id="res" value="<?php echo $result ? $result['res'] : ''; ?>" class="form-control getCertificateData" />
                </div>
                <div class="col-md-2">
                    <label for="x">X</label>
                    <input type="text" name="x" value="<?php echo $result ? $result['x'] : ''; ?>" id="x" class="form-control" />
                    <input type="hidden" name="ref_uncert" id="ref_uncert" class="form-control" />
                </div>
                <div class="col-md-2 hide">
                    <label for="range_min">Range</label>
                    <input type="number" id="range_min" class="form-control" placeholder="Min" value="0" />
                </div>
                <div class="col-md-2 hide">
                    <label for="range_min"></label>
                    <input type="number" id="range_max" class="form-control" placeholder="Max" value="0" />
                </div>
                <div class="col-md-2 hide">
                    <label for="x_split_no">X (split no)</label>
                    <input type="text" id="x_split_no" class="form-control" />
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
                    <input type="text" name="equipment_name" id="equipment_name" value="<?php echo $result ? $result['equipment_name'] : ''; ?>" class="form-control" readonly />
                </div>
                <div class="col-md-4">
                    <label for="brand">Brand</label>
                    <input type="text" name="brand" id="brand" value="<?php echo $result ? $result['brand'] : ''; ?>" class="form-control" readonly />
                </div>
                <div class="col-md-4">
                    <label for="serial_no">Serial #</label>
                    <input type="text" name="serial_no" id="serial_no" value="<?php echo $result ? $result['serial_no'] : ''; ?>" class="form-control" readonly />
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-4">
                    <label for="unit_ref">Unit ref</label>
                    <input type="text" name="unit_ref" id="unit_ref" value="<?php echo $result ? $result['unit_ref'] : ''; ?>" class="form-control" readonly />
                </div>
                <div class="col-md-4">
                    <label for="resolution_ref">Resolution ref</label>
                    <input type="text" name="resolution_ref" id="resolution_ref" value="<?php echo $result ? $result['resolution_ref'] : ''; ?>" class="form-control" readonly />
                </div>
                <div class="col-md-4">
                    <label for="cal_date_2">Cal Date</label>
                    <input type="date" name="cal_date_2" id="cal_date_2" value="<?php echo $result ? $result['cal_date_2'] : ''; ?>" class="form-control" readonly />
                </div>
            </div>
            <div class="row mt-2 mb-4">
                <div class="col-md-4">
                    <label for="C1">C1</label>
                    <input type="text" name="C1" id="C1" value="<?php echo $result ? $result['C1'] : ''; ?>" class="form-control" readonly />
                </div>
                <div class="col-md-4">
                    <label for="C2">C2</label>
                    <input type="text" name="C2" id="C2" value="<?php echo $result ? $result['C2'] : ''; ?>" class="form-control" readonly />
                </div>
                <div class="col-md-4">
                    <label for="C3">C3</label>
                    <input type="text" name="C3" id="C3" value="<?php echo $result ? $result['C3'] : ''; ?>" class="form-control" readonly />
                </div>
                <div class="col-md-4">
                    <label for="C4">C4</label>
                    <input type="text" name="C4" id="C4" value="<?php echo $result ? $result['C4'] : ''; ?>" class="form-control" readonly />
                </div>
                <div class="col-md-4">
                    <label for="C5">C5</label>
                    <input type="text" name="C5" id="C5" value="<?php echo $result ? $result['C5'] : ''; ?>" class="form-control" readonly />
                </div>
            </div>
            <hr />
            <div class="row mt-2">
                <?php
                // $querySiRefEqInfo = "SELECT * FROM si_ref_eq_info GROUP BY unit";
                // $statementSiRefEqInfo = $conn->prepare($querySiRefEqInfo);
                // $statementSiRefEqInfo->execute();
                // $siRefEqInfos = $statementSiRefEqInfo->fetchAll(PDO::FETCH_ASSOC);
                ?>
                <div class="col-md-4">
                    <label for="unit_uuc">Unit UUC</label>
                    <select class="form-control" name="unit_uuc" id="unit_uuc">
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
                    <input type="number" name="resolution_uuc" id="resolution_uuc" class="form-control" value="<?php echo $result ? $result['resolution_uuc'] : ''; ?>" />
                </div>
            </div>
            <div class="mt-4">
                <?php

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
                        WHERE  h.level IN ($in) AND h.layout_id = ? AND h.layout_template_id = ? 
                        ORDER BY h.id, h.parent_id";

                $statement1 = $conn->prepare($query1);
                // $params = array_merge($hLevel, [$layoutID, $layoutTemplateID]);
                $params = [...$hLevel, $layout_id, $layoutTemplateID];
                $statement1->execute($params);
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

                echo '<div class="table-responsive">';
                echo '<table id="template-table" class="table table-bordered">';
                $count = 0;
                $lastRow = [];
                foreach ($rows as $key => $row) {
                    $count = 0;
                    echo '<tr>';
                    foreach ($row as $heading) {
                        $hideClass = $heading['column_type']=='FUNCTION' ? 'hide1' : '';
                        echo '<th class="p-1 text-nowrap text-center '.$hideClass.'" colspan="'.$heading['colspan'].'">';
                        echo $heading['title'];
                        echo '<input class="hide heading_check" data-data="'.htmlspecialchars(json_encode($heading)).'" type="checkbox" />';
                        echo '</th>';
                        $count++;
                    }
                    echo '<th>&nbsp;</th>';
                    echo '</tr>';
                    if ($key == count($rows)) {
                        $lastRow = $row;
                    }
                }

                $queryCalculationTemplate = "SELECT * FROM calculation_template WHERE template_id = :templateID";
                $statementCalculationTemplate = $conn->prepare($queryCalculationTemplate);
                $statementCalculationTemplate->bindParam(':templateID', $layoutTemplateID, PDO::PARAM_INT);
                $statementCalculationTemplate->execute();
                $analyses = $statementCalculationTemplate->fetchAll(PDO::FETCH_ASSOC);
                
                $totalEntries = count($analyses);
                if ($totalEntries > 0) {
                    $columns = count($row);
                    $rows1 = 0; 
                    $firstTitle = $analyses[0]['title'];
                    foreach ($analyses as $analysis) {
                        if($analysis['title'] !=$firstTitle){
                            break;
                        }
                        $rows1++;
                    }
    
                    for ($i = 0; $i < $rows1; $i++) {
                        echo '<tr>';
                            for ($j = 0; $j < $columns; $j++) {
                                $hideClass = $row[$j]['column_type']=='FUNCTION' ? 'hide1' : '';
                                echo '<td class="'.$hideClass.'" colspan="' . $row[$j]['colspan'] . '">';
                                    $currentIndex = $j * $rows1 + $i;
                                    if ($currentIndex < $totalEntries) {
                                        if ($row[$j]['column_type'] == "FUNCTION") {
                                            echo '<textarea name="title[' . $row[$j]['id'] . '][]" class="form-control input_val_' . $row[$j]['id'] . '" style="resize:none;" readonly rows="3">'.$analyses[$currentIndex]['title_value'].'</textarea>';
                                        } else {
                                            echo '<textarea name="title[' . $row[$j]['id'] . '][]" class="form-control input_val_' . $row[$j]['id'] . ' input-validation" style="resize:none;" rows="3">'.$analyses[$currentIndex]['title_value'].'</textarea>';
                                        }
                                    } else {
                                        echo '&nbsp;';
                                    }
                                echo '</td>';
                            }
                            echo '<td class="align-middle"><button class="btnDeleteRow border-1" type="button">&times;</button></td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr>';
                    for ($i = 0; $i <= $count - 1; $i++) {
                        $hideClass = $row[$i]['column_type']=='FUNCTION' ? 'hide1' : '';
                        echo '<td class="p-1 '.$hideClass.'" colspan="' . $row[$i]['colspan'] . '">';
                            if ($row[$i]['column_type'] == "FUNCTION") {
                                echo '<textarea name="title[' . $row[$i]['id'] . '][]" class="form-control input_val_' . $row[$i]['id'] . '" style="resize:none;" readonly rows="3"></textarea>';
                            } else {
                                echo '<textarea name="title[' . $row[$i]['id'] . '][]" class="form-control input_val_' . $row[$i]['id'] . ' input-validation" style="resize:none;" rows="3"></textarea>';
                            }
                        echo '</td>';
                    }
                    echo '<td class="align-middle"><button class="btnDeleteRow border-1" type="button" disabled>&times;</button></td>';
                    echo '</tr>';
                }

                echo '</table>';
                echo '</div>';
                echo '<div class="mb-5 mt-5" style="border-left:0 !important; border-right:0 !important">
                            <button onclick="calculate();" class="btn btn-primary btnCalulate" type="button">Calculate & Save <i class="fa fa-spinner fa-spin" style="display:none;"></i></button>
                            <a href="analysis.php?layout_id='.$layout_id.'" class="btn btn-primary" type="button" target="_blank">Analysis</a>
                            <button class="btn btn-primary float-end btnAddRow" type="button"><i class="fa-solid fa-plus"></i> Add Row</button>                    
                        </div>';
                ?>
            </div>
        </form>
    <?php endif; ?>
</div>

<script>
    var layout_id = <?php echo $layout_id; ?>;
    var layout_template_id = <?php echo $layoutTemplateID; ?>;

    $('.input-validation').on('input', function() {
        var sanitizedValue = $(this).val().replace(/[^0-9.\n]/g, '');
        $(this).val(sanitizedValue);
    });

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

    $(function(){
        $('.getCertificateData').trigger('input');
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
                        $("#resolution_ref").val(response.data.split_no);
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
                                            if (index==0) {
                                                $("#ref_uncert").val(row.uncert);
                                            }
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
                    var unit_uuc = $("#unit_uuc").val();
                    var unit_ref = $("#unit_ref").val() == 'Meter' ? 'm' : 'm';
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
                    var unit_ref = $("#unit_ref").val() == 'Meter' ? 'm' : 'm';
                    var unit_uuc = $("#unit_uuc").val();
                    // var resolution_ref = $("#resolution_ref").val() == 'Feet' ? 'ft' : 'ft';
                    // var resolution_uuc = $("#resolution_uuc").val();
                    var refUnitCon = false;
                    var refUnitConVal = [];
                    if (unit_ref != "" && unit_uuc != "") {
                        multipleArr.forEach(function(column) {
                            column.forEach(function(val) {
                                if (val == function_fields[heading_id] + '@@@') {
                                    refUnitCon = true;
                                } else if (isNaN(val) && val.indexOf('@@@') > 0) {
                                    refUnitCon = false;
                                }
                                if (refUnitCon && val != function_fields[heading_id] + '@@@') {
                                    if (unit_ref == "m" && unit_uuc == "ft") {
                                        refUnitConVal.push(convertMetersToFeet(val));
                                    } else if (unit_ref == "ft" && unit_uuc == "m") {
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
        var resolution_uuc = $("#resolution_uuc").val();
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
        if (resolution_uuc == '') {
            toastrErrorMessage('Resolution UUC field is required!');
            return false;
        }

        $('.input-validation').each(function() {
            isValid = validateInput($(this));
        });

        if (isValid == false) {
            toastrErrorMessage('Please fill data entry fields is required!');
        } else {
            // $(".heading_check").trigger("click");

            $('.heading_check').each(function() {
                var heading = JSON.parse($(this).attr('data-data'));
                var column_type = heading.column_type;
                if (column_type === 'DATA') {
                    $(this).click();
                }
            });

            $('.heading_check').each(function() {
                var heading = JSON.parse($(this).attr('data-data'));
                var column_type = heading.column_type;
                if (column_type !== 'DATA') {
                    $(this).click();
                }
            });

            var formData = new FormData($("#calculation_template_form")[0]);
            formData.append('action', 'storeCalculationFormData');
            formData.append('layout_id', layout_id);
            formData.append('template_id', layout_template_id);
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
</script>

<?php require 'footer.php'; ?>
