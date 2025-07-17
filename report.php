<?php
require_once __DIR__ . '/partials/_session.php';
require_once __DIR__ . '/includes/config.php';


$startDate = isset($_POST['start_date']) ? $_POST['start_date'] . ' 00:00:00' : null;
$endDate = isset($_POST['end_date']) ? $_POST['end_date'] . ' 23:59:59' : null;


function formatReadableDate($dateString)
{
    $timestamp = strtotime($dateString);
    $day = date('j', $timestamp);
    $month = strtoupper(date('M', $timestamp));
    $year = date('Y', $timestamp);

    // Get ordinal suffix
    if ($day % 100 >= 11 && $day % 100 <= 13) {
        $suffix = 'th';
    } else {
        switch ($day % 10) {
            case 1:
                $suffix = 'st';
                break;
            case 2:
                $suffix = 'nd';
                break;
            case 3:
                $suffix = 'rd';
                break;
            default:
                $suffix = 'th';
        }
    }

    return $day . $suffix . ' ' . $month . ' ' . $year;
}

function formatBDT($amount)
{
    $amount = (int)$amount;
    $number = (string)$amount;

    if (strlen($number) <= 3) {
        return  $number . ' ৳';
    }

    $lastThree = substr($number, -3);
    $restUnits = substr($number, 0, -3);

    $restFormatted = '';
    if ($restUnits != '') {
        $restFormatted = preg_replace("/\B(?=(\d{2})+(?!\d))/", ",", $restUnits);
    }

    return  $restFormatted . ',' . $lastThree . ' ৳';
}

