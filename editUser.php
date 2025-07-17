<?php
require_once __DIR__ . '/partials/_session.php';
require_once __DIR__ . '/includes/config.php';

$profileId = $_GET['id'];

$stmt = $dbh->prepare("SELECT fName, lName, email FROM users WHERE user_id = ?");
$stmt->execute([$profileId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$fName = $user['fName'];
$lName = $user['lName'];
$email = $user['email'];

$title = "Edit Profile";
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

                    <!-- Begin Page Content -->
                    <div class="container-fluid">
                        <h1 class="h3 mb-2 text-gray-800">Edit User Profile</h1>
                        <p class="mb-4">
                            This section allows super admin to view and manage user profile information. Super admin can update user personal details, change user email address. Keeping user profile up to date ensures better communication and account security.
                        </p>
                        <hr>
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
                                        <input type="hidden" id="userId" name="userId" value="<?php echo $profileId; ?>">
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
                                        <input type="hidden" id="userId" name="userId" value="<?php echo $profileId; ?>">
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <input type="submit" class="btn btn-success form-control-alternative" value="Submit">
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
                const userIdInput = document.getElementById('userId');

                const newFName = fNameInput.value.trim();
                const newLName = lNameInput.value.trim();
                const userId = userIdInput.value.trim();

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
                    formData.append('userId', userId);

                    const response = await fetch('update_userProfileName.php', {
                        method: 'POST',
                        body: formData
                    });

                    const result = await response.json();

                    if (result.success) {
                        Swal.fire('Success', result.message, 'success').then(() => {
                            window.location.href = 'manageUser.php';
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
                const userIdInput = document.getElementById('userId');

                if (!emailInput) {
                    Swal.fire('Oops!', 'Email input not found.', 'error');
                    return;
                }

                const newEmail = emailInput.value.trim();
                const originalEmail = emailInput.dataset.original.trim();
                const userId = userIdInput.value.trim();

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
                    const response = await fetch('update_userEmail.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: 'email=' + encodeURIComponent(newEmail) + '&userId=' + encodeURIComponent(userId)
                    });

                    const result = await response.json();

                    if (result.success) {
                        Swal.fire('Success', result.message, 'success').then(() => {
                            window.location.href = 'manageUser.php';
                        });
                    } else {
                        Swal.fire('Error', result.message, 'error');
                    }
                } catch (error) {
                    Swal.fire('Error', 'Something went wrong. ' + error, 'error');
                }
            });
        </script>
    </body>

</html>