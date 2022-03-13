<?php
session_start();
if (!$_SESSION['user']) {
    header('Location: index.php');
}
require_once 'db.php';
$service_num = mysqli_real_escape_string($link, $_POST['service_num']);
$service_name = mysqli_real_escape_string($link, $_POST['service_name']);
$service_desc = mysqli_real_escape_string($link, $_POST['service_desc']);
$service_group = mysqli_real_escape_string($link, $_POST['service_group']);
$device_type = mysqli_real_escape_string($link, $_POST['device_type']);
$device_brand = mysqli_real_escape_string($link, $_POST['device_brand']);
$device_model = mysqli_real_escape_string($link, $_POST['device_model']);
$time_rate = mysqli_real_escape_string($link, $_POST['time_rate']);
$query = "
        INSERT INTO `admin_logic_gw`.`service_list` (
        `service_id`, `service_num`, `service_name`, `service_desc`, `service_group`, `device_type`, `device_brand`, `device_model`, `time_rate`, `service_visible`) 
        VALUES (NULL, '$service_num', '$service_name', '$service_desc', '$service_group', '$device_type', '$device_brand', '$device_model', '$time_rate', 1)";
mysqli_query($link, $query);
header('Location: service_cat.php?f_type='.$device_type.'&f_brand='.$device_brand.'&f_model='.$device_model);
