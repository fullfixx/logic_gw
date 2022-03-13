<?php
session_start();
if (!$_SESSION['user']) {
    header('Location: index.php');
}
require_once 'db.php';
$sparepart_num = mysqli_real_escape_string($link, $_POST['sparepart_num']);
$sparepart_name = mysqli_real_escape_string($link, $_POST['sparepart_name']);
$sparepart_brand = mysqli_real_escape_string($link, $_POST['sparepart_brand']);
$cross_reference = mysqli_real_escape_string($link, $_POST['cross_reference']);

$query = "INSERT INTO `admin_logic_gw`.`spareparts` 
(`sparepart_id`, `sparepart_num`, `sparepart_name`, `sparepart_brand`, `stock_min`, `stock_max`, `sparepart_visible`, `cross_reference`) VALUES 
(NULL, '$sparepart_num', '$sparepart_name', '$sparepart_brand', NULL, NULL, '1', '$cross_reference')";
mysqli_query($link, $query);

$hard_filter = mysqli_fetch_assoc(mysqli_query($link, "SELECT * FROM `spareparts` WHERE `sparepart_num`='$sparepart_num'"));
header('Location: spareparts_list.php?hard_filter='.$hard_filter['sparepart_num']);
