<?php include('../includes/session.php'); ?>
<?php include('../includes/db.php'); ?>
<?php include('../includes/header.php'); ?>
<button onclick="window.history.back();" style="margin: 10px 0; padding: 8px 12px; font-size: 16px;">&larr; Back</button>
<h2>My Bills</h2>

<table border="1" cellpadding="10" style="width:100%;">
    <tr>
        <th>Bill ID</th>
        <th>Description</th>
        <th>Amount</th>
        <th>Bill Date</th>
        <th>Paid</th>
    </tr>

<?php
$patientId = $_SESSION['user']['patient_id'];

$stmt = $conn->prepare("
    SELECT * FROM billing
    WHERE patient_id = ?
    ORDER BY bill_date DESC
");
$stmt->execute([$patientId]);
$bills = $stmt->fetchAll();

if ($bills) {
    foreach ($bills as $bill) {
        $paidStatus = $bill['paid'] ? 'Yes' : 'No';
        echo "<tr>
                <td>{$bill['bill_id']}</td>
                <td>{$bill['description']}</td>
                <td>{$bill['amount']}</td>
                <td>{$bill['bill_date']}</td>
                <td>{$paidStatus}</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='5'>No billing records found.</td></tr>";
}
?>
</table>

<?php include('../includes/footer.php'); ?>