<?php
include('connect_to_db.php');

function top($selected_tab = 'now')
{
    echo "<!DOCTYPE html>";
    echo "<html><head>";
    echo "<title>Camp bank</title>";
    echo "<meta charset='UTF-8'>";
    echo ' <meta name="viewport" content="width=device-width, initial-scale=1">';
    echo "<link rel='stylesheet' href='style.css'>";
    echo '<link rel="stylesheet" href="./fontawesome/all.css">';
    echo '<link href="https://fonts.googleapis.com/css?family=Inter:400,700,400i&display=swap" rel="stylesheet"> ';
    echo '<link rel="apple-touch-icon" sizes="57x57" href="icons/apple-icon-57x57.png" />';
    echo '<link rel="apple-touch-icon" sizes="72x72" href="icons/apple-icon-72x72.png" />';
    echo '<link rel="apple-touch-icon" sizes="114x114" href="icons/apple-icon-114x114.png" />';
    echo '<link rel="apple-touch-icon" sizes="144x144" href="icons/apple-icon-144x144.png" />';
    echo '<script src="jquery-3.7.0.min.js"></script>';
    echo '<script src="script.js"></script>';
    echo "</head><body>";
    echo "<div class='topbar'>";
    echo "<a href='index.php'><div class='topbutton " . tab_class('index', $selected_tab) . "'>" . '<i class="fas fa-store"></i>' . "</div></a>"; # Personal
    echo "<a href='names.php'><div class='topbutton " . tab_class('names', $selected_tab) . "'>" . '<i class="fas fa-users"></i>' . "</div></a>"; # Names
    echo "<a href='items.php'><div class='topbutton " . tab_class('stock', $selected_tab) . "'>" . '<i class="fas fa-cubes"></i>' . "</div></a>"; # Names
    echo "<a href='reports.php'><div class='topbutton " . tab_class('reports', $selected_tab) . "'>" . '<i class="fas fa-clipboard-list"></i>' . "</div></a>"; # Full timetable
    echo "<div class='topbutton clock'>" . date("D H:i") . "</div>";
    echo "</div>";
}

function bottom()
{
    echo "</body></html>";
}

function tab_class($tab, $selected_tab)
{
    if ($tab == $selected_tab) {
        return "selected_tab";
    }
}

function format_money($pence)
{
    if ($pence == 0) {
        return "";
    } elseif ($pence < 100) {
        return $pence . "p";
    } else {
        return "£" . number_format($pence / 100, 2);
    }
}

function format_money_table($pence)
{
    if ($pence < 0) {
        return "<span class='negative_money'>&minus;£" . number_format(-$pence / 100, 2) . "</span>";
    } else {
        return "£" . number_format($pence / 100, 2);
    }
}

function tick_or_cross($bool)
{
    if ($bool) {
        return '<i class="fas fa-check"></i>';
    } else {
        return '<i class="fas fa-times x"></i>';
    }
}

function coins_required($money){
    $coins = [1, 2, 5, 10, 20, 50, 100, 200, 500, 1000, 2000];
    $num_of_each = [0,0,0,0,0,0,0,0,0,0,0,0];
    for($i = sizeof($coins)-1; $i>=0; $i-=1){
        while($money >= $coins[$i]){
            $money-=$coins[$i];
            $num_of_each[$i]+=1;
        }
    }
    return $num_of_each;
}