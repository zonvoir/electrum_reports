<?php require 'header.php'; ?>

<?php
$layout_id = $_GET['id'];

$query = "SELECT * FROM layouts WHERE id = :layoutID";
$statement = $conn->prepare($query);
$statement->bindParam(':layoutID', $layout_id, PDO::PARAM_INT);
$statement->execute();
$layout = $statement->fetch(PDO::FETCH_ASSOC);

$query = "SELECT * FROM layout_template WHERE id = :layoutTemplateID";
$statement = $conn->prepare($query);
$statement->bindParam(':layoutTemplateID', $layout['layout_template_id'], PDO::PARAM_INT);
$statement->execute();
$temlpate = $statement->fetch(PDO::FETCH_ASSOC);
?>

<div class="container">
    <?php if ($role['name'] != 'admin'): ?>
        <?php require '401.php'; ?>
    <?php else: ?>
        <div class="row mt-4">
            <div class="col-sm-1" style="font-size: 25px;">
                <!-- <i class="fa-solid fa-file-circle-plus" ></i> -->
                <a href="index.php"><i class="fa-solid fa-house" style="color: dodgerblue;cursor: pointer;"></i></a>
                <!-- <i id="load-titles" class="fa-solid fa-arrows-rotate" style="margin-right: 5px;color:blue;cursor: pointer;"></i> -->
            </div>
        </div>
        <div class="row mt-2">
            <h4>Layout Edit</h4>
        </div>
        <div class="row mt-2">
            <div class="col-sm-4">
                <select class="form-select" aria-label="" id="templateSelect">
                    <option selected value="0">Select Template</option>
                </select>
            </div>
            <div class="col-sm-4">
                <!-- <a class="btn btn-primary" href="javascript:void(0);" id="link-template">
                <i class="fa-solid fa-link"></i> Link with Layout
            </a> -->
            </div>
            <div class="col-sm-4 text-end">
                <a class="btn btn-primary addHeading" href="javascript:void(0);">
                    <i class="fa-solid fa-plus"></i> Add Column
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="alert alert-primary" role="alert" style="display: none;"></div>
                <h5 style="margin-top: 20px;">
                    Layout: <span style="color: dimgray;"> <?= $layout ? $layout['layout_name'] : 'N/A'; ?></span>
                    Template: <span style="color: dimgray;" id="template-name"><?= $temlpate ? $temlpate['template_name'] : 'N/A'; ?></span>
                </h5>
                <?php if ($layout && $layout['layout_template_id'] == null): ?>
                    <div class="alert alert-warning" role="alert">
                        Please assign a Template to the Layout. and make sure to save the assigned template before leave.
                    </div>
                <?php endif; ?>
                <div class="mt-4" id="tableContainer">
                </div>
            </div>
        </div>
        
        <div class="mt-5">
            <div class="scrollbar-overlay mx-n1 px-1" style="padding: 10px !important;">
                <div class="row g-3" style="padding-bottom: 10px;">
                    <div class="col">
                        <h4>Uncertainty Budget Table</h4>
                    </div>
                    <div class="col-auto text-end">
                        <button class="btn btn-primary addComponent">
                            <span class="fas fa-plus me-2"></span>Add Component
                        </button>
                    </div>
                </div>
                 
                <div id="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th colspan="7" class="text-center">Uncertainty Budget</th>
                            </tr>
                            <tr>
                                <th>Component</th>
                                <th>Magnitude</th>
                                <th>Distribution</th>
                                <th>Divisor</th>
                                <th>Sensitivity</th>
                                <th>Std uncert</th>
                                <th>DOF</th>
                            </tr>
                        </thead>
                        <tbody id="componentsTablebody">
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row g-3" style="padding-bottom: 10px; padding-top: 10px;">
                <div class="col">
                </div>
                <div class="col-auto text-end">
                    <a href="view-template.php?layout_id=<?= $layout['id']; ?>" class="btn btn-success">
                        Proceed
                    </a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
