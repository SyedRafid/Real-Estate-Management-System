<?php
include 'partials/_session.php';

$title = "Add Investor";
?>

<!DOCTYPE html>
<html lang="en">
<?php include 'partials/_head.php'; ?>

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
                    <h1 class="h3 mb-2 text-gray-800">Add An Investor</h1>
                    <p class="mb-4">
                        This section allow to register a new investor. Provide the necessary information such as name, contact details, and investment-related data. Keeping investor records accurate and up-to-date ensures better financial tracking and management within the system.
                    </p>
                </div>
                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="container d-flex align-items-center justify-content-center my-5">
                            <div class="row justify-content-center w-100">
                                <div class="col-lg-7">
                                    <div class="p-5 shadow rounded bg-white">
                                        <div class="text-center">
                                            <h1 class="h4 text-gray-900 mb-4">Add New Investor!</h1>
                                        </div>
                                        <form class="user" id="registerForm">
                                            <div class="form-group row">
                                                <div class="col-sm-6 mb-3 mb-sm-0">
                                                    <input type="text" class="form-control form-control-user" id="firstName" name="firstName" placeholder="First Name">
                                                </div>
                                                <div class="col-sm-6">
                                                    <input type="text" class="form-control form-control-user" id="lastName" name="lastName" placeholder="Last Name">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <input type="tel" class="form-control form-control-user" id="phone" name="phone" placeholder="Phone Number">
                                            </div>
                                            <div class="form-group">
                                                <input type="email" class="form-control form-control-user" id="email" name="email" placeholder="Email Address">
                                            </div>
                                            <div class="form-group">
                                                <textarea name="address" id="address" class="form-control form-control-user" placeholder="Enter address here..."></textarea>
                                            </div>
                                            <button type=" submit" class="btn btn-primary btn-user btn-block">
                                                Register Investor
                                            </button>
                                        </form>
                                        <hr>
                                    </div>
                                </div>
                            </div>
                        </div>
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
        document.getElementById('registerForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const form = e.target;
            const formData = new FormData(form);

            // validate form data
            if (!formData.get('firstName') || !formData.get('lastName') || !formData.get('email') || !formData.get('phone') || !formData.get('address')) {
                Swal.fire('Oops...', 'Please fill in all fields.', 'warning');
                return;
            }

            try {
                const response = await fetch('addInvestor_process.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    Swal.fire('Success', result.message, 'success').then(() => {
                        window.location.href = 'manageInvestor.php';
                    });
                } else {
                    Swal.fire('Error', result.message, 'error');
                }
            } catch (error) {
                swal.fire('Error', 'An error occurred while processing your request.', 'error');
            }
        });
    </script>
</body>

</html>