<?php
include('functions.php');
top('index');
?>
<div id='container'>
    <div id='shop_window'>
        <?
        $sql = "SELECT items.id, items.name, price,
                item_categories.css_class FROM items
                LEFT JOIN item_categories ON items.category = item_categories.id
                WHERE available=1
                ORDER BY item_categories.id, items.name";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) { ?>
            <div class='item <?php echo $row['css_class']; ?>' name="<?php echo $row['name'] ?>" item_id=<?php echo $row['id'] ?> credit=<?php echo -$row['price'] ?>>
                <div class='name'><?php echo $row['name'] ?></div>
                <div class='price'><?php echo format_money($row['price']) ?></div>
            </div>
        <?php } ?>
    </div>
    <div id='transaction'>
        <div id='customer_info' class='text_and_money'>
            <div id='name'></div>
            <div id='current_balance'>
                <div class='label'>Current balance</div>
                <div class='money'>
                    <span class='pound_sign'>£</span><span class='amount'>0.00</span>
                </div>
            </div>
        </div>
        <div id='receipt'>
            <!-- <div class='item'>
                <div class='quantity'>2</div>
                <div class='name'>Diet Coke</div>
                <div class='price'>£1.10</div>
            </div> -->
        </div>
        <div id='total_cost' class='text_and_money'>
            <div class='label'>Total</div>
            <div class='money'>
                <span class='pound_sign'>£</span><span class='amount'>0.00</span>
            </div>
        </div>
        <div id='remaining_balance' class='text_and_money'>
            <div class='label'>This will leave you with</div>
            <div class='money'>
                <span class='pound_sign'>£</span><span class='amount'>0.00
            </div>
        </div>
        <div id='confirm_or_cancel_container'>
            <div id='confirm'>Tap card to confirm</div>
            <div id='cancel'>Cancel</div>
        </div>
    </div>
</div>

<?php
bottom()
?>