$title = "Report";
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
                    <h1 class="h3 mb-2 text-gray-800">Report Overview</h1>
                    <p class="mb-4 text-muted">
                        This page provides a detailed summary of all financial activities, including fundings, payments, and expenses within a selected date range. Review each entry with details like purpose, amount, date, and contributor. Use this report to monitor transactions, ensure financial accuracy, and maintain accountability.
                    </p>
                </div>
                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <div class="container border text-center p-3">
                        <div class="row align-items-center">
                            <div class="col-md-2 col-12 text-center mb-3 mb-md-0">
                                <img src="img/company-logo.svg" alt="Company Logo" class="img-fluid" style="max-height: 140px;">
                            </div>
                            <div class="col-md-10 col-12">
                                <h2 class="mb-1 fw-bold text-success">রয়েল ক্রাউন রিয়েল এ্যাস্টেট লিমিটেড</h2>
                                <p class="mb-1" style="font-size: 0.95rem;">
                                    ৮৬/১, বনমালা রোড, হাউজবিল্ডিং, দত্তপাড়া, টঙ্গি, গাজীপুর, বাংলাদেশ
                                </p>
                                <p class="mb-1" style="font-size: 0.95rem;">
                                    মোবাইল: ০১৯৬৮ ৩৯১৭৫৩, ০১৭৪৭ ৪৩২৯৪৮, ০১৭১৩ ৯৬১১৩০
                                </p>
                                <p class="mb-0 fw-bold text-danger" style="font-size: 2rem;">
                                    <i class="fas fa-phone-volume text-success me-2"></i> 01935 664455 (P)
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="container py-4">
                        <form method="POST" class="mb-4 p-3 bg-light rounded shadow-sm">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-4 mt-2">
                                    <label for="start_date" class="form-label fw-semibold">Start Date</label>
                                    <input type="date" id="start_date" name="start_date" class="form-control" required value="<?= htmlspecialchars($_POST['start_date'] ?? date('Y-m-d')) ?>">
                                </div>
                                <div class="col-md-4 mt-2">
                                    <label for="end_date" class="form-label fw-semibold">End Date</label>
                                    <input type="date" id="end_date" name="end_date" class="form-control" required value="<?= htmlspecialchars($_POST['end_date'] ?? date('Y-m-d')) ?>">
                                </div>
                                <div class="col-md-4 mt-2">
                                    <button type="submit" class="btn btn-primary w-100 mt-2">Generate Report</button>
                                </div>
                            </div>
                        </form>

                        <?php if ($startDate && $endDate): ?>
                            <h4 class="mb-4 fw-bold text-primary">
                                Report from <?= formatReadableDate($startDate) ?> to <?= formatReadableDate($endDate) ?>
                            </h4>

                            <!-- Fundings Section -->
                            <div class="card mb-4 shadow-sm">
                                <div class="card-header bg-primary text-white fw-semibold">Fundings</div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm table-hover mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Funding NO.</th>
                                                    <th>Investor Name</th>
                                                    <th>Phone</th>
                                                    <th>Amount (৳)</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $stmt = $dbh->prepare("SELECT f.fun_id, f.amount, f.note, f.created_at, i.fName, i.lName, i.phone FROM fundings f JOIN investor i ON f.in_id = i.in_id WHERE f.created_at BETWEEN :start AND :end");
                                                $stmt->execute([':start' => $startDate, ':end' => $endDate]);
                                                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                                $grandTotal = 0;
                                                if (!empty($rows)):
                                                    foreach ($rows as $row):
                                                        $grandTotal += $row['amount'];
                                                ?>
                                                        <tr>
                                                            <td><?= htmlspecialchars($row['fun_id']) ?></td>
                                                            <td><?= htmlspecialchars($row['fName'] . ' ' . $row['lName']) ?></td>
                                                            <td><?= htmlspecialchars($row['phone']) ?></td>
                                                            <td><?= formatBDT($row['amount']) ?></td>
                                                            <td><?= htmlspecialchars(date('d M Y, h:i A', strtotime($row['created_at']))) ?></td>
                                                        </tr>
                                                    <?php endforeach;
                                                else: ?>
                                                    <tr>
                                                        <td colspan="5" class="text-center">No records found for selected dates.</td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="3" class="text-end">Grand Total</th>
                                                    <th colspan="2"><?= formatBDT($grandTotal) ?></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Payments Section -->
                            <div class="card mb-4 shadow-sm">
                                <div class="card-header bg-success text-white fw-semibold">Payments</div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped table-hover mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Payment NO.</th>
                                                    <th>Sale NO.</th>
                                                    <th>Building</th>
                                                    <th>Customer</th>
                                                    <th>Phone</th>
                                                    <th>Amount (৳)</th>
                                                    <th>Sale Date</th>
                                                    <th>Payment Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $stmt = $dbh->prepare("
                                SELECT 
                                    p.pay_id, p.sale_id, p.amount AS payment_amount, p.created_at AS payment_date,
                                    s.first_name, s.last_name, s.phone, s.created_at AS sale_date,
                                    b.building_name
                                FROM payments p
                                JOIN sales s ON p.sale_id = s.sale_id
                                JOIN flats f ON s.flat_id = f.flat_id
                                JOIN floors fl ON f.floor_id = fl.floor_id
                                JOIN buildings b ON fl.building_id = b.building_id
                                WHERE p.created_at BETWEEN :start AND :end
                                ORDER BY p.created_at DESC
                                                          ");
                                                $stmt->execute([':start' => $startDate, ':end' => $endDate]);
                                                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                                $grandTotal = 0;
                                                if (!empty($results)):
                                                    foreach ($results as $row):
                                                        $grandTotal += $row['payment_amount'];
                                                ?>
                                                        <tr>
                                                            <td><?= htmlspecialchars($row['pay_id']) ?></td>
                                                            <td><?= htmlspecialchars($row['sale_id']) ?></td>
                                                            <td><?= htmlspecialchars($row['building_name']) ?></td>
                                                            <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
                                                            <td><?= htmlspecialchars($row['phone']) ?></td>
                                                            <td><?= formatBDT($row['payment_amount']) ?></td>
                                                            <td><?= htmlspecialchars($row['sale_date']) ?></td>
                                                            <td><?= htmlspecialchars($row['payment_date']) ?></td>
                                                        </tr>
                                                    <?php endforeach;
                                                else: ?>
                                                    <tr>
                                                        <td colspan="8" class="text-center">No records found for selected dates.</td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="5" class="text-end">Grand Total</th>
                                                    <th colspan="3"><?= formatBDT($grandTotal) ?></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Expenses Section -->
                            <div class="card shadow-sm">
                                <div class="card-header bg-danger text-white fw-semibold">Expenses</div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover table-sm mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Expense NO.</th>
                                                    <th>Purpose</th>
                                                    <th>Amount (৳)</th>
                                                    <th>Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $stmt = $dbh->prepare("SELECT * FROM expense WHERE created_at BETWEEN :start AND :end");
                                                $stmt->execute([':start' => $startDate, ':end' => $endDate]);
                                                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                                $grandTotal = 0;
                                                if (!empty($rows)):
                                                    foreach ($rows as $row):
                                                        $grandTotal += $row['amount'];
                                                ?>
                                                        <tr>
                                                            <td><?= htmlspecialchars($row['ep_id']) ?></td>
                                                            <td><?= htmlspecialchars($row['purpose']) ?></td>
                                                            <td><?= formatBDT($row['amount']) ?></td>
                                                            <td><?= htmlspecialchars(date('d M Y, h:i A', strtotime($row['created_at']))) ?></td>
                                                        </tr>
                                                    <?php endforeach;
                                                else: ?>
                                                    <tr>
                                                        <td colspan="4" class="text-center">No records found for selected dates.</td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="2" class="text-end">Grand Total</th>
                                                    <th colspan="2"><?= formatBDT($grandTotal) ?></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info text-center mt-5">Please select a start and end date to view the report.</div>
                        <?php endif; ?>
                    </div>
                    <div class="d-flex justify-content-center m-3">
                        <a id="printReportBtn" class="btn btn-outline-secondary"
                            href="print_report.php?start_date=<?= urlencode($startDate) ?>&end_date=<?= urlencode($endDate) ?>"
                            target="_blank">
                            🖨️ Print Report
                        </a>
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
        flatpickr("#start_date", {
            dateFormat: "Y-m-d",
            disableMobile: true
        });

        flatpickr("#end_date", {
            dateFormat: "Y-m-d",
            disableMobile: true
        });
    </script>


</body>

</html>