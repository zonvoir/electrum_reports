<?php
require_once('database.php');
$database = new Database();
$conn = $database->getConnection();
require 'check-login.php';
require 'header.php';
?>

<body class="overflow-auto auth-body">
    <div class="auth-nav-wrap">
        <?php
        require 'navigation.php';
        ?>
    </div>
    <section class="reg-card">
        <div class="container">
            <div class="auth-form-area">
                <h4>Create your account</h4>
                <p>Enter your personal details to create account</p>
                <form id="form" action="functions/add-title.php" method="post">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>First Name</label>
                            <input type="text" name="first_name" id="first_name" class="form-control" placeholder="Enter first name" required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Last Name</label>
                            <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Enter last name" required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Email</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="Enter email" required />
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Password</label>
                            <input type="password" name="password" id="passsword" class="form-control" placeholder="Enter password" required />
                        </div>
                        <div class="col-md-12 mb-3">
                            <label>Mobile No.</label>
                            <input type="text" name="mobile_no" id="mobile_no" class="form-control input-field" placeholder="Enter mobile no." minlength="10" maxlength="10" required />
                        </div>
                        <div class="col-md-12 mb-3">
                            <?php
                            $query = "SELECT * FROM roles";
                            $statement = $conn->prepare($query);
                            $statement->execute();
                            $roles = $statement->fetchAll(PDO::FETCH_ASSOC);
                            ?>
                            <label>Role</label>
                            <select name="role_id" id="role_id" class="form-control" required>
                                <option value="">Select Role</option>
                                <?php
                                foreach ($roles as $role) {
                                ?>
                                <option value="<?php echo $role['id']; ?>"><?php echo $role['display_name']; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn bg-body-tertiary text-white w-100 mt-3" name="action" value="userSignUp">
                                Create Account <i class="fa fa-spinner fa-spin" style="display: none;"></i>
                            </button>
                        </div>
                        <div class="col-md-12">
                            <p class="create-account">Already have an account? <a href="login.php">Sign in</a></p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <?php
    require 'modals.php';
    ?>
    <script>
        $('.input-field').on('input', function() {
            var sanitizedValue = $(this).val().replace(/[^0-9.\n]/g, '');
            $(this).val(sanitizedValue);
        });
    </script>
<?php
require 'footer.php';
?>