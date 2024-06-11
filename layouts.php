<?php
require 'header.php';
?>

<body class="wrap-tbl-content">
<?php
require 'navigation.php';
?>
<div class="container" style="padding-top: 50px;padding-bottom: 20px;">

<div class="card border-0 tbl-csmz mb-5">
    
    <div class="card-header p-3 ">
    <h4>Layouts</h4>
     </div>

     <div class="card-body p-0">
    <table id="layoutsTable" class="display table table-sm  table-hover" style="width:100%">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Template Name</th>
                <th>Layout Name</th>
                <th style="width: 80px;">Actions</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
    </div>
    </div>

</div>

<?php
require 'modals.php';
?>
</body>

<script>
$(document).ready(function() {
    $('#layoutsTable').DataTable({
        "draw": 1,
        "processing": true,
        "serverSide": true,
        "recordsTotal": 5, // Total number of records in your dataset
        "recordsFiltered": 5, // Total number of records after filtering (if you're implementing filtering)
        "ajax": {
            "url": "functions/add-title.php", // Path to your server-side script
            "type": "POST",
            data: {
                action: "allLayouts"
            },
        },
        "columns": [
            {
                "data": "id"
            },
            {
                "data": "template_name"
            },
            {
                "data": "layout_name"
            },
            {
                "data": null,
                "defaultContent": "<div class='d-flex'>\
                                    <button class='btn btn-sm edit-btn me-2'>\<i class='fa-solid fa-pen-to-square '></i></button>\
                                    <button class='btn btn-sm delete-btn'><i class='fa-solid fa-trash'></i></button>\
                                    </div>",
                "orderable": false
            },
        ],
        "pagingType": "full_numbers",
    });

    $(document).on('click', '.edit-btn', function() 
    {
        var row = $(this).closest('tr');
        var data = $('#layoutsTable').DataTable().row(row).data();
        var layoutId = data.id;
        window.location.href = 'edit-layout.php?id=' + layoutId;
    });

    $(document).on('click', '.delete-btn', function() 
    {
        if (confirm("Are you sure you want to delete this record?")) 
        {
            var row = $(this).closest('tr');
            var data = $('#layoutsTable').DataTable().row(row).data();
            var layout_id = data.id;
            deleteLayout(layout_id, row);
        }
    });
    function deleteLayout(layout_id, row) 
    {
        $.ajax({
            type: 'POST',
            url: 'functions/add-title.php',
            data: {
                action: 'removeLayout',
                layout_id: layout_id
            },
            success: function(data) {
                var response = JSON.parse(data)
                toastr.success(response.message, 'Success!')
                $('#layoutsTable').DataTable().row(row).remove().draw();
            },
            error: function() {
                // Handle error case
                console.log('Error loading layouts.');
            }
        });
    }
});
</script>
<?php
require 'footer.php';
?>
