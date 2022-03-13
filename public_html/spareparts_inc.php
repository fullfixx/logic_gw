<?php
session_start();
if (!$_SESSION['user']) {
    header('Location: index.php');
}
require_once 'db.php';

$supplier = mysqli_real_escape_string($link, $_POST['supplier']);
$invoice_num = mysqli_real_escape_string($link, $_POST['invoice_num']);
$invoice_date = mysqli_real_escape_string($link, $_POST['invoice_date']);
$sparepart = mysqli_real_escape_string($link, $_POST['sparepart']);
$cost = mysqli_real_escape_string($link, $_POST['cost']);
$price = mysqli_real_escape_string($link, $_POST['price']);
$qty_inc = mysqli_real_escape_string($link, $_POST['qty_inc']);
$user_id = $_SESSION['user']['id'];

$query = "INSERT INTO `admin_logic_gw`.`spareparts_stock` 
(`transaction_id`, `supplier`, `invoice_num`, `invoice_date`, `sparepart`, `cost`, `price`, `qty_inc`, `qty_now`, `transaction_date`, `transaction_creator`) VALUES 
(NULL, ?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIME, ?)";
$stmt = mysqli_stmt_init($link);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_bind_param($stmt, "sssssssss", $supplier, $invoice_num, $invoice_date, $sparepart, $cost, $price, $qty_inc, $qty_inc, $user_id);
    mysqli_stmt_execute($stmt);
} else {
    echo "STMT error";
}
header('Location: spareparts_stock.php');