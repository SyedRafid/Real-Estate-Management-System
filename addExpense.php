<?php
include 'partials/_session.php';

$title = "Add Expense";
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
                    <h1 class="h3 mb-2 text-gray-800">Record Expenses</h1>
                    <p class="mb-4">
                        This section allows you to record a new expense entry. Provide details such as the expense category, amount, and a brief description. Keeping accurate and timely expense logs helps ensure better financial tracking and accountability within the system.
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
                                            <h1 class="h4 text-gray-900 mb-4">Add Expense Log</h1>
                                        </div>
                                        <form class="user" id="addExSupport" enctype="multipart/form-data">
                                            <div class="form-group">
                                                <label for="aPurpose">Expenditure Purpose</label>
                                                <input type="text" name="aPurpose" id="aPurpose" class="form-control form-control-user" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="Amount">Amount Spent (৳)</label>
                                                <input type="number" name="amount" id="amount" class="form-control form-control-user" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="note">Additional Note (optional)</label>
                                                <textarea name="note" id="note" class="form-control form-control-user"></textarea>
                                            </div>

                                            <div class="form-group">
                                                <label for="image">Upload Receipt</label>
                                                <label for="image" class="custom-file-upload">
                                                    <i class="fas fa-upload"></i> Choose file...
                                                </label>
                                                <input type="file" id="image" name="image" accept="image/*" onchange="previewImage(this)">
                                                <img id="preview" src="" alt="Image Preview" style="display:none;" />
                                            </div>

                                            <button type="submit" id="submit" class="btn btn-primary btn-user btn-block">Submit</button>
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
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('preview');
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }


        document.getElementById('addExSupport').addEventListener('submit', async function(e) {
            e.preventDefault();

            const form = e.target;
            const formData = new FormData(form);
            const warning = [];

            // Check if the additional note is empty and warn the user
            if (!formData.get('note')?.trim()) {
                warning.push('Additional Note is empty.');
            }

            // Check if a receipt image has been uploaded
            const imageFile = formData.get('image');
            if (!imageFile || !imageFile.name) {
                warning.push('Receipt (Image) is not uploaded.');
            }

            if (warning.length > 0) {
                // Display warning if optional fields are empty
                Swal.fire({
                    icon: 'warning',
                    title: 'Warning',
                    text: warning.join(' '),
                    showCancelButton: true,
                    confirmButtonText: 'Submit Anyway',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Proceed with form submission
                        submitForm(formData);
                    }
                });
            } else {
                // No warnings, proceed with submission directly
                submitForm(formData);
            }
        });


        async function submitForm(formData) {
            try {
                const response = await fetch('addExpense_process.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();
                if (result.success) {
                    Swal.fire('Success', result.message, 'success').then(() => window.location.href = 'expenseReport.php');
                } else {
                    Swal.fire('Error', result.message, 'error');
                }
            } catch (error) {
                Swal.fire('Error', 'An error occurred while submitting the form.', 'error');
            }
        }
    </script>
</body>

</html>