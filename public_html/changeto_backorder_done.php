<?php
session_start();
if (!$_SESSION['user']) {
    header('Location: index.php');
}
require_once "db.php";
$gas_id = mysqli_real_escape_string($link, $_GET['gas_id']);

$query = "UPDATE `admin_logic_gw`.`gas` SET `gas_sold` = '1' WHERE `gas`.`gas_id` = ?";
$stmt = mysqli_stmt_init($link);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_bind_param($stmt, "s", $gas_id);
    mysqli_stmt_execute($stmt);
}

header('Location: backorders.php');
