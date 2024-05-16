<?php
require_once('../database.php');
$database = new Database();
$conn = $database->getConnection();

require '../theme/layout_header.php';
require '../theme/layout_navigations.php';
include 'modals.php';
?>
<div class="modal fade" id="searchBoxModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="true" data-phoenix-modal="data-phoenix-modal" style="--phoenix-backdrop-opacity: 1;">
    <div class="modal-dialog">
        <div class="modal-content mt-15 rounded-pill">
            <div class="modal-body p-0">
                <div class="search-box navbar-top-search-box" data-list='{"valueNames":["title"]}' style="width: auto;">
                    <form class="position-relative" data-bs-toggle="search" data-bs-display="static">
                        <input class="form-control search-input fuzzy-search rounded-pill form-control-lg" type="search" placeholder="Search..." aria-label="Search" />
                        <span class="fas fa-search search-box-icon"></span>

                    </form>
                    <div class="btn-close position-absolute end-0 top-50 translate-middle cursor-pointer shadow-none" data-bs-dismiss="search">
                        <button class="btn btn-link p-0" aria-label="Close"></button>
                    </div>
                    <div class="dropdown-menu border start-0 py-0 overflow-hidden w-100">
                        <div class="scrollbar-overlay" style="max-height: 30rem;">
                            <div class="list pb-3">
                                <h6 class="dropdown-header text-body-highlight fs-10 py-2">24 <span class="text-body-quaternary">results</span></h6>
                                <hr class="my-0" />
                                <h6 class="dropdown-header text-body-highlight fs-9 border-bottom border-translucent py-2 lh-sm">Recently Searched </h6>
                                <div class="py-2"><a class="dropdown-item" href="../../../apps/e-commerce/landing/product-details.html">
                                        <div class="d-flex align-items-center">

                                            <div class="fw-normal text-body-highlight title"><span class="fa-solid fa-clock-rotate-left" data-fa-transform="shrink-2"></span> Store Macbook</div>
                                        </div>
                                    </a>
                                    <a class="dropdown-item" href="../../../apps/e-commerce/landing/product-details.html">
                                        <div class="d-flex align-items-center">

                                            <div class="fw-normal text-body-highlight title"> <span class="fa-solid fa-clock-rotate-left" data-fa-transform="shrink-2"></span> MacBook Air - 13″</div>
                                        </div>
                                    </a>

                                </div>
                                <hr class="my-0" />
                                <h6 class="dropdown-header text-body-highlight fs-9 border-bottom border-translucent py-2 lh-sm">Products</h6>
                                <div class="py-2"><a class="dropdown-item py-2 d-flex align-items-center" href="../../../apps/e-commerce/landing/product-details.html">
                                        <div class="file-thumbnail me-2"><img class="h-100 w-100 fit-cover rounded-3" src="../assets/img/products/60x60/3.png" alt="" /></div>
                                        <div class="flex-1">
                                            <h6 class="mb-0 text-body-highlight title">MacBook Air - 13″</h6>
                                            <p class="fs-10 mb-0 d-flex text-body-tertiary"><span class="fw-medium text-body-tertiary text-opactity-85">8GB Memory - 1.6GHz - 128GB Storage</span></p>
                                        </div>
                                    </a>
                                    <a class="dropdown-item py-2 d-flex align-items-center" href="../../../apps/e-commerce/landing/product-details.html">
                                        <div class="file-thumbnail me-2"><img class="img-fluid" src="../assets/img/products/60x60/3.png" alt="" /></div>
                                        <div class="flex-1">
                                            <h6 class="mb-0 text-body-highlight title">MacBook Pro - 13″</h6>
                                            <p class="fs-10 mb-0 d-flex text-body-tertiary"><span class="fw-medium text-body-tertiary text-opactity-85 ms-2">30 Sep at 12:30 PM</span></p>
                                        </div>
                                    </a>

                                </div>
                                <hr class="my-0" />
                                <h6 class="dropdown-header text-body-highlight fs-9 border-bottom border-translucent py-2 lh-sm">Quick Links</h6>
                                <div class="py-2"><a class="dropdown-item" href="../../../apps/e-commerce/landing/product-details.html">
                                        <div class="d-flex align-items-center">

                                            <div class="fw-normal text-body-highlight title"><span class="fa-solid fa-link text-body" data-fa-transform="shrink-2"></span> Support MacBook House</div>
                                        </div>
                                    </a>
                                    <a class="dropdown-item" href="../../../apps/e-commerce/landing/product-details.html">
                                        <div class="d-flex align-items-center">

                                            <div class="fw-normal text-body-highlight title"> <span class="fa-solid fa-link text-body" data-fa-transform="shrink-2"></span> Store MacBook″</div>
                                        </div>
                                    </a>

                                </div>
                                <hr class="my-0" />
                                <h6 class="dropdown-header text-body-highlight fs-9 border-bottom border-translucent py-2 lh-sm">Files</h6>
                                <div class="py-2"><a class="dropdown-item" href="../../../apps/e-commerce/landing/product-details.html">
                                        <div class="d-flex align-items-center">

                                            <div class="fw-normal text-body-highlight title"><span class="fa-solid fa-file-zipper text-body" data-fa-transform="shrink-2"></span> Library MacBook folder.rar</div>
                                        </div>
                                    </a>
                                    <a class="dropdown-item" href="../../../apps/e-commerce/landing/product-details.html">
                                        <div class="d-flex align-items-center">

                                            <div class="fw-normal text-body-highlight title"> <span class="fa-solid fa-file-lines text-body" data-fa-transform="shrink-2"></span> Feature MacBook extensions.txt</div>
                                        </div>
                                    </a>
                                    <a class="dropdown-item" href="../../../apps/e-commerce/landing/product-details.html">
                                        <div class="d-flex align-items-center">

                                            <div class="fw-normal text-body-highlight title"> <span class="fa-solid fa-image text-body" data-fa-transform="shrink-2"></span> MacBook Pro_13.jpg</div>
                                        </div>
                                    </a>

                                </div>
                                <hr class="my-0" />
                                <h6 class="dropdown-header text-body-highlight fs-9 border-bottom border-translucent py-2 lh-sm">Members</h6>
                                <div class="py-2"><a class="dropdown-item py-2 d-flex align-items-center" href="../../../pages/members.html">
                                        <div class="avatar avatar-l status-online  me-2 text-body">
                                            <img class="rounded-circle " src="../assets/img/team/40x40/10.webp" alt="" />

                                        </div>
                                        <div class="flex-1">
                                            <h6 class="mb-0 text-body-highlight title">Carry Anna</h6>
                                            <p class="fs-10 mb-0 d-flex text-body-tertiary">anna@technext.it</p>
                                        </div>
                                    </a>
                                    <a class="dropdown-item py-2 d-flex align-items-center" href="../../../pages/members.html">
                                        <div class="avatar avatar-l  me-2 text-body">
                                            <img class="rounded-circle " src="../assets/img/team/40x40/12.webp" alt="" />

                                        </div>
                                        <div class="flex-1">
                                            <h6 class="mb-0 text-body-highlight title">John Smith</h6>
                                            <p class="fs-10 mb-0 d-flex text-body-tertiary">smith@technext.it</p>
                                        </div>
                                    </a>

                                </div>
                                <hr class="my-0" />
                                <h6 class="dropdown-header text-body-highlight fs-9 border-bottom border-translucent py-2 lh-sm">Related Searches</h6>
                                <div class="py-2"><a class="dropdown-item" href="../../../apps/e-commerce/landing/product-details.html">
                                        <div class="d-flex align-items-center">

                                            <div class="fw-normal text-body-highlight title"><span class="fa-brands fa-firefox-browser text-body" data-fa-transform="shrink-2"></span> Search in the Web MacBook</div>
                                        </div>
                                    </a>
                                    <a class="dropdown-item" href="../../../apps/e-commerce/landing/product-details.html">
                                        <div class="d-flex align-items-center">

                                            <div class="fw-normal text-body-highlight title"> <span class="fa-brands fa-chrome text-body" data-fa-transform="shrink-2"></span> Store MacBook″</div>
                                        </div>
                                    </a>

                                </div>
                            </div>
                            <div class="text-center">
                                <p class="fallback fw-bold fs-7 d-none">No Result Found.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<style>
    .dataTables_paginate{padding-top: 10px !important;}
    .dataTables_paginate a,span{color: #000 !important;}
    .dataTables_paginate a{color: #000 !important; font-size: 12px !important;padding-left: 10px !important; padding-right: 10px !important;}
</style>



<div class="content">
    <nav class="mb-2" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="#!">Page 1</a></li>
            <li class="breadcrumb-item"><a href="#!">Page 2</a></li>
            <li class="breadcrumb-item active">Default</li>
        </ol>
    </nav>
    <div class="mb-9">
        <div class="row g-2 mb-4">
            <div class="col-auto">
                <h2 class="mb-0">Templates</h2>
            </div>
        </div>
        <ul class="nav nav-links mb-3 mb-lg-2 mx-n3">
            <!-- <li class="nav-item"><a class="nav-link active" aria-current="page" href="#"><span>All </span><span class="text-body-tertiary fw-semibold">(68817)</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="#"><span>New </span><span class="text-body-tertiary fw-semibold">(6)</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="#"><span>Abandoned checkouts </span><span class="text-body-tertiary fw-semibold">(17)</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="#"><span>Locals </span><span class="text-body-tertiary fw-semibold">(6,810)</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="#"><span>Email subscribers </span><span class="text-body-tertiary fw-semibold">(8)</span></a></li>
                    <li class="nav-item"><a class="nav-link" href="#"><span>Top reviews </span><span class="text-body-tertiary fw-semibold">(2)</span></a></li> -->
        </ul>
        <div id="products" data-list='{"valueNames":["Layout","email","total-orders","total-spent","city","last-seen","last-order"],"page":10,"pagination":true}'>
            <div class="mb-4">
                <div class="row g-3">
                    <div class="col-auto">
                        <div class="search-box">
                            <form class="position-relative">
                                <input class="form-control search-input search" type="search" placeholder="Search Layouts" aria-label="Search" />
                                <span class="fas fa-search search-box-icon"></span>

                            </form>
                        </div>
                    </div>
                    <div class="col-auto scrollbar overflow-hidden-y flex-grow-1">
                        <div class="btn-group position-static" role="group">
                            <div class="btn-group position-static text-nowrap">
                                <button class="btn btn-phoenix-secondary px-7 flex-shrink-0" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent">
                                    YEAR<span class="fas fa-angle-down ms-2"></span></button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">2022</a></li>
                                    <li><a class="dropdown-item" href="#">2023</a></li>
                                    <li><a class="dropdown-item" href="#">2024</a></li>
                                </ul>
                            </div>
                            <div class="btn-group position-static text-nowrap">
                                <button class="btn btn-sm btn-phoenix-secondary px-7 flex-shrink-0" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent">
                                    TYPE<span class="fas fa-angle-down ms-2"></span></button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">TYPE 1</a></li>
                                    <li><a class="dropdown-item" href="#">TYPE 2</a></li>
                                    <li><a class="dropdown-item" href="#">TYPE 3</a></li>
                                    <li></li>
                                </ul>
                            </div>
                            <button class="btn btn-phoenix-secondary px-7 flex-shrink-0">More filters</button>
                        </div>
                    </div>
                    <div class="col-auto">
                        <!-- <button class="btn btn-link text-body me-4 px-0"><span class="fa-solid fa-file-export fs-9 me-2"></span>Export</button> -->
                        <button class="btn btn-primary" id="addTemplateModal" data-bs-toggle="modal" data-bs-target="#addTemplateModal"><span class="fas fa-plus me-2"></span>Add Template</button>
                    </div>
                </div>
            </div>
            <div class="mx-n4 px-4 mx-lg-n6 px-lg-6 bg-body-emphasis border-top border-bottom border-translucent position-relative top-1">
                <div class="table-responsive scrollbar-overlay mx-n1 px-1" style="padding: 10px !important;">
                    <table id="template-table" class="table table-sm fs-9 mb-0">
                        <thead>
                            <tr>
                                <th class="white-space-nowrap fs-9 align-middle ps-0">
                                    <div class="form-check mb-0 fs-8">
                                        <input class="form-check-input" id="checkbox-bulk-customers-select" type="checkbox" data-bulk-select='{"body":"customers-table-body"}' />
                                    </div>
                                </th>
                                <th class="sort align-middle pe-5" scope="col" data-sort="customer" style="width:25%">ID</th>
                                <th class="sort align-middle pe-5" scope="col" data-sort="email" style="width:35%">Template Name</th>
                                <th class="sort align-middle text-end ps-3" scope="col" style="width:20%">DELETE</th>
                                <th class="sort align-middle ps-7" scope="col" style="width:20%">EDIT</th>
                            </tr>
                        </thead>
                        <tbody class="list" id="template-table-body" style="padding: 20px !important;">
                           
                        </tbody>
                    </table>

                </div>

            </div>
        </div>
    </div>


    <?php
    require '../theme/layout_footer.php';

    ?>