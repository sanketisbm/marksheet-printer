<!DOCTYPE html>
<html lang="en">
<head><?php include 'assets/partials/header.html' ?></head>

<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper p-0">
            <div class="content-wrapper d-flex align-items-center auth p-0">
                <div class="row w-100 h-100 mx-0">
                    <div class="col-lg-7 mx-auto p-0">
                        <img src="assets/images/login.jpg" class="login-banner">
                    </div>
                    <div class="col-lg-5 col-md-12 auth-form-light mx-auto p-0">
                        <div class="auth-form text-left py-5 px-4 px-sm-5">
                            <div class="brand-logo">
                                <img src="assets/images/logo.jpg" alt="logo">
                            </div>
                            <h4>Hello! let's get started</h4>
                            <h6 class="font-weight-light">Sign in to continue.</h6>
                            <form class="pt-3" action="dbFiles/loginSQL.php" method="POST">
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-lg" name="username" placeholder="Username">
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control form-control-lg" name="password" placeholder="Password">
                                </div>
                                <div class="mt-3">
                                    <button type="submit" name="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">SIGN IN</button>
                                </div>
                                <div class="my-2 d-flex justify-content-between align-items-center">
                                    <a href="#" class="auth-link text-black">Forgot password?</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'assets/partials/plugins_js.html' ?>

</body>

</html>