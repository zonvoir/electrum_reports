<footer class="footer position-absolute">
    <div class="row g-0 justify-content-between align-items-center h-100">
        <div class="col-12 col-sm-auto text-center">
            <p class="mb-0 mt-2 mt-sm-0 text-body">all rights reserved<span class="d-none d-sm-inline-block"></span><span class="d-none d-sm-inline-block mx-1">|</span><br class="d-sm-none" />2024 &copy;<a class="mx-1" href="https://electruments.com/">Electruments</a></p>
        </div>
        <div class="col-12 col-sm-auto text-center">
            <p class="mb-0 text-body-tertiary text-opacity-85">v1.15.0</p>
        </div>
    </div>
</footer>
</div>
<script>
    var navbarTopStyle = window.config.config.phoenixNavbarTopStyle;
    var navbarTop = document.querySelector('.navbar-top');
    if (navbarTopStyle === 'darker') {
        navbarTop.setAttribute('data-navbar-appearance', 'darker');
    }

    var navbarVerticalStyle = window.config.config.phoenixNavbarVerticalStyle;
    var navbarVertical = document.querySelector('.navbar-vertical');
    if (navbarVertical && navbarVerticalStyle === 'darker') {
        navbarVertical.setAttribute('data-navbar-appearance', 'darker');
    }
</script>

</main>
<!-- ===============================================-->
<!--    End of Main Content-->
<!-- ===============================================-->


<!-- ===============================================-->
<!--    JavaScripts-->
<!-- ===============================================-->
<script src="../vendors/popper/popper.min.js"></script>
<script src="../vendors/bootstrap/bootstrap.min.js"></script>
<script src="../vendors/anchorjs/anchor.min.js"></script>
<script src="../vendors/is/is.min.js"></script>
<script src="../vendors/fontawesome/all.min.js"></script>
<script src="../vendors/lodash/lodash.min.js"></script>
<script src="https://polyfill.io/v3/polyfill.min.js?features=window.scroll"></script>
<script src="../vendors/list.js/list.min.js"></script>
<script src="../vendors/feather-icons/feather.min.js"></script>
<script src="../vendors/dayjs/dayjs.min.js"></script>
<script src="../assets/js/phoenix.js"></script>

<script src="../vendors/flatpickr/flatpickr.min.js"></script>

