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
<div class="container" style="padding-top: 50px;">
    <h4>Templates</h4>
    <table id="templatesTable" class="display table table-sm  table-hover" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Template Name</th>
                <th style="width: 80px;">Actions</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>
<?php
require 'modals.php';
?>
</body>

<script>
$(document).ready(function() {
    $('#templatesTable').DataTable({
        "draw": 1,
        "processing": true,
        "serverSide": true,
        "recordsTotal": 5, // Total number of records in your dataset
        "recordsFiltered": 5, // Total number of records after filtering (if you're implementing filtering)
        "ajax": {
            "url": "functions/add-title.php", // Path to your server-side script
            "type": "POST",
            data: {
                action: "allTemplates"
            },
        },
        "columns": [
            {
                "data": "id"
            },
            // {
            //     "data": "template_name",
            //     "render": function(data, type, row) {
            //         var templateName = data || "";
            //         return '<button class="btn btn-link template-btn" data-template-id="' + row.id + '">' + templateName + '</button>';
            //     }
            // },
            {
                "data": "template_name",
                "render": function(data, type, row) {
                    var templateName = data || "";
                    return '<a href="view-template.php?id='+row.id+'">' + templateName + '</a>';
                }
            },
            {

                "data": null,
                "defaultContent": "<div class='d-flex'>\
                                    <button class='btn btn-warning btn-sm edit-btn me-2'>\<i class='fa-solid fa-pen-to-square '></i></button>\
                                    <button class='btn btn-danger btn-sm delete-btn me-2'><i class='fa-solid fa-trash'></i></button>\
                                    <button class='btn btn-info btn-sm analysis-btn'><i class='fa-solid fa-chart-line'></i></button>\
                                    </div>",
                "orderable": false
            }
        ],
        "pagingType": "full_numbers",
    });

    // $('#templatesTable').on('click', 'tbody tr', function() 
    // {
    //     if (!$(event.target).closest('.delete-btn, .edit-btn').length) {
    //         var data = $('#templatesTable').DataTable().row(this).data();
    //         if (data && data.id) {
    //             window.location.href = 'view-template.php?id=' + data.id;
    //         }
    //     }
    // });

    $(document).on('click', '.edit-btn', function() 
    {
        var row = $(this).closest('tr');
        var data = $('#templatesTable').DataTable().row(row).data();
        $('#templateName').val(data.template_name);
        $('#template_id').val(data.id);
        $("#templateModalLabel").text("Edit Template");
        $('#templateModal').modal('show');
    });

    $(document).on('click', '.delete-btn', function() 
    {
        if (confirm("Are you sure you want to delete this record?")) 
        {
            var row = $(this).closest('tr');
            var data = $('#templatesTable').DataTable().row(row).data();
            var template_id = data.id;
            deleteTemplate(template_id, row);
        }
    });
    function deleteTemplate(template_id, row) 
    {
        $.ajax({
            type: 'POST',
            url: 'functions/add-title.php',
            data: {
                action: 'removeTemplate',
                template_id: template_id
            },
            success: function(data) {
                var response = JSON.parse(data)
                toastr.success(response.message, 'Success!')
                $('#templatesTable').DataTable().row(row).remove().draw();
            },
            error: function() {
                console.log('Error loading layouts.');
            }
        });
    }

    $(document).on('click', '.analysis-btn', function() 
    {
        var row = $(this).closest('tr');
        var data = $('#templatesTable').DataTable().row(row).data();
        if (data && data.id) {
            window.location.href = 'analysis.php?id=' + data.id;
        }
    });
});
</script>
<?php
require 'footer.php';
?>
