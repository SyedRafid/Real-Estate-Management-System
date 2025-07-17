<?php
require_once __DIR__ . '/partials/_session.php';
require_once __DIR__ . '/includes/config.php';
$title = "Manage Building";
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
                        <h1 class="h3 mb-2 text-gray-800">Manage Buildings</h1>
                        <p class="mb-4">
                            This section allows administrators to view building layouts and remove building records when necessary. Use the table below to efficiently browse and manage all building-related information. Editing is not permitted in this section.
                        </p>

                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Buildings Lists</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Buildings Name</th>
                                                <th>buildings Address</th>
                                                <th>Date</th>
                                                <th>View</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Fetch building data from the database
                                            $stmt = $dbh->prepare("SELECT *FROM buildings ORDER BY created_at aSC");
                                            $stmt->execute();
                                            $buildings = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                            $cnt = 1;

                                            foreach ($buildings as $building) {
                                                echo "<tr>";
                                                echo "<td>" . $cnt . "</td>";
                                                echo "<td>" . htmlspecialchars($building['building_name']) . "</td>";
                                                echo "<td>" . htmlspecialchars($building['location']) . "</td>";
                                                echo "<td>" . htmlspecialchars($building['created_at']) . "</td>";

                                                echo "<td>
                                                 <a href=\"viewBuilding.php?id=" . htmlspecialchars($building['building_id']) . "\"
                                                    class=\"btn btn-transparent btn-xs\"
                                                    data-bs-toggle=\"tooltip\"
                                                    data-bs-placement=\"top\"
                                                    title=\"View\">
                                                    <i class=\"fa-regular fa-eye\" style=\"font-size: 24px; color:rgb(4, 197, 20);\"></i>
                                                    </a>
                                                    </td>";
                                                echo "<td>
                                                    <a href=\"javascript:void(0);\" 
                                                    onclick=\"confirmDelete({$building['building_id']})\"
                                                    class=\"btn btn-transparent btn-xs\"
                                                    data-bs-toggle=\"tooltip\"
                                                    data-bs-placement=\"top\"
                                                    title=\"Delete\">
                                                    <i class=\"fas fa-trash-alt\" style=\"font-size: 24px; color: red;\"></i>
                                                    </a>
                                                </td>";
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


            function confirmDelete(buildingId) {
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
                        fetch('deleteBuilding.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded'
                                },
                                body: 'id=' + encodeURIComponent(buildingId)
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
                                Swal.fire('Error!', 'An error occurred while deleting the building.', 'error');
                            });
                    }
                });
            }
        </script>

    </body>

</html>