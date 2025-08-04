function update_displayed_total() {
    if (total_credit <= 0) {
        $("#total_cost .money .amount").html((-total_credit / 100).toFixed(2));
    } else {
        $("#total_cost .money .amount").html((total_credit / 100).toFixed(2) + "<span class='total_cr'>CR</span>");
    }
}

function update_displayed_money_left() {
    money_left = current_balance + total_credit;
    if (money_left >= 0) {
        $("#remaining_balance .money .amount").html((money_left / 100).toFixed(2));
        $("#remaining_balance .money .amount").removeClass("negative_money");
    } else {
        $("#remaining_balance .money .amount").html((-money_left / 100).toFixed(2) + "<span class='total_cr'>DR</span>");
        $("#remaining_balance .money .amount").addClass("negative_money");
    }
}

function update_displayed_current_balance() {
    if (current_balance >= 0) {
        $("#current_balance .money .amount").html((current_balance / 100).toFixed(2));
        $("#current_balance .money .amount").removeClass("negative_money");
    } else {
        $("#current_balance .money .amount").html((-current_balance / 100).toFixed(2) + "<span class='total_cr'>DR</span>");
        $("#current_balance .money .amount").addClass("negative_money");
    }
}

function update_displayed_receipt() {
    $("#receipt").html("")
    receipt.forEach(add_line_to_displayed_receipt);
}

function add_line_to_displayed_receipt(item) {
    if (item[3] <= 0) {
        price = (-item[3] / 100).toFixed(2);
    } else {
        price = (item[3] / 100).toFixed(2) + "&nbsp;CR";
    }
    if (item[0] <= 2) {
        item_for_display = "";
    } else {
        item_for_display = item[1];
    }
    item_html = ('<div class="item" qty=' + item[1] + ' price=' + item[3] + ' item_id=' + item[0] + '>'
        + '<div class="quantity">' + item_for_display + '</div>'
        + '<div class="name">' + item[2] + '</div>'
        + '<div class="price">' + price + "</div></div>");
    $("#receipt").html($("#receipt").html() + item_html);
}

function update_customer_name(customer_name, customer_id = 0) {
    if (customer_name != "") {
        $("#customer_info #name").html(
            "<a href='name.php?id="
            + customer_id + "'>"
            + customer_name + "</a>");
    } else {
        $("#customer_info #name").html("");
    }
}

function get_current_balance() {

}

function add_item(div) {
    credit = parseInt(div.attr("credit")); // Normally negative price
    name_ = div.attr("name");
    item_id = div.attr("item_id");
    total_credit += credit;
    update_displayed_total();
    update_displayed_money_left();
    already_on_receipt = false;
    receipt.forEach(function (i) {
        if (i[0] == item_id) {
            already_on_receipt = true;
            i[1] += 1;
            i[3] += credit;
        }
    })
    if (!already_on_receipt) {
        receipt.push([item_id, 1, name_, credit]);
    }
    update_displayed_receipt();
}

function remove_item(div) {
    qty = parseInt(div.attr('qty'));
    item_id = parseInt(div.attr('item_id'));
    credit = parseInt(div.attr('price'));
    unit_credit = credit / qty;
    total_credit -= unit_credit;
    update_displayed_total();
    update_displayed_money_left();
    new_receipt = [];
    if (qty > 1) {
        receipt.forEach(function (i) {
            if (i[0] == item_id) {
                i[1] -= 1;
                i[3] -= unit_credit;
            }
        })
    } else {
        receipt.forEach(function (i) {
            if (i[0] != item_id) {
                new_receipt.push(i);
            }
        })
        receipt = new_receipt;
    }
    update_displayed_receipt();
}

function reset_customer() {
    total_credit = 0;
    current_balance = 0;
    customer_id = 0;
    receipt = [];
    update_customer_name("");
    update_displayed_total();
    update_displayed_money_left();
    update_displayed_current_balance();
    update_displayed_receipt();
}

function keycard_beeped(card) {
    customer_data = find_customer_from_card(card);
    if (customer_data !== null) {
        if (parseInt(customer_data[0]) == customer_id && total_credit != 0) {
            // This is the second time the card is beeped, so make purchase.
            make_purchase()
            reset_customer();
        } else {
            customer_id = parseInt(customer_data[0]);
            customer_name = customer_data[1];
            current_balance = parseInt(customer_data[2]);
            update_customer_name(customer_name, customer_id);
            update_displayed_current_balance();
            update_displayed_money_left();
        }
    } else {
        // New user?
        reset_customer();
        customer_name = prompt("Card not recognised. To register this as a new card, enter the name of the cardholder:");
        if (customer_name) {
            customer_id = create_customer(card, customer_name);
            update_customer_name(customer_name, customer_id);
            update_displayed_current_balance();
            update_displayed_money_left();
        }
    }
}

