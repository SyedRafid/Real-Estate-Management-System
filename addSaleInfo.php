<?php
require_once __DIR__ . '/partials/_session.php';
require_once __DIR__ . '/includes/config.php';

$flatId = $_GET['flat_id'] ?? null;
if (!$flatId) {
    header("Location: addSale.php");
    exit;
}

$stmt = $dbh->prepare("
    SELECT 
        b.building_name, 
        b.location, 
        f.flat_lable
    FROM flats f
    INNER JOIN floors fl ON f.floor_id = fl.floor_id
    INNER JOIN buildings b ON fl.building_id = b.building_id
    WHERE f.flat_id = ?
");
$stmt->execute([$flatId]);
$info = $stmt->fetch(PDO::FETCH_ASSOC);

$buildingName = $info['building_name'] ?? '';
$buildingLocation = $info['location'] ?? '';
$flatLabel = $info['flat_lable'] ?? '';


$title = "Sale Information";
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
                <div class="m-4">
                    <h1 class="h3 text-gray-800">Add Sales Information</h1>
                    <p class="mb-0">
                        This section allows you to record a new sale transaction. Provide essential details such as the buyer's information, property sold, and any associated documentation. Keeping accurate sale records ensures better financial tracking and supports transparent business operations.
                    </p>
                </div>

                <div class="container-fluid">
                    <div class="card-body p-0">
                        <h1 class="h4 text-gray-800 mb-0">Building Information:</h1>
                        <div class="p-5">
                            <form class="user">
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 d-flex justify-content-center">
                                        <div class="flat available d-inline-flex">
                                            <span class="flat-badge available"></span>
                                            <span class="flat-label"><strong><?php echo htmlspecialchars($flatLabel); ?></strong></span>
                                            <select class="flat-select" disabled>
                                                <option selected>Available</option>
                                                <option>EMI</option>
                                                <option>Sold</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="text" class="form-control form-control-user" value="<?php echo htmlspecialchars($buildingName); ?>" disabled>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user" value="<?php echo htmlspecialchars($buildingLocation); ?>" disabled>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="container d-flex align-items-center justify-content-center">
                        <div class="row justify-content-center w-100">
                            <div class="col-lg-9">
                                <div class="p-5 shadow rounded bg-white">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Add Sales Information!</h1>
                                    </div>
                                    <form class="user" id="customerForm">
                                        <div class="form-group row">
                                            <div class="col-sm-6 mb-3 mb-sm-0">
                                                <input type="text" class="form-control form-control-user" id="firstName" name="firstName" placeholder="First Name">
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control form-control-user" id="lastName" name="lastName" placeholder="Last Name">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-6 mb-3 mb-sm-0">
                                                <input type="tel" class="form-control form-control-user" id="phone" name="phone" placeholder="Phone Number">
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="email" class="form-control form-control-user" id="email" name="email" placeholder="Email Address">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user" id="address" name="address" placeholder="Customer Address">
                                        </div>
                                        <div class="text-center">
                                            <h1 class="h4 text-gray-900 mb-4">Add Sales Information!</h1>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-6 mb-3 mb-sm-0">
                                                <input type="number" class="form-control form-control-user" id="price" name="price" placeholder="Flat Price">
                                            </div>
                                            <div class="col-sm-6">
                                                <select class="form-control custom-flat-select" id="paymentOption">
                                                    <option value="" selected disabled>Payment Option</option>
                                                    <option value="EMI">EMI</option>
                                                    <option value="Sold">Fully paid</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row" id="paymentDiv" style="display:none;">
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control form-control-user" id="payment" name="payment" placeholder="Payment Amount">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <textarea type="text" class="form-control form-control-user" id="note" name="note" placeholder="Sales note (Can be left blank!!)"></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block">
                                            Submit
                                        </button>
                                    </form>
                                    <hr>
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
        document.getElementById('paymentOption').addEventListener('change', function() {
            var paymentDiv = document.getElementById('paymentDiv');
            if (this.value === 'EMI') {
                paymentDiv.style.display = '';
            } else {
                paymentDiv.style.display = 'none';
            }
        });


        document.getElementById('customerForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = e.target;
            const data = {
                flatId: "<?php echo htmlspecialchars($flatId); ?>",
                firstName: form.firstName.value.trim(),
                lastName: form.lastName.value.trim(),
                phone: form.phone.value.trim(),
                email: form.email.value.trim(),
                address: form.address.value.trim(),
                price: form.price.value.trim(),
                paymentOption: form.paymentOption.value,
                payment: (form.payment && form.payment.value.trim()) ? form.payment.value.trim() : form.price.value.trim(),
                note: (form.note && form.note.value.trim()) ? form.note.value.trim() : "N/A"
            };

            if (!data.firstName) {
                Swal.fire('Validation Error', 'First Name is required.', 'warning');
                return;
            }
            if (!data.lastName) {
                Swal.fire('Validation Error', 'Last Name is required.', 'warning');
                return;
            }
            if (!data.phone) {
                Swal.fire('Validation Error', 'Phone Number is required.', 'warning');
                return;
            }
            if (!data.email) {
                Swal.fire('Validation Error', 'Email Address is required.', 'warning');
                return;
            }
            if (!data.price) {
                Swal.fire('Validation Error', 'Flat Price is required.', 'warning');
                return;
            }
            if (!data.paymentOption) {
                Swal.fire('Validation Error', 'Please select a Payment Option.', 'warning');
                return;
            }
            if (data.paymentOption === 'EMI' && !data.payment) {
                Swal.fire('Validation Error', 'Payment Amount is required for EMI.', 'warning');
                return;
            }

            fetch('addSaleInfo_process.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                })
                .then(res => res.json())
                .then(result => {
                    if (result.success) {
                        Swal.fire('Success', 'Sale information saved!', 'success').then(() => {
                            window.location.href = 'manageSale.php';
                        });
                    } else {
                        Swal.fire('Error', result.message || 'Failed to save data.', 'error');
                    }
                })
                .catch(() => {
                    Swal.fire('Error', 'Server error.', 'error');
                });
        });
    </script>
</body>

</html>