$(document).ready(function() {
    var layout_id = <?= $_GET['id']; ?>;
    var template_id = <?= $layout['layout_template_id']; ?>;
    
    loadTemplates(template_id);
    loadHeadings(template_id);
    loadComponents(template_id);

    function loadTemplates(template_id) {
        $.ajax({
            type: 'POST',
            url: './functions/add-title.php',
            dataType: 'json',
            data: {
                action: 'loadTemplates'
            },
            success: function(response) {
                if (response.status === 'success') {
                    var templateSelect = $('#templateSelect');
                    $.each(response.templates, function(index, template) {
                        var selected = template_id == template.id ? 'selected' : '';
                        templateSelect.append('<option value="' + template.id + '" ' + selected + ' data-name="' + template.template_name + '">' + template.template_name + '</option>');
                    });
                } else {
                    console.error('Error: ' + response.message);
                }
            },
            error: function(error) {
                alert('Error fetching templates!');
                console.log(error);
            }
        });
    }

    function loadHeadings(template_id) {
        
        $.ajax({
            type: 'POST',
            url: './functions/add-title.php', // Adjust the URL accordingly
            data: {
                action: 'getHeadings',
                layout_id: layout_id,
                layout_template_id: template_id,
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    $('#tableContainer').html(response.tableHTML);
                } else {
                    // Handle the error case
                    // console.error('Error: ' + response.message);
                }
            },
            error: function(error) {
                alert('Error adding title!');
                console.log(error);
            }
        });
    }

    function loadComponents(template_id) {
        var layput_id = <?= $_GET['id']; ?>;
        $.ajax({
            type: 'POST',
            url: './functions/ComponentAction.php', 
            data: {
                action: 'loadComponents',
                layout_id: layput_id,
                template_id: template_id,
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    $('#componentsTablebody').html(response.tableHTML);
                } else {
                    // console.error('Error: ' + response.message);
                }
            },
            error: function(error) {
                alert('Error adding title!');
                console.log(error);
            }
        });
    }

    $(document).on('change', '#templateSelect', function() {
        var selectedTemplateId = $(this).val();
        var selectedTemplateName = $(this).find(':selected').attr('data-name');
        if (selectedTemplateId != 0) {
            $("#template-name").text(selectedTemplateName);
            loadHeadings(selectedTemplateId);
            loadComponents(selectedTemplateId);
        }
    });

    $(document).on('click', '.addHeading', function() {
        var selectedTemplateId = $("#templateSelect").find(':selected').val();
        $.ajax({
            type: 'POST',
            url: './functions/add-title.php',
            data: {
                action: 'getMaxLevel',
                layout_id: layout_id,
                template_id: selectedTemplateId,
            },
            success: function(response) {
                var response = JSON.parse(response);
                var inputLevel = $('#inputLevel');
                var totalLevels = response['levels'];
                var optionsHTML = '';
                for (var count = 1; count <= totalLevels; count++) {
                    optionsHTML += '<option value="' + count + '">' + count + '</option>';
                }
                inputLevel.html(optionsHTML);

                var dataFieldsOptions = '';
                var column_function_options = $('#column_function_options_add');
                response['dataFields'].forEach(function(number, index) {
                    dataFieldsOptions += '<option value="' + number['id'] + '">' + number['title'] + '</option>';
                });
                column_function_options.html(dataFieldsOptions);

                initializeSelect2('addHeadingModal');

                $('#addHeadingModal').modal('show');
            }
        });
    });

    $(document).on('click', '.store-hedding', function() {
        var templateID = $("#templateSelect").find(':selected').val();
        var title = $('#inputTitle').val();
        var level = $('#inputLevel').val();
        var colspan = $('#inputColspan').val();
        var column_type = $('#column_type').val();
        var column_function = $('#column_function').val();
        var function_fields = $('#column_function_options_add').val();
        var function_field = function_fields.toString();

        if (templateID == "0" || templateID === undefined) {
            toastr.error('Template not selected!', 'Error!')
            return false;
        }
        if (title == "") {
            toastr.error('Title field is required!', 'Error!')
            return false;
        }

        var multi_line = 0;
        var data_entry = 0;
        var analysis = 0;
        var report = 0;
        if ($('#multi_line').prop('checked')) {
            multi_line = 1;
        }
        if ($('#data_entry').prop('checked')) {
            data_entry = 1;
        }
        if ($('#analysis').prop('checked')) {
            analysis = 1;
        }
        if ($('#report').prop('checked')) {
            report = 1;
        }

        $.ajax({
            type: 'POST',
            url: './functions/add-title.php',
            data: {
                action: 'addHeading',
                layout_id: layout_id,
                layout_template_id: templateID,
                title: title,
                level: level,
                colspan: colspan,
                column_type: column_type,
                function_fields: function_field,
                column_function: column_function,
                multi_line: multi_line,
                data_entry: data_entry,
                analysis: analysis,
                report: report
            },
            success: function(data) {
                var response = JSON.parse(data)
                if (response.status == 'success') {
                    toastr.success(response.message, 'Success!', {
                        timeOut: 3000,
                        extendedTimeOut: 2000,
                        progressBar: true,
                        closeButton: true,
                        tapToDismiss: false,
                        positionClass: "toast-top-right",
                    });
                    loadHeadings(templateID);
                    $('#addHeadingModal').modal('hide');
                }
            },
            error: function(error) {
                toastr.error('Error adding title!', 'Error!')
            }
        });
    });

    $(document).on('click', '.heading-edit', function() {
        var selectedTemplateId = $("#templateSelect").find(':selected').val();
        var heading = JSON.parse($(this).attr('data-data'));
        $.ajax({
            type: 'POST',
            url: './functions/add-title.php',
            data: {
                action: 'getMaxLevel',
                layout_id: layout_id,
                template_id: selectedTemplateId,
            },
            success: function(response) {
                var response = JSON.parse(response);

                $('#headingId').val(heading.id);
                $('#inputTitleEdit').val(heading.title);
                var inputLevel = $('#inputLevelEdit');
                var optionsHTML = '';
                for (var count = 1; count <= response['levels']; count++) {
                    optionsHTML += '<option selected value="' + count + '">' + count + '</option>';
                }
                inputLevel.html(optionsHTML);
                $('#inputLevelEdit').val(heading.level);
                $('#inputColspanEdit').val(heading.colspan);
                $('#column_type_edit').val(heading.column_type);
                $('#column_function_edit').val(heading.column_function);
                if (heading.column_type === 'FUNCTION') {
                    $('.inputFunction').closest('.row').show();                            
                } else {
                    $('.inputFunction').closest('.row').hide();

                    if (heading.multi_line) {
                        $("#multi_line_edit").prop("checked", true);
                    }

                    if (heading.data_entry) {
                        $("#data_entry_edit").prop("checked", true);
                    }

                    if (heading.analysis) {
                        $("#analysis_edit").prop("checked", true);
                    }

                    if (heading.report) {
                        $("#report_edit").prop("checked", true);
                    }
                }
                var functionFieldsOptions = '';
                var columnFunctionOptions = $('#column_function_options');
                response['dataFields'].forEach(function(number, index) {
                    functionFieldsOptions += '<option value="' + number['id'] + '">' + number['title'] + '</option>';
                });
                columnFunctionOptions.html(functionFieldsOptions);

                var functionFieldsArr = heading.function_fields ? heading.function_fields.split(",") : 0;
                $("#column_function_options").val(functionFieldsArr);
                
                initializeSelect2('editHeadingModal');
                
                $('#editHeadingModal').modal('show');
            }
        });
    });

    $(document).on('click', '.update-heading', function() {
        // Get headingId and headingText values
        var selectedTemplateId = $("#templateSelect").find(':selected').val();
        var headingId = $('#headingId').val();
        var title = $('#inputTitleEdit').val();
        var level = $('#inputLevelEdit').val();
        var colspan = $('#inputColspanEdit').val();
        var column_type = $('#column_type_edit').val();
        var function_fields = $('#column_function_options').val();
        var function_field = '';
        if (function_fields != '')
            function_field = function_fields.toString();
        var column_function = $('#column_function_edit').val();
        var multi_line = 0;
        var data_entry = 0;
        var analysis = 0;
        var report = 0;
        if ($('#multi_line_edit').prop('checked')) {
            multi_line = 1;
        }
        if ($('#data_entry_edit').prop('checked')) {
            data_entry = 1;
        }
        if ($('#analysis_edit').prop('checked')) {
            analysis = 1;
        }
        if ($('#report_edit').prop('checked')) {
            report = 1;
        }
        // Make AJAX request
        $.ajax({
            type: 'POST',
            url: './functions/add-title.php',
            // dataType: 'json',
            data: {
                action: 'updateHeading',
                layout_id: layout_id,
                template_id: selectedTemplateId,
                heading_id: headingId,
                title: title,
                level: level,
                colspan: colspan,
                column_type: column_type,
                function_fields: function_field,
                column_function: column_function,
                multi_line: multi_line,
                data_entry: data_entry,
                analysis: analysis,
                report: report
            },
            success: function(responseJson) {
                console.log(responseJson);
                var response = JSON.parse(responseJson)
                if (response.status == 'success') {
                    toastr.success(response.message, 'Success!', {
                        timeOut: 3000,
                        extendedTimeOut: 2000,
                        progressBar: true,
                        closeButton: true,
                        tapToDismiss: false,
                        positionClass: "toast-top-right",
                    });
                    loadHeadings(selectedTemplateId);
                    $('#editHeadingModal').modal('hide');
                }
            },
            error: function(error) {
                toastr.error('Error adding title!', 'Error!')
            }
        });
    });

    $(document).on('click', '.delete-heading', function() {
        if (confirm("Are you sure you want to delete this record?")) {
            var headingId = $('#headingId').val();
            $.ajax({
                type: 'POST',
                url: './functions/add-title.php',
                data: {
                    action: 'deleteHeading',
                    heading_id: headingId
                },
                success: function(responseJson) {
                    var response = JSON.parse(responseJson)
                    if (response.status === 'success') {
                        // Handle success case
                        toastr.success(response.message, 'Success!')
                        loadHeadings(template_id);
                        $('#editHeadingModal').modal('hide');
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
        }
    });

    $(document).on('click', '.addSubTitle', function() {
        var selectedTemplateId = $("#templateSelect").find(':selected').val();
        var heading = JSON.parse($(this).attr('data-data'));
        $('#subTitleModalLabel').html(heading.title);
        $('#inputHeddigsId').val(heading.id);
        $('#subTitleModal').modal('show');
    });

    $(document).on('click', '.storeSubTitle', function() {
        // var templateID = $("#templateSelect").find(':selected').val();
        var headingsId = $('#inputHeddigsId').val();
        var title = $('#inputSubTitle').val();
        var column_type = $('#column_type_add_sub_title').val();
        var column_function = $('#column_function_add_sub_title').val();
        var level = 2;

        $.ajax({
            type: 'POST',
            url: './functions/add-title.php', // Adjust the URL accordingly
            data: {
                action: 'addSubTitle',
                template_id: template_id,
                headings_id: headingsId,
                column_type: column_type,
                title: title,
                column_function: column_function,
                level: level,
            },
            success: function(response) {
                var response = JSON.parse(responseJson)
                if (response.status === 'success') {
                    toastr.success(response.message, 'Success!')
                    loadHeadings(templateID);
                    $('#subTitleModal').modal('hide');
                } else {
                    console.error('Error: ' + response.message);
                }
            },
            error: function(error) {
                alert('Error adding title!');
            }
        });
    });

    $(document).on('click', '.addComponent', function(){
        var template_id = $("#templateSelect").find(':selected').val();
        $.ajax({
            type: 'POST',
            url: './functions/ComponentAction.php', 
            dataType: 'json',
            data: {
                action: 'getComponents',
                layout_id: layout_id,
                template_id: template_id,
            },
            success: function(response) {
                if (response.status == 'success') {
                        var magnitudeOptions = '<optgroup label="Table Columns" id="magnitudeHeadingsGroup">\
                                                    <option value="">Select</option>';
                        $.each(response.headings, function(index, heading) {
                            magnitudeOptions += '<option value="' + heading.id + '">' + heading.title + '</option>';
                        });
                        magnitudeOptions += '</optgroup>\
                                            <optgroup label="Fixed Inputs">\
                                                <option value="-1">Resolution Ref</option>\
                                                <option value="-2">Resolution UUC</option>\
                                                <option value="-3">Ref Uncert</option>\
                                            </optgroup>';
                        $('.magnitudeHeadingsGroup').html(magnitudeOptions);
                        $('#uncertaintyBudgetModal').modal('show');
                } else {
                    toastr.error('Error: ' + response.message);
                }
            },
        });
    });

    $(document).on('click', '.component-submit-btn', function() {
        var layout_id = <?= $_GET['id']; ?>;
        var template_id = $("#templateSelect").find(':selected').val();
        var component_name = $('#component_name').val();
        var heading_id = $('#heading_id').val();

        if (component_name == '') {
            toastrErrorMessage('Component field is required!');
            return false;
        }
        if (heading_id == '') {
            toastrErrorMessage('Magnitude field is required!');
            return false;
        }
        
        $.ajax({
            type: 'POST',
            url: './functions/ComponentAction.php', 
            dataType: 'json',
            data: {
                action: 'manageComponent',
                layout_id: layout_id,
                template_id: template_id,
                component_name: component_name,
                heading_id: heading_id,
            },
            success: function(response) {
                if (response.status == 'success') {
                    toastr.success(response.message, 'Success!', {
                        timeOut: 3000,
                        extendedTimeOut: 2000,
                        progressBar: true,
                        closeButton: true,
                        tapToDismiss: false,
                        positionClass: "toast-top-right",
                    });
                    loadComponents(template_id);
                    $('#component_name').val('');
                    $('#heading_id').val('');
                    $('#uncertaintyBudgetModal').modal('hide');
                } else {
                    toastr.error('Error: ' + response.message);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle AJAX error
            }
        });
    });

    $(document).on('click', '.component-delete-btn', function() {
        var layout_id = <?= $_GET['id']; ?>;
        var template_id = $("#templateSelect").find(':selected').val();
        var component_name = $('#component_name').val();
        var heading_id = $('#heading_id').val();

        if (heading_id == '') {
            toastrErrorMessage('Magnitude field is required!');
            return false;
        }
        if (confirm("Are you sure you want to delete this record?")) {
            $.ajax({
                type: 'POST',
                url: './functions/ComponentAction.php', 
                dataType: 'json',
                data: {
                    action: 'deleteComponent',
                    layout_id: layout_id,
                    template_id: template_id,
                    heading_id: heading_id,
                },
                success: function(response) {
                    if (response.status == 'success') {
                        toastr.success(response.message, 'Success!', {
                            timeOut: 3000,
                            extendedTimeOut: 2000,
                            progressBar: true,
                            closeButton: true,
                            tapToDismiss: false,
                            positionClass: "toast-top-right",
                        });
                        loadComponents(template_id);
                        $('#heading_id').val('');
                        $('#uncertaintyBudgetModal').modal('hide');
                    }  else {
                        toastrErrorMessage(response.message);
                    }
                }
            });
            return false;
        }
    });
    
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

    function initializeSelect2(modalId) {
        $('.select2').select2({
            dropdownParent: $('#'+modalId),
            placeholder: 'Select an option',
            allowClear: true,
            width: '100%'
        });
    }
});
</script>

<?php
require 'footer.php';
?>