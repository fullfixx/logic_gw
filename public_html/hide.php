<?php
session_start();
if (!$_SESSION['user']) {
    header('Location: index.php');
}
$order_num = $_GET['id'];

$query = "UPDATE `admin_logic_gw`.`orders_list` SET `visible`='2' WHERE `orders_list`.`order_num`=?";
$stmt = mysqli_stmt_init($link);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_bind_param($stmt, "s", $order_num);
    mysqli_stmt_execute($stmt);
} else {
    echo "STMT error";
}
header('Location: index.php');