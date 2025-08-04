<?php
include('functions.php');
top('names');
if (isset($_GET['sort']) && $_GET['sort'] == "balance") {
    $sort_by = "balance DESC, name";
} else {
    $sort_by = "name";
}
?>
<h2>Bank cards</h2>
<table class='names_and_balances'>
    <thead>
        <tr class='thead'>
            <th>ID</th>
            <th><a href='?sort=name'>Name</a></th>
            <th>Shop</th>
            <th>Bank card</th>
            <th><a href='?sort=balance'>Balance</a></th>
        </tr>
    </thead>
    <tbody>
        <?php
        $coins = [1, 2, 5, 10, 20, 50, 100, 200, 500, 1000, 2000];
        $coins_names = ["1p", "2p", "5p", "10p", "20p", "50p", "£1", "£2", "£5", "£10", "£20"];
        $coins_required = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        $sql = "SELECT customers.id, name, sum(credit) as balance
            FROM customers
            LEFT JOIN transactions
            ON customers.id = transactions.customer
            GROUP BY customers.id
            ORDER BY " . $sort_by;
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo "<a href='index.php?id=" . $row['id'] . "'>Shop</a>" ?></td>
                <td><?php echo "<a href='name.php?id=" . $row['id'] . "'>Bank card</a>" ?></td>
                <td class='money'><?php if (isset($_GET['adam'])) {
                                        echo "" . format_money_table($row['balance']);
                                    } ?></td>
                <?php if (!in_array($row['name'], ["FLOAT"])) {
                    $x = coins_required($row['balance']);
                    for ($i = 0; $i < sizeof($coins_required); $i++) {
                        $coins_required[$i] += $x[$i];
                    }
                }
                ?>
            </tr>
        <?php } ?>
    </tbody>
</table>

<h2>Coins and notes required to empty bank</h2>
<table class='names_and_balances'>
    <tbody>
        <?php
        for ($i = sizeof($coins) - 1; $i >= 0; $i -= 1) {
            echo "<tr><td>$coins_names[$i]</td><td>$coins_required[$i]</td></tr>";
        }
        ?>
    </tbody>
</table>

<?php
bottom()
?>