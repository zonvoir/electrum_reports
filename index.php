<?php
require 'header.php';
?>
<body>
<form action="report.php" method="post">
    <style>
        .alert-title {
            font-size: 15px !important;
        }
        .alert-container {
            width: 20em;
            height: 10em;
            margin: 0 auto;
            /* Center horizontally */
            position: relative;
            /* Position relative to the viewport */
            top: 50%;
            /* Move down by 50% of the viewport height */
            transform: translateY(-50%);
            /* Center vertically */
        }
        .form-floating>.form-control,
        .form-floating>.form-control-plaintext {
            padding: 0;
        }
        .form-floating>label {
            position: absolute;
            top: -14px;
            left: -5px;
            font-size: 12px;
        }
        .form-floating>.form-control,
        .form-floating>.form-control-plaintext,
        .form-floating>.form-select {
            height: calc(2rem rem + calc(var(--bs-border-width) * 2));
            min-height: 2.2rem;
        }
        .form-floating>.form-control-plaintext:focus,
        .form-floating>.form-control-plaintext:not(:placeholder-shown),
        .form-floating>.form-control:focus,
        .form-floating>.form-control-plaintext:focus,
        .form-floating>.form-control-plaintext:not(:placeholder-shown),
        .form-floating>.form-control:focus,
        .form-floating>.form-control:not(:placeholder-shown) {
            padding-top: 11px;
            padding-left: 6px;
            padding-bottom: 1px;
        }
        .fa-circle-plus {
            color: green;
            cursor: pointer;
        }
        .bg-body-tertiary {
            --bs-bg-opacity: 1;
            background-color: #013e6d !important;
        }
        #preview_title {
            padding: 10px 0;
        }
        .table input {
            padding: 0;
            border: 0px;
            cursor: pointer;
        }
        .table th, td {
            cursor: pointer;
        }
        .table input:focus {
            border-color: lightblue !important;
            /* Change border color on focus */
        }
        :focus-visible {
            outline: 2px solid red;
            /* Example focus style */
        }
        .copy-color-1 {
            background-color: #b3ffb3;
        }
        .hilight {
            background-color: #ffe033;
        }
        .result-color {
            background-color: #e6c300;
        }
    </style>

    <?php
    require 'navigation.php';
    ?>

    <div class="container">
        <div class="row">
            <div class="offset-md-2 col-md-8" style="padding-top: 30px;">
                <div class="mb-3 row">
                    <div class="col">
                        <div class="row">
                            <label for="certificate_type" class="col-sm-3 col-form-label">Certificate Type </label>
                            <div class="col-sm-8" style="padding-right: 0;">
                                <?php
                                $query = "SELECT id, certificate_name FROM certificate_types";
                                // Execute the query
                                $stmt = $conn->query($query);
                                if ($stmt) {
                                ?>
                                    <select class="form-select form-select-sm" aria-label="" id="certificate_id" name="certificate_id">
                                        <option value="">Select the Certificate</option>
                                        <?php
                                        $certificateData = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                        foreach ($certificateData as $certificate) {
                                            $cerficateId = $certificate['id'];
                                            $certificateName = $certificate['certificate_name'];
                                            echo "<option value=\"$cerficateId\">$certificateName</option>";
                                        }
                                        ?>
                                    </select>
                                <?php
                                } else {
                                    echo "<option>Error Loading Certificates</option>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row text-center">
                    <h2 id="preview_title"></h2>
                </div>

                <div class="mb-3 row">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control form-control-sm" id="index_no" name="index_no" maxlength="3" required placeholder=" ">
                                    <label for="index_no" class="form-label" id="">Index</label>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-floating">
                                    <input type="text" class="form-control form-control-sm" id="certificate_no" name="certificate_no" placeholder=" ">
                                    <label for="make_input" class="form-label">Certificate Ref</label>
                                </div>
                            </div>
                            <div class="col-5">
                                <div class="row">
                                    <label for="date" class="col-sm-2 col-form-label">Date</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control form-control-sm dpicker" id="date" name="date" placeholder="">
                                    </div>
                                    <div class="col-sm-2" style="font-size: 20px;">
                                        <i class="fa-solid fa-calendar-days"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-3 row" style="margin-top: 10px;">
                    <label for="customer" class="col-sm-3 col-form-label">Customer <i class="fa-solid fa-circle-plus"></i></label>
                    <div class="col-sm-8" style="padding-right: 0;">
                        <?php
                        $query = "SELECT company, address_1, address_2 FROM customers";
                        // Execute the query
                        $stmt = $conn->query($query);
                        if ($stmt) {
                        ?>
                            <select class="form-select form-select-sm" aria-label="" id="customer" name="customer">
                                <option></option>
                                <?php
                                $data = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all data into an associative array
                                foreach ($data as $row) {
                                    $companyName = $row['company'];
                                    $addressA = $row['address_1'];
                                    $address2 = $row['address_2'];
                                    echo "<option value=\"$companyName - Address: $addressA, $address2\">$companyName - Address: $addressA, $address2</option>";
                                }
                                ?>
                            </select>
                        <?php
                        } else {
                            echo "<option>Error Loading Layout</option>";
                        }
                        ?>
                    </div>
                </div>

                <div class="card" style="padding:10px;margin-bottom:15px;">
                    <div class="mb-3 row">
                        <h6 class="col-sm-3">Instrument</h6>
                    </div>
                    <div class="mb-3 row">
                        <label for="layout" class="col-sm-3 col-form-label">Layout <i class="fa-solid fa-circle-plus"></i></label>
                        <div class="col-sm-9">
                            <?php
                            $query = "SELECT id, layout_name FROM layouts";
                            // Execute the query
                            $stmt = $conn->query($query);
                            if ($stmt) {
                            ?>
                                <select class="form-select form-select-sm" aria-label="" id="layout" name="layout">
                                    <option value="">Select the Layout</option>
                                    <?php
                                    $layoutData = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($layoutData as $layout) {
                                        $layoutId = $layout['id'];
                                        $layoutName = $layout['layout_name'];
                                        echo "<option value=\"$layoutId\">$layoutName</option>";
                                    }
                                    ?>
                                </select>
                            <?php
                            } else {
                                echo "<option>Error Loading Layout</option>";
                            }
                            ?>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-sm-3"></div>
                        <div class="col-sm-9">
                            <div class="row">
                                <div class="col-3">
                                    <div class="form-floating">
                                        <input type="text" class="form-control form-control-sm" id="layout_profile_id" name="layout_profile_id" placeholder=" ">
                                        <label for="id_input" class="form-label">ID</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-floating">
                                        <input type="text" class="form-control form-control-sm" id="layout_profile_make" name="layout_profile_make" placeholder=" ">
                                        <label for="make_input" class="form-label">Make</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-floating">
                                        <input type="text" class="form-control form-control-sm" id="layout_profile_model" name="layout_profile_model" placeholder=" ">
                                        <label for="model_input" class="form-label">Model</label>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-floating">
                                        <input type="text" class="form-control form-control-sm" id="layout_profile_sn" name="layout_profile_sn" placeholder=" ">
                                        <label for="sn_input" class="form-label">S/N</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-3 row">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-3">
                                <div class="form-floating">
                                    <input type="text" class="form-control form-control-sm" id="resolution" name="resolution" placeholder=" ">
                                    <label for="resolution" class="form-label" id="">Resolution</label>
                                </div>
                            </div>
                            <div class="col-5">
                                <div class="form-floating">
                                    <input type="text" class="form-control form-control-sm" id="range" name="range" placeholder=" ">
                                    <label for="range" class="form-label">Range</label>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-floating">
                                    <input type="text" class="form-control form-control-sm" id="unit" name="unit" placeholder=" ">
                                    <label for="unit" class="form-label">Unit</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="table-responsive" style="overflow-x: auto;">
                            <table class="table" style="font-size:10px; max-width: 100%;" id="show_template"></table>
                        </div>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="equipment_used" class="col-sm-3 col-form-label">Equipment Used</label>
                    <div class="col-sm-9">
                        <?php
                        $query = "SELECT id, equipment_id, standard, cal_date,name,statement,certificate_no_new,chain,lab FROM traceability_table";
                        // Execute the query
                        $stmt = $conn->query($query);
                        if ($stmt) {
                        ?>
                            <select class="form-select form-select-sm" aria-label="" id="equipment_used" name="equipment_used" class="select2">
                                <option></option>
                                <?php
                                $traceabilityData = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($traceabilityData as $traceability) {
                                    $id = $traceability['id'];
                                    $equipmentId = $traceability['equipment_id'];
                                    $calDate = $traceability['cal_date'];
                                    echo "<option value=\"$equipmentId\">$equipmentId</option>";
                                }
                                ?>
                            </select>
                        <?php
                        } else {
                            echo "<option>Error Loading Layout</option>";
                        }
                        ?>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="date_place_calibration" class="col-sm-3 col-form-label">Date and place of Calibration</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control form-control-sm dpicker" id="date_place_calibration" name="date_place_calibration" placeholder="">
                    </div>
                    <div class="col-sm-1" style="font-size: 20px;">
                        <i class="fa-solid fa-calendar-days"></i>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="method_of_test" class="col-sm-3 col-form-label">Reference Method </label>
                    <div class="col-sm-9">
                        <textarea class="form-control" id="method_of_tests" name="method_of_tests" rows="3"></textarea>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="traceability" class="col-sm-3 col-form-label">Standard
                        And Traceability </label>
                    <div class="col-sm-9">
                        <textarea class="form-control" id="traceability" name="traceability" rows="3"></textarea>
                    </div>
                </div>

                <div class="mb-3 row">
                    <div class="col">
                        <div class="row">
                            <label for="ambient_conditions" class="col-sm-3 col-form-label">Ambient Conditions</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control form-control-sm" id="ambient" name="ambient" placeholder="">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-3 row">
                    <div class="col">
                        <div class="row">
                            <label for="ambient_conditions" class="col-sm-3 col-form-label">Layouts</label>
                            <div class="col-sm-9">
                                <i id="editLayout" class="fa-solid fa-pen-to-square" style="margin-left: 10px;margin-right: 10px;"></i><i class="fa-solid fa-circle-plus" id="addLayout" data-bs-toggle="modal" data-bs-target="#addLayoutModal"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-3 row">
                    <div class="col">
                        <div class="row">
                            <div class="mb-3 row">
                                <label for="instrument_received_date" class="col-sm-3 col-form-label">Instrument Received Date</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control form-control-sm dpicker" id="instrument_received_date" name="instrument_received_date" placeholder="">
                                </div>
                                <div class="col-sm-1" style="font-size: 20px;">
                                    <i class="fa-solid fa-calendar-days"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-3 row">
                    <div class="col">
                        <div class="row">
                            <label for="date_place_calibration" class="col-sm-6 col-form-label">Date and place of Calibration</label>
                            <div class="col-sm-5">
                                <input type="text" class="form-control form-control-sm dpicker" id="date_place_calibration" name="date_place_calibration" placeholder="">
                            </div>
                            <div class="col-sm-1" style="font-size: 20px;">
                                <i class="fa-solid fa-calendar-days"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="mb-3 row input-group">
                            <label for="due_date" class="col-sm-5 col-form-label" style="text-align: right;">Due Date</label>
                            <div class="col-sm-6 ">
                                <input type="text" class="form-control form-control-sm dpicker" id="due_date" name="due_date" placeholder="">

                            </div>
                            <div class="col-sm-1" style="font-size: 20px;">
                                <i class="fa-solid fa-calendar-days"></i>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col">
                        <div class="row">
                            <div class="col-sm-3"><button class=" btn btn-primary btn-sm" type="button" id="loadTraceabilityStatement"><i class="fa-solid fa-retweet"></i> Reload</button></div>
                        </div>
                    </div> -->
                </div>

                <div class="mb-3 row">
                    <label for="pi" class="col-sm-3 col-form-label">Preliminary Investigations</label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control form-control-sm" id="pi" name="pi" placeholder="">
                    </div>
                </div>
            </div>

            <!-- <div class="col-md-6 text-center" style="background-color: #FCFEFC;">
                <img src="./assets/loading.gif" id="loading_preview" width="200px" style="margin-top: 200px;" />
                <p style="color: dimgrey;">Preview</p>
                <div id="preview" style="display: none;">
                    <h2 id="preview_title"></h2>
                    <table class="table" style="text-align: left;">
                        <tr>
                            <td class="text-start">Index No: <span id="show_index"></span></td>
                            <td class="text-start">Certificate Ref: <span id="show_certificate_no"></span></td>
                            <td class="text-start">Date: <span id="show_date"></span></td>
                            <td class="text-start">Pg 1 of 2</td>
                        </tr>
                        <tr>
                            <td class="text-start">Customer </td>
                            <td colspan="3" style="text-align: left;"><span id="show_customer"></span></td>
                        </tr>
                        <tr>
                            <td class="text-start">Unit under Test</td>
                            <td colspan="3">
                                <span id="show_layout_profile"></span>
                                <table style="width: 100%;text-align: left;display: none;" id="profile_data_table">
                                    <tr>
                                        <td>ID <span id="show_layout_profile_id"></span></td>
                                        <td>Make <span id="show_layout_profile_make"></span></td>
                                        <td>Model <span id="show_layout_profile_model"></span></td>
                                        <td>S/N <span id="show_layout_profile_sn"></span></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-start">Method of Test</td>
                            <td colspan="3" class="text-start">
                                <p id="show_method_of_tests"></p>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-start">Standard And Traceability</td>
                            <td colspan="3">
                                <p class="text-start" id="show_traceability"></p>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-start">Ambient Conditions</td>
                            <td colspan="3" class="text-start"> <span id="show_ambient"></span></td>
                        </tr>
                        <tr>
                            <td class="text-start">Preliminary Investigations</td>
                            <td colspan="3" class="text-start"> <span id="show_pi"></span></td>
                        </tr>
                        <tr>
                            <td class="text-start">Instrument Received Date</td>
                            <td colspan="3" class="text-start"> <span id="show_instrument_received_date"></span></td>
                        </tr>
                        <tr>
                            <td class="text-start">Date Place Calibration</td>
                            <td colspan="3" class="text-start"> <span id="show_date_place_calibration"></span></td>
                        </tr>
                        <tr>
                            <td class="text-start">Due Date</td>
                            <td colspan="3" class="text-start"> <span id="show_due_date"></span></td>
                        </tr>
                        <tr>
                            <td class="text-start">Result</td>
                            <td colspan="3" class="text-start"> </td>
                        </tr>
                        <tr>
                            <td colspan="6">
                                <table style="width: 100%;" id="result-preview">

                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
            </div> -->
        </div>
    </div>
</form>
<?php
require 'modals.php';
?>

<script>
$(document).ready(function() {
    $(document).on('click', '.td-text', function() {
        var cellText = $(this).text();
        var cellId = $(this).attr('data-value-id');
        console.log(cellId);
        $.ajax({
            type: 'POST',
            url: './functions/add-title.php',
            dataType: 'json',
            data: {
                action: 'getCellValues',
                cell_id: cellId
            },
            success: function(response) {
                if (response.length > 0) {
                    var values = response.map(function(item) {
                        return item.value;
                    }).join(', ');
                    $('#cell-entered-values').val(values);
                    $('#cellValueModalLabel').html("Update Call Value");
                    $('.add-template-name').removeClass('btn-primary');
                    $('.add-template-name').addClass('btn-warning');
                    $('.add-template-name').text("Update Values");
                    $('.add-template-name').addClass("btnEditCellValues");
                    $('.btnEditCellValues').addClass("add-template-name");
                    $('.btnEditCellValues').attr("data-cell-id", cellId);
                    $('#cellValueModal').modal('show');
                } else {
                    // Handle the error case
                    console.error('Error: ' + response.message);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle AJAX error
                console.error('AJAX Error:', textStatus, errorThrown);
            }
        });
    });

    $(document).on('click', '.btnEditCellValues', function() {
        var value = $('#cell-entered-values').val();
        const average = calculateAverage(value);
        var cellId = $(this).attr('data-cell-id');
        $.ajax({
            type: 'POST',
            url: './functions/add-title.php',
            dataType: 'json',
            data: {
                action: 'updateCellValue',
                cell_id: cellId,
                cell_value: average
            },
            success: function(response) {
                if (response.status === 'success') {
                    // Handle success case
                    toastr.success(average + ' Value Saved', 'Success!')
                    loadTemplate($('#layout').val());
                } else {
                    // Handle the error case
                    console.error('Error: ' + response.message);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle AJAX error
                console.error('AJAX Error:', textStatus, errorThrown);
            }
        });
    });


    // Function to hide a column
    function hideColumn(columnIndex) {

        // Get all cells in the specified column
        var cells = $("tr.data-row td:nth-child(" + (columnIndex + 1) + ")");

        // Hide all cells in the column
        cells.hide();
    }

    // Function to show a column
    function showColumn(columnIndex) {
        // Get all cells in the specified column
        var cells = $("tr.data-row td:nth-child(" + (columnIndex + 1) + ")");

        // Show all cells in the column
        cells.show();
    }

    // Example usage:
    hideColumn(0); // Hide the third column (index starts from 0)
    // showColumn(2); // Show the third column again

    var clickedCount = 0;
    var firstColumnValues = [];
    var secondColumnValues = [];
    var correctionValues = [];
    var valueCounts = [];

    // $(document).on('click', '.header-cell', function() {
    //     if (clickedCount === 0) {
    //         clickedCount = 1;
    //         toastr.success('First dataset copied!', 'Success!')
    //     } else if (clickedCount === 1) {
    //         clickedCount = 2;
    //         toastr.success('Second dataset copied!', 'Success!')
    //     }
    //     var columnIndex = $(this).index();
    //     var columnType = $(this).attr('column_type');
    //     var columnFunction = $(this).attr('column_function');
    //     var tdClass = columnFunction.toLowerCase();
    //     var templateId = $(this).attr('data-template-id');
    //     var headerId = $(this).attr('data-id');
    //     $("tr.data-row").each(function() {
    //         var cells = $(this).find("td");
    //         if (columnIndex < cells.length) {
    //             if (columnType === "DATA") {
    //                 var cellValue = cells.eq(columnIndex).text().trim();
    //                 if (cellValue !== "") {
    //                     if (clickedCount === 1) {
    //                         firstColumnValues.push(cellValue);
    //                         valueCounts.push(cells.attr('data-value-count'));
    //                     } else {
    //                         secondColumnValues.push(cellValue);
    //                         clickedCount = 2;
    //                     }
    //                 }
    //             } else if (columnType === "FUNCTION") {
    //                 console.log('td-class',tdClass);
    //                 clickedCount = 0;
    //                 switch (columnFunction) {
    //                     case "CORRECTION":
    //                         correctionValues = performCorrection(cells, columnIndex);
    //                         fillCellsInClickedColumn(columnIndex, correctionValues, templateId, headerId,tdClass);
    //                         firstColumnValues = [];
    //                         secondColumnValues = [];
    //                         break;
    //                     case "TS": //TEST STDEV
    //                         correctionValues = performStdev(cells, columnIndex);

    //                         //fillCellsInClickedColumn(columnIndex, correctionValues);
    //                         console.log("Correction Values STDEV:", correctionValues);

    //                         firstColumnValues = [];
    //                         secondColumnValues = [];
    //                         break;
    //                     case "TUC": // TEST UNIT CONVERTION
    //                         correctionValues = performStdev(cells, columnIndex);

    //                         //fillCellsInClickedColumn(columnIndex, correctionValues);
    //                         console.log("Correction Values STDEV:", correctionValues);

    //                         firstColumnValues = [];
    //                         secondColumnValues = [];
    //                         break;
    //                     case "TC": // TEST COUNT
    //                        // correctionValues = performStdev(cells, columnIndex);

    //                         fillCountIntoCells(columnIndex, valueCounts,tdClass);

    //                         firstColumnValues = [];
    //                         secondColumnValues = [];
    //                         valueCounts =[];
    //                         break;
    //                     case "RS": // REF STDEV
    //                         correctionValues = performStdev(cells, columnIndex);

    //                         //fillCellsInClickedColumn(columnIndex, correctionValues);
    //                         console.log("Correction Values STDEV:", correctionValues);

    //                         firstColumnValues = [];
    //                         secondColumnValues = [];
    //                         break;
    //                     case "RC": // REF COUNT
    //                         correctionValues = performStdev(cells, columnIndex);

    //                         //fillCellsInClickedColumn(columnIndex, correctionValues);
    //                         console.log("Correction Values STDEV:", correctionValues);

    //                         firstColumnValues = [];
    //                         secondColumnValues = [];
    //                         break;
    //                 }
    //             }
    //         }

    //         // Add CSS class based on clickedCount
    //         var cssClass = (clickedCount === 0) ? "copy-color-1" : (clickedCount === 1) ? "copy-color-2" : "result-color";
    //         cells.eq(columnIndex).addClass(cssClass);

    //         // Remove the classes after 3 seconds
    //         setTimeout(function() {
    //             cells.eq(columnIndex).removeClass("copy-color-1 copy-color-2 result-color");
    //         }, 3000);

    //     });

    //     console.log("1st Column Values f:", firstColumnValues);
    //     console.log("2nd Column Values f:", secondColumnValues);
    //     console.log("Correction Values f:", correctionValues);
    // });



    var clickCount = 0; // Initialize click count
    var firstColumnValues = [];
    var secondColumnValues = [];
    var valueCounts = [];
    $(document).on('click', '.header-cell', function() {
        var columnIndex = $(this).index();
        var columnType = $(this).attr("column_type");
        var columnFunction = $(this).attr("column_function");

        if (columnType === "DATA") {
            $("tr.data-row").each(function() {
                var cells = $(this).find("td");

                if (clickCount < 2) {
                    var cssClass = "hilight";
                    cells.eq(columnIndex).addClass(cssClass);
                    // Remove the classes after 3 seconds
                    setTimeout(function() {
                        cells.eq(columnIndex).removeClass("hilight");
                    }, 3000);
                } else {
                    var cssClass = "copy-color-1";
                    cells.eq(columnIndex).addClass(cssClass);
                    // Remove the classes after 3 seconds
                    setTimeout(function() {
                        cells.eq(columnIndex).removeClass("hilight");
                    }, 3000);
                }



                if (columnIndex < cells.length) {
                    var cellValue = cells.eq(columnIndex).text().trim();
                    if (cellValue !== "") {

                        if (clickCount === 0) {
                            firstColumnValues.push(cellValue);
                            valueCounts.push(cells.attr('data-value-count'));
                        } else {
                            secondColumnValues.push(cellValue);
                            valueCounts.push(cells.attr('data-value-count'));
                        }
                    }
                }
            });
            clickCount++; // Increment click count for next click
            if (clickCount === 4) {
                clickCount = 0; // Reset click count after the 3rd click to cycle the process
                firstColumnValues = []; // Reset firstColumnValues
                secondColumnValues = []; // Reset secondColumnValues
                valueCounts = [];
            }
        } else if (columnType === "FUNCTION") {
            if (columnFunction === "CORRECTION") {
                // Call the correction function and get the array of values
                var correctionValues = performCorrection(firstColumnValues, secondColumnValues);

                // Copy the values to <td> elements with the class 'data-td-class="correction"'
                $("td[data-td-class='correction']").each(function(index) {
                    if (index < correctionValues.length) {
                        $(this).text(correctionValues[index]);
                    }
                });
            } else if (columnFunction === "TC") {
                $("td[data-td-class='tc']").each(function(index) {
                    if (index < valueCounts.length) {
                        $(this).text(valueCounts[index]);
                    }
                });
            }
            // Add more conditions for other column functions if needed
        }


        console.log("firstColumnValues", firstColumnValues);
        console.log("secondColumnValues", secondColumnValues);
        console.log("valueCounts", valueCounts);
        console.log("clickCount", clickCount);
    });


    // Example correction function
    function correctionFunction() {
        // Your logic to generate or fetch correction values
        return [1, 2, 3, 4, 5]; // Example array of values
    }


    function fillCellsInClickedColumn(columnIndex, values, templateID, headerId, tdClass) {
        var rows = $("tr.data-row");


        rows.each(function(index) {

            var cells = $(this).find("td." + tdClass);
            if (columnIndex < cells.length && index < values.length) {
                var cell = cells.eq(columnIndex);
                console.log('values', values);
                var cellValue = Math.round(values[index]);
                var valueId = cell.attr('data-value-id'); // Get data-value-id attribute value
                var rowId = cell.attr('data-row-id'); // Get data-value-id attribute value
                cell.text(cellValue);

                //performe multiple value insert
                $.ajax({
                    type: 'POST',
                    url: './functions/add-title.php',
                    dataType: 'json',
                    data: {
                        action: 'addResultValue',
                        value_id: valueId,
                        value: cellValue,
                        template_id: templateID,
                        header_id: headerId,
                        row_id: rowId
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            // Handle success case
                            toastr.success(cellValue + ' Value Saved', 'Success!')
                            // loadTitles();
                        } else {
                            // Handle the error case
                            // console.error('Error: ' + response.message);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        // Handle AJAX error
                        console.error('AJAX Error:', textStatus, errorThrown);
                    }
                });

            }
        });

        console.log("Filled cells in the clicked column with valuesdddddd", values);
        correctionValues = [];
    }


    function performStdev(cells, columnIndex) {
        return correctionValues;
    }


    function performCorrection(firstColumnValues, secondColumnValues) {
        // Assuming firstColumnValues and secondColumnValues have the same length
        var numRows = firstColumnValues.length;
        var resultArray = [];

        // Loop through each row and perform the correction
        for (var i = 0; i < numRows; i++) {
            var correctionValue = parseFloat(firstColumnValues[i]) - parseFloat(secondColumnValues[i]);
            resultArray.push(correctionValue);
        }

        return resultArray;
    }

    function calculateAverage(str) {
        // Split the string by comma and filter out empty strings
        const numbers = str.split(',').filter(Boolean).map(Number);

        // Filter out the non-numeric values
        const filteredNumbers = numbers.filter(num => !isNaN(num));

        // Calculate the sum of the numbers
        const sum = filteredNumbers.reduce((acc, num) => acc + num, 0);

        // Calculate the average
        const average = sum / filteredNumbers.length;

        // Round the average to two decimal places
        const roundedAverage = Math.round(average * 100) / 100;

        return roundedAverage;
    }


    $(document).on('click', '.value-cell', function() {
        $('#cellValueModal').modal('show');
        $(this).addClass('active'); // Add 'active' class to the clicked input
    });

    $('#cellValueModal').on('hidden.bs.modal', function() {
        var value = $('#cell-entered-values').val();

        const average = calculateAverage(value);

        $('.value-cell.active').attr('cell-value-str', value); // Set value to the input with 'active' class
        $('.value-cell.active').val(average); // Set value to the input with 'active' class
        $('.value-cell.active').removeClass('active'); // Remove 'active' class
    });


    $('#equipment_used').select2();
    // $('.datepicker').datepicker({
    //     format: 'yyyy-mm-dd' // Set the desired date format
    // });

    function methosOfTests(selectedLayout) {
        $.ajax({
            type: 'GET',
            url: 'functions/get_method_of_test.php',
            data: {
                layout: selectedLayout
            },
            success: function(data) {
                // Update the method_of_test textarea with the retrieved data
                $('#method_of_tests').val(data.description);
            },
            error: function() {
                alert('Error loading method of test.');
            }
        });
    }


    function loadTemplate($layoutId) {
        $.ajax({
            type: 'GET',
            url: 'functions/get_template.php',
            data: {
                layout_id: $layoutId
            },
            success: function(data) {
                if (data === false) {
                    alert('No data found for the specified equipment and date.');
                } else {
                    $('#show_template').html(data);
                }
            },
            error: function() {
                alert('Error loading data.');
            }
        });
    }


    function loadTemplateForPreview($layoutId) {
        $.ajax({
            type: 'GET',
            url: 'functions/get_template_preview.php',
            data: {
                layout_id: $layoutId
            },
            success: function(data) {
                if (data === false) {
                    alert('No data found for the specified equipment and date.');
                } else {
                    $('#result-preview').html(data);
                }
            },
            error: function() {
                alert('Error loading data.');
            }
        });
    }

    function loadTraceabilityData(cal_date, equipment_id) {
        $.ajax({
            type: 'GET',
            url: 'functions/get_traceability.php',
            data: {
                cal_date: cal_date,
                equipment_id: equipment_id
            },
            success: function(data) {
                if (data === false) {
                    if ($('#date_place_calibration').val() != '') {
                        alert('No data found for the specified equipment and date.');
                    }

                } else {
                    // Update the traceability textarea with the retrieved data
                    $('#traceability').val(data.statement);
                    $('#show_traceability').html(data.statement);
                }
            },
            error: function() {
                alert('Error loading data.');
            }
        });
    }

    function reformatDate(inputDate) {
        const parts = inputDate.split('-');
        if (parts.length === 3) {
            // Rearrange the date components to 'YYYY-MM-DD'
            return parts[0] + '-' + parts[2] + '-' + parts[1];
        } else {
            return null; // Invalid date format
        }
    }


    $('#editLayout').on('click', function() {
        var layout = $("#layout").val();
        if (layout != "") {
            window.location.href = 'edit-layout.php?id=' + layout;
        } else {
            alert("Layout not selected!");
        }

    });

    $('#loadTraceabilityStatement').on('click', function() {
        const cal_date = $('#date_place_calibration').val().trim();
        var equipment_id = $('#equipment_used').val();
        //  const cal_date = '2022-08-11';
        loadTraceabilityData(cal_date, equipment_id);

    });
    $('#date_place_calibration').on('change', function() {
        const cal_date = $(this).val().trim();
        var equipment_id = $('#equipment_used').val();
        //  const cal_date = '2022-08-11';
        loadTraceabilityData(cal_date, equipment_id);

    });
    $('#equipment_used').on('change', function() {
        const cal_date = $('#date_place_calibration').val().trim();
        var equipment_id = $('#equipment_used').val();
        //  const cal_date = '2022-08-11';
        loadTraceabilityData(cal_date, equipment_id);

    });

    $('#layout').on('change', function() {
        var selectedLayout = $(this).val();
        // Make an AJAX request to get the method of test and traceability
        methosOfTests(selectedLayout);
        loadTemplate(selectedLayout);
        loadTemplateForPreview(selectedLayout);
    });
    // Watch for changes in the index_no input field
    $('#index_no').on('input', function() {
        var indexValue = $(this).val();
        // Update the show_index element with the value from index_no
        $('#show_index').text(indexValue);
    });
    $('#certificate_no').on('input', function() {
        var indexValue = $(this).val();
        // Update the show_index element with the value from index_no
        $('#show_certificate_no').text(indexValue);
    });
    $('#date').on('input', function() {
        var indexValue = $(this).val();
        // Update the show_index element with the value from index_no
        $('#show_date').text(indexValue);
    });
    $('#certificate_type').on('change', function() {
        var selectedOptionText = $(this).find('option:selected').text();
        // Update the element with the selected option text
        $('#preview_title').html(selectedOptionText);
    });


    $('#layout').on('change', function() {
        var selectedOptionText = $(this).find('option:selected').text();
        // Update the element with the selected option text
        $('#loading_preview').hide();
        $('#preview').fadeIn(400);
    });
    $('#layout_profile').on('input', function() {
        var indexValue = $(this).val();
        // Update the show_index element with the value from index_no
        $('#show_layout_profile').text(indexValue);
        $('#profile_data_table').fadeIn(400);
    });
    $('#layout_profile_id').on('input', function() {
        var indexValue = $(this).val();
        // Update the show_index element with the value from index_no
        $('#show_layout_profile_id').text(indexValue);
    });
    $('#layout_profile_make').on('input', function() {
        var indexValue = $(this).val();
        // Update the show_index element with the value from index_no
        $('#show_layout_profile_make').text(indexValue);
    });
    $('#layout_profile_model').on('input', function() {
        var indexValue = $(this).val();
        // Update the show_index element with the value from index_no
        $('#show_layout_profile_model').text(indexValue);
    });
    $('#layout_profile_sn').on('input', function() {
        var indexValue = $(this).val();
        // Update the show_index element with the value from index_no
        $('#show_layout_profile_sn').text(indexValue);
    });
    $('#method_of_tests').on('input', function() {
        var indexValue = $(this).val();
        // Update the show_index element with the value from index_no
        $('#show_method_of_tests').text(indexValue);
    });

    $('#traceability').on('input', function() {
        var indexValue = $(this).val();
        // Update the show_index element with the value from index_no
        $('#show_traceability').text(indexValue);
    });
    $('#ambient').on('input', function() {
        var indexValue = $(this).val();
        // Update the show_index element with the value from index_no
        $('#show_ambient').text(indexValue);
    });
    $('#pi').on('input', function() {
        var indexValue = $(this).val();
        // Update the show_index element with the value from index_no
        $('#show_pi').text(indexValue);
    });
    $('#customer').on('input', function() {
        var indexValue = $(this).val();
        $('#show_customer').text(indexValue);
    });
    $('#instrument_received_date').on('input', function() {
        var indexValue = $(this).val();
        $('#show_instrument_received_date').text(indexValue);
    });

    $('#date_place_calibration').on('input', function() {
        var indexValue = $(this).val();
        $('#show_date_place_calibration').text(indexValue);
    });

    $('#due_date').on('input', function() {
        var indexValue = $(this).val();
        $('#show_due_date').text(indexValue);
    });

    $(document).on('click', '#addRaw', function(e) {
        var layout = $(this).attr('data-layout');
        var level = $(this).attr('data-level');
        var template = $(this).attr('data-template');
        var inputElements = document.querySelectorAll(".input_" + level);
        var ids = [];
        var values = [];
        var strs = [];
        // Iterate through the elements and push their values to the array
        inputElements.forEach(function(element) {
            values.push(element.value);
            ids.push(element.getAttribute('data-id'));
            strs.push(element.getAttribute('cell-value-str'));
        });
        // console.log(ids);
        // console.log(values);
        $.ajax({
            type: 'POST',
            url: 'functions/add-title.php',
            data: {
                level: level,
                values: values,
                ids: ids,
                strs: strs,
                template_id: template,
                action: 'addHedingValues'
            },
            success: function(data) {
                loadTemplate(layout);
                loadTemplateForPreview(layout);
            },
            error: function() {
                alert('Error loading data.');
            }
        });
    });

    $(document).on('click', '#clearRaws', function(e) {
        var layout = $(this).attr('data-layout');
        var template = $(this).attr('data-template');
        $.ajax({
            type: 'POST',
            url: 'functions/add-title.php',
            data: {
                layout: layout,
                template_id: template,
                action: 'removeHedingValues'
            },
            success: function(data) {
                loadTemplate(layout);
                loadTemplateForPreview(layout);
            },
            error: function() {
                alert('Error loading data.');
            }
        });
    });
}); //end
</script>

<script>
    $('#addLayout').on('click', function(e) {
        e.preventDefault();

        // Get templates using the provided function
        getTemplates().then(function(data) {
            // Check if response has a status of "success"
            if (data.status === 'success') {
                // Clear existing options
                $('#templatesForLayout').empty();

                // Add an option for selecting a template
                $('#templatesForLayout').append('<option value="">Select the template</option>');

                // Loop through the retrieved templates
                for (var i = 0; i < data.templates.length; i++) {
                    var template = data.templates[i];
                    var option = $('<option></option>');
                    option.attr('value', template.id);
                    option.text(template.template_name); // Adjusted to use the correct property name
                    $('#templatesForLayout').append(option);
                }

                // Now open the modal
                $('#addLayoutModal').modal('show');

                // Load layouts into the select
                // loadLayouts();
            } else {
                // Handle error case
                alert('Error retrieving templates: ' + data.error);
            }
        });
    });

    $('#addLayoutModal').on('hidden.bs.modal', function() {
        // Load layouts into the select after the modal is fully shown
        loadLayouts();
    });

    function getTemplates() {
        // Create a deferred object
        var deferred = $.Deferred();

        $.ajax({
            type: 'POST',
            url: 'functions/add-title.php',
            data: {
                action: 'loadTemplates'
            },
            dataType: 'json', // Ensure that the response is parsed as JSON
            success: function(data) {
                // Resolve the deferred object with the response data
                deferred.resolve(data);
            },
            error: function() {
                // Reject the deferred object with an error message
                deferred.reject('Error loading data.');
            }
        });

        // Return the promise of the deferred object
        return deferred.promise();
    }


    function loadLayouts() {
        $.ajax({
            type: 'POST',
            url: 'functions/add-title.php',
            data: {
                action: 'loadLayouts'
            },
            dataType: 'json',
            success: function(data) {
                console.log(data); // Log the received data to the console

                // $('#layout').empty();

                // Check if response has a status of "success"
                if (data.status === 'success') {
                    // Check if layouts array exists and has data
                    if (Array.isArray(data.templates) && data.templates.length > 0) {
                        // Loop through the retrieved layouts
                        for (var i = 0; i < data.templates.length; i++) {
                            console.log(data.templates[i]);
                            var layout = data.templates[i];
                            var option = $('<option value=""></option>');
                            option.attr('value', layout.id);
                            option.text(layout.layout_name); // Assuming the column name is layout_name
                            $('#layout').append(option);
                        }
                    } else {
                        console.log('No layouts data or empty array.');
                    }
                } else {
                    // Handle error case
                    console.log('Error retrieving layouts: ' + data.error);
                }
            },
            error: function(xhr, status, error) {
                console.log('AJAX Error:', status, error);
            },

            error: function() {
                // Handle error case
                console.log('Error loading layouts.');
            }
        });
    }
</script>
<script>
    function myFunction() {
        alert();
    }
</script>

<?php
require 'footer.php';
?>