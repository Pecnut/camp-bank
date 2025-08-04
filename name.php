<?php
include('functions.php');
top('names');
$customer_id = intval($_GET['id']);
?>
<h2>Bank card: <?php
                $sql = "SELECT `name` FROM customers WHERE id = " . $customer_id;
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                echo ($row['name']);
                ?></h2>
<table>
    <thead>
        <tr class='thead'>
            <th>Date</th>
            <th>Transaction</th>
            <th>Credit</th>
            <th>Remaining balance</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $balance = 0;
        $sql = "SELECT id, credit, time
                FROM transactions WHERE customer = " . $customer_id . "
                ORDER BY time";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $balance += $row['credit'];
        ?>
            <tr>
                <td class='date'><?php echo date("D d/m H:i", strtotime($row['time'])); ?></td>
                <td><?
                    $sql2 = "SELECT quantity, items.name, items_bought.credit
                            FROM transactions
                            LEFT JOIN items_bought
                            ON transactions.id = items_bought.transaction
                            LEFT JOIN items
                            ON items_bought.item = items.id
                            WHERE transactions.id = " . $row['id'];
                    $result2 = $conn->query($sql2);
                    while ($row2 = $result2->fetch_assoc()) {
                        if (in_array($row2['name'], ["Cash in", "Cash out"])) {
                            echo $row2['name'] . " " . format_money(abs($row2['credit'])) . "<br />";
                        } else {
                            echo $row2['quantity'] . " " . $row2['name'] . "<br />";
                        }
                    }
                    ?></td>
                <td class='money'><?php echo format_money_table($row['credit']); ?></td>
                <td class='money'><?php echo format_money_table($balance); ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<?php
bottom()
?>