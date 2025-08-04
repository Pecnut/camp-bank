<?php
include('connect_to_db.php');

$receipt = $_POST['receipt'];
$customer_id = intval($_POST['customer_id']);
$total_credit = intval($_POST['total_credit']);

// Add transaction to the DB
$sql = "INSERT INTO transactions
        (customer, credit)
        VALUES
        (" . $customer_id . "," . $total_credit . ")";
$result = $conn->query($sql);
$transaction_id = $conn->insert_id;

foreach ($receipt as $item) {
    $item_id = $item[0];
    $qty = $item[1];
    $credit = $item[3];

    $sql = "INSERT INTO items_bought
        (`transaction`, quantity, item, credit)
        VALUES
        (" . $transaction_id . "," . $qty . "," . $item_id . "," . $credit . ")";
    echo $sql;
    $result = $conn->query($sql);
}
