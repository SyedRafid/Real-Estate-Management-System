<?php
require_once __DIR__ . '/partials/_session.php';

$title = "Payments";
?>

<!DOCTYPE html>
<html lang="en">
<?php include 'partials/_head2.php'; ?>
<style>
    .child-row-wrapper {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-left: 4px solid #5e72e4;
        padding: 20px;
        margin-top: 10px;
        border-radius: 10px;
        font-family: Arial, sans-serif;
    }

    .child-row-header {
        background-color: #5e72e4;
        color: white;
        padding: 10px 15px;
        border-radius: 8px 8px 0 0;
        font-size: 16px;
        font-weight: bold;
        margin-bottom: 15px;
        text-align: center;
    }

    .child-row-wrapper table {
        background-color: white;
        border-radius: 6px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .child-row-wrapper thead {
        background-color: #e9ecef;
    }

    .child-row-wrapper th,
    .child-row-wrapper td {
        padding: 10px;
        vertical-align: middle;
    }

    .child-row-wrapper tfoot td {
        font-weight: bold;
        background-color: #f1f1f1;
    }
</style>

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
                    <h1 class="h3 text-gray-800">Payments</h1>
                    <p class="mb-0">
                        This section provides a detailed overview of all payment transactions related to property sales. You can track received payments, monitor outstanding balances, and ensure that financial records remain accurate and up-to-date. For properties sold under EMI plans, you can also add installment payments directly from here for better tracking and management.
                    </p>
                </div>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Payment List</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Sale Code</th>
                                            <th>Building Name</th>
                                            <th>Flat Label</th>
                                            <th>Customer</th>
                                            <th>Phone</th>
                                            <th>Price</th>
                                            <th>Payment</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = "SELECT sales.*, 
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

                                        $stmt = $dbh->prepare($sql);
                                        $stmt->execute();
                                        $sales = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                        foreach ($sales as $sale): ?>
                                            <tr class="data-row" data-sale-id="<?= $sale['sale_id']; ?>">
                                                <td><?= htmlspecialchars($sale['sale_id']); ?></td>
                                                <td><?= htmlspecialchars($sale['buildingName']); ?></td>
                                                <td><?= htmlspecialchars('Floor-' . $sale['floor'] . '-' . $sale['flatLabel']); ?></td>
                                                <td><?= htmlspecialchars($sale['first_name'] . ' ' . $sale['last_name']); ?></td>
                                                <td><?= htmlspecialchars($sale['phone']); ?></td>
                                                <td><?= htmlspecialchars($sale['price']); ?> (৳)</td>
                                                <td><?= htmlspecialchars($sale['flatStatus']); ?></td>
                                                <td>
                                                    <?php
                                                    $remaining = $sale['price'] - $sale['payment'];
                                                    if ($remaining <= 0) {
                                                        echo '<span style="color: green;"><i class="fas fa-check-circle"></i> Paid</span>';
                                                    } else {
                                                        echo '<span style="color: red;"><i class="fas fa-exclamation-circle"></i> Due <br>' . number_format($remaining) . ' (৳)</span>';
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php if ($remaining > 0): ?>
                                                        <button class="btn btn-success" onclick="event.stopPropagation(); confirmPay(<?= $sale['sale_id']; ?>)">Pay</button>
                                                    <?php else: ?>
                                                        <button class="btn btn-danger" onclick="event.stopPropagation();" disabled>Paid</button>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
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
        $(document).ready(function() {
            // Safely initialize DataTable
            if ($.fn.DataTable.isDataTable('#dataTable')) {
                $('#dataTable').DataTable().destroy();
            }

            const table = $('#dataTable').DataTable({
                order: [
                    [7, "asc"]
                ],
                pageLength: 25
            });

            $('#dataTable tbody').on('click', 'tr.data-row', function() {
                const $row = $(this);
                const saleId = $row.data('sale-id');
                const $next = $row.next('tr.child-row');

                // Remove existing details row
                $('.child-row').remove();
                $('tr.data-row').removeClass('highlighted');

                if ($next.length > 0) {
                    // Collapse
                    return;
                }

                $row.addClass('highlighted');

                // AJAX call or inline HTML
                $.ajax({
                    url: 'get_payment_details.php',
                    method: 'POST',
                    data: {
                        sale_id: saleId
                    },
                    success: function(data) {
                        const html = `
                    <tr class="child-row">
                        <td colspan="8">
                            <div class="details-box" style="padding: 20px; background: #f0f0f0; border-radius: 10px;">
                                ${data}
                            </div>
                        </td>
                    </tr>`;
                        $row.after(html);
                    },
                    error: function() {
                        alert('Could not load payment details.');
                    }
                });
            });
        });


        function confirmPay(saleId) {
            const $row = $(`tr.data-row[data-sale-id="${saleId}"]`);
            let remainingText = $row.find('td:nth-child(8)').text().trim();
            let remainingAmount = 0;

            const match = remainingText.match(/([\d,\.]+)\s*\(৳\)/);
            if (match) {
                remainingAmount = parseFloat(match[1].replace(/,/g, ''));
            }
            const maxVal = parseFloat(remainingAmount);

            Swal.fire({
                title: 'Confirm Payment',
                html: `
                    <div style="text-align: center; margin-bottom: 15px;">
                        <p style="font-size: 17px; color: #555; font-weight: bold;">Remaining Amount: <span style="color: #28a745;">${remainingAmount} (৳)</span></p>
                    </div>
                    <div style="text-align: left; font-size: 16px; margin-bottom: 10px; color: #333; text-align: center;">
                        Please enter payment amount:
                    </div>
                    <div style="text-align: center;">
                        <input type="number" id="paymentAmount" class="swal2-input" value="${remainingAmount}" min="1" max="${maxVal}"required  style="width: 100%; max-width: 400px; margin: auto; padding: 8px; font-size: 14px; border: 1px solid #ccc; border-radius: 4px;">
                    </div>      
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Submit',
                confirmButtonColor: '#28a745',
                cancelButtonText: 'Cancel',
                cancelButtonColor: '#dc3545',
                preConfirm: () => {
                    const paymentAmount = document.getElementById('paymentAmount').value;
                    if (!paymentAmount) {
                        Swal.showValidationMessage('Payment amount is required.');
                        return false;
                    }

                    if (paymentAmount <= 0) {
                        Swal.showValidationMessage('Payment amount must be greater than 0.');
                        return false;
                    }

                    if (paymentAmount > remainingAmount) {
                        Swal.showValidationMessage('Payment amount cannot exceed the remaining amount.');
                        return false;
                    }
                    return {
                        paymentAmount
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('payments_process.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: `sale_id=${encodeURIComponent(saleId)}&amount=${encodeURIComponent(result.value.paymentAmount)}&max=${encodeURIComponent(maxVal)}`
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire('Success!', 'Payment information saved!', 'success').then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire('Error!', data.message, 'error');
                            }
                        })
                        .catch(() => {
                            Swal.fire('Error!', 'An error occurred while submitting the payment.', 'error');
                        });
                }
            });
        }
    </script>

</body>

</html>