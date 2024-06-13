jQuery( function( $ ){

    $("#form").validate({
        // errorPlacement: function (error, element) {
        //     return;
        // },
        // highlight: function(element) {
        //     $(element).addClass('is-invalid');
        //     $(element).parent().addClass("error");
        // },
        // unhighlight: function(element) {
        //     console.log(element);
        //     $(element).parent().removeClass("error");
        //     $(element).removeClass('is-invalid');
        // },
        submitHandler: function(form){
            var formData = new FormData($("#form")[0]);
            $.ajax({
                beforeSend:function(){
                    $("#form").find('button').attr('disabled',true);
                    $("#form").find('button>i').show();
                },
                url: $("#form").attr('action'),
                data: formData,
                type: 'POST',
                processData: false,
                contentType: false,
                success:function(dataJSON){
                    var response = JSON.parse(dataJSON)
                    if(response.status){
                        toastr.success(response.message, 'Success', {
                            timeOut: 3000,
                            extendedTimeOut: 2000,
                            progressBar: true,
                            closeButton: true,
                            tapToDismiss: false,
                            positionClass: "toast-top-right",
                        });
                        if (response.redirect_url !='') {
                            setTimeout(function(){
                                location.href = response.redirect_url;
                            },2000);
                        }else{
                            location.reload();
                        }
                    }else{
                        toastr.error(response.message, '', {
                            timeOut: 3000,
                            extendedTimeOut: 2000,
                            progressBar: true,
                            closeButton: true,
                            tapToDismiss: false,
                            positionClass: "toast-top-right",
                        });
                    }
                },
                complete:function(){
                    $("#form").find('button').attr('disabled',false);
                    $("#form").find('button>i').hide();
                },
                error:function(xhr, status, error){
                    var errors = JSON.parse(xhr.responseText);
                    if(xhr.status == 422){
                        $("#form").find('button').attr('disabled',false);
                        $("#form").find('button>i').hide();
                        $.each(errors.errors, function(i,v){
                            toastr.error(v, '', {
                                timeOut: 3000,
                                extendedTimeOut: 2000,
                                progressBar: true,
                                closeButton: true,
                                tapToDismiss: false,
                                positionClass: "toast-top-right",
                            });
						});
					}else{
						toastr.error(errors.message, 'Opps!', {
                            timeOut: 3000,
                            extendedTimeOut: 2000,
                            progressBar: true,
                            closeButton: true,
                            tapToDismiss: false,
                            positionClass: "toast-top-right",
                        });
					}
              	}
			});
			return false;
		}
	});
    
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
            toastrErrorMessage('Template name field is required!')
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
            toastrErrorMessage('Layout name field is required!')
            return false
        }
        if(layout_template_id==''){
            toastrErrorMessage('Template name field is required!')
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
});