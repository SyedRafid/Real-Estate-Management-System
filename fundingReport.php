<?php
require_once __DIR__ . '/partials/_session.php';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/vendor/autoload.php';

use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Pagerfanta\View\TwitterBootstrap4View;

// Fetch all fundings with investor info
$query = "
    SELECT 
        f.*, 
        i.fName, 
        i.lName, 
        i.phone 
    FROM 
        fundings f
    JOIN 
        investor i ON f.in_id = i.in_id
    ORDER BY 
        f.created_at DESC
";
$allFundings = $dbh->query($query)->fetchAll(PDO::FETCH_ASSOC);

// Setup Pagerfanta adapter
$adapter = new ArrayAdapter($allFundings);
$pagerfanta = new Pagerfanta($adapter);

// Set items per page
$pagerfanta->setMaxPerPage(9);

$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($currentPage < 1) $currentPage = 1;

// Ensure the current page does not exceed max pages
$maxPages = $pagerfanta->getNbPages();
if ($currentPage > $maxPages) {
    $currentPage = $maxPages > 0 ? $maxPages : 1;
}

$pagerfanta->setCurrentPage($currentPage);

// Get current page results
$currentPageFundings = $pagerfanta->getCurrentPageResults();

// Prepare Bootstrap 4 pagination view
$view = new TwitterBootstrap4View();
$routeGenerator = function ($page) {
    return '?page=' . $page;
};

$title = "Manage Funding";
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
                    <h1 class="h3 mb-2 text-gray-800">Funding Report</h1>
                    <p class="mb-4">
                        This section provides a detailed report of all recorded funding contributions made by investors. You can review each entry, including the investor's name, contributed amount, funding date, any attached agreements, and additional notes. The report allows for efficient tracking of incoming funds, ensuring financial transparency, accountability, and better investment oversight within the system.
                    </p>
                </div>
                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <div class="card-body pt-4">
                        <div class="kola">
                            <?php foreach ($currentPageFundings as $funding): ?>
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <div class="card-custom" onclick="toggleCard(this)">
                                        <div class="amount-circle-container">
                                            <div class='circle'> <?= $funding['fun_id']; ?></div>
                                            <div class='months'>
                                                <?php
                                                $date = $funding['created_at'];
                                                $timestamp = strtotime($date);
                                                $formattedDate = date('j-M', $timestamp) . '(' . date('y', $timestamp) . ')';
                                                ?>
                                                <p><?= htmlentities($formattedDate) ?></p>
                                            </div>
                                            <div class="amount"><?= number_format($funding['amount'], 0); ?>৳</div>
                                        </div>
                                        <div class="details">
                                            <p class="details-title">Investor: <?= $funding['fName'] . ' ' . $funding['lName']; ?></p>
                                            <p><strong>Phone:</strong> <?= htmlentities($funding['phone']); ?></p>
                                            <p><strong>Note:</strong> <?= !empty($funding['note']) ? htmlentities($funding['note']) : 'N/A'; ?></p>
                                        </div>

                                        <div style="text-align: right;">
                                            <div style="text-align: right;">
                                                <a href="javascript:void(0);"
                                                    onclick="deleteFunding(event, <?php echo $funding['fun_id']; ?>, this)"
                                                    class="btn btn-transparent btn-xs"
                                                    data-bs-toggle="tooltip"
                                                    data-bs-placement="top"
                                                    title="Remove">
                                                    <i class="fas fa-trash-alt" style='font-size:24px;color:red'></i>
                                                </a>
                                            </div>
                                        </div>

                                        <div class="card-expanded">
                                            <p><strong>Invoice Slip:</strong></p>
                                            <div style="text-align: center; margin: 20px 0;">
                                                <?php
                                                $image = $funding['image'];
                                                ?>
                                                <div style="display: inline-block; border: 2px solid #6a25d7; border-radius: 10px; padding: 5px; background-color: #f9f9f9; box-shadow: -3px 3px 10px rgb(0 0 0 / 34%);">
                                                    <img src="<?= !empty($image) ? 'img/uploads/' . htmlentities($image) : 'img/uploads/default-image.png'; ?>" alt="Invoice Image" style="max-width: 100%; height: auto; border-radius: 8px;">
                                                </div>
                                            </div>
                                            <div style="padding: 10px; background-color: #6a25d745; border-radius: 5px; margin-top: 10px; text-align: center;">
                                                <strong>Recorded On:</strong> <?= date('d M Y, h:i A', strtotime($date)); ?><br>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            <?php endforeach; ?>

                            <?php if (count($currentPageFundings) === 0): ?>
                                <div class="col-md-12 text-center mt-4">
                                    <h4>No fundings record found!</h4>
                                    <p>Please add some fundings log to see them listed here.</p>
                                </div>
                            <?php endif; ?>
                        </div> <!-- End row -->

                        <!-- Pagerfanta pagination controls -->
                        <div class="d-flex justify-content-center">
                            <?= $view->render($pagerfanta, $routeGenerator); ?>
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

        // Function to delete an fundings
        function deleteFunding(event, fundingId, button) {
            event.stopPropagation(); // Prevent the card's onclick from firing

            Swal.fire({
                title: 'Are you sure?',
                text: 'You won\'t be able to revert this!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'funding_delete.php',
                        type: 'POST',
                        contentType: 'application/json',
                        data: JSON.stringify({
                            id: fundingId
                        }), // Send JSON
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: 'Your fundings log has been deleted.',
                                    icon: 'success',
                                    confirmButtonText: 'OK'
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire(
                                    'Error!',
                                    'Error deleting fundings: ' + response.message,
                                    'error'
                                );
                            }
                        },
                        error: function() {
                            Swal.fire(
                                'Error!',
                                'An error occurred while deleting the fundings.',
                                'error'
                            );
                        }
                    });
                }
            });
        }

        // Function to toggle the expanded content of the card
        function toggleCard(card) {
            const expandedContent = card.querySelector('.card-expanded');
            if (expandedContent.style.display === 'block') {
                expandedContent.style.display = 'none';
            } else {
                expandedContent.style.display = 'block';
            }
        }
    </script>
</body>

</html>