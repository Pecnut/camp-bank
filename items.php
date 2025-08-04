<?php
include('functions.php');
top('stock');
if (isset($_GET['sort']) && $_GET['sort'] == "qty"){
    $sort_by = "quantity_sold DESC, items.category, items.name";
} elseif (isset($_GET['sort']) && $_GET['sort'] == "price") {
    $sort_by = "price DESC, items.category, items.name";
} else {
    $sort_by = "items.category, items.name";
}
?>
<h2>Stock</h2>
<table class='names_and_balances'>
    <thead>
        <tr class='thead'>
            <th><a href='?sort=name'>Name</a></th>
            <th>Available?</th>
            <th>Last sold</th>
            <th>Best customer</th>
            <th><a href='?sort=qty'>Qty sold</a></th>
            <th><a href='?sort=price'>Price</a></th>
        </tr>
    </thead>
    <tbody>
        <?php
        $sql = "SELECT items.id, items.name, price, available,
                       item_categories.css_class,
                       sum(quantity) as quantity_sold,
                       max(transactions.time) as latest_sold
            FROM items
            LEFT JOIN items_bought
            ON items.id = items_bought.item
            LEFT JOIN transactions
            ON items_bought.transaction = transactions.id
            LEFT JOIN item_categories
            ON items.category = item_categories.id
            WHERE items.id > 2
            GROUP BY items.id
            ORDER BY " . $sort_by;
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            # Best customer
            $sql2 = "SELECT SUM(quantity) as total_bought, customer, customers.name
                     FROM items_bought
                     LEFT JOIN transactions ON transactions.id = items_bought.transaction
                     LEFT JOIN customers ON customers.id = transactions.customer
                     WHERE items_bought.item = " . $row['id'] . "
                     GROUP BY customer
                     ORDER BY total_bought DESC
                     LIMIT 1";
            $result2 = $conn->query($sql2);
            $best_customer = "";
            while ($row2 = $result2->fetch_assoc()) {
                $best_customer = $row2['name'] . " (" . $row2['total_bought'] . ")";
            }
            ?>
            <tr>
                <td><?php echo "<div class='category_color " . $row['css_class'] . "'></div><a href='item.php?id=" . $row['id'] . "'>" . $row['name'] . "</a>"; ?></td>
                <td><?php echo tick_or_cross($row['available']); ?></td>
                <td><?php echo date("D H:i",strtotime($row['latest_sold'])); ?></td>
                <td><?php echo $best_customer; ?>
                <td class='money'><?php echo $row['quantity_sold']; ?></td>
                <td class='money'><?php echo " £" . number_format($row['price'] / 100, 2); ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<?php
bottom()
?>