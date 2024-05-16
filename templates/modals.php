<?php
require_once('../database.php');
$database = new Database();
$conn = $database->getConnection();

?>


<div class="modal fade" id="addTemplateModal" tabindex="-1" aria-labelledby="addTemplateModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title fs-5" id="" style="font-size: 16px !important;">Add Template</h3>
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 row">
                    <label for="inputTitle" class="col-sm-4 col-form-label text-end">Template Name</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control form-control-sm" id="inputTemplateName">
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="inputTemplateHeadingLevel" class="col-sm-4 col-form-label text-end">No of Heading Levels</label>
                    <div class="col-sm-6">
                        <select class="form-select" id="inputTemplateHeadingLevel" aria-label="Default select example">
                           <option value="1" selected >One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-sm add-template-name">Save<i class="fa-regular fa-floppy-disk" style="margin-left: 10px;"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>