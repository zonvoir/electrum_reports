<div class="modal fade" id="templateModal" tabindex="-1" aria-labelledby="templateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="templateModalLabel"></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <label for="templateName" class="col-sm-4 col-form-label text-end">Template Name</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control form-control-sm" id="templateName">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" class="form-control form-control-sm" id="template_id" value="0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary add-and-update-template">Submit</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addLayoutModal" tabindex="-1" aria-labelledby="addLayoutModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="">Add Layout</h1>
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 row">
                    <label for="inputTitle" class="col-sm-4 col-form-label text-end">Layout name</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control form-control-sm" id="inputLayoutName">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="selectTemplate" class="col-sm-4 col-form-label text-end">Template</label>
                    <div class="col-sm-6">
                        <?php
                        $query = "SELECT id, template_name FROM layout_template";
                        // Execute the query
                        $stmt = $conn->query($query);
                        if ($stmt) {
                        ?>
                            <select class="form-select form-select-sm" aria-label="" id="layout_template_id" required>
                                <option value="">Select the template</option>
                                <?php
                                $templates = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($templates as $template) {
                                    $templateId = $template['id'];
                                    $templateName = $template['template_name'];
                                    echo "<option value=\"$templateId\">$templateName</option>";
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-sm add-layout-name">Save<i class="fa-regular fa-floppy-disk" style="margin-left: 10px;"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addHeadingModal" tabindex="-1" aria-labelledby="addHeadingModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="addHeadingModal">Add Heading</h1>
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 row">
                    <label for="inputTitle" class="col-sm-4 col-form-label text-end">Title</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control form-control-sm" id="inputTitle">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="inputLevel" class="col-sm-4 col-form-label text-end">Level</label>
                    <div class="col-sm-6">
                        <select class="form-control form-control-sm inputType" id="inputLevel">
                        </select>
                    </div>
                </div>  
                <div class="mb-3 row">
                    <label for="inputColspan" class="col-sm-4 col-form-label text-end">Colspan</label>
                    <div class="col-sm-6">
                        <input type="number" class="form-control form-control-sm" id="inputColspan">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="column_type" class="col-sm-4 col-form-label text-end">Type</label>
                    <div class="col-sm-6">
                        <select class="form-control form-control-sm inputType" id="column_type">
                            <option value="DATA">DATA</option>
                            <option value="FUNCTION">FUNCTION</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3 row" style="display: none;">
                    <label for="column_function" class="col-sm-4 col-form-label text-end">Function</label>
                    <div class="col-sm-6">
                        <select class="form-control form-control-sm inputFunction" id="column_function">
                            <option value="">Select Function</option>
                            <option value="CORRECTION">CORRECTION</option>
                            <option value="TUC">TEST UNIT CONVERTION</option>
                            <option value="TS">TEST STDEV</option>
                            <option value="TC">TEST COUNT</option>
                            <option value="RS">REF STDEV</option>
                            <option value="RC">REF COUNT</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-sm add-hedding">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editHeadingModal" tabindex="-1" aria-labelledby="headingEditModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="headingEditModal">Edit Heading</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 row">
                    <label for="inputTitleEdit" class="col-sm-4 col-form-label text-end">Title</label>
                    <div class="col-sm-6">
                        <input type="hidden" id="headingId" />
                        <input type="text" class="form-control form-control-sm" id="inputTitleEdit" />
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="inputLevelEdit" class="col-sm-4 col-form-label text-end">Level</label>
                    <div class="col-sm-6">
                        <select class="form-control form-control-sm inputType" id="inputLevelEdit">
                        </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="inputColspanEdit" class="col-sm-4 col-form-label text-end">Colspan</label>
                    <div class="col-sm-6">
                        <input type="number" class="form-control form-control-sm" id="inputColspanEdit">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="column_type_edit" class="col-sm-4 col-form-label text-end">Type</label>
                    <div class="col-sm-6">
                        <select class="form-control form-control-sm inputType" id="column_type_edit">
                            <option value="DATA">DATA</option>
                            <option value="FUNCTION">FUNCTION</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3 row" style="display: none;">
                    <label for="column_function_edit" class="col-sm-4 col-form-label text-end">Function</label>
                    <div class="col-sm-6">
                        <select class="form-control form-control-sm inputFunction" id="column_function_edit">
                            <option value="CORRECTION">CORRECTION</option>
                            <option value="TUC">TEST UNIT CONVERTION</option>
                            <option value="TS">TEST STDEV</option>
                            <option value="TC">TEST COUNT</option>
                            <option value="RS">REF STDEV</option>
                            <option value="RC">REF COUNT</option>
                            <option value="VC">Voltage Calculate</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger delete-heading" data-bs-dismiss="modal">Delete</button>
                <button type="button" class="btn btn-primary update-heading" data-bs-dismiss="modal">Save</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="subTitleModal" tabindex="-1" aria-labelledby="subTitleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="subTitleModalLabel"></h1>
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 row">
                    <label for="inputSubTitle" class="col-sm-4 col-form-label text-end">Sub Title</label>
                    <div class="col-sm-6">
                        <input type="hidden" class="form-control form-control-sm" id="inputHeddigsId">
                        <input type="text" class="form-control form-control-sm" id="inputSubTitle">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="inputType" class="col-sm-4 col-form-label text-end">Type</label>
                    <div class="col-sm-6">
                        <select class="form-control form-control-sm inputType" id="column_type_add_sub_title">
                            <option value="DATA">DATA</option>
                            <option value="FUNCTION">FUNCTION</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3 row" style="display: none;">
                    <label for="inputFunction" class="col-sm-4 col-form-label text-end">Function</label>
                    <div class="col-sm-6">
                        <select class="form-control form-control-sm inputFunction" id="column_function_add_sub_title">
                            <option value="CORRECTION">CORRECTION</option>
                            <option value="TUC">TEST UNIT CONVERTION</option>
                            <option value="TS">TEST STDEV</option>
                            <option value="TC">TEST COUNT</option>
                            <option value="RS">REF STDEV</option>
                            <option value="RC">REF COUNT</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-sm storeSubTitle">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="secondSubtitleModal" tabindex="-1" aria-labelledby="secondSubTitleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="secondSubTitleModalLabel"></h1>
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 row">
                    <label for="inputSecondSubTitle" class="col-sm-4 col-form-label text-end">Sub Title</label>
                    <div class="col-sm-6">
                        <input type="hidden" class="form-control form-control-sm" id="inputSubHeddigsId">
                        <input type="text" class="form-control form-control-sm" id="inputSecondSubTitle">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="column_type" class="col-sm-4 col-form-label text-end">Type</label>
                    <div class="col-sm-6">
                        <select class="form-control form-control-sm inputType" id="column_type_second_sub_title">
                            <option value="DATA">DATA</option>
                            <option value="FUNCTION">FUNCTION</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3 row" style="display: none;">
                    <label for="column_function" class="col-sm-4 col-form-label text-end">Function</label>
                    <div class="col-sm-6">
                        <select class="form-control form-control-sm inputFunction" id="column_function_second_sub_title">
                            <option value="">Select Function</option>
                            <option value="CORRECTION">CORRECTION</option>
                            <option value="TUC">TEST UNIT CONVERTION</option>
                            <option value="TS">TEST STDEV</option>
                            <option value="TC">TEST COUNT</option>
                            <option value="RS">REF STDEV</option>
                            <option value="RC">REF COUNT</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-sm add-second-sub-title">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="cellValueModal" tabindex="-1" aria-labelledby="cellValueModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="cellValueModalLabel">Add Cell Data</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 row">
                    <label for="cell-entered-values" class="col-sm-4 col-form-label text-end">Values</label>
                    <div class="col-sm-6">
                        <textarea class="form-control form-control-sm" id="cell-entered-values"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary add-template-name" data-bs-dismiss="modal">Add Value</button>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        $(document).on('change', '.inputType', function() {
            if ($(this).val() === 'FUNCTION') {
                $('.inputFunction').closest('.row').show();
            } else {
                $('.inputFunction').closest('.row').hide();
            }
        });
    });
</script>