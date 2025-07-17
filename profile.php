<?php
include 'partials/_analytics.php';

$title = "Profile";
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'partials/_head.php'; ?>

<body id="page-top">

    <body id="page-top">

        <!-- Page Wrapper -->
        <div id="wrapper">

            <!-- Sidebar -->
            <?php include 'partials/_sidebar.php'; ?>
            <!-- End of Sidebar -->

            <!-- Content Wrapper -->
            <div id="content-wrapper" class="d-flex flex-column">

                <!-- Main Content -->
                <div id="content">

                    <!-- Topbar -->
                    <?php include 'partials/_topbar.php'; ?>
                    <!-- End of Topbar -->
                    <div class="m-4">
                        <h1 class="h3 mb-2 text-gray-800">My Profile</h1>
                        <p class="mb-4">
                            This section allows you to view and update your personal profile information. You can edit your name, email address, and password to keep your account up to date. Keeping your profile current helps ensure secure and accurate access to your account.
                        </p>
                    </div>
                    <!-- Begin Page Content -->
                    <div class="container-fluid">
                        <div class="card-body">
                            <form id="userName">
                                <h6 class="heading-small text-muted mb-4">User information</h6>
                                <div class="pl-lg-4">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-fName">First Name</label>
                                                <input type="text" name="fName" id="input-fName"
                                                    value="<?php echo htmlspecialchars($fName); ?>"
                                                    data-original="<?php echo htmlspecialchars($fName); ?>"
                                                    class="form-control form-control-alternative">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-lName">Last Name</label>
                                                <input type="text" name="lName" id="input-lName"
                                                    value="<?php echo htmlspecialchars($lName); ?>"
                                                    data-original="<?php echo htmlspecialchars($lName); ?>"
                                                    class="form-control form-control-alternative">
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <input type="submit" class="btn btn-success form-control-alternative" value="Submit">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <hr>
                            <form id="userEmail">
                                <h6 class="heading-small text-muted mb-4">User Email</h6>
                                <div class="pl-lg-4">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label class="form-control-label" for="input-email">Email</label>
                                                <input type="text" name="email" value="<?php echo htmlentities($email); ?>" data-original="<?php echo htmlspecialchars($email); ?>" id="input-email" class="form-control form-control-alternative">
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <input type="submit" class="btn btn-success form-control-alternative" value="Submit">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <hr>
                            <form id="changePasswordForm">
                                <h6 class="heading-small text-muted mb-4">Change Password</h6>
                                <div class="pl-lg-4">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label class="form-control-label" for="old-password">Old Password</label>
                                                <input type="password" name="old_password" id="old-password" class="form-control form-control-alternative">
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label class="form-control-label" for="new-password">New Password</label>
                                                <input type="password" id="new-password" name="new_password" class="form-control form-control-alternative">
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label class="form-control-label" for="confirm-password">Confirm New Password</label>
                                                <input type="password" id="confirm-password" name="confirm_password" class="form-control form-control-alternative">
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <input type="submit" id="submit-button" class="btn btn-success form-control-alternative" value="Change Password">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- /.container-fluid -->
                </div>
                <!-- End of Main Content -->
                <!-- Footer -->
                <?php include 'partials/_footer.php'; ?>
                <!-- End of Footer -->
            </div>
            <!-- End of Content Wrapper -->
        </div>
        <!-- End of Page Wrapper -->

        <!-- Logout Modal-->
        <?php include 'partials/_logoutModal.php'; ?>
        <?php include 'partials/_scripts.php'; ?>

        <script>
            document.getElementById('userName').addEventListener('submit', async function(e) {
                e.preventDefault();

                const fNameInput = document.getElementById('input-fName');
                const lNameInput = document.getElementById('input-lName');

                const newFName = fNameInput.value.trim();
                const newLName = lNameInput.value.trim();

                const originalFName = fNameInput.dataset.original.trim();
                const originalLName = lNameInput.dataset.original.trim();

                if (!newFName || !newLName) {
                    Swal.fire('Oops!', 'Please fill in all required fields.', 'warning');
                    return;
                }

                if (newFName === originalFName && newLName === originalLName) {
                    Swal.fire('Notice', 'No changes detected.', 'info');
                    return;
                }

                try {
                    const formData = new FormData();
                    formData.append('fName', newFName);
                    formData.append('lName', newLName);

                    const response = await fetch('update_profileName.php', {
                        method: 'POST',
                        body: formData
                    });

                    const result = await response.json();

                    if (result.success) {
                        Swal.fire('Success', result.message, 'success').then(() => {
                            // Optionally update the original values
                            fNameInput.dataset.original = newFName;
                            lNameInput.dataset.original = newLName;
                        });
                    } else {
                        Swal.fire('Error', result.message, 'error');
                    }
                } catch (error) {
                    Swal.fire('Error', 'An error occurred while processing your request.', 'error');
                }
            });


            document.getElementById('userEmail').addEventListener('submit', async function(e) {
                e.preventDefault();

                const emailInput = document.getElementById('input-email');

                if (!emailInput) {
                    Swal.fire('Oops!', 'Email input not found.', 'error');
                    return;
                }

                const newEmail = emailInput.value.trim();
                const originalEmail = emailInput.dataset.original.trim();

                // Basic validation
                if (!newEmail) {
                    Swal.fire('Oops!', 'Please enter an email address.', 'warning');
                    return;
                }

                // Check for change
                if (newEmail === originalEmail) {
                    Swal.fire('Notice', 'No changes detected.', 'info');
                    return;
                }

                try {
                    const response = await fetch('update_email.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: 'email=' + encodeURIComponent(newEmail)
                    });

                    const result = await response.json();

                    if (result.success) {
                        Swal.fire('Success', result.message, 'success');
                        emailInput.dataset.original = newEmail;
                    } else {
                        Swal.fire('Error', result.message, 'error');
                    }
                } catch (error) {
                    Swal.fire('Error', 'Something went wrong. ' + error, 'error');
                }
            });


            document.getElementById('changePasswordForm').addEventListener('submit', async function(e) {
                e.preventDefault();

                const oldPassword = document.getElementById('old-password').value.trim();
                const newPassword = document.getElementById('new-password').value.trim();
                const confirmPassword = document.getElementById('confirm-password').value.trim();

                if (!oldPassword || !newPassword || !confirmPassword) {
                    Swal.fire('Oops!', 'All fields are required.', 'warning');
                    return;
                }

                if (newPassword !== confirmPassword) {
                    Swal.fire('Error', 'New password and confirmation do not match.', 'error');
                    return;
                }

                try {
                    const formData = new FormData();
                    formData.append('old_password', oldPassword);
                    formData.append('new_password', newPassword);
                    formData.append('confirm_password', confirmPassword);

                    const response = await fetch('update_password.php', {
                        method: 'POST',
                        body: formData
                    });

                    const result = await response.json();

                    if (result.success) {
                        Swal.fire('Success!', result.message, 'success');
                        document.getElementById('changePasswordForm').reset();
                    } else {
                        Swal.fire('Error!', result.message, 'error');
                    }
                } catch (err) {
                    Swal.fire('Error!', 'Something went wrong while updating the password.' + err, 'error');
                }
            });
        </script>
    </body>

</html>