<?php
require_once __DIR__ . '/partials/_session.php';
require_once __DIR__ . '/includes/config.php';

$investorId = $_GET['id'];

$stmt = $dbh->prepare("SELECT fName, lName, phone, email, `address` FROM investor WHERE in_id = ?");
$stmt->execute([$investorId]);
$investor = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if the investor exists
if (!$investor) {
    header("Location: manageInvestor.php");
    exit();
}

$fName = htmlspecialchars($investor['fName']);
$lName = htmlspecialchars($investor['lName']);
$phone = htmlspecialchars($investor['phone']);
$email = htmlspecialchars($investor['email']);
$address = htmlspecialchars($investor['address']);

$title = "Edit Investor";
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

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <h1 class="h3 mb-2 text-gray-800">Edit Investor Information</h1>
                    <p class="mb-4">
                        This section allows you to update an existing investor's information. Modify details such as name, contact information, or investment-related data to ensure records remain accurate and current. Maintaining up-to-date investor profiles supports effective financial management and communication.
                    </p>

                    <div class="card o-hidden border-0 shadow-lg my-5">
                        <div class="card-body p-0">
                            <!-- Nested Row within Card Body -->
                            <div class="container d-flex align-items-center justify-content-center my-5">
                                <div class="row justify-content-center w-100">
                                    <div class="col-lg-7">
                                        <div class="p-5 shadow rounded bg-white">
                                            <div class="text-center">
                                                <h1 class="h4 text-gray-900 mb-4">Edit Investor!</h1>
                                            </div>
                                            <form class="user" id="registerForm">
                                                <div class="form-group row">
                                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                                        <input type="text" class="form-control form-control-user" id="firstName" name="firstName" value="<?php echo $fName; ?>"
                                                            data-original="<?php echo $fName; ?>" placeholder="First Name">
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="text" class="form-control form-control-user" id="lastName" name="lastName" value="<?php echo $lName; ?>" data-original="<?php echo $lName; ?>" placeholder="Last Name">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <input type="phone" class="form-control form-control-user" id="phone" name="phone" value="<?php echo $phone; ?>" data-original="<?php echo $phone; ?>" placeholder="Phone Number">
                                                </div>
                                                <div class="form-group">
                                                    <input type="email" class="form-control form-control-user" id="email" name="email" value="<?php echo $email; ?>" data-original="<?php echo $email; ?>" placeholder="Email Address">
                                                </div>
                                                <div class="form-group">
                                                    <textarea name="address" id="address" class="form-control form-control-user" placeholder="Enter address here..." data-original="<?php echo $address; ?>"><?php echo $address; ?></textarea>
                                                </div>
                                                <button type="submit" class="btn btn-primary btn-user btn-block">
                                                    Update Investor
                                                </button>
                                            </form>
                                            <hr>
                                        </div>
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

            const fNameInput = document.getElementById('firstName');
            const lNameInput = document.getElementById('lastName');
            const phoneInput = document.getElementById('phone');
            const emailInput = document.getElementById('email');
            const addressInput = document.getElementById('address');

            const fName = fNameInput.value.trim();
            const lName = lNameInput.value.trim();
            const phone = phoneInput.value.trim();
            const email = emailInput.value.trim();
            const address = addressInput.value.trim();

            const originalFName = fNameInput.getAttribute('data-original');
            const originalLName = lNameInput.getAttribute('data-original');
            const originalPhone = phoneInput.getAttribute('data-original');
            const originalEmail = emailInput.getAttribute('data-original');
            const originalAddress = addressInput.getAttribute('data-original');

            if (!fName || !lName || !phone || !email || !address) {
                Swal.fire('Warning', 'Please fill in all required fields.', 'warning');
                return;
            }

            if (fName === originalFName && lName === originalLName && phone === originalPhone && email === originalEmail && address === originalAddress) {
                Swal.fire('Info', 'No changes detected.', 'info');
                return;
            }

            const formData = new FormData();
            formData.append('firstName', fName);
            formData.append('lastName', lName);
            formData.append('phone', phone);
            formData.append('email', email);
            formData.append('address', address);
            formData.append('investorId', '<?php echo $investorId; ?>');

            try {
                const response = await fetch('editInvestor_process.php', {
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