<?php
require_once __DIR__ . '/partials/_session.php';
require_once __DIR__ . '/includes/config.php';

$startDate = $_GET['start_date'] ?? null;
$endDate = $_GET['end_date'] ?? null;

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

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Print Report</title>
    <link rel="icon" href="img/logo.ico" type="image/ico">
    <link href="css/sb-admin-2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'monospace';
            font-size: large;
        }

        @media print {

            * {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .container-fluid {
                width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            .card,
            .card-header,
            .card-body {
                border: 1px solid #000 !important;
                box-shadow: none !important;
                border-radius: 0 !important;
                background-color: #fff !important;
            }

            .bg-white,
            .bg-primary,
            .bg-success,
            .bg-danger {
                background-color: #fff !important;
                color: #000 !important;
            }

            .text-white {
                color: #000 !important;
            }

            .shadow,
            .rounded-4,
            .rounded-top {
                box-shadow: none !important;
                border-radius: 0 !important;
            }

            .table {
                width: 100% !important;
                border-collapse: collapse !important;
                page-break-inside: auto !important;
            }

            .table th,
            .table td {
                border: 1px solid #000 !important;
                padding: 0.25rem !important;
            }

            thead {
                display: table-header-group;
            }

            tfoot {
                display: table-footer-group;
            }

            h2,
            h4 {
                color: #000 !important;
                text-transform: none !important;
            }

            p,
            .small {
                color: #000 !important;
            }

            .no-print,
            .fa,
            .btn {
                display: none !important;
            }
        }

        tfoot.table-secondary {
            background-color: #cfe2ff !important;
            color: #000 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        tfoot.table-secondary th {
            font-weight: bold;
            border: 1px solid #000;
        }

        thead {
            display: table-header-group;
        }

        tfoot {
            display: table-row-group;
        }

        tr {
            page-break-inside: avoid;
        }
    </style>
</head>

<body class="bg-light text-dark">
    <div class="container-fluid">
        <!-- Header Section -->
        <div class="container bg-white border rounded-4 shadow-sm text-center p-4 mt-4">
            <div class="row align-items-center">
                <div class="col-md-2 col-12 mb-3 mb-md-0">
                    <img src="img/company-logo.svg" alt="Company Logo" class="img-fluid" style="max-height: 100px;">
                </div>
                <div class="col-md-10 col-12 text-md-start">
                    <h2 class="fw-bold text-success">রয়েল ক্রাউন রিয়েল এ্যাস্টেট লিমিটেড</h2>
                    <p class="mb-1 small">
                        ৮৬/১, বনমালা রোড, হাউজবিল্ডিং, দত্তপাড়া, টঙ্গি, গাজীপুর, বাংলাদেশ
                    </p>
                    <p class="mb-1 small">
                        মোবাইল: ০১৯৬৮ ৩৯১৭৫৩, ০১৭৪৭ ৪৩২৯৪৮, ০১৭১৩ ৯৬১১৩০
                    </p>
                    <p class="fw-bold text-danger fs-4 mb-0">
                        <i class="fas fa-phone-volume text-success me-2"></i> 01935 664455 (P)
                    </p>
                </div>
            </div>
        </div>

        <div class="container py-5">
            <?php if ($startDate && $endDate): ?>
                <h4 class="fw-bold text-primary border-start border-4 ps-3 mb-4">
                    Report from <?= formatReadableDate($startDate) ?> to <?= formatReadableDate($endDate) ?>
                </h4>

                <!-- Fundings Section -->
                <div class="card mb-4 border-0 shadow">
                    <div class="card-header bg-primary text-white fw-semibold rounded-top">
                        <i class="fas fa-hand-holding-usd me-1"></i> Fundings
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Funding NO.</th>
                                        <th>Investor Name</th>
                                        <th>Phone</th>
                                        <th>Amount (টাকা)</th>
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
                                            <td colspan="5" class="text-center text-muted">No records found for selected dates.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                                <tfoot class="table-secondary text-center">
                                    <tr>
                                        <th colspan="3">Grand Total</th>
                                        <th colspan="2"><?= formatBDT($grandTotal) ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Payments Section -->
                <div class="card mb-4 border-0 shadow">
                    <div class="card-header bg-success text-white fw-semibold rounded-top">
                        <i class="fas fa-coins me-1"></i> Payments
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Payment NO.</th>
                                        <th>Sale NO.</th>
                                        <th>Building</th>
                                        <th>Customer</th>
                                        <th>Phone</th>
                                        <th>Amount (টাকা)</th>
                                        <th>Sale Date</th>
                                        <th>Payment Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $stmt = $dbh->prepare("SELECT p.pay_id, p.sale_id, p.amount AS payment_amount, p.created_at AS payment_date, s.first_name, s.last_name, s.phone, s.created_at AS sale_date, b.building_name FROM payments p JOIN sales s ON p.sale_id = s.sale_id JOIN flats f ON s.flat_id = f.flat_id JOIN floors fl ON f.floor_id = fl.floor_id JOIN buildings b ON fl.building_id = b.building_id WHERE p.created_at BETWEEN :start AND :end ORDER BY p.created_at DESC");
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
                                                <td><?= htmlspecialchars(date('d M Y', strtotime($row['sale_date']))) ?></td>
                                                <td><?= htmlspecialchars(date('d M Y', strtotime($row['payment_date']))) ?></td>
                                            </tr>
                                        <?php endforeach;
                                    else: ?>
                                        <tr>
                                            <td colspan="8" class="text-center text-muted">No records found for selected dates.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                                <tfoot class="table-secondary text-center">
                                    <tr>
                                        <th colspan="5">Grand Total</th>
                                        <th colspan="3"><?= formatBDT($grandTotal) ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Expenses Section -->
                <div class="card border-0 shadow">
                    <div class="card-header bg-danger text-white fw-semibold rounded-top">
                        <i class="fas fa-receipt me-1"></i> Expenses
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-sm align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Expense NO.</th>
                                        <th>Purpose</th>
                                        <th>Amount (টাকা)</th>
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
                                            <td colspan="4" class="text-center text-muted">No records found for selected dates.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                                <tfoot class="table-secondary text-center">
                                    <tr>
                                        <th colspan="2">Grand Total</th>
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
    </div>

    <script>
        window.onload = function() {
            window.print();
        };

        window.onafterprint = function() {
            window.close();
        };
    </script>
</body>


</html>