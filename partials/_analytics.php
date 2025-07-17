<?php
require_once __DIR__ . '/_session.php';
require_once __DIR__ . '/../includes/config.php';

// 1. Get the user data
$stmt = $dbh->prepare("SELECT fName, lName, email FROM users WHERE user_id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$fName = $user['fName'];
$lName = $user['lName'];
$email = $user['email'];

function formatBDT($amount)
{
    $amount = (int)$amount;
    $number = (string)$amount;

    if (strlen($number) <= 3) {
        return '৳' . $number;
    }

    $lastThree = substr($number, -3);
    $restUnits = substr($number, 0, -3);

    $restFormatted = '';
    if ($restUnits != '') {
        $restFormatted = preg_replace("/\B(?=(\d{2})+(?!\d))/", ",", $restUnits);
    }

    return '৳' . $restFormatted . ',' . $lastThree;
}

// 2. Total sales for Current Month
$stmt = $dbh->prepare("SELECT SUM(price) AS total_price_this_month
FROM sales
WHERE MONTH(created_at) = MONTH(CURRENT_DATE())
  AND YEAR(created_at) = YEAR(CURRENT_DATE())");
$stmt->execute();
$monthSales = $stmt->fetch(PDO::FETCH_ASSOC);

$monthSale = $monthSales['total_price_this_month'] ?? 0;

// 3. Total sales for curent year
$stmt = $dbh->prepare("SELECT SUM(price) AS total_price_this_year
FROM sales
WHERE YEAR(created_at) = YEAR(CURRENT_DATE());
");
$stmt->execute();
$yearSales = $stmt->fetch(PDO::FETCH_ASSOC);

$yearSale = $yearSales['total_price_this_year'] ?? 0;

// 4. Total expense for current day
$stmt = $dbh->prepare("SELECT SUM(amount) AS total_amount_today
FROM expense
WHERE DATE(created_at) = CURRENT_DATE();
");
$stmt->execute();
$dayexpenses = $stmt->fetch(PDO::FETCH_ASSOC);

$dayexpense = $dayexpenses['total_amount_today'] ?? 0;

// 5. Total expense for Current Month
$stmt = $dbh->prepare("SELECT SUM(amount) AS total_amount_this_month
FROM expense
WHERE MONTH(created_at) = MONTH(CURRENT_DATE())
  AND YEAR(created_at) = YEAR(CURRENT_DATE())");
$stmt->execute();
$monthExpenses = $stmt->fetch(PDO::FETCH_ASSOC);

$monthExpense = $monthExpenses['total_amount_this_month'] ?? 0;

// 6. Total expense for curent year
$stmt = $dbh->prepare("SELECT SUM(amount) AS total_amount_this_year
FROM expense
WHERE YEAR(created_at) = YEAR(CURRENT_DATE());
");
$stmt->execute();
$yearxpenses = $stmt->fetch(PDO::FETCH_ASSOC);

$yearxpense = $yearxpenses['total_amount_this_year'] ?? 0;

// 7. Total fundings for curent year
$stmt = $dbh->prepare("SELECT SUM(amount) AS total_funding_this_year
FROM fundings
WHERE YEAR(created_at) = YEAR(CURRENT_DATE());
");
$stmt->execute();
$yearfundings = $stmt->fetch(PDO::FETCH_ASSOC);

$yearfunding = $yearfundings['total_funding_this_year'] ?? 0;

// 8. Total payments for Current Month
$stmt = $dbh->prepare("SELECT SUM(amount) AS total_payment_this_month
FROM payments
WHERE MONTH(created_at) = MONTH(CURRENT_DATE())
  AND YEAR(created_at) = YEAR(CURRENT_DATE())");
$stmt->execute();
$monthPayments = $stmt->fetch(PDO::FETCH_ASSOC);

$monthPayment = $monthPayments['total_payment_this_month'] ?? 0;

// 9. Total payments for curent year
$stmt = $dbh->prepare("SELECT SUM(amount) AS total_payment_this_year
FROM payments
WHERE YEAR(created_at) = YEAR(CURRENT_DATE());
");
$stmt->execute();
$yearPayments = $stmt->fetch(PDO::FETCH_ASSOC);

$yearPayment = $yearPayments['total_payment_this_year'] ?? 0;

// 10. Total Assets
$stmt = $dbh->prepare("
    SELECT 
        (SELECT IFNULL(SUM(amount), 0) FROM payments) +
        (SELECT IFNULL(SUM(amount), 0) FROM fundings) -
        (SELECT IFNULL(SUM(amount), 0) FROM expense) AS total_assets
");
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

$totalAssets = $result['total_assets'] ?? 0;

// 11. Past 12 months assets
$stmt = $dbh->prepare("SELECT 
  DATE_FORMAT(ds.month, '%b %Y') AS label,
  (
    SELECT IFNULL(SUM(p.amount), 0)
    FROM payments p
    WHERE p.created_at <= ds.month
  ) +
  (
    SELECT IFNULL(SUM(f.amount), 0)
    FROM fundings f
    WHERE f.created_at <= ds.month
  ) -
  (
    SELECT IFNULL(SUM(e.amount), 0)
    FROM expense e
    WHERE e.created_at <= ds.month
  ) AS total_assets
FROM (
  SELECT LAST_DAY(DATE_SUB(CURDATE(), INTERVAL n MONTH)) AS month
  FROM (
    SELECT 0 AS n UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 
    UNION ALL SELECT 4 UNION ALL SELECT 5
    UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9
    UNION ALL SELECT 10 UNION ALL SELECT 11
  ) AS numbers
) AS ds
ORDER BY ds.month ASC;");
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

$labels = [];
$totals = [];

foreach ($data as $row) {
    $labels[] = $row['label'];
    $totals[] = (float)$row['total_assets'];
}

// 12. Total Payments
$stmt = $dbh->prepare("SELECT IFNULL(SUM(amount), 0) AS total FROM payments");
$stmt->execute();
$totalPayments = $stmt->fetchColumn();

// 13. Tatal Fundings
$stmt = $dbh->prepare("SELECT IFNULL(SUM(amount), 0) AS total FROM fundings");
$stmt->execute();
$totalFundings = $stmt->fetchColumn();

// 14.  Total sales price per month over the past 12 months
$saleLabels = [];
$saleData = [];

$stmt = $dbh->prepare("SELECT 
  DATE_FORMAT(ds.month, '%b %Y') AS label,
  IFNULL(SUM(s.price), 0) AS total_sales
FROM (
  SELECT LAST_DAY(DATE_SUB(CURDATE(), INTERVAL n MONTH)) AS month
  FROM (
    SELECT 0 AS n UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 
    UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 
    UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 
    UNION ALL SELECT 10 UNION ALL SELECT 11
  ) AS nums
) ds
LEFT JOIN sales s ON DATE_FORMAT(s.created_at, '%Y-%m') = DATE_FORMAT(ds.month, '%Y-%m')
GROUP BY ds.month
ORDER BY ds.month ASC;");
$stmt->execute();

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $saleLabels[] = $row['label'];
    $saleData[] = (float)$row['total_sales'];
}

$jsLabels = json_encode($saleLabels);
$jsData = json_encode($saleData);

// 15.  Total payment price per month over the past 12 months
$paymentStmt = $dbh->prepare("SELECT 
    DATE_FORMAT(ds.month, '%b %Y') AS label,
    IFNULL(SUM(p.amount), 0) AS monthly_payment_total
  FROM (
    SELECT LAST_DAY(DATE_SUB(CURDATE(), INTERVAL n MONTH)) AS month
    FROM (
      SELECT 0 AS n UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 
      UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 
      UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 
      UNION ALL SELECT 10 UNION ALL SELECT 11
    ) AS nums
  ) ds
  LEFT JOIN payments p ON DATE_FORMAT(p.created_at, '%Y-%m') = DATE_FORMAT(ds.month, '%Y-%m')
  GROUP BY ds.month
  ORDER BY ds.month ASC");
$paymentStmt->execute();
$monthly_payments_data = $paymentStmt->fetchAll(PDO::FETCH_ASSOC);
