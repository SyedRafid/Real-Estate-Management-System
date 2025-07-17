<?php
require_once __DIR__ . '/partials/_session.php';
require_once __DIR__ . '/includes/config.php';
$title = "Manage Investor";
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'partials/_head2.php'; ?>

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
                        <!-- Page Heading -->
                        <h1 class="h3 mb-2 text-gray-800">Manage Investors</h1>
                        <p class="mb-4">
                            This section allows administrators to manage investor accounts. You can view profiles, update details, or remove investor records as needed. Use the table below to efficiently organize and oversee all investor-related information.
                        </p>

                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Investor Lists</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>First Name</th>
                                                <th>Last Name</th>
                                                <th>Phone</th>
                                                <th>Email</th>
                                                <th>Address</th>
                                                <th>Created At</th>
                                                <th>Updated At</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Fetch investor data from the database
                                            $stmt = $dbh->prepare("SELECT *FROM investor ORDER BY created_at aSC");
                                            $stmt->execute();
                                            $investors = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                            $cnt = 1;

                                            foreach ($investors as $investor) {
                                                echo "<tr>";
                                                echo "<td>" . $cnt . "</td>";
                                                echo "<td>" . htmlspecialchars($investor['fName']) . "</td>";
                                                echo "<td>" . htmlspecialchars($investor['lName']) . "</td>";
                                                echo "<td>" . htmlspecialchars($investor['phone']) . "</td>";
                                                echo "<td>" . htmlspecialchars($investor['email']) . "</td>";
                                                echo "<td>" . htmlspecialchars($investor['address']) . "</td>";
                                                $createdAt = !empty($investor['created_at']) ? date('d M Y, h:i A', strtotime($investor['created_at'])) : 'N/A';
                                                echo "<td>" . htmlspecialchars($createdAt) . "</td>";
                                                $updatedAt = !empty($investor['updated_at']) ? date('d M Y, h:i A', strtotime($investor['updated_at'])) : 'N/A';
                                                echo "<td>" . htmlspecialchars($updatedAt) . "</td>";
                                                echo '<td>';
                                                if ($investor['status'] == 'active') {
                                                    echo '<span class="btn btn-sm btn-success" style="font-style: normal;">
                                                            <i class="fa fa-check-circle"></i>
                                                          </span>';
                                                } else {
                                                    echo '<span class="btn btn-sm btn-danger" style="font-style: normal;">
                                                            <i class="fa fa-times-circle"></i>
                                                          </span>';
                                                }

                                                echo '<br>&nbsp;';

                                                echo '<select class="form-control form-control-sm status-select"
                                                             name="status_' . htmlspecialchars($investor['in_id']) . '"
                                                             data-id="' . htmlspecialchars($investor['in_id']) . '">
                                                        <option value="active"' . ($investor['status'] == 'active' ? ' selected' : '') . '>Active</option>
                                                        <option value="inactive"' . ($investor['status'] == 'inactive' ? ' selected' : '') . '>Inactive</option>
                                                      </select>';
                                                echo '</td>';
                                                echo "<td>";

                                                if ($investor['in_id'] != 1) {
                                                    echo "
                                                        <a href=\"javascript:void(0);\" 
                                                        onclick=\"confirmDelete({$investor['in_id']})\"
                                                        class=\"btn btn-transparent btn-xs\"
                                                        data-bs-toggle=\"tooltip\"
                                                        data-bs-placement=\"top\"
                                                        title=\"Delete\">
                                                        <i class=\"fas fa-trash-alt\" style=\"font-size: 24px; color: red;\"></i>
                                                        </a>
                                                
                                                        <a href=\"editInvestor.php?id=" . htmlspecialchars($investor['in_id']) . "\"
                                                        class=\"btn btn-transparent btn-xs\"
                                                        data-bs-toggle=\"tooltip\"
                                                        data-bs-placement=\"top\"
                                                        title=\"Edit\">
                                                        <i class=\"fa-regular fa-pen-to-square\" style=\"font-size: 24px; color: #007bff;\"></i>
                                                        </a>
                                                    ";
                                                } else {
                                                    echo "<span class=\"text-muted\">Protected</span>";
                                                }

                                                echo "</td>";
                                                echo "</tr>";
                                                $cnt++;
                                            }
                                            ?>
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
            // Initialize Bootstrap tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });


            document.querySelectorAll('.status-select').forEach(function(selectElement) {
                selectElement.addEventListener('change', function() {
                    const newStatus = this.value;
                    const investorId = this.getAttribute('data-id');

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You are about to change the status!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, change it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const formData = new FormData();
                            formData.append('in_id', investorId);
                            formData.append('status', newStatus);

                            fetch('update_status.php', {
                                    method: 'POST',
                                    body: formData
                                })
                                .then(response => response.text())
                                .then(() => {
                                    Swal.fire(
                                        'Updated!',
                                        'Status has been changed.',
                                        'success'
                                    ).then(() => {
                                        location.reload();
                                    });
                                })
                                .catch(() => {
                                    Swal.fire(
                                        'Error!',
                                        'Something went wrong!',
                                        'error'
                                    );
                                });
                        } else {
                            location.reload();
                        }
                    });
                });
            });


            function confirmDelete(investorId) {
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
                        fetch('deleteInvestor.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded'
                                },
                                body: 'id=' + encodeURIComponent(investorId)
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
                                Swal.fire('Error!', 'An error occurred while deleting the investor.', 'error');
                            });
                    }
                });
            }
        </script>

    </body>

</html>