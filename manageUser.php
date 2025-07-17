<?php
include 'partials/_session.php';
require_once __DIR__ . '/includes/config.php';
$title = "Manage User";
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
                        <h1 class="h3 mb-2 text-gray-800">Manage User</h1>
                        <p class="mb-4">
                            This section allows administrators to manage user accounts. You can search, sort, view details, update information, or delete users as needed. Use the table below to efficiently handle user-related tasks and maintain control over your system's access.
                        </p>

                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">User List</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>First Name</th>
                                                <th>Last Name</th>
                                                <th>Email</th>
                                                <th>User Type</th>
                                                <th>Updated At</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Fetch user data from the database
                                            $stmt = $dbh->prepare("SELECT user_id, fName, lName, email, updated_at,userType FROM users ORDER BY created_at aSC");
                                            $stmt->execute();
                                            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                            foreach ($users as $user) {
                                                echo "<tr>";
                                                echo "<td>" . htmlspecialchars($user['fName']) . "</td>";
                                                echo "<td>" . htmlspecialchars($user['lName']) . "</td>";
                                                echo "<td>" . htmlspecialchars($user['email']) . "</td>";
                                                echo "<td>" . ($user['userType'] === 'superAdmin' ? 'Super Admin' : 'Admin') . "</td>";
                                                $updatedAt = !empty($user['updated_at']) ? date('d M Y, h:i A', strtotime($user['updated_at'])) : 'N/A';
                                                echo "<td>" . htmlspecialchars($updatedAt) . "</td>";
                                                echo '<td>';
                                                if ($user['userType'] !== 'superAdmin') {
                                                    echo '<a href="editUser.php?id=' . htmlspecialchars($user['user_id']) . '" class="btn btn-primary">Edit</a> ';

                                                    echo '<button class="btn btn-danger" onclick="confirmDelete(' . htmlspecialchars($user['user_id']) . ')">Delete</button>';
                                                } else {
                                                    echo '<button class="btn btn-primary" disabled>Edit</button> ';

                                                    echo '<button class="btn btn-danger" disabled >Delete</button>';
                                                }
                                                echo '</td>';
                                                echo "</tr>";
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

            function confirmDelete(userId) {
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
                        fetch('deleteUser.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded'
                                },
                                body: 'id=' + encodeURIComponent(userId)
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