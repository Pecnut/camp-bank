<?php
include('functions.php');

if (isset($_POST['submit'])) {
    $id = intval($_POST['id']);
    $sql = 'UPDATE items SET
    name = "' . $_POST['name'] . '",
    available = ' . intval($_POST['available']) . ',
    price = ' . intval($_POST['price'] * 100) . '
    WHERE id = ' . $id;
    // echo $sql;
    $result = $conn->query($sql);

    header("Location: items.php");
}

top('stock');

$sql = "SELECT items.id, name, price, available
            FROM items
            WHERE items.id = " . intval($_GET['id']);
$result = $conn->query($sql);
$row = $result->fetch_assoc();  ?>

<h2>Edit item</h2>
<form method='post'>
    <table class='names_and_balances'>
        <thead>
            <tr class='thead'>
                <th colspan=2>Edit item</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Name</td>
                <td><input name='name' value="<?php echo $row['name']; ?>" />
                    <input type=hidden name='id' value="<?php echo $row['id']; ?>" />
                </td>
            </tr>
            <tr>
                <td>Available?</td>
                <td><input name='available' type=checkbox value=1 <?php if ($row['available']) {
                                                                        echo "checked";
                                                                    }; ?> /></td>
            </tr>
            <tr>
                <td>Price</td>
                <td>£ <input name='price' value="<?php echo number_format($row['price'] / 100, 2); ?>" /></td>
            </tr>
            <tr>
                <td></td>
                <td><input type=submit name='submit' value="Save changes" /></td>
            </tr>
        </tbody>
    </table>
</form>

<?php
bottom()
?>