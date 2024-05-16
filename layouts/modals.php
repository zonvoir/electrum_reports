<?php
require_once('../database.php');
$database = new Database();
$conn = $database->getConnection();
$funtionCol = 1;
if (!empty($_GET['id'])) {
    $layoutID = $_GET['id'];
    $queryTemplate = "SELECT layout_template.levels FROM layout_template JOIN layouts ON layout_template.id = layouts.layout_template_id WHERE layouts.id = :layoutID ";
    $statementTemplate = $conn->prepare($queryTemplate);
    $statementTemplate->bindParam(':layoutID', $layoutID, PDO::PARAM_INT);
    $statementTemplate->execute();
    $template =  $statementTemplate->fetch(PDO::FETCH_ASSOC);

    // check header column type is functions and restrict to add sub heading
    $funtionCol = $template ? $template['levels'] : 1;
}

?>


<div class="modal fade" id="addLayoutModal" tabindex="-1" aria-labelledby="addLayoutModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title fs-5" id="" style="font-size: 16px !important;">Add Layout</h3>
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 row">
                    <label for="inputTitle" class="col-sm-4 col-form-label text-end">Layout Name</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control form-control-sm" id="inputLayoutName">
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="selectTemplate" class="col-sm-4 col-form-label text-end">Template</label>
                    <div class="col-sm-6">
                        <select class="form-select" aria-label="Default select example" id="templatesForLayout">
                            <option selected>Select the template</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-sm add-layout-name">Save<i class="fa-regular fa-floppy-disk" style="margin-left: 10px;"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="titleModal" tabindex="-1" aria-labelledby="titleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="titleModalLabel"></h1>
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
                        <option value="">Select Function</option>
                        <option value="">Select Function</option>
                            <option value="ADDITION">Addition</option>
                            <option value="AV">Average</option>
                            <option value="CORRECTION">Correction</option>
                            <option value="COUNT">Count</option>
                            <option value="STDEV">Standard deviation</option>
                            <option value="SUB">Subtraction</option>
                            <option value="MAL">Multiplication</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-sm add-sub-title">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="subtitleModal" tabindex="-1" aria-labelledby="subTitleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="subTitleModalLabel"></h1>
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
                        <option value="">Select Function</option>
                            <option value="ADDITION">Addition</option>
                            <option value="AV">Average</option>
                            <option value="CORRECTION">Correction</option>
                            <option value="COUNT">Count</option>
                            <option value="STDEV">Standard deviation</option>
                            <option value="SUB">Subtraction</option>
                            <option value="MAL">Multiplication</option>
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

<div class="modal fade" id="addHeadingModal" tabindex="-1" aria-labelledby="addHeadingModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="addHeadingModal">Add Headings</h1>
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
                    <label for="column_type" class="col-sm-4 col-form-label text-end">Type <?php echo $funtionCol; ?></label>
                    <div class="col-sm-6">
                        <select class="form-control form-control-sm inputType" id="column_type">
                            <option value="DATA">DATA</option>

                            <?php if ($funtionCol == 1) { ?>
                                <option value="FUNCTION">FUNCTION</option>
                            <?php } ?>

                        </select>
                    </div>
                </div>
                <div class="mb-3 row" style="display: none;">
                    <label for="column_function" class="col-sm-4 col-form-label text-end">Function</label>
                    <div class="col-sm-6">
                        <select class="form-control form-control-sm inputFunction" id="column_function">
                        <option value="">Select Function</option>
                            <option value="ADDITION">Addition</option>
                            <option value="AV">Average</option>
                            <option value="CORRECTION">Correction</option>
                            <option value="COUNT">Count</option>
                            <option value="STDEV">Standard deviation</option>
                            <option value="SUB">Subtraction</option>
                            <option value="MAL">Multiplication</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-sm add-hedding">Save</button>
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
</div>

