<?php
require 'header.php';
?>

<body class="overflow-hidden auth-body">
    <div class="auth-nav-wrap">
        <?php
        require 'navigation.php';
        ?>
    </div>
    <section class="reg-card">
        <div class="container">
            <div class="auth-form-area">
                <h4>Sign in to account</h4>
                <p>Enter your email & password to login</p>
                <form id="form" action="functions/add-title.php" method="post">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label>Email</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="Enter email" required />
                        </div>
                        <div class="col-md-12 mb-3">
                            <label>Password</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Enter password" required />
                            <a class="forget-password-link" href="forget-password.html">Forgot password?</a>
                        </div>
                        <div class="col-md-12">
                        <button type="submit" class="btn bg-body-tertiary text-white w-100 mt-1" name="action" value="userSignIn">
                            Sign in <i class="fa fa-spinner fa-spin" style="display: none;"></i>
                        </button>
                        </div>
                        <div class="col-md-12">
                            <p class="create-account">Don't have account? <a href="register.php">Create Account</a></p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <?php
    require 'modals.php';
    ?>
<?php
require 'footer.php';
?>