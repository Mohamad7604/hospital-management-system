 <?php include('../includes/db.php'); ?>
 <?php include('../includes/footer.php');?>
<h2>Manage Billing</h2>
<form method="post">
    <input type="number" name="patient_id" placeholder="Patient ID" required><br>
    <input type="text" name="description" placeholder="Service Description"><br>
    <input type="number" step="0.01" name="amount" placeholder="Amount"><br>
    <select name="paid">
        <option value="1">Paid</option>
        <option value="0">Unpaid</option>
    </select><br>
    <button type="submit" name="bill">Add Bill</button>
</form>

<?php
if (isset($_POST['bill'])) {
    $stmt = $conn->prepare("INSERT INTO billing (patient_id, description, amount, paid)
                            VALUES (?, ?, ?, ?)");
    $stmt->execute([
        $_POST['patient_id'], $_POST['description'],
        $_POST['amount'], $_POST['paid']
    ]);
    echo "Bill added!";
}
?>