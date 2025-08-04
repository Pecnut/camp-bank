<?php
include('connect_to_db.php');

$card = $_POST['card'];
$name = $_POST['name'];

// Add transaction to the DB
$sql = "INSERT INTO customers
        (card, name)
        VALUES
        (" . $card . ', "' . $name . '")';
$result = $conn->query($sql);
$transaction_id = $conn->insert_id;

echo $transaction_id;