function customer_login_through_get(id) {
    customer_data = find_customer_from_id(id);
    if (customer_data !== null) {
        customer_id = parseInt(customer_data[0]);
        customer_name = customer_data[1];
        current_balance = parseInt(customer_data[2]);
        update_customer_name(customer_name, customer_id);
        update_displayed_current_balance();
        update_displayed_money_left();
    }
}

// function verify_id(id) {
//     verify_id_ajax(id).then(function (o) {
//         return o;
//     });
// }

function create_customer(card, name) {
    $.post({
        url: 'ajax_create_customer.php',
        data: {
            card: card,
            name: name
        },
        async: false,
        success: function (response) {
            id = response;
        }
    });
    return id;
}

function find_customer_from_card(card) {
    $.post({
        url: 'ajax_lookup_card.php',
        data: "card=" + card,
        async: false,
        success: function (response) {
            if (response.length > 0) {
                o = response.split(",")
            } else {
                o = null
            }
        }
    });
    return o
}

function find_customer_from_id(id) {
    $.post({
        url: 'ajax_lookup_id.php',
        data: "id=" + id,
        async: false,
        success: function (response) {
            if (response.length > 0) {
                o = response.split(",")
            } else {
                o = null
            }
        }
    });
    return o
}

function make_purchase() {
    $.post({
        url: 'ajax_make_purchase.php',
        data: {
            customer_id: customer_id,
            receipt: receipt,
            total_credit: total_credit
        },
        async: false,
        success: function (response) {
            // alert(response);
        }
    });
}

function deal_with_cash(in_out) {
    if (in_out == 'in') {
        message = "CASH IN: Enter cash amount being deposited into the bank, in £";
        item_id = 1;
        name_ = "CASH IN";
        minus = 1;
    } else {
        message = "CASH OUT: Enter cash amount being withdrawn from the bank, in £"
        item_id = 2;
        name_ = "CASH OUT";
        minus = -1;
    }
    cash_amount = prompt(message);
    if (cash_amount > 0) {
        credit = 100 * minus * cash_amount;
        already_on_receipt = false;
        receipt.forEach(function (i) {
            if (i[0] == item_id) {
                already_on_receipt = true;
                i[3] += credit;
            }
        })
        if (!already_on_receipt) {
            receipt.push([item_id, 1, name_, credit]);
        }
        total_credit += credit;
        update_displayed_receipt();
        update_displayed_total();
        update_displayed_money_left();
    }
}

$(document).keydown(function (event) {
    const currentTime = Date.now();
    const key = event.key;
    if (key == 'Enter' && key_buffer.length > 0) {
        // Submit buffer
        combined_buffer = key_buffer.join('');
        keycard_beeped(combined_buffer);
    } else {
        // Add to buffer
        if (currentTime - time_last_key_pressed > keystrokeDelay) {
            key_buffer = [key];
        } else {
            key_buffer.push(key)
        }
        time_last_key_pressed = currentTime;
    }
});

var serverTime = new Date();

function updateTime() {
    serverTime = new Date(serverTime.getTime() + 1000);
    $('.clock').html(serverTime.toLocaleString('en-gb', {
        timeZone: 'Europe/London',
        weekday: 'short',
        hour: '2-digit',
        minute: '2-digit'
    }));
}

$(function () {
    updateTime();
    setInterval(updateTime, 1000);
})

$(document).ready(function () {
    total_credit = 0;
    current_balance = 0;
    customer_id = 0;
    receipt = [];
    key_buffer = [];
    time_last_key_pressed = 0;
    keystrokeDelay = 200; //milliseconds

    // Check if ID has been passed to GET to log in customer without a card
    let searchParams = new URLSearchParams(window.location.search)
    if (searchParams.has('id')) {
        customer_id = searchParams.get('id')
        customer_login_through_get(customer_id);
    }

    $("#shop_window div.item").on("click", function () {
        if ($(this).attr('name') == "Cash in") {
            deal_with_cash('in');
        } else if ($(this).attr('name') == "Cash out") {
            deal_with_cash('out');
        } else {
            add_item($(this));
        }
    });

    $("div#receipt").on("click", ".item", function () {
        remove_item($(this));
    })

    $("#cancel").on("click", function () {
        reset_customer();
    })

    $("#confirm").on("click", function () {
        if (customer_id > 0) {
            make_purchase();
            reset_customer();
        }
    })
});