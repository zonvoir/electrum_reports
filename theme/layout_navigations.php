<?php


?>

<nav class="navbar navbar-vertical navbar-expand-lg">
            <script>
                var navbarStyle = window.config.config.phoenixNavbarStyle;
                if (navbarStyle && navbarStyle !== 'transparent') {
                    document.querySelector('body').classList.add(`navbar-${navbarStyle}`);
                }
            </script>
            <div class="collapse navbar-collapse" id="navbarVerticalCollapse">
                <!-- scrollbar removed-->
                <div class="navbar-vertical-content">
                    <ul class="navbar-nav flex-column" id="navbarVerticalNav">
                        <li class="nav-item">
                            <!-- parent pages-->
                            <div class="nav-item-wrapper"><a class="nav-link dropdown-indicator label-1" href="#nv-home" role="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="nv-home">
                                    <div class="d-flex align-items-center">
                                        <div class="dropdown-indicator-icon"><span class="fas fa-caret-right"></span></div><span class="nav-link-icon"><span data-feather="pie-chart"></span></span><span class="nav-link-text">Home</span>
                                    </div>
                                </a>
                                <div class="parent-wrapper label-1">
                                    <ul class="nav collapse parent" data-bs-parent="#navbarVerticalCollapse" id="nv-home">
                                        <li class="collapsed-nav-item-title d-none">Home
                                        </li>
                                        <li class="nav-item"><a class="nav-link" href="../layouts/index.php" data-bs-toggle="" aria-expanded="false">
                                                <div class="d-flex align-items-center"><span class="nav-link-text">Layouts</span>
                                                </div>
                                            </a>
                                            <!-- more inner pages-->
                                        </li>
                                        <li class="nav-item"><a class="nav-link" href="../templates/index.php" data-bs-toggle="" aria-expanded="false">
                                                <div class="d-flex align-items-center"><span class="nav-link-text">Templates</span>
                                                </div>
                                            </a>
                                            <!-- more inner pages-->
                                        </li>
                                        
                                        
                                    </ul>
                                </div>
                            </div>
                        </li>
                        <li class="nav-item">
                            <!-- label-->
                            <p class="navbar-vertical-label">Apps
                            </p>
                            <hr class="navbar-vertical-line" />
                            <!-- parent pages-->
                            <div class="nav-item-wrapper"><a class="nav-link dropdown-indicator label-1" href="#nv-e-commerce" role="button" data-bs-toggle="collapse" aria-expanded="true" aria-controls="nv-e-commerce">
                                    <div class="d-flex align-items-center">
                                        <div class="dropdown-indicator-icon"><span class="fas fa-caret-right"></span></div><span class="nav-link-icon"><span data-feather="shopping-cart"></span></span><span class="nav-link-text">Management</span>
                                    </div>
                                </a>
                                <div class="parent-wrapper label-1">
                                    <ul class="nav collapse parent show" data-bs-parent="#navbarVerticalCollapse" id="nv-e-commerce">
                                        <li class="collapsed-nav-item-title d-none">E commerce
                                        </li>
                                        <li class="nav-item"><a class="nav-link dropdown-indicator" href="#nv-admin" data-bs-toggle="collapse" aria-expanded="true" aria-controls="nv-admin">
                                                <div class="d-flex align-items-center">
                                                    <div class="dropdown-indicator-icon"><span class="fas fa-caret-right"></span></div><span class="nav-link-text">Admin</span>
                                                </div>
                                            </a>
                                            <!-- more inner pages-->
                                            <div class="parent-wrapper">
                                                <ul class="nav collapse parent show" data-bs-parent="#e-commerce" id="nv-admin">
                                                    <li class="nav-item"><a class="nav-link" href="../../../apps/e-commerce/admin/add-product.html" data-bs-toggle="" aria-expanded="false">
                                                            <div class="d-flex align-items-center"><span class="nav-link-text">User Management</span>
                                                            </div>
                                                        </a>
                                                        <!-- more inner pages-->
                                                    </li>
                                                    <li class="nav-item"><a class="nav-link" href="../../../apps/e-commerce/admin/products.html" data-bs-toggle="" aria-expanded="false">
                                                            <div class="d-flex align-items-center"><span class="nav-link-text">Invoicing</span>
                                                            </div>
                                                        </a>
                                                        <!-- more inner pages-->
                                                    </li>
                                                    <li class="nav-item"><a class="nav-link active" href="../../../apps/e-commerce/admin/customers.html" data-bs-toggle="" aria-expanded="false">
                                                            <div class="d-flex align-items-center"><span class="nav-link-text">Customers</span>
                                                            </div>
                                                        </a>
                                                        <!-- more inner pages-->
                                                    </li>
                                                    
                                                    <li class="nav-item"><a class="nav-link" href="../../../apps/e-commerce/admin/orders.html" data-bs-toggle="" aria-expanded="false">
                                                            <div class="d-flex align-items-center"><span class="nav-link-text">Orders</span>
                                                            </div>
                                                        </a>
                                                        <!-- more inner pages-->
                                                    </li>
                                                    
                                                    
                                                </ul>
                                            </div>
                                        </li>
                                        
                                    </ul>
                                </div>
                            </div>
                            
                            <!-- parent pages-->
                            <div class="nav-item-wrapper"><a class="nav-link dropdown-indicator label-1" href="#nv-project-management" role="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="nv-project-management">
                                    <div class="d-flex align-items-center">
                                        <div class="dropdown-indicator-icon"><span class="fas fa-caret-right"></span></div><span class="nav-link-icon"><span data-feather="clipboard"></span></span><span class="nav-link-text">Project management</span>
                                    </div>
                                </a>
                                <div class="parent-wrapper label-1">
                                   
                                </div>
                            </div>
                           
                          
                            <!-- parent pages-->
                            <div class="nav-item-wrapper"><a class="nav-link dropdown-indicator label-1" href="#nv-events" role="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="nv-events">
                                    <div class="d-flex align-items-center">
                                        <div class="dropdown-indicator-icon"><span class="fas fa-caret-right"></span></div><span class="nav-link-icon"><span data-feather="bookmark"></span></span><span class="nav-link-text">Events</span>
                                    </div>
                                </a>
                                <div class="parent-wrapper label-1">
                                    <ul class="nav collapse parent" data-bs-parent="#navbarVerticalCollapse" id="nv-events">
                                        <li class="collapsed-nav-item-title d-none">Events
                                        </li>
                                        <li class="nav-item"><a class="nav-link" href="../../../apps/events/create-an-event.html" data-bs-toggle="" aria-expanded="false">
                                                <div class="d-flex align-items-center"><span class="nav-link-text">Create an event</span>
                                                </div>
                                            </a>
                                            <!-- more inner pages-->
                                        </li>
                                        <li class="nav-item"><a class="nav-link" href="../../../apps/events/event-detail.html" data-bs-toggle="" aria-expanded="false">
                                                <div class="d-flex align-items-center"><span class="nav-link-text">Event detail</span>
                                                </div>
                                            </a>
                                            <!-- more inner pages-->
                                        </li>
                                    </ul>
                                </div>
                            </div>
                           
                            
                            <!-- parent pages-->
                            <div class="nav-item-wrapper"><a class="nav-link label-1" href="../../../apps/calendar.html" role="button" data-bs-toggle="" aria-expanded="false">
                                    <div class="d-flex align-items-center"><span class="nav-link-icon"><span data-feather="calendar"></span></span><span class="nav-link-text-wrapper"><span class="nav-link-text">Calendar</span></span>
                                    </div>
                                </a>
                            </div>
                        </li>
                       
                       
                       
                    </ul>
                </div>
            </div>
            <div class="navbar-vertical-footer">
                <button class="btn navbar-vertical-toggle border-0 fw-semibold w-100 white-space-nowrap d-flex align-items-center"><span class="uil uil-left-arrow-to-left fs-8"></span><span class="uil uil-arrow-from-right fs-8"></span><span class="navbar-vertical-footer-text ms-2">Collapsed View</span></button>
            </div>
        </nav>
        <nav class="navbar navbar-top fixed-top navbar-expand" id="navbarDefault">
            <div class="collapse navbar-collapse justify-content-between">
                <div class="navbar-logo">

                    <button class="btn navbar-toggler navbar-toggler-humburger-icon hover-bg-transparent" type="button" data-bs-toggle="collapse" data-bs-target="#navbarVerticalCollapse" aria-controls="navbarVerticalCollapse" aria-expanded="false" aria-label="Toggle Navigation"><span class="navbar-toggle-icon"><span class="toggle-line"></span></span></button>
                    <a class="navbar-brand me-1 me-sm-3" href="../../../index.html">
                        <div class="d-flex align-items-center">
                            <div class="d-flex align-items-center"><img src="../assets/img/icons/logo.png" alt="phoenix" width="27" />
                                <p class="logo-text ms-2 d-none d-sm-block">Electruments</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="search-box navbar-top-search-box d-none d-lg-block" data-list='{"valueNames":["title"]}' style="width:25rem;">
                    <form class="position-relative" data-bs-toggle="search" data-bs-display="static">
                        <input class="form-control search-input fuzzy-search rounded-pill form-control-sm" type="search" placeholder="Search..." aria-label="Search" />
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
                <ul class="navbar-nav navbar-nav-icons flex-row">
                    <li class="nav-item">
                        <div class="theme-control-toggle fa-icon-wait px-2">
                            <input class="form-check-input ms-0 theme-control-toggle-input" type="checkbox" data-theme-control="phoenixTheme" value="dark" id="themeControlToggle" />
                            <label class="mb-0 theme-control-toggle-label theme-control-toggle-light" for="themeControlToggle" data-bs-toggle="tooltip" data-bs-placement="left" title="Switch theme"><span class="icon" data-feather="moon"></span></label>
                            <label class="mb-0 theme-control-toggle-label theme-control-toggle-dark" for="themeControlToggle" data-bs-toggle="tooltip" data-bs-placement="left" title="Switch theme"><span class="icon" data-feather="sun"></span></label>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="#" style="min-width: 2.25rem" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-bs-auto-close="outside"><span data-feather="bell" style="height:20px;width:20px;"></span></a>

                        <!-- <div class="dropdown-menu dropdown-menu-end notification-dropdown-menu py-0 shadow border navbar-dropdown-caret" id="navbarDropdownNotfication" aria-labelledby="navbarDropdownNotfication">
                            <div class="card position-relative border-0">
                                <div class="card-header p-2">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="text-body-emphasis mb-0">Notificatons</h5>
                                        <button class="btn btn-link p-0 fs-9 fw-normal" type="button">Mark all as read</button>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div class="scrollbar-overlay" style="height: 27rem;">
                                        <div class="px-2 px-sm-3 py-3 notification-card position-relative read border-bottom">
                                            <div class="d-flex align-items-center justify-content-between position-relative">
                                                <div class="d-flex">
                                                    <div class="avatar avatar-m status-online me-3"><img class="rounded-circle" src="../assets/img/team/40x40/30.webp" alt="" />
                                                    </div>
                                                    <div class="flex-1 me-sm-3">
                                                        <h4 class="fs-9 text-body-emphasis">Jessie Samson</h4>
                                                        <p class="fs-9 text-body-highlight mb-2 mb-sm-3 fw-normal"><span class='me-1 fs-10'>💬</span>Mentioned you in a comment.<span class="ms-2 text-body-quaternary text-opacity-75 fw-bold fs-10">10m</span></p>
                                                        <p class="text-body-secondary fs-9 mb-0"><span class="me-1 fas fa-clock"></span><span class="fw-bold">10:41 AM </span>August 7,2021</p>
                                                    </div>
                                                </div>
                                                <div class="d-none d-sm-block">
                                                    <button class="btn fs-10 btn-sm dropdown-toggle dropdown-caret-none transition-none notification-dropdown-toggle" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h fs-10 text-body"></span></button>
                                                    <div class="dropdown-menu dropdown-menu-end py-2"><a class="dropdown-item" href="#!">Mark as unread</a></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="px-2 px-sm-3 py-3 notification-card position-relative unread border-bottom">
                                            <div class="d-flex align-items-center justify-content-between position-relative">
                                                <div class="d-flex">
                                                    <div class="avatar avatar-m status-online me-3">
                                                        <div class="avatar-name rounded-circle"><span>J</span></div>
                                                    </div>
                                                    <div class="flex-1 me-sm-3">
                                                        <h4 class="fs-9 text-body-emphasis">Jane Foster</h4>
                                                        <p class="fs-9 text-body-highlight mb-2 mb-sm-3 fw-normal"><span class='me-1 fs-10'>📅</span>Created an event.<span class="ms-2 text-body-quaternary text-opacity-75 fw-bold fs-10">20m</span></p>
                                                        <p class="text-body-secondary fs-9 mb-0"><span class="me-1 fas fa-clock"></span><span class="fw-bold">10:20 AM </span>August 7,2021</p>
                                                    </div>
                                                </div>
                                                <div class="d-none d-sm-block">
                                                    <button class="btn fs-10 btn-sm dropdown-toggle dropdown-caret-none transition-none notification-dropdown-toggle" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h fs-10 text-body"></span></button>
                                                    <div class="dropdown-menu dropdown-menu-end py-2"><a class="dropdown-item" href="#!">Mark as unread</a></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="px-2 px-sm-3 py-3 notification-card position-relative unread border-bottom">
                                            <div class="d-flex align-items-center justify-content-between position-relative">
                                                <div class="d-flex">
                                                    <div class="avatar avatar-m status-online me-3"><img class="rounded-circle avatar-placeholder" src="../assets/img/team/40x40/avatar.webp" alt="" />
                                                    </div>
                                                    <div class="flex-1 me-sm-3">
                                                        <h4 class="fs-9 text-body-emphasis">Jessie Samson</h4>
                                                        <p class="fs-9 text-body-highlight mb-2 mb-sm-3 fw-normal"><span class='me-1 fs-10'>👍</span>Liked your comment.<span class="ms-2 text-body-quaternary text-opacity-75 fw-bold fs-10">1h</span></p>
                                                        <p class="text-body-secondary fs-9 mb-0"><span class="me-1 fas fa-clock"></span><span class="fw-bold">9:30 AM </span>August 7,2021</p>
                                                    </div>
                                                </div>
                                                <div class="d-none d-sm-block">
                                                    <button class="btn fs-10 btn-sm dropdown-toggle dropdown-caret-none transition-none notification-dropdown-toggle" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h fs-10 text-body"></span></button>
                                                    <div class="dropdown-menu dropdown-menu-end py-2"><a class="dropdown-item" href="#!">Mark as unread</a></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="px-2 px-sm-3 py-3 notification-card position-relative unread border-bottom">
                                            <div class="d-flex align-items-center justify-content-between position-relative">
                                                <div class="d-flex">
                                                    <div class="avatar avatar-m status-online me-3"><img class="rounded-circle" src="../assets/img/team/40x40/57.webp" alt="" />
                                                    </div>
                                                    <div class="flex-1 me-sm-3">
                                                        <h4 class="fs-9 text-body-emphasis">Kiera Anderson</h4>
                                                        <p class="fs-9 text-body-highlight mb-2 mb-sm-3 fw-normal"><span class='me-1 fs-10'>💬</span>Mentioned you in a comment.<span class="ms-2 text-body-quaternary text-opacity-75 fw-bold fs-10"></span></p>
                                                        <p class="text-body-secondary fs-9 mb-0"><span class="me-1 fas fa-clock"></span><span class="fw-bold">9:11 AM </span>August 7,2021</p>
                                                    </div>
                                                </div>
                                                <div class="d-none d-sm-block">
                                                    <button class="btn fs-10 btn-sm dropdown-toggle dropdown-caret-none transition-none notification-dropdown-toggle" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h fs-10 text-body"></span></button>
                                                    <div class="dropdown-menu dropdown-menu-end py-2"><a class="dropdown-item" href="#!">Mark as unread</a></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="px-2 px-sm-3 py-3 notification-card position-relative unread border-bottom">
                                            <div class="d-flex align-items-center justify-content-between position-relative">
                                                <div class="d-flex">
                                                    <div class="avatar avatar-m status-online me-3"><img class="rounded-circle" src="../assets/img/team/40x40/59.webp" alt="" />
                                                    </div>
                                                    <div class="flex-1 me-sm-3">
                                                        <h4 class="fs-9 text-body-emphasis">Herman Carter</h4>
                                                        <p class="fs-9 text-body-highlight mb-2 mb-sm-3 fw-normal"><span class='me-1 fs-10'>👤</span>Tagged you in a comment.<span class="ms-2 text-body-quaternary text-opacity-75 fw-bold fs-10"></span></p>
                                                        <p class="text-body-secondary fs-9 mb-0"><span class="me-1 fas fa-clock"></span><span class="fw-bold">10:58 PM </span>August 7,2021</p>
                                                    </div>
                                                </div>
                                                <div class="d-none d-sm-block">
                                                    <button class="btn fs-10 btn-sm dropdown-toggle dropdown-caret-none transition-none notification-dropdown-toggle" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h fs-10 text-body"></span></button>
                                                    <div class="dropdown-menu dropdown-menu-end py-2"><a class="dropdown-item" href="#!">Mark as unread</a></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="px-2 px-sm-3 py-3 notification-card position-relative read ">
                                            <div class="d-flex align-items-center justify-content-between position-relative">
                                                <div class="d-flex">
                                                    <div class="avatar avatar-m status-online me-3"><img class="rounded-circle" src="../assets/img/team/40x40/58.webp" alt="" />
                                                    </div>
                                                    <div class="flex-1 me-sm-3">
                                                        <h4 class="fs-9 text-body-emphasis">Benjamin Button</h4>
                                                        <p class="fs-9 text-body-highlight mb-2 mb-sm-3 fw-normal"><span class='me-1 fs-10'>👍</span>Liked your comment.<span class="ms-2 text-body-quaternary text-opacity-75 fw-bold fs-10"></span></p>
                                                        <p class="text-body-secondary fs-9 mb-0"><span class="me-1 fas fa-clock"></span><span class="fw-bold">10:18 AM </span>August 7,2021</p>
                                                    </div>
                                                </div>
                                                <div class="d-none d-sm-block">
                                                    <button class="btn fs-10 btn-sm dropdown-toggle dropdown-caret-none transition-none notification-dropdown-toggle" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h fs-10 text-body"></span></button>
                                                    <div class="dropdown-menu dropdown-menu-end py-2"><a class="dropdown-item" href="#!">Mark as unread</a></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer p-0 border-top border-translucent border-0">
                                    <div class="my-2 text-center fw-bold fs-10 text-body-tertiary text-opactity-85"><a class="fw-bolder" href="../../../pages/notifications.html">Notification history</a></div>
                                </div>
                            </div>
                        </div> -->
                    </li>
                    <!-- <li class="nav-item dropdown">
                        <a class="nav-link" id="navbarDropdownNindeDots" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" data-bs-auto-close="outside" aria-expanded="false">
                            <svg width="16" height="16" viewbox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="2" cy="2" r="2" fill="currentColor"></circle>
                                <circle cx="2" cy="8" r="2" fill="currentColor"></circle>
                                <circle cx="2" cy="14" r="2" fill="currentColor"></circle>
                                <circle cx="8" cy="8" r="2" fill="currentColor"></circle>
                                <circle cx="8" cy="14" r="2" fill="currentColor"></circle>
                                <circle cx="14" cy="8" r="2" fill="currentColor"></circle>
                                <circle cx="14" cy="14" r="2" fill="currentColor"></circle>
                                <circle cx="8" cy="2" r="2" fill="currentColor"></circle>
                                <circle cx="14" cy="2" r="2" fill="currentColor"></circle>
                            </svg></a>

                        <div class="dropdown-menu dropdown-menu-end navbar-dropdown-caret py-0 dropdown-nine-dots shadow border" aria-labelledby="navbarDropdownNindeDots">
                            <div class="card bg-body-emphasis position-relative border-0">
                                <div class="card-body pt-3 px-3 pb-0 overflow-auto scrollbar" style="height: 20rem;">
                                    <div class="row text-center align-items-center gx-0 gy-0">
                                        <div class="col-4"><a class="d-block bg-body-secondary-hover p-2 rounded-3 text-center text-decoration-none mb-3" href="#!"><img src="../assets/img/nav-icons/behance.webp" alt="" width="30" />
                                                <p class="mb-0 text-body-emphasis text-truncate fs-10 mt-1 pt-1">Behance</p>
                                            </a></div>
                                        <div class="col-4"><a class="d-block bg-body-secondary-hover p-2 rounded-3 text-center text-decoration-none mb-3" href="#!"><img src="../assets/img/nav-icons/google-cloud.webp" alt="" width="30" />
                                                <p class="mb-0 text-body-emphasis text-truncate fs-10 mt-1 pt-1">Cloud</p>
                                            </a></div>
                                        <div class="col-4"><a class="d-block bg-body-secondary-hover p-2 rounded-3 text-center text-decoration-none mb-3" href="#!"><img src="../assets/img/nav-icons/slack.webp" alt="" width="30" />
                                                <p class="mb-0 text-body-emphasis text-truncate fs-10 mt-1 pt-1">Slack</p>
                                            </a></div>
                                        <div class="col-4"><a class="d-block bg-body-secondary-hover p-2 rounded-3 text-center text-decoration-none mb-3" href="#!"><img src="../assets/img/nav-icons/gitlab.webp" alt="" width="30" />
                                                <p class="mb-0 text-body-emphasis text-truncate fs-10 mt-1 pt-1">Gitlab</p>
                                            </a></div>
                                        <div class="col-4"><a class="d-block bg-body-secondary-hover p-2 rounded-3 text-center text-decoration-none mb-3" href="#!"><img src="../assets/img/nav-icons/bitbucket.webp" alt="" width="30" />
                                                <p class="mb-0 text-body-emphasis text-truncate fs-10 mt-1 pt-1">BitBucket</p>
                                            </a></div>
                                        <div class="col-4"><a class="d-block bg-body-secondary-hover p-2 rounded-3 text-center text-decoration-none mb-3" href="#!"><img src="../assets/img/nav-icons/google-drive.webp" alt="" width="30" />
                                                <p class="mb-0 text-body-emphasis text-truncate fs-10 mt-1 pt-1">Drive</p>
                                            </a></div>
                                        <div class="col-4"><a class="d-block bg-body-secondary-hover p-2 rounded-3 text-center text-decoration-none mb-3" href="#!"><img src="../assets/img/nav-icons/trello.webp" alt="" width="30" />
                                                <p class="mb-0 text-body-emphasis text-truncate fs-10 mt-1 pt-1">Trello</p>
                                            </a></div>
                                        <div class="col-4"><a class="d-block bg-body-secondary-hover p-2 rounded-3 text-center text-decoration-none mb-3" href="#!"><img src="../assets/img/nav-icons/figma.webp" alt="" width="20" />
                                                <p class="mb-0 text-body-emphasis text-truncate fs-10 mt-1 pt-1">Figma</p>
                                            </a></div>
                                        <div class="col-4"><a class="d-block bg-body-secondary-hover p-2 rounded-3 text-center text-decoration-none mb-3" href="#!"><img src="../assets/img/nav-icons/twitter.webp" alt="" width="30" />
                                                <p class="mb-0 text-body-emphasis text-truncate fs-10 mt-1 pt-1">Twitter</p>
                                            </a></div>
                                        <div class="col-4"><a class="d-block bg-body-secondary-hover p-2 rounded-3 text-center text-decoration-none mb-3" href="#!"><img src="../assets/img/nav-icons/pinterest.webp" alt="" width="30" />
                                                <p class="mb-0 text-body-emphasis text-truncate fs-10 mt-1 pt-1">Pinterest</p>
                                            </a></div>
                                        <div class="col-4"><a class="d-block bg-body-secondary-hover p-2 rounded-3 text-center text-decoration-none mb-3" href="#!"><img src="../assets/img/nav-icons/ln.webp" alt="" width="30" />
                                                <p class="mb-0 text-body-emphasis text-truncate fs-10 mt-1 pt-1">Linkedin</p>
                                            </a></div>
                                        <div class="col-4"><a class="d-block bg-body-secondary-hover p-2 rounded-3 text-center text-decoration-none mb-3" href="#!"><img src="../assets/img/nav-icons/google-maps.webp" alt="" width="30" />
                                                <p class="mb-0 text-body-emphasis text-truncate fs-10 mt-1 pt-1">Maps</p>
                                            </a></div>
                                        <div class="col-4"><a class="d-block bg-body-secondary-hover p-2 rounded-3 text-center text-decoration-none mb-3" href="#!"><img src="../assets/img/nav-icons/google-photos.webp" alt="" width="30" />
                                                <p class="mb-0 text-body-emphasis text-truncate fs-10 mt-1 pt-1">Photos</p>
                                            </a></div>
                                        <div class="col-4"><a class="d-block bg-body-secondary-hover p-2 rounded-3 text-center text-decoration-none mb-3" href="#!"><img src="../assets/img/nav-icons/spotify.webp" alt="" width="30" />
                                                <p class="mb-0 text-body-emphasis text-truncate fs-10 mt-1 pt-1">Spotify</p>
                                            </a></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li> -->
                    <li class="nav-item dropdown"><a class="nav-link lh-1 pe-0" id="navbarDropdownUser" href="#!" role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="false">
                            <div class="avatar avatar-l ">
                                <img class="rounded-circle " src="../assets/img/team/40x40/57.webp" alt="" />

                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end navbar-dropdown-caret py-0 dropdown-profile shadow border" aria-labelledby="navbarDropdownUser">
                            <div class="card position-relative border-0">
                                <div class="card-body p-0">
                                    <div class="text-center pt-4 pb-3">
                                        <div class="avatar avatar-xl ">
                                            <img class="rounded-circle " src="../assets/img/team/72x72/57.webp" alt="" />

                                        </div>
                                        <h6 class="mt-2 text-body-emphasis">Jerry Seinfield</h6>
                                    </div>
                                    <div class="mb-3 mx-3">
                                        <input class="form-control form-control-sm" id="statusUpdateInput" type="text" placeholder="Update your status" />
                                    </div>
                                </div>
                                <div class="overflow-auto scrollbar" style="height: 10rem;">
                                    <ul class="nav d-flex flex-column mb-2 pb-1">
                                        <li class="nav-item"><a class="nav-link px-3" href="#!"> <span class="me-2 text-body" data-feather="user"></span><span>Profile</span></a></li>
                                        <li class="nav-item"><a class="nav-link px-3" href="#!"><span class="me-2 text-body" data-feather="pie-chart"></span>Dashboard</a></li>
                                        <li class="nav-item"><a class="nav-link px-3" href="#!"> <span class="me-2 text-body" data-feather="lock"></span>Posts &amp; Activity</a></li>
                                        <li class="nav-item"><a class="nav-link px-3" href="#!"> <span class="me-2 text-body" data-feather="settings"></span>Settings &amp; Privacy </a></li>
                                        <li class="nav-item"><a class="nav-link px-3" href="#!"> <span class="me-2 text-body" data-feather="help-circle"></span>Help Center</a></li>
                                        <li class="nav-item"><a class="nav-link px-3" href="#!"> <span class="me-2 text-body" data-feather="globe"></span>Language</a></li>
                                    </ul>
                                </div>
                                <div class="card-footer p-0 border-top border-translucent">
                                    <ul class="nav d-flex flex-column my-3">
                                        <li class="nav-item"><a class="nav-link px-3" href="#!"> <span class="me-2 text-body" data-feather="user-plus"></span>Add another account</a></li>
                                    </ul>
                                    <hr />
                                    <div class="px-3"> <a class="btn btn-phoenix-secondary d-flex flex-center w-100" href="#!"> <span class="me-2" data-feather="log-out"> </span>Sign out</a></div>
                                    <div class="my-2 text-center fw-bold fs-10 text-body-quaternary"><a class="text-body-quaternary me-1" href="#!">Privacy policy</a>&bull;<a class="text-body-quaternary mx-1" href="#!">Terms</a>&bull;<a class="text-body-quaternary ms-1" href="#!">Cookies</a></div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>