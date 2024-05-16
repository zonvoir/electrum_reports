jQuery( function( $ ){
    $("body").on('click', '.addTemplate', function(){
        $("#templateName").val("");
        $("#template_id").val(0);
        $("#templateModalLabel").text("Add Template");
        $("#templateModal").modal("show");
    });
    $("body").on('click', '.add-and-update-template', function() {
        var templateName = $('#templateName').val();
        var template_id = $('#template_id').val();
        if(templateName==''){
            toastr.error('Template name field is required!', 'Error!')
            return false
        }
        $.ajax({
            type: 'POST',
            url: 'functions/add-title.php',
            data: {
                action: 'addNewTemplate',
                template_name: templateName,
                template_id: template_id,
            },
            success: function(data) {
                $("#templateName").val("");
                var response = JSON.parse(data)
                toastr.success(response.message, 'Success!', {
                    timeOut: 3000,
                    extendedTimeOut: 2000,
                    progressBar: true,
                    closeButton: true,
                    tapToDismiss: false,
                    positionClass: "toast-top-right",
                });
                $('#templateModal').modal('hide');
                setTimeout(function(){
                    location.reload();
                },1000);
            },
            error: function() {
                alert('Error loading Layouts.');
            }
        });
    });

    $(document).on('click', '.add-layout-name', function() {
        var layoutName = $('#inputLayoutName').val();
        var layout_template_id = $("#layout_template_id").val();
        if(layoutName==''){
            toastr.error('Layout name field is required!', 'Error!')
            return false
        }
        if(layout_template_id==''){
            toastr.error('Template name field is required!', 'Error!')
            return false
        }
        $.ajax({
            type: 'POST',
            url: 'functions/add-title.php',
            data: {
                action: 'addNewLayout',
                layout_name: layoutName,
                layout_template_id: layout_template_id
            },
            success: function(data) {
                $("#inputLayoutName").val("");
                $("#layout_template_id").val("");
                var response = JSON.parse(data)
                toastr.success(response.message, 'Success!')
                $('#addLayoutModal').modal('hide');
                location.reload();
            },
            error: function() {
                alert('Error loading Layouts.');
            }
        });
    });

    $(function() {
        $(".dpicker").datepicker();
        $(".dpicker").datepicker("option", "dateFormat", "yy-mm-dd");

    });
});