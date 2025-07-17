<?php
require_once __DIR__ . '/partials/_session.php';

$title = "Manage Sales";
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
                    <h1 class="h3 text-gray-800">Manage Sales</h1>
                    <p class="mb-0">
                        This section provides an overview of all property sales. You can view, or remove sales transactions and monitor customer details. Keeping sales records up-to-date helps ensure accurate reporting and efficient property management.
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
                                            <th class="text-dark">Sale Code</th>
                                            <th>Building Name</th>
                                            <th>Flat Label</th>
                                            <th>Customer</th>
                                            <th>Phone</th>
                                            <th>Price</th>
                                            <th>Note</th>
                                            <th>Payment</th>
                                            <th>Status</th>
                                            <th>Created</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $ret = "SELECT sales.*, 
                                               SUM(payments.amount) AS payment,
                                               floors.floor_number AS floor,
                                               flats.flat_lable AS flatLabel,
                                               flats.status AS flatStatus,
                                               building_name AS buildingName
                                        FROM sales
                                        INNER JOIN payments ON sales.sale_id = payments.sale_id
                                        INNER JOIN flats ON sales.flat_id = flats.flat_id
                                        INNER JOIN floors ON flats.floor_id = floors.floor_id
                                        INNER JOIN buildings ON floors.building_id = buildings.building_id
                                        GROUP BY sales.sale_id
                                        ORDER BY sales.created_at DESC";
                                        $stmt =  $dbh->prepare($ret);
                                        $stmt->execute();
                                        $sales = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                        foreach ($sales as $sale) {
                                        ?>
                                            <tr>
                                                <th class="text-dark"><?php echo htmlspecialchars($sale['sale_id']); ?></th>
                                                <td><?php echo htmlspecialchars($sale['buildingName']); ?></td>
                                                <td><?php echo htmlspecialchars('Floor-' . $sale['floor'] . "-" . $sale['flatLabel']); ?></td>
                                                <td><?php echo htmlspecialchars($sale['first_name'] . ' ' . $sale['last_name']); ?></td>
                                                <td><?php echo htmlspecialchars($sale['phone']); ?></td>
                                                <td><?php echo htmlspecialchars($sale['price']); ?> (৳)</td>
                                                <td>
                                                    <p><?php echo htmlspecialchars($sale['note']); ?></p>
                                                </td>
                                                <td><?php echo htmlspecialchars($sale['flatStatus']); ?></td>
                                                <td>
                                                    <?php
                                                    $remainingPayment = $sale['price'] - $sale['payment'];
                                                    if ($remainingPayment <= 0) {
                                                        // Fully paid, green icon
                                                        echo '<span>
                                                          <i class="fas fa-check-circle fa-lg" style="color: green;"></i> Paid </span>';
                                                    } else {
                                                        // Due, red icon
                                                        echo '<span>
                                                          <i class="fas fa-exclamation-circle fa-lg" style="color: red;"></i> Due <br>' . number_format($remainingPayment) . ' (৳)</span>';
                                                    }
                                                    ?>
                                                </td>
                                                <td><?php echo date("h:i A", strtotime($sale['created_at'])) . "<br>" . date("jS M Y", strtotime($sale['created_at'])); ?></td>
                                                <td>
                                                    <a href="javascript:void(0);"
                                                        onclick="confirmDelete(<?php echo htmlspecialchars($sale['sale_id']); ?>)"
                                                        class="btn btn-transparent btn-xs"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        title="Delete">
                                                        <i class="fas fa-trash-alt" style="font-size: 24px; color: red;"></i>
                                                    </a>
                                                </td>
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
                [0, "desc"]
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