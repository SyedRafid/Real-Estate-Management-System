<?php
require_once __DIR__ . '/includes/config.php';
$title = "Login Page  ";
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'partials/_head.php'; ?>

<body class="bg-gradient-primary">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg" style="margin-top:6rem!important">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-password-image">
                                <img src="img/undraw_profile_4.svg" class="img-fluid">
                            </div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                                    </div>
                                    <form class="user" id="loginForm">
                                        <div class="form-group">
                                            <input type="email" class="form-control form-control-user"
                                                id="inputEmail" name="email" placeholder="Enter Email Address...">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user"
                                                id="inputPassword" name="password" placeholder="Password">
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox small">
                                                <input type="checkbox" class="custom-control-input" id="customCheck">
                                                <label class="custom-control-label" for="customCheck">Remember Me</label>
                                            </div>
                                        </div>
                                        <button class="btn btn-primary btn-user btn-block" type="submit">Login</button>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="forgot-password.php">Forgot Password?</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <?php include 'partials/_scripts.php'; ?>

    <?php
    if (isset($_GET['logged_out'])) {
        echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Logged out successfully',
            timer: 3000,
        });
    </script>";
    }
    ?>

    <script>
        document.getElementById("loginForm").addEventListener("submit", async function(e) {
            e.preventDefault(); // Prevent the default form submission

            const form = e.target;
            const formData = new FormData(form);

            if (!formData.get("email") || !formData.get("password")) {
                swal.fire("Error", "Please fill in all required fields.", "error");
                return;
            }

            if (!formData.get("email").includes("@")) {
                swal.fire("Error", "Please enter a valid email address.", "error");
                return;
            }

            try {
                const res = await fetch("login_process.php", {
                    method: "POST",
                    body: formData
                });

                const result = await res.json();
                if (result.success) {
                    swal.fire({
                        icon: 'success',
                        title: 'Login Successful',
                        text: result.message,
                        timer: 2000,
                    }).then(() => {
                        window.location.href = "dashboard.php";
                    });
                } else {
                    swal.fire("Login Failed", result.message, "error");
                }
            } catch (error) {
                swal.fire("Error", "An error occurred while processing your request.", "error");
            }
        });
    </script>
</body>

</html>