<?php
session_start();
if (!$_SESSION['user']) {
    header('Location: index.php');
}
require_once 'db.php';
$gas_id = mysqli_real_escape_string($link, $_POST['gas_id']);
$gas_supplier = mysqli_real_escape_string($link, $_POST['gas_supplier']);
$gas_name = mysqli_real_escape_string($link, $_POST['gas_name']);
$cost = mysqli_real_escape_string($link, $_POST['cost']);
$price = mysqli_real_escape_string($link, $_POST['price']);
$gas_qty = mysqli_real_escape_string($link, $_POST['gas_qty']);
$gas_comment = mysqli_real_escape_string($link, $_POST['gas_comment']);

$query = "UPDATE `admin_logic_gw`.`gas` SET `gas_supplier` = ?, `gas_name` = ?, `cost` = ?, `price` = ?, `gas_qty` = ?, `gas_comment` = ? WHERE `gas`.`gas_id` = ?";
$stmt = mysqli_stmt_init($link);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_bind_param($stmt, "sssssss", $gas_supplier, $gas_name, $cost, $price, $gas_qty, $gas_comment, $gas_id);
    mysqli_stmt_execute($stmt);
} else {
    echo "STMT error";
}

header('Location: backorders.php?gas_supplier='.$gas_supplier);
