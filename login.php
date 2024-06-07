<?php
require_once('database.php');
$database = new Database();
$conn = $database->getConnection();
require 'header.php';
?>

<body>
    <?php
    require 'navigation.php';
    ?>
    <section class="vh-100" style="background-color: #eee;">
        <div class="container h-100">
            <div class="row">
                <div class="col-lg-12 col-xl-11 p-2">
                    <div class="card text-black">
                        <div class="card-body">
                            <div class="row justify-content-center">
                                <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">
                                    <p class="text-center h1 mb-3">Sign in</p>
                                    <hr/>
                                    <form class="mx-1 mx-md-4">
                                        <div class="row">
                                            <div class="col-md-12 mb-2">
                                                <label class="fw-bold">Email</label>
                                                <input type="email" name="email" id="email" class="form-control" placeholder="Enter email" />
                                            </div>
                                            <div class="col-md-12 mb-2">
                                                <label class="fw-bold">Password</label>
                                                <input type="password" name="password" id="passsword" class="form-control" placeholder="Enter password" />
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-10 col-lg-6 col-xl-7 d-flex align-items-center order-1 order-lg-2">
                                    <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-registration/draw1.webp" class="img-fluid" alt="Sample image">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php
    require 'modals.php';
    ?>
<?php
require 'footer.php';
?>