<?php
include('functions.php');
top('reports');
?>
<h2>Total cash in till</h2>
<table class='names_and_balances'>
    <!-- <thead>
        <tr class='thead'>
            <th>Name</th>
            <th>Available?</th>
            <th>Quantity sold</th>
            <th>Price</th>
        </tr>
    </thead> -->
    <tbody>
        <?php
        $sql = "SELECT
                       sum(credit) as total
            FROM items
            LEFT JOIN items_bought
            ON items.id = items_bought.item
            WHERE items.id <= 2";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td>Total cash in till</td>
                <td class='money'><?php echo " £" . number_format($row['total'] / 100, 2); ?></td>
            </tr>
        <?php } ?>
        </tbody>
        </table>

        <h2>Daily sales</h2>
<table class='names_and_balances'>
<tbody>
        <?php
        $sql2 = "SELECT
            DATE(time) as date_, sum(items_bought.credit) as total
            FROM items
            RIGHT JOIN items_bought
            ON items.id = items_bought.item
            RIGHT JOIN transactions
            ON items_bought.transaction = transactions.id
            WHERE items.id > 2
            GROUP BY date_";
        $result = $conn->query($sql2);
        while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo date("D j F",strtotime($row['date_'])) ?></td>
                <td class='money'><?php echo " £" . number_format(-$row['total'] / 100, 2); ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<h2>Top 10 customers</h2>
<table class='names_and_balances'>
    <tbody>
    <?php
        $sql2 = "SELECT
            customers.name, sum(items_bought.credit) as total
            FROM items
            RIGHT JOIN items_bought
            ON items.id = items_bought.item
            RIGHT JOIN transactions
            ON items_bought.transaction = transactions.id
            RIGHT JOIN customers
            ON customers.id = transactions.customer
            WHERE items.id > 2
            GROUP BY name
            ORDER BY total
            LIMIT 50";
        $result = $conn->query($sql2);
        $i = 1;
        while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $i . ". "  . $row['name'];
                if ($i==1){echo " 🥇";}
                if ($i==2){echo " 🥈";}
                if ($i==3){echo " 🥉";} ?></td>
                <td class='money'><?php if (isset($_GET['adam'])){echo " £" . number_format(-$row['total'] / 100, 2);} ?></td>
            </tr>
        <?php
        $i++; } ?>
        </tbody>
        </table>

<?php
bottom()
?>