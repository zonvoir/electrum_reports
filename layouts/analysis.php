<?php
require_once('../database.php');
$database = new Database();
$conn = $database->getConnection();

require '../theme/layout_header.php';
require '../theme/layout_navigations.php';

?>

<style>
    .fa-gear {
        cursor: pointer;
        color: #d6630a;
        margin-right: 5px;
    }

    .fa-eye {
        cursor: pointer;
        color: #004dff;
    }
</style>

<div class="content">
    <div class="toast align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
        <div class="d-flex">
            <div class="toast-body">
                An error occurred. Please try again later.
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
    <nav class="mb-2" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="#!">Layout</a></li>
            <li class="breadcrumb-item"><a href="#!">Analysis</a></li>
        </ol>
    </nav>
    <div class="mb-5">
        <div class="row justify-content-between align-items-end g-3 mb-5">
            <div class="col-12 col-sm-auto col-xl-8">
                <h2 class="mb-0">Analysis</h2>
            </div>
            <div class="col-12 col-sm-auto col-xl-2">
                <div class="d-flex">
                    <button class="btn btn-primary px-5 w-100 text-nowrap request-btn">Request</button>
                </div>
            </div>
        </div>


        <div class="row g-5">
            <div class="col-xl-2">
                <div class="row gx-3 gy-4">
                    <div class="col-sm-6 col-md-12">
                        <div class="form-floating">
                            <input class="form-control" id="parameter" type="text" placeholder="Parameter" />
                            <label for="parameter">Parameter</label>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-12 mt-0">
                        <label class="form-label" for="eq_name">Equipment Name</label>
                        <input class="form-control form-control-sm" id="eq_name" type="text" />
                    </div>

                    <div class="col-sm-6 col-md-12 mt-0">
                        <label class="form-label" for="unit_ref">Unit Ref</label>
                        <input class="form-control form-control-sm" id="unit_ref" type="text" />
                    </div>

                    <div class="col-sm-6 col-md-12 mt-0">
                        <label class="form-label" for="c1">C1</label>
                        <input class="form-control form-control-sm" id="c1" type="text" />
                    </div>
                    <div class="col-sm-6 col-md-12 mt-0">
                        <label class="form-label" for="c4">C4</label>
                        <input class="form-control form-control-sm" id="c4" type="text" />
                    </div>
                </div>
            </div>
            <div class="col-xl-2">
                <div class="row gx-3 gy-4">
                    <div class="col-sm-6 col-md-12">
                        <div class="form-floating">
                            <input class="form-control" id="eq_id" type="text" placeholder="Equipment ID" />
                            <label for="eq_id">Equipment ID </label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-12 mt-0">
                        <label class="form-label" for="brand">Brand</label>
                        <input class="form-control form-control-sm" id="brand" type="text" />
                    </div>
                    <div class="col-sm-6 col-md-12 mt-0">
                        <label class="form-label" for="resolution_ref">Resolution Ref</label>
                        <input class="form-control form-control-sm" id="resolution_ref" type="text" />
                    </div>
                    <div class="col-sm-6 col-md-12 mt-0">
                        <label class="form-label" for="c2">C2</label>
                        <input class="form-control form-control-sm" id="c2" type="text" />
                    </div>
                    <div class="col-sm-6 col-md-12 mt-0">
                        <label class="form-label" for="c5">C5</label>
                        <input class="form-control form-control-sm" id="c5" type="text" />
                    </div>


                </div>
            </div>

            <div class="col-xl-2">
                <div class="row gx-3 gy-4">
                    <div class="col-sm-6 col-md-12">
                        <div class="form-floating">
                            <input class="form-control" id="sensor_id" type="text" placeholder="Sensor ID" />
                            <label for="sensor_id">Sensor ID</label>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-12 mt-0">
                        <label class="form-label" for="serial">Serial</label>
                        <input class="form-control form-control-sm" id="serial" type="text" />
                    </div>

                    <div class="col-sm-6 col-md-12 mt-0">
                        <label class="form-label" for="cal_date">Cal Date</label>
                        <input class="form-control form-control-sm" id="cal_date" type="text" />
                    </div>

                    <div class="col-sm-6 col-md-12 mt-0">
                        <label class="form-label" for="c3">C3</label>
                        <input class="form-control form-control-sm" id="c3" type="text" />
                    </div>

                </div>
            </div>
            <div class="col-xl-2">
                <div class="row gx-3 gy-4">
                    <div class="col-sm-6 col-md-12">
                        <div class="form-floating">
                            <div class="flatpickr-input-container">
                                <div class="form-floating">
                                    <input class="form-control datetimepicker" id="cal_date_input" type="text" placeholder="Cal Date" data-options='{"disableMobile":true}' /><span class="uil uil-calendar-alt flatpickr-icon text-body-tertiary"></span>
                                    <label class="ps-6" for="startDatepicker">Cal Date</label>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-xl-2">
                <div class="row gx-3 gy-4">
                    <div class="col-sm-6 col-md-12">
                        <div class="form-floating">
                            <div class="flatpickr-input-container">
                                <div class="form-floating">
                                    <input class="form-control" id="range_min" type="text" placeholder="Min" />
                                    <label for="range_min">MIN</label>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-xl-2">
                <div class="row gx-3 gy-4">
                    <div class="col-sm-6 col-md-12">
                        <div class="form-floating">
                            <div class="flatpickr-input-container">
                                <div class="form-floating">
                                    <input class="form-control" id="range_max" type="text" placeholder="Max" />
                                    <label for="range_max">MAX</label>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>





    </div>


    <div class="mb-6">
        <div class="row g-5" style="padding-top: 20px;">
            <div class="col-xl-2">
                <div class="row gx-3 gy-4">
                    <div class="col-sm-6 col-md-12">
                        <div class="form-floating">
                            <input class="form-control" id="unit_uuc" type="text" placeholder="Unit UUC" />
                            <label for="unit_uuc">UNIT UUC</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-2">
                <div class="row gx-3 gy-4">
                    <div class="col-sm-6 col-md-12">
                        <div class="form-floating">
                            <input class="form-control" id="resolution_uuc" type="text" placeholder="Unit UUC" />
                            <label for="resolution_uuc">Resolution UUC</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-2">
                <div class="row gx-3 gy-4">
                    <div class="col-sm-6 col-md-12">
                        <div class="form-floating">
                            <input class="form-control" id="resolution_uncert" type="text" placeholder="Resolution Uncert" />
                            <label for="resolution_uncert">Resolution Uncert</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <h4>Template</h4>
    <div id="show_template"></div>

