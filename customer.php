<?php
require_once __DIR__ . '/partials/_session.php';

$title = "Customers";
?>

<!DOCTYPE html>
<html lang="en">
<?php include 'partials/_head2.php'; ?>

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
                    <h1 class="h3 text-gray-800">Customers</h1>
                    <p class="mb-0">
                        This section displays a list of all registered customers. As an admin, you can view customer details to support sale management and ensure data accuracy. No other actions can be performed from this section.
                    </p>
                </div>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Sale List</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Phone</th>
                                            <th>Email</th>
                                            <th>Address</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $ret = "SELECT * FROM sales ORDER BY sales.created_at DESC";
                                        $stmt =  $dbh->prepare($ret);
                                        $stmt->execute();
                                        $sales = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                        foreach ($sales as $sale) {
                                        ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($sale['first_name'] . ' ' . $sale['last_name']); ?></td>
                                                <td><?php echo htmlspecialchars($sale['phone']); ?></td>
                                                <td><?php echo htmlspecialchars($sale['email']); ?></td>
                                                <td><?php echo htmlspecialchars($sale['address']); ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
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
        $('#dataTable').DataTable({
            "order": [
                [0, "asc"]
            ]
        });

        // Initialize Bootstrap tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        function confirmDelete(saleId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('deleteSale.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: 'id=' + encodeURIComponent(saleId)
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('Deleted!', data.message, 'success').then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire('Error!', data.message, 'error');
                            }
                        })
                        .catch(() => {
                            Swal.fire('Error!', 'An error occurred while deleting the user.', 'error');
                        });
                }
            });
        }
    </script>

</body>

</html>