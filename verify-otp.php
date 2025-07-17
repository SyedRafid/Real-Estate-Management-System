<?php
$title = "Verify OTP";
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
                                <img src="img/undraw_otp_verify.svg" class="img-fluid">
                            </div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-2">Verify your OTP!!!</h1>
                                        <p class="mb-4">
                                            We’ve sent a One-Time Password (OTP) to your email. Please enter it below to verify your identity and continue resetting your password.
                                            <strong>The OTP will expire in 5 minutes.</strong>
                                        </p>
                                    </div>
                                    <form id="resetForm" class="user">
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user"
                                                id="otp" name="otp"
                                                placeholder="Enter Your OTP...">
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            Verify OTP
                                        </button>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="index.php">Already have an account? Login!</a>
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

    <script>
        const params = new URLSearchParams(window.location.search);
        const email = decodeURIComponent(params.get('email'));

        document.getElementById('resetForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Show loading indicator
            Swal.fire({
                title: 'Verifying...',
                text: 'Please wait while we process your request.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch('verify-otp-process.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'otp=' + document.getElementById('otp').value
                })
                .then(response => response.json())
                .then(data => {
                    Swal.close();

                    if (data.success) {
                        Swal.fire({
                            title: 'OTP Verified!',
                            icon: 'success',
                            html: `
                                <h5 style="margin-bottom:1rem;">Set Your New Password</h5>
                                <div style="display: flex; flex-direction: column; gap: 1rem;">
                                    <input type="password" id="newPassword" class="swal2-input" placeholder="New Password" style="font-size:1rem;">
                                    <input type="password" id="confirmPassword" class="swal2-input" placeholder="Confirm Password" style="font-size:1rem;">
                                </div>
                            `,
                            focusConfirm: false,
                            preConfirm: () => {
                                const newPassword = Swal.getPopup().querySelector('#newPassword').value;
                                const confirmPassword = Swal.getPopup().querySelector('#confirmPassword').value;

                                if (!newPassword || !confirmPassword) {
                                    Swal.showValidationMessage('Please enter both password fields');
                                    return false;
                                }

                                if (newPassword !== confirmPassword) {
                                    Swal.showValidationMessage('Passwords do not match');
                                    return false;
                                }

                                if (newPassword.length < 6) {
                                    Swal.showValidationMessage('Password must be at least 6 characters long');
                                    return false;
                                }

                                if (newPassword.length > 30) {
                                    Swal.showValidationMessage('Password must be less than 30 characters long');
                                    return false;
                                }

                                return {
                                    newPassword: newPassword
                                };
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Send new password + email to server to update password
                                fetch('change-password.php', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/x-www-form-urlencoded'
                                        },
                                        body: `email=${email}&password=${encodeURIComponent(result.value.newPassword)}`
                                    })
                                    .then(res => res.json())
                                    .then(resp => {
                                        if (resp.success) {
                                            Swal.fire('Success!', resp.message, 'success').then(() => {
                                                window.location.href = 'index.php';
                                            });
                                        } else {
                                            Swal.fire('Error!', resp.message, 'error');
                                        }
                                    })
                                    .catch(() => {
                                        Swal.fire('Error!', 'Server error. Please try again later.', 'error');
                                    });
                            }
                        });
                    } else {
                        Swal.fire('Error!', data.message, 'error');
                    }
                })
                .catch(error => {
                    Swal.close();
                    Swal.fire('Error!', 'Something went wrong. ' + error, 'error');
                });
        });
    </script>


</body>

</html>