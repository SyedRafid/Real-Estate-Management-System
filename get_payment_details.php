<?php
require_once __DIR__ . '/includes/config.php';
$sale_id = $_POST['sale_id'];

$stmt = $dbh->prepare("SELECT * FROM payments WHERE sale_id = ? ORDER BY created_at DESC");
$stmt->execute([$sale_id]);
$payments = $stmt->fetchAll(PDO::FETCH_ASSOC);

$html = '<div class="child-row-wrapper">
            <div class="child-row-header">Payment Records for Sale ID:' . htmlspecialchars($sale_id) . '</div>
            <table class="table table-bordered table-sm text-center">
                <thead>
                    <tr>
                        <th>Payment Code</th>
                        <th>Sale Code</th>
                        <th>Amount</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>';

$total = 0;

foreach ($payments as $pay) {
    $html .= '<tr>
                <td>PAY-' . htmlspecialchars($pay['pay_id']) . '</td>
                <td>' . number_format($pay['amount'], 2) . ' ৳</td>
                <td>' . date('h:i A d M Y', strtotime($pay['created_at'])) . '</td>
              </tr>';
    $total += $pay['amount'];
}
$html .= '</tbody>
            <tfoot>
                <tr>
                    <td colspan="2">Grand Total:</td>
                    <td colspan="2">' . number_format($total, 2) . ' (৳)</td>
                </tr>
            </tfoot>
        </table>
    </div>';

echo $html;
