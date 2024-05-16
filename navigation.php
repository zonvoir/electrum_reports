<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container">
        <a class="navbar-brand" href="#"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="index.php" style="color: #FCFEFC;font-size: 25px;">Electruments</a>
                </li>
                <li class="nav-item dropdown" style="margin-top: 6px;color: #FCFEFC;">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: #FCFEFC;">
                        Actions
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item addTemplate" href="javascript:void(0);">Add new Template</a></li>
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#addLayoutModal">Add new Layout</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="templates.php">All Templates</a></li>
                        <li><a class="dropdown-item" href="layouts.php">All Layouts</a></li>
                        <!-- <li>
                            <hr class="dropdown-divider">
                        </li> -->
                        <!-- <li><a class="dropdown-item" href="#">Something else here</a></li> -->
                    </ul>
                </li>
            </ul>
        </div>
        <div class="mb-3 row">
            <div class="col">
                <!-- This empty div will take up the remaining space -->
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-gear"></i> Generate</button>
            </div>
        </div>
    </div>
</nav>