</div>


<?php
include 'modals.php';
require '../theme/layout_footer.php';

?>


<script>
    $(document).ready(function() {

        function calculateStandardDeviation(scores) {
            console.log("STDEV ", scores);
            // Step 1: Calculate the mean
            var sum = scores.reduce(function(a, b) {
                return a + b;
            }, 0);
            var mean = sum / scores.length;
            // Step 2: Calculate the squared differences from the mean
            var squaredDifferences = scores.map(function(score) {
                return Math.pow(score - mean, 2);
            });
            // Step 3: Calculate the mean of the squared differences
            var meanOfSquaredDifferences = squaredDifferences.reduce(function(a, b) {
                return a + b;
            }, 0) / squaredDifferences.length;
            // Step 4: Calculate the square root of the mean of squared differences
            var standardDeviation = Math.sqrt(meanOfSquaredDifferences);
            return [standardDeviation];
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


        $(document).on('click', '.ub-table-process', function() {
            var row_id = $(this).attr('data-rowid');
            var template_id = $(this).attr('data-template-id');

            $.ajax({
                type: 'POST',
                url: '../functions/provider.php',
                dataType: 'json',
                data: {
                    action: 'process_ub_table',
                    row_id: row_id,
                    layout_id: <?= $_GET['id']; ?>,
                    template_id: template_id

                },
                success: function(response) {
                    if (response.status === 'success') {
                        // Bind data to specific HTML elements


                    } else {
                        // Handle the error case
                        console.error('Error: ' + response.message);

                        // Show toast message for the error
                        //  $('.toast-body').text('Error: ' + response.message);
                        // $('.toast').toast('show');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // Handle AJAX error
                    console.error('AJAX Error:', textStatus, errorThrown);
                }
            });


        });


        $(document).on('click', '.function-btn', function() {
            var layout = $(this).attr('data-layout');
            // Get the parent <th> element of the clicked button
            var parentTh = $(this).closest('th');
            // Get the value of the column_function attribute
            var columnFunction = parentTh.attr('column_function');
            var data_id = parentTh.attr('data-id');
            var data_template_id = parentTh.attr('data-template-id');
            var formattedColumnFunction = columnFunction.charAt(0).toUpperCase() + columnFunction.slice(1).toLowerCase();
            // Now you can use the columnFunction variable as needed
            //console.log('Column Function:', formattedColumnFunction);


            $.ajax({
                type: 'POST',
                url: '../functions/provider.php',
                dataType: 'json',
                data: {
                    action: formattedColumnFunction,
                    heading_id: data_id,
                    template_id: data_template_id

                },
                success: function(response) {
                    if (response.status === 'success') {
                        loadTemplate(layout);


                    } else {
                        // Handle the error case
                        console.error('Error: ' + response.message);

                        // Show toast message for the error
                        //  $('.toast-body').text('Error: ' + response.message);
                        // $('.toast').toast('show');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // Handle AJAX error
                    // console.error('AJAX Error:', textStatus, errorThrown);

                    loadTemplate(layout);
                }
            });

        });


        $(document).on('click', '.table_2_preview', function() {
            var row_id = $(this).attr('data-rowid');
            var headings_id = $(this).attr('data-headings-id');
            var value_id = $(this).attr('data-value-id');
            var template_id = $(this).attr('data-template-id');


            var res_ref = $('#resolution_ref').val();
            var res_uncert = $('#resolution_uncert').val();
            var res_uuc = $('#resolution_uuc').val();

            $.ajax({
                type: 'POST',
                url: '../functions/provider.php',
                dataType: 'json',
                data: {
                    action: 'get_table2_template',
                    row_id: row_id,
                    headings_id: headings_id,
                    value_id: value_id,
                    template_id: template_id,
                    layout_id: <?= $_GET['id']; ?>,
                    resolution_ref: res_ref,
                    resolution_uncert: res_uncert,
                    resolution_uuc: res_uuc
                },
                success: function(response) {

                    if (response.status === 'success') {


                        // Bind data to specific HTML elements
                        $('#tabel-2-preview-data').html(response.result);

                        // Show the modal
                        $('#tabel-2-preview').modal('show');
                    } else {
                        // Handle the error case
                        console.error('Error: ' + response.message);

                        // Show toast message for the error
                        // $('.toast-body').text('Error: ' + response.message);
                        // $('.toast').toast('show');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // Handle AJAX error
                    // console.error('AJAX Error:', textStatus, errorThrown);
                }
            });
        });


        $(document).on('click', '#submitButton', function(e) {
            e.preventDefault(); // Prevent default form submission// Serialize form data


            var row_id = $('#data-div').attr('row_id');
            var headings_id = $('#data-div').attr('headings_id');
            var value_id = $('#data-div').attr('value_id');
            var template_id = $('#data-div').attr('template_id');



            var inputElements = document.querySelectorAll(".distribution-data");
            var inputElements = document.querySelectorAll(".divisor-data");
            var inputElements = document.querySelectorAll(".sensitivity-data");

            var distributionValues = [];
            var divisorValues = [];
            var sensitivityValues = [];
            var dataId = [];
            // Iterate through the input elements and push their values to the arrays
            $('.input-data[name="distribution[]"]').each(function() {
                distributionValues.push($(this).val());
            });

            $('.input-data[name="dataids[]"]').each(function() {
                dataId.push($(this).val());
            });

            $('.input-data[name="divisor[]"]').each(function() {
                divisorValues.push($(this).val());
            });

            $('.input-data[name="sensitivity[]"]').each(function() {
                sensitivityValues.push($(this).val());
            });

            console.log('Distribution Values:', distributionValues);
            console.log('Divisor Values:', divisorValues);
            console.log('Sensitivity Values:', sensitivityValues);
            console.log('data_id Values:', dataId);
            $.ajax({
                type: 'POST',
                url: '../functions/provider.php', // Replace with your backend script URL
                data: {
                    action: 'addComponentData',
                    distributionValues: distributionValues,
                    dataids: dataId,
                    divisorValues: divisorValues,
                    sensitivityValues: sensitivityValues
                },
                success: function(response) {

                    $.ajax({
                        type: 'POST',
                        url: '../functions/provider.php',
                        dataType: 'json',
                        data: {
                            action: 'get_table2_template',
                            row_id: row_id,
                            headings_id: headings_id,
                            value_id: value_id,
                            template_id: template_id,
                            layout_id: <?= $_GET['id']; ?>,
                        },
                        success: function(response) {

                            if (response.status === 'success') {


                                // Bind data to specific HTML elements
                                $('#tabel-2-preview-data').html(response.result);

                                // Show the modal
                                //  $('#tabel-2-preview').modal('show');
                            } else {
                                // Handle the error case
                                console.error('Error: ' + response.message);

                                // Show toast message for the error
                                // $('.toast-body').text('Error: ' + response.message);
                                // $('.toast').toast('show');
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            // Handle AJAX error
                            // console.error('AJAX Error:', textStatus, errorThrown);
                        }
                    });

                },
                error: function() {
                    // alert('Error loading Layouts.');
                }
            });
            // return false; 
        });




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



        $(document).on('click', '.request-btn', function() {
            var parameter = $('#parameter').val();
            var eq_Id = $('#eq_id').val();
            var sensor_Id = $('#sensor_id').val();
            var cal_date = $('#request-btn').val();
            var min = $('#range_min').val();
            var max = $('#range_max').val();

            $.ajax({
                type: 'POST',
                url: '../functions/provider.php',
                dataType: 'json',
                data: {
                    action: 'getbasicdata',
                    parameter: parameter,
                    eq_Id: eq_Id,
                    sensor_Id: sensor_Id,
                    cal_date: cal_date,
                    min: min,
                    max: max
                },
                success: function(response) {
                    if (response.result.status === 'success') {
                        // Bind data to specific HTML elements
                        $('#equipment_name').text(response.result.equipment_name);
                        $('#brand').text(response.result.brand);
                        $('#serial').text(response.result.serial);
                        $('#nit_ref').text(response.result.nit_ref);
                        $('#resolution_ref').text(response.result.resolution_ref);
                        $('#cal_date_input').text(response.result.cal_date_input);
                        $('#c1').text(response.result.c1);
                        $('#c2').text(response.result.c2);
                        $('#c3').text(response.result.c3);
                        $('#c4').text(response.result.c4);
                        $('#c5').text(response.result.c5);
                        $('#unit_uuc').text(response.result.unit_uuc);
                        $('#resolution_uuc').text(response.result.resolution_uuc);
                    } else {
                        // Handle the error case
                        console.error('Error: ' + response.message);

                        // Show toast message for the error
                        $('.toast-body').text('Error: ' + response.message);
                        $('.toast').toast('show');
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

        function loadTemplate() {
            $.ajax({
                type: 'GET',
                url: '../functions/get_template.php',
                data: {
                    layout_id: <?= (isset($_GET['id'])) ? $_GET['id'] : '0'; ?>
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
        loadTemplate()



        $(document).on('click', '#addRaw', function(e) {

            var res_ref = $('#resolution_ref').val();
            var res_uncert = $('#resolution_uncert').val();
            var res_uuc = $('#resolution_uuc').val();
            var layout = $(this).attr('data-layout');
            var level = $(this).attr('data-level');
            var template = $(this).attr('data-template');
            var inputElements = document.querySelectorAll(".input_" + level);
            var ids = [];
            var values = [];
            var strs = [];
            if (res_ref == '' || res_uncert == '' || res_uuc== '') {
                alert("Fill the fixed values before save");
            } else {
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
                    url: '../functions/provider.php',
                    data: {
                        level: level,
                        values: values,
                        ids: ids,
                        strs: strs,
                        template_id: template,
                        resolution_ref: res_ref,
                        resolution_uuc: res_uuc,
                        resolution_uncert: res_uncert,
                        action: 'addHedingValues'
                    },
                    success: function(data) {
                        loadTemplate(layout);
                        //loadTemplateForPreview(layout);
                    },
                    error: function() {
                        alert('Error loading data.');
                    }
                });
            }
        });


    });
</script>