<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.11.5/datatables.min.js"></script>
<script>
    $(document).ready(function() {


        function loadTemplates() {
            // Check if DataTable is already initialized on the target table
            if ($.fn.DataTable.isDataTable('#template-table')) {
                // DataTable already initialized, destroy it first
                $('#template-table').DataTable().destroy();
            }

            $('#template-table').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "../functions/provider.php",
                    "type": "POST",
                    "data": function(d) {
                        d.action = "allTemplates"; // Add the action parameter
                    },
                    "dataSrc": function(json) {
                        console.log('JSON Response:', json);
                        return json.result.data;
                    },
                },
                "columns": [{
                        "data": null,
                        "render": function(data, type, row, meta) {
                            return '<td class="fs-9 align-middle ps-0 py-3">' +
                                '<div class="form-check mb-0 fs-8">' +
                                '<input class="form-check-input" type="checkbox" data-bulk-select-row=\'{"customer":{"avatar":"/team/32.webp","name":"Carry Anna"},"email":"annac34@gmail.com","city":"Budapest","totalOrders":89,"totalSpent":23987,"lastSeen":"34 min ago","lastOrder":"Dec 12, 12:56 PM"}\' />' +
                                '</div>' +
                                '</td>';
                        }
                    },
                    {
                        "data": "id",
                        "className": "align-middle white-space-nowrap pe-5"
                    },
                    {
                        "data": "template_name",
                        "className": "email align-middle white-space-nowrap pe-5"
                    },
                    {
                        "data": null,
                        "defaultContent": " <i class='fa-solid fa-trash template-delete-btn'></i>",
                        "orderable": false,
                        "className": "text-end"
                    },
                    {
                        "data": null,
                        "defaultContent": " <i class='fa-solid fa-pen-to-square edit-btn'></i>",
                        "orderable": false,
                        "className": "text-end"
                    }
                ],
                "pagingType": "full_numbers",
                "searching": false,
                "createdRow": function(row, data, dataIndex) {
                    $(row).addClass('hover-actions-trigger btn-reveal-trigger position-static');
                },
                "language": {
                    "paginate": {
                        "previous": "<i class='fas fa-chevron-left'></i>",
                        "next": "<i class='fas fa-chevron-right'></i>"
                    }
                }
            });
        }

        function loadLayouts() {
            // Check if DataTable is already initialized on the target table
            if ($.fn.DataTable.isDataTable('#layouts-table')) {
                // DataTable already initialized, destroy it first
                $('#layouts-table').DataTable().destroy();
            }

            $('#layouts-table').DataTable({
                // "draw": 1,
                // "recordsTotal": 1,
                // "recordsFiltered": 1,
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "../functions/provider.php",
                    "type": "POST",
                    "data": function(d) {
                        d.action = "allLayouts"; // Add the action parameter
                    },
                    "dataSrc": function(json) {
                        console.log('JSON Response:', json);
                        return json.result.data;
                    },
                },
                "columns": [{
                        "data": null,
                        "render": function(data, type, row, meta) {
                            return '<td class="fs-9 align-middle ps-0 py-3">' +
                                '<div class="form-check mb-0 fs-8">' +
                                '<input class="form-check-input" type="checkbox" data-bulk-select-row=\'{"customer":{"avatar":"/team/32.webp","name":"Carry Anna"},"email":"annac34@gmail.com","city":"Budapest","totalOrders":89,"totalSpent":23987,"lastSeen":"34 min ago","lastOrder":"Dec 12, 12:56 PM"}\' />' +
                                '</div>' +
                                '</td>';
                        }
                    },
                    {
                        "data": "id",
                        "className": "align-middle white-space-nowrap pe-5"
                    },
                    {
                        "data": "layout_name",
                        "className": "email align-middle white-space-nowrap pe-5"
                    },
                    {
                        "data": "template_name",
                        "className": "email align-middle white-space-nowrap pe-5"
                    },
                    {
                        "data": null,
                        "defaultContent": " <i class='fa-solid fa-trash delete-btn'></i>",
                        "orderable": false,
                        "className": "text-end"
                    },
                    {
                        "data": null,
                        "defaultContent": " <i class='fa-solid fa-pen-to-square edit-btn'></i>",
                        "orderable": false,
                        "className": "text-end"
                    }
                ],
                "pagingType": "full_numbers",
                "searching": false,
                "createdRow": function(row, data, dataIndex) {
                    $(row).addClass('hover-actions-trigger btn-reveal-trigger position-static');
                },
                "language": {
                    "paginate": {
                        "previous": "<i class='fas fa-chevron-left'></i>",
                        "next": "<i class='fas fa-chevron-right'></i>"
                    }
                }
            });
        }

        loadLayouts();
        loadTemplates();

        $(document).on('click', '.template-delete-btn', function() {
            var data = $('#template-table').DataTable().row($(this).parents('tr')).data();
            var templateId = data.id;
            deleteTemplate(templateId);
        });

        $(document).on('click', '.template-edit-btn', function() {
            // Handle delete button click here
            var data = $('#layouts-table').DataTable().row($(this).parents('tr')).data();
            var layoutId = data.id;
            //window.location.href = 'edit.php?id=' + layoutId;

            //open a modal to edit template name 
        });

        $(document).on('click', '.edit-btn', function() {
            // Handle delete button click here
            var data = $('#layouts-table').DataTable().row($(this).parents('tr')).data();
            var layoutId = data.id;
            window.location.href = 'edit.php?id=' + layoutId;

            //open a modal to edit template name 
        });


        $('#addLayout').on('click', function(e) {
            e.preventDefault();

            // Get templates using the provided function
            getTemplates().then(function(data) {
                //console.log('Response Data:', data); // Log response data to inspect its structure

                // Check if response has a status of "success"
                if (data.status === 'success') {
                    // Clear existing options
                    $('#templatesForLayout').empty();

                    // Add an option for selecting a template
                    $('#templatesForLayout').append('<option value="">Select the template</option>');

                    // Loop through the retrieved templates
                    for (var i = 0; i < data.result.length; i++) {
                        var template = data.result[i];
                        console.log('Template:', template); // Log each template object to inspect its properties

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


        function getTemplates() {
            var deferred = $.Deferred();

            $.ajax({
                type: 'POST',
                url: '../functions/provider.php',
                data: {
                    action: 'loadTemplates'
                },
                dataType: 'json',
                success: function(data) {
                    console.log('Success Response:', data); // Log success response to inspect data
                    deferred.resolve(data);
                },
                error: function(xhr, status, error) {
                    console.error('Error Response:', status, error); // Log error response for debugging
                    deferred.reject('Error loading data.');
                }
            });

            return deferred.promise();
        }


        $('#addLayoutModal').on('hidden.bs.modal', function() {
            // Load layouts into the select after the modal is fully shown
            loadLayouts();
        });



        function deleteTemplate(templateId) {
            $.ajax({
                type: 'POST',
                url: '../functions/provider.php',
                data: {
                    action: 'removeTemplate',
                    template_id: templateId
                },
                dataType: 'json',
                success: function(data) {
                    loadTemplates();
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


        $(document).on('click', '.add-layout-name', function() {
            var layoutName = $('#inputLayoutName').val();
            var template = $("#templatesForLayout").val();
            $.ajax({
                type: 'POST',
                url: '../functions/provider.php',
                data: {
                    action: 'addNewLayout',
                    layout_name: layoutName,
                    template: template
                },
                success: function(data) {
                    // $('#method_of_tests').val(data.description);
                    loadLayouts();
                    $('#addLayoutModal').modal('hide');
                },
                error: function() {
                    alert('Error loading Layouts.');
                }
            });
        });


        $(document).on('click', '.add-template-name', function() {
            var templateName = $('#inputTemplateName').val();
            var templateHeadingLevels = $('#inputTemplateHeadingLevel').val();
            $.ajax({
                type: 'POST',
                url: '../functions/provider.php',
                data: {
                    action: 'addNewTemplate',
                    template_name: templateName,
                    levels: templateHeadingLevels,
                },
                success: function(data) {
                    // $('#method_of_tests').val(data.description);
                    loadTemplates();
                    $('#addTemplateModal').modal('hide');
                },
                error: function() {
                    alert('Error loading Layouts.');
                }
            });
        });







    });
</script>
</body>




</html>