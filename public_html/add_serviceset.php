<?php
session_start();
if (!$_SESSION['user']) {
    header('Location: index.php');
}
require_once 'db.php';
$serviceset_num = mysqli_real_escape_string($link, $_POST['serviceset_num']);
$serviceset_name = mysqli_real_escape_string($link, $_POST['serviceset_name']);
$device_type = mysqli_real_escape_string($link, $_POST['device_type']);
$device_brand = mysqli_real_escape_string($link, $_POST['device_brand']);
$device_model = mysqli_real_escape_string($link, $_POST['device_model']);
$serviceset_element = mysqli_real_escape_string($link, $_POST['serviceset_element']);
$serviceset_element_qty = mysqli_real_escape_string($link, $_POST['serviceset_element_qty']);
$f_type = mysqli_real_escape_string($link, $_POST['f_type']);
$f_brand = mysqli_real_escape_string($link, $_POST['f_brand']);
$f_model = mysqli_real_escape_string($link, $_POST['f_model']);

$query = "INSERT INTO `admin_logic_gw`.`serviceset_list` 
(`serviceset_id`, `serviceset_num`, `serviceset_name`, `device_type`, `device_brand`, `device_model`, `serviceset_element`, `serviceset_element_qty`, `serviceset_visible`) VALUES 
(NULL, NULL, '$serviceset_name', '$device_type', '$device_brand', '$device_model', '$serviceset_element', '$serviceset_element_qty', '1')";
mysqli_query($link, $query);
header('Location: serviceset_cat.php?f_type='.$f_type.'&f_brand='.$f_brand.'&f_model='.$f_model);