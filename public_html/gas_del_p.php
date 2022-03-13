<?php
session_start();
if (!$_SESSION['user']) {
    header('Location: index.php');
}
require_once 'db.php';

$order_num = mysqli_real_escape_string($link, $_POST['order_num']);
$transaction = mysqli_real_escape_string($link, $_POST['transaction']);
$gas_qty = mysqli_real_escape_string($link, $_POST['gas_qty']);
$search_sp = mysqli_real_escape_string($link, $_POST['search_sp']);
$search_ss = mysqli_real_escape_string($link, $_POST['search_ss']);
$gas_id = mysqli_real_escape_string($link, $_POST['gas_id']);

mysqli_query($link, "UPDATE `admin_logic_gw`.`spareparts_stock` SET `qty_now` = `qty_now`+'$gas_qty' WHERE `spareparts_stock`.`transaction_id` = '$transaction';");
mysqli_query($link, "DELETE FROM `gas` WHERE `gas_id`='$gas_id'");
header('Location: update.php?id='.$order_num.'&search_sp='.$search_sp.'&search_ss='.$search_ss);