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
                        $statement = $conn->query($query);
                        $templates = $statement->fetchAll(PDO::FETCH_ASSOC);
                        ?>
                        <select class="form-select form-select-sm" aria-label="" id="layout_template_id" required>
                            <option value="">Select the template</option>
                            <?php
                            foreach ($templates as $template) {
                                $templateId = $template['id'];
                                $templateName = $template['template_name'];
                                echo "<option value=\"$templateId\">$templateName</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
                            <!-- <option value="TUC">TEST UNIT CONVERTION</option> -->
                            <!-- <option value="TS">TEST STDEV</option> -->
                            <!-- <option value="TC">TEST COUNT</option> -->
                            <option value="RS">REF STDEV</option>
                            <!-- <option value="RC">REF COUNT</option> -->
                            <option value="UC">UUC CONVERT</option>
                            <option value="RM">REF MEAN</option>
                            <option value="UCM">UUC CONVERT MEAN</option>
                            <option value="UM">UUC MEAN</option>
                            <option value="RUC">REF UNIT CON</option>
                            <option value="CUS">COVERTD UUC STDEV</option>
                            <option value="CR">CORRECTED REF</option>
                            <!-- <option value="VC">Voltage Calculate</option> -->
                        </select>
                    </div>
                </div>

                <div class="mb-3 row" style="display: none;">
                    <label for="column_function_multi_select" class="col-sm-4 col-form-label text-end">Function fields</label>
                    <div class="col-sm-6">
                        <select class="form-control select2 inputFunction" id="column_function_options_add" multiple>
                        </select>
                    </div>
                </div>

                <div class="mb-6 row">
                    <label for="column_type_edit" class="col-sm-4 col-form-label text-end">&nbsp;</label>
                    <div class="col-sm-3">
                        <input class="form-check-input" name="multi_line" type="checkbox" id="multi_line"> Multi-line
                    </div>
                    <div class="col-sm-3">
                        <input class="form-check-input" name="data_entry" type="checkbox" id="data_entry"> Data Entry
                    </div>
                </div>

                <div class="mb-6 row">
                    <label for="column_type_edit" class="col-sm-4 col-form-label text-end">&nbsp;</label>
                    <div class="col-sm-3">
                        <input class="form-check-input" name="analysis" type="checkbox" id="analysis" disabled checked> Analysis
                    </div>
                    <div class="col-sm-3">
                        <input class="form-check-input" name="report" type="checkbox" id="report"> Report
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary store-hedding">Save</button>
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
                        <select class="form-control form-control-sm" id="inputLevelEdit">
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
                            <option value="">Select Function</option>
                            <option value="CORRECTION">CORRECTION</option>
                            <!-- <option value="TUC">TEST UNIT CONVERTION</option> -->
                            <!-- <option value="TS">TEST STDEV</option> -->
                            <!-- <option value="TC">TEST COUNT</option> -->
                            <option value="RS">REF STDEV</option>
                            <!-- <option value="RC">REF COUNT</option> -->
                            <option value="UC">UUC CONVERT</option>
                            <option value="RM">REF MEAN</option>
                            <option value="UCM">UUC CONVERT MEAN</option>
                            <option value="UM">UUC MEAN</option>
                            <option value="RUC">REF UNIT CON</option>
                            <option value="CUS">COVERTD UUC STDEV</option>
                            <option value="CR">CORRECTED REF</option>
                            <!-- <option value="VC">Voltage Calculate</option> -->
                        </select>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="column_function_multi_select" class="col-sm-4 col-form-label text-end">Function fields</label>
                    <div class="col-sm-6">
                        <select class="form-control select2 inputFunction" id="column_function_options" multiple>
                        </select>
                    </div>
                </div>
        
                <div class="mb-6 row">
                    <label for="column_type_edit" class="col-sm-4 col-form-label text-end">&nbsp;</label>
                    <div class="col-sm-3">
                        <input class="form-check-input" name="multi_line" type="checkbox" id="multi_line_edit"> Multi-line
                    </div>
                    <div class="col-sm-3">
                        <input class="form-check-input" name="data_entry" type="checkbox" id="data_entry_edit"> Data Entry
                    </div>
                </div>
                <div class="mb-6 row">
                    <label for="column_type_edit" class="col-sm-4 col-form-label text-end">&nbsp;</label>
                    <div class="col-sm-3">
                        <input class="form-check-input " name="analysis" type="checkbox" id="analysis_edit" disabled checked> Analysis
                    </div>
                    <div class="col-sm-3">
                        <input class="form-check-input" name="report" type="checkbox" id="report_edit"> Report
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


<div class="modal fade" id="uncertaintyBudgetModal" tabindex="-1" aria-labelledby="uncertaintyBudgetModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="templateModalLabel">Uncertainty Budget</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 row">
                    <label for="component_name" class="col-sm-4 col-form-label text-end">Component</label>
                    <div class="col-sm-6">
                        <input type="text" id="component_name" class="form-control form-control-sm" />
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="heading_id" class="col-sm-4 col-form-label text-end">Magnitude</label>
                    <div class="col-sm-6 multi-select">
                        <select id="heading_id" class="form-control magnitudeHeadingsGroup">
                            <!-- <optgroup label="Table Columns" id="magnitudeHeadingsGroup">
                                <option value="">Select</option>
                            </optgroup>
                            <optgroup label="Fixed Inputs">
                                <option value="-1">Resolution Ref</option>
                                <option value="-2">Resolution UUC</option>
                                <option value="-3">Ref Uncert</option>
                            </optgroup> -->
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger component-delete-btn">Delete</button>
                <button type="button" class="btn btn-primary component-submit-btn">Submit</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="table2Modal" tabindex="-1" aria-labelledby="table2Modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="max-width: 900px;">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="templateModalLabel">Uncertainty Budget</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
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
                                <th class="text-nowrap">Std uncert</th>
                                <th>DOF</th>
                            </tr>
                        </thead>
                        <tbody id="table2tbody">
                            
                        </tbody>
                    </table>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">Expanded uncertainty reporting</th>
                                <th class="text-center"><button type="button" class="btn btn-primary btn-sm addValues">Add Values</button></th>
                            </tr>
                        </thead>
                        <tbody id="table3tbody" style="display: none;">
                            <tr>
                                <td>Combined Standard Uncertainty</td>
                                <td><input type="text" name="csu" class="form-control csu" readonly /></td>
                            </tr>
                            <tr>
                                <td>Effective Degree of Freedom</td>
                                <td>Info</td>
                            </tr>
                            <tr>
                                <td>Coverage factor (K) at 95% C.L.</td>
                                <td>2</td>
                            </tr>
                            <tr><td colspan="2"></td></tr>
                            <tr>
                                <td>Expanded Uncertainty</td>
                                <td><input type="text" name="expanded_uncertainty" class="form-control expanded_uncertainty" readonly /></td>
                            </tr>
                            <tr>
                                <td>CMC Claimed</td>
                                <td><input type="text" name="cmc_claimed" class="form-control cmc_claimed" value="0.5" readonly /></td>
                            </tr>
                            <tr>
                                <td>Expanded Uncertainty - to report</td>
                                <td><input type="text" name="report" class="form-control report" value="" readonly /></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- <div class="row">
                    <div class="col-md-12 text-end">
                        <button type="button" class="btn btn-primary btn-sm">Add Values</button>
                    </div>
                </div> -->
            </div>
        </div>
    </div>
</div>