<div class="modal fade" id="editHeadingModal" tabindex="-1" aria-labelledby="headingEditModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="templateModalLabel">Edit Heading</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="mb-3 row">
                    <label for="cell-entered-values" class="col-sm-4 col-form-label text-end">Values</label>
                    <div class="col-sm-6">
                        <input type="hidden" id="headingId" />
                        <input type="text" class="form-control form-control-sm" id="heading-text" />
                    </div>
                </div>


                <div class="mb-3 row">
                    <label for="inputType" class="col-sm-4 col-form-label text-end">Type</label>
                    <div class="col-sm-6">
                        <select class="form-control form-control-sm inputType" id="column_type_edit">
                            <option value="DATA">DATA</option>
                            <option value="FUNCTION">FUNCTION</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3 row" style="display: none;">
                    <label for="inputFunction" class="col-sm-4 col-form-label text-end">Function</label>
                    <div class="col-sm-6">
                        <select class="form-control form-control-sm inputFunction" id="column_function_edit">
                            <option value="">Select Function</option>
                            <option value="ADDITION">Addition</option>
                            <option value="AV">Average</option>
                            <option value="CORRECTION">Correction</option>
                            <option value="COUNT">Count</option>
                            <option value="STDEV">Standard deviation</option>
                            <option value="SUB">Subtraction</option>
                            <option value="MAL">Multiplication</option>
                        </select>
                    </div>
                </div>



                <div class="mb-3 row">
                    <label for="inputType" class="col-sm-4 col-form-label text-end">Reference Columns</label>
                    <div class="col-sm-6 multi-select">
                        <select class="form-select inputFunction" id="multiple-columns" data-choices="data-choices" multiple="multiple" data-options='{"removeItemButton":true,"placeholder":true}'>
                            <option value="">Select columns... </option>
                            <?php
                            try {
                                $layout_id = $_GET['id']; // Define the layout template ID

                                $query = "SELECT layout_template_id FROM `layouts` WHERE id = :id";
                                $statement = $conn->prepare($query);
                                // Bind the layout template ID directly without using bindParam
                                $statement->bindValue(':id', $layout_id, PDO::PARAM_INT);
                                $statement->execute();
                                $result = $statement->fetch(PDO::FETCH_ASSOC);

                                // Check if a layout with the given ID exists
                                if ($result) {
                                    $queryh = "SELECT * FROM headings WHERE layout_template_id = :layout_template_id";
                                    $statementh = $conn->prepare($queryh);
                                    // Bind the layout template ID directly without using bindParam
                                    $statementh->bindValue(':layout_template_id', $result['layout_template_id'], PDO::PARAM_INT);
                                    $statementh->execute();
                                    $resulth = $statementh->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($resulth as $option) {
                            ?>
                                        <option value="<?= $option['id']; ?>"><?= $option['title']; ?></option>
                            <?php
                                    }
                                } else {
                                    echo "Layout not found."; // Handle the case where layout ID doesn't exist
                                }
                            } catch (\Throwable $th) {
                                echo $th; // Handle exceptions
                            }
                            ?>
                        </select>
                    </div>
                </div>





            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger delete-heading" data-bs-dismiss="modal">Delete</button>
                <button type="button" class="btn btn-primary update-heading" data-bs-dismiss="modal">Save</button>
                <!-- <button type="button" class="btn btn-primary check-values">Check</button> -->
            </div>
        </div>
    </div>
</div>

<!-- //Uncertainty Budget -->


<div class="modal fade" id="uncertaintyBudgetModal" tabindex="-1" aria-labelledby="uncertaintyBudgetModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="templateModalLabel">Uncertainty Budget</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 row">
                    <label for="cell-entered-values" class="col-sm-4 col-form-label text-end">Component</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control form-control-sm" id="component-text" />
                    </div>
                </div>
                <!-- <div class="mb-3 row">
                    <label for="cell-entered-values" class="col-sm-4 col-form-label text-end">Distribution</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control form-control-sm" id="distribution_text" />
                    </div>
                </div> -->
                <div class="mb-3 row">
                    <label for="inputType" class="col-sm-4 col-form-label text-end">Magnitude</label>
                    <div class="col-sm-6 multi-select">
                        <select class="form-select " id="heading-id-for-component" aria-label="Reference column headings">
                            <optgroup label="Table Columns">
                                <?php
                                try {
                                    $layout_id = $_GET['id']; // Define the layout template ID

                                    $query = "SELECT id,layout_template_id  FROM `layouts` WHERE id = :id";
                                    $statement = $conn->prepare($query);
                                    // Bind the layout template ID directly without using bindParam
                                    $statement->bindValue(':id', $layout_id, PDO::PARAM_INT);
                                    $statement->execute();
                                    $result = $statement->fetch(PDO::FETCH_ASSOC);

                                    // Check if a layout with the given ID exists
                                    if ($result) {
                                        $queryh = "SELECT * FROM headings WHERE layout_template_id = :layout_template_id";
                                        $statementh = $conn->prepare($queryh);
                                        // Bind the layout template ID directly without using bindParam
                                        $statementh->bindValue(':layout_template_id', $result['layout_template_id'], PDO::PARAM_INT);
                                        $statementh->execute();
                                        $resulth = $statementh->fetchAll(PDO::FETCH_ASSOC);

                                        foreach ($resulth as $option) {
                                ?>
                                            <option value="<?= $option['id']; ?>"><?= $option['title']; ?></option>
                                <?php
                                        }
                                    } else {
                                        echo "Layout not found."; // Handle the case where layout ID doesn't exist
                                    }
                                } catch (\Throwable $th) {
                                    echo $th; // Handle exceptions
                                }
                                ?>
                            </optgroup>
                            <optgroup label="Fixed Inputs">
                                <option value="-1">Resolution Ref</option>
                                <option value="-2">Resolution UUC</option>
                                <option value="-3">Ref Uncert</option>
                            </optgroup>
                            </optgroup>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger delete-component" data-bs-dismiss="modal">Delete</button>
                <button type="button" class="btn btn-primary add-component" data-bs-dismiss="modal">Add</button>
                <!-- <button type="button" class="btn btn-primary check-values">Check</button> -->
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
                <button type="button" class="btn btn-primary add-cell-value" data-bs-dismiss="modal">Add Value</button>
            </div>
        </div>
    </div>
</div>
<style>
    table th,
    td {
        border: 1px solid #000;
        padding: 5px;
    }
</style>


<div class="modal fade" id="tabel-2-preview" tabindex="-1" aria-labelledby="tabel-2-preview" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen" style="padding:20px">
        <div class="modal-content" style="padding: 10px;">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="uncertainty_budget">Uncertainty Budget</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 row" id="tabel-2-preview-data">



                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="submitButton">Add Value</button>
                <!-- <button type="button" class="btn btn-primary add-template-name" data-bs-dismiss="modal">Close</button> -->
            </div>
        </div>
    </div>
</div>