<?php
require_once('../database.php');
$database = new Database();
$conn = $database->getConnection();

require '../theme/layout_header.php';
require '../theme/layout_navigations.php';

?>

<?php
$layoutID = $_GET['id'];
$query = "SELECT * FROM layouts WHERE id = :layoutID";
$statement = $conn->prepare($query);
$statement->bindParam(':layoutID', $layoutID, PDO::PARAM_INT);
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);

// print_r($result);
$temlpate = 'Not Assigned <i class="fa-solid fa-triangle-exclamation" style="color:coral"></i>';
if ($result[0]['layout_template_id'] != null) {
    $query = "SELECT * FROM layout_template WHERE id = :layoutTemplateID";
    $statement = $conn->prepare($query);
    $statement->bindParam(':layoutTemplateID', $result[0]['layout_template_id'], PDO::PARAM_INT);
    $statement->execute();
    $templateResult = $statement->fetchAll(PDO::FETCH_ASSOC);

    $temlpate = $templateResult[0]['template_name'];
}

?>

<div class="content">
    <nav class="mb-2" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="#!">Page 1</a></li>
            <li class="breadcrumb-item"><a href="#!">Page 2</a></li>
            <li class="breadcrumb-item active">Default</li>
        </ol>
    </nav>
    <div class="mb-9">
        <div class="row g-2 mb-4">
            <div class="col-auto">
                <h2 class="mb-0">Layout Edit</h2>
            </div>
        </div>
        <ul class="nav nav-links mb-3 mb-lg-2 mx-n3">
        </ul>
        <div id="products" data-list='{"valueNames":["Layout","email","total-orders","total-spent","city","last-seen","last-order"],"page":10,"pagination":true}'>
            <div class="mb-4">
                <div class="row g-3">
                    <div class="col-auto">
                        <div class="search-box">
                            <select class="form-select" aria-label="" id="templateSelect">
                                <option selected value="0">Select Template</option>
                            </select>
                            <div style="margin-top: 20px;">Layout: <span style="color: dimgray;"> <?= $result[0]['layout_name']; ?></span> Template: <span style="color: dimgray;" id="template-name"><?= $temlpate; ?></span></div>
                        </div>
                    </div>
                    <div class="col-auto scrollbar overflow-hidden-y flex-grow-1">
                        <div class="btn-group position-static" role="group">
                            <div class="btn-group position-static text-nowrap">
                                <button class="btn btn-primary btn-sm">
                                    <i id="link-template" class="fa-solid fa-link" style="margin-right: 5px;color:lightskyblue;cursor: pointer;font-size: 18px;"></i>
                                    Link
                                </button>

                            </div>

                        </div>
                    </div>
                    <div class="col-auto">
                        <!-- <button class="btn btn-link text-body me-4 px-0"><span class="fa-solid fa-file-export fs-9 me-2"></span>Export</button> -->
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addHeadingModal"><span class="fas fa-plus me-2"></span>Add Column</button>
                    </div>
                </div>
            </div>
            <div class="mx-n4 px-4 mx-lg-n6 px-lg-6 bg-body-emphasis border-top border-bottom border-translucent position-relative top-1">
                <div class="table-responsive scrollbar-overlay mx-n1 px-1" style="padding: 10px !important;">




                    <div class="row g-3">
                        <div class="col-auto">
                            <h4>Main Table</h4>
                        </div>

                        <div class="col-auto">

                        </div>
                    </div>


                    <style>
                        #tableContainer th {
                            border: 1px solid #000;
                            padding: 5px;
                        }
                    </style>


                    <table id="tableContainer">

                    </table>



                </div>

            </div>



            <div class="mx-n4 px-4 mx-lg-n6 px-lg-6 bg-body-emphasis border-top border-bottom border-translucent position-relative top-1 mt-10">
                <div class="table-responsive scrollbar-overlay mx-n1 px-1" style="padding: 10px !important;">




                    <div class="row g-3" style="padding-bottom: 10px;">
                        <div class="col">
                            <h4>Uncertainty Budget Table</h4>
                        </div>
                        <div class="col-auto text-end">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uncertaintyBudgetModal">
                                <span class="fas fa-plus me-2"></span>Add Component
                            </button>
                        </div>
                    </div>
                    <style>
                        #ubtable table th,
                        td {
                            border: 1px solid #000;
                            padding: 5px;
                        }
                    </style>


                    <div id="ubtable">

                        <table>

                            <thead>
                                <tr>
                                    <th colspan="7" class="text-center">Uncertainty Budget</th>
                                </tr>
                                <tr>
                                    <th width="25%">Component</th>
                                    <th width="10%">Magnitude</th>
                                    <th width="25%">Distribution</th>
                                    <th width="10%">Divisor</th>
                                    <th width="10%">Sensitivity</th>
                                    <th width="10%">Std uncert</th>
                                    <th width="10%">DOF</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                $queryh = "SELECT * FROM uncertainty_budget_tempplate WHERE layout_id = :layout_id AND template_id = :template_id";
                                $statementh = $conn->prepare($queryh);
                                // Bind the layout template ID directly without using bindParam
                                $statementh->bindValue(':layout_id', $_GET['id'], PDO::PARAM_INT);
                                $statementh->bindValue(':template_id', $result[0]['layout_template_id'], PDO::PARAM_INT);
                                $statementh->execute();
                                $resulth = $statementh->fetchAll(PDO::FETCH_ASSOC);

                                foreach ($resulth as $component) {

                                ?>

                                    <tr>
                                        <td><?= $component['component']; ?></td>
                                        <td>

                                            <?php

                                            try {
                                                $queryh = "SELECT title FROM headings WHERE id = :id";
                                                $statementh = $conn->prepare($queryh);
                                                $statementh->bindValue(':id', $component['reference_column'], PDO::PARAM_INT);
                                                $statementh->execute();
                                                $resulth = $statementh->fetch(PDO::FETCH_ASSOC);

                                                if ($resulth) {
                                                    echo $resulth['title'];
                                                } else {
                                                    echo "N/A";
                                                }
                                            } catch (PDOException $e) {
                                                echo "Error fetching title: " . $e->getMessage();
                                            }
                                            ?>

                                        </td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>

                                <?php
                                }
                                ?>


                            </tbody>
                        </table>

                    </div>



                </div>

            </div>



            <div class="row g-3" style="padding-bottom: 10px;padding-top: 10px;">
                <div class="col">
                </div>
                <div class="col-auto text-end">
                    <a href="analysis.php?id= <?= $_GET['id']; ?>" class="btn btn-success">
                        Proceed
                    </a>
                </div>
            </div>



        </div>
    </div>

    <script>
        $(document).ready(function() {

            $(document).on('click', '.heading-edit', function() {

                // Find the parent td element
                var parentTd = $(this).closest('th');

                // Get data attributes and attributes from the parent td
                var headingText = $(this).attr('data-text');
                var headingId = $(this).attr('data-id');
                var headingType = parentTd.attr('column_type');
                var headingFunction = parentTd.attr('column_function');

                $('#heading-text').val(headingText);
                $('#headingId').val(headingId);
                $('#column_type_edit').val(headingType);
                $('#column_function_edit').val(headingFunction);

                // If the heading type is 'FUNCTION', show the row containing the function select
                if (headingType === 'FUNCTION') {
                    $('.inputFunction').closest('.row').show();
                } else {
                    $('.inputFunction').closest('.row').hide();
                }

                // Show the Bootstrap modal
                $('#editHeadingModal').modal('show');
            });




            $(document).on('click', '.update-heading', function() {
                // Get headingId and headingText values
                var headingId = $('#headingId').val();
                var headingText = $('#heading-text').val();
                var headingType = $('#column_type_edit').val();
                var headingFunction = $('#column_function_edit').val();
                var referenceColumns = $('#multiple-columns').val();

                // Make AJAX request
                $.ajax({
                    type: 'POST',
                    url: '../functions/provider.php',
                    dataType: 'json',
                    data: {
                        action: 'editTitle',
                        heading_id: headingId,
                        heading_text: headingText,
                        heading_type: headingType,
                        heading_function: headingFunction,
                        re_columns: referenceColumns

                    },
                    success: function(response) {

                        console.log(response.status);
                        if (response.status === 'success') {
                            // Handle success case
                            // toastr.success('Title Saved', 'Success!')
                            loadTitles();
                            $('#editHeadingModal').modal('hide');
                        } else {
                            // Handle the error case
                            console.error('Error: ' + response.message);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        // Handle AJAX error
                    }
                });
            });

            $(document).on('click', '.delete-heading', function() {
                // Get headingId and headingText values
                var headingId = $('#headingId').val();

                // Make AJAX request
                $.ajax({
                    type: 'POST',
                    url: '../functions/provider.php',
                    dataType: 'json',
                    data: {
                        action: 'deleteHeading',
                        heading_id: headingId
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            // Handle success case
                            // toastr.success('Heading Deleted.', 'Success!')
                            loadTitles();
                            $('#editHeadingModal').modal('hide');
                        } else {
                            // Handle the error case
                            console.error('Error: ' + response.message);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        // Handle AJAX error
                    }
                });
            });
            $(document).on('click', '.add-component', function() {
                // Get headingId and headingText values
                var headingId = $('#heading-id-for-component').val();
                // Make AJAX request
                $.ajax({
                    type: 'POST',
                    url: '../functions/provider.php',
                    dataType: 'json',
                    data: {
                        action: 'addComponent',
                        heading_id: headingId,
                        layout_id: <?= $_GET['id']; ?>,
                        template_id: <?= $result[0]['layout_template_id']; ?>,
                        component_text: $('#component-text').val(),
                        // distribution_text: $('#distribution_text').val()
                    },
                    success: function(response) {
                        if (response.status === 'success') {

                            window.location.reload();
                            $('#editHeadingModal').modal('hide');
                        } else {
                            // Handle the error case
                            console.error('Error: ' + response.message);
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        // Handle AJAX error
                    }
                });
            });




            loadTemplates();
            loadTitles();


            $("#link-template").click(function(e) {
                e.preventDefault();
                var layoutID = <?= $_GET['id']; ?>;
                var templateID = $('#templateSelect').val();
                $.ajax({
                    type: 'POST',
                    url: '../functions/provider.php',
                    dataType: 'json',
                    data: {
                        action: 'linkTemplate',
                        layout_id: layoutID,
                        template_id: templateID
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            var templateSelect = $('#templateSelect');
                            $('.alert').html(response.message);
                            $('.alert').fadeIn(400);
                            setTimeout(function() {
                                $('.alert').fadeOut();
                            }, 2000);
                        } else {
                            // Handle the error case
                            console.error('Error: ' + response.message);
                        }
                    },
                    error: function(error) {
                        alert('Error fetching templates!');
                        console.log(error);
                    }
                });



            });

            function loadTemplates() {
                $.ajax({
                    type: 'POST',
                    url: '../functions/provider.php',
                    dataType: 'json',
                    data: {
                        action: 'loadTemplates'
                    },
                    success: function(response) {

                        console.log(response.templates);
                        if (response.status === 'success') {
                            // Update the HTML of the select with the received options
                            var templateSelect = $('#templateSelect');
                            $.each(response.result, function(index, template) {
                                console.log(template.template_name);
                                templateSelect.append('<option value="' + template.id + '">' + template.template_name + '</option>');
                            });
                        } else {
                            // Handle the error case
                            console.error('Error: ' + response.message);
                        }
                    },
                    error: function(error) {
                        alert('Error fetching templates!');
                        console.log(error);
                    }
                });
            }

            function loadTitles() {
                var templateID = <?= $result[0]['layout_template_id']; ?>;
                var layoutID = <?= $_GET['id']; ?>;
                $.ajax({
                    type: 'POST',
                    url: '../functions/provider.php', // Adjust the URL accordingly
                    data: {
                        action: 'loadTitles',
                        layout_template_id: templateID,
                        layout_id: layoutID,
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            $('#tableContainer').html(response.result.tableHTML);
                        } else {
                            // Handle the error case
                            // console.error('Error: ' + response.message);
                        }
                    },
                    error: function(error) {
                        //alert('Error loading title!');
                        console.log('Error loading title! ' + error);
                    }
                });
            }

            $(document).on('click', '.add-sub-title', function() {
                var templateID = <?= $result[0]['layout_template_id']; ?>;
                var heddingsId = $('#inputHeddigsId').val();
                var title = $('#inputSubTitle').val();
                var column_type = $('#column_type_add_sub_title').val();
                var column_function = $('#column_function_add_sub_title').val();
                var level = 2;
                var layoutID = <?= $_GET['id']; ?>;

                $.ajax({
                    type: 'POST',
                    url: '../functions/provider.php', // Adjust the URL accordingly
                    data: {
                        title: title,
                        headings_id: heddingsId,
                        level: level,
                        action: 'addSubTitle',
                        template_id: templateID,
                        column_type: column_type,
                        column_function: column_function,
                        layoutID: layoutID
                    },
                    dataType: 'json',
                    success: function(response) {

                        if (response.result.status === 'success') {
                            loadTitles();
                            $('#titleModal').modal('hide');
                        } else {
                            // Handle the error case
                            // console.error('Error: ' + response.message);
                        }
                    },
                    error: function(error) {
                        alert('Error adding sub title!', error);

                    }
                });
            });

            $(document).on('click', '.add-second-sub-title', function() {
                var templateID = <?= $result[0]['layout_template_id']; ?>;
                var headings_id = $('#inputSubHeddigsId').val();
                var title = $('#inputSecondSubTitle').val();

                var column_type = $('#column_type_second_sub_title').val();
                var column_function = $('#column_function_second_sub_title').val();
                var level = 3;
                var layoutID = <?= $_GET['id']; ?>;
                $.ajax({
                    type: 'POST',
                    url: '../functions/provider.php', // Adjust the URL accordingly
                    data: {
                        title: title,
                        headings_id: headings_id,
                        level: level,
                        action: 'addSubTitle',
                        template_id: templateID,
                        column_type: column_type,
                        column_function: column_function,
                        layoutID: layoutID
                    },
                    dataType: 'json',
                    success: function(response) {

                        if (response.status === 'success') {
                            loadTitles();
                            $('#titleModal').close();
                        } else {
                            // Handle the error case
                            // console.error('Error: ' + response.message);
                        }
                    },
                    error: function(error) {
                        alert('Error adding sub title!');

                    }
                });
            });

            $(document).on('change', '#templateSelect', function() {
                var selectedTemplate = $(this).val();

                if (selectedTemplate != 0) {
                    // alert(selectedTemplate)
                }

            });


            $(document).on('click', '.add-hedding', function() {
                var title = $('#inputTitle').val();
                var column_type = $('#column_type').val();
                var column_function = $('#column_function').val();
                var templateID = <?= $result[0]['layout_template_id']; ?>;
                var layoutID = <?= $_GET['id']; ?>;
                if (templateID == "0" || templateID === undefined) {
                    alert("template not selected");
                    return;
                }

                $.ajax({
                    type: 'POST',
                    url: '../functions/provider.php', // Adjust the URL accordingly
                    data: {
                        layout_template_id: templateID,
                        layout_id: layoutID,
                        title: title,
                        level: 1,
                        action: 'addTitle',
                        column_type: column_type,
                        column_function: column_function

                    },
                    dataType: 'json',
                    success: function(response) {
                        console.log(response.status);
                        if (response.status === 'success') {
                            loadTitles();
                            $('#addHeadingModal').modal('hide');
                        } else {
                            // Handle the error case
                            // console.error('Error: ' + response.message);
                        }
                    },
                    error: function(error) {
                        alert('Error adding heading!');
                        console.log(error);
                    }
                });
            });



            $(document).on('click', '.add-sub-title-modal', function() {
                var titleId = $(this).attr('data-id');
                var title = $(this).attr('data-text');                
                var column_type = $('#column_type').val();
                var column_function = $('#column_function').val();
                var hasFunction = $(this).attr('data-has-function');
                var selectBox = document.getElementById('column_type_add_sub_title');
                var optionToHide = 'FUNCTION';
                for (var i = 0; i < selectBox.options.length; i++) {
                    if (selectBox.options[i].value === optionToHide && hasFunction == 0) {
                        selectBox.options[i].disabled = true; // Disable the option
                        selectBox.options[i].style.display = 'none'; // Hide the option
                        break; // Exit the loop after hiding the option
                    }
                }

                $('#titleModalLabel').html(title);
                $('#inputHeddigsId').val(titleId);
            });

            $(document).on('click', '.add-second-sub-title-modal', function() {
                var titleId = $(this).attr('data-id');
                var title = $(this).attr('data-text');                
                $('#subTitleModalLabel').html(title);
                $('#inputSubHeddigsId').val(titleId);
            });



            $('#load-titles').on('click', function() {
                loadTitles();
            });

            $('#add-title').on('click', function() {
                var title = $('#titleInput').val();
                if (title.trim() !== '') {
                    // AJAX request to send the title to your PHP script
                    $.ajax({
                        type: 'POST',
                        url: '../functions/provider.php', // Adjust the URL accordingly
                        data: {
                            title: title,
                            action: 'add',
                            column_type: column_type,
                            coulumn_function: column_function
                        },
                        dataType: 'json',
                        success: function(response) {
                            console.log(response.status);
                            if (response.status === 'success') {
                                loadTitles();
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
                } else {
                    alert('Please enter a title.');
                }
            });
        });
    </script>

    <?php
    include 'modals.php';
    require '../theme/layout_footer.php';

    ?>