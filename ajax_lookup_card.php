<?php
include('connect_to_db.php');

$card_id = intval($_POST['card']);
$sql = "SELECT customers.id as id, customers.name,
            SUM(transactions.credit) as balance
        FROM customers
        LEFT JOIN transactions ON customers.id = transactions.customer
        WHERE customers.card = '$card_id'
        GROUP BY customers.id";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    if (is_null($row['balance'])) {
        $row['balance'] = 0;
    }
    echo $row['id'] . "," . $row['name'] . "," . $row['balance'];
}
