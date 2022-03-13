<?php
session_start();
if (!$_SESSION['user']) {
    header('Location: index.php');
}
require_once 'db.php';

$service_id = mysqli_real_escape_string($link, $_POST['service_id']);
$f_type = mysqli_real_escape_string($link, $_POST['f_type']);
$f_brand = mysqli_real_escape_string($link, $_POST['f_brand']);
$f_model = mysqli_real_escape_string($link, $_POST['f_model']);
$time_rate = mysqli_real_escape_string($link, $_POST['time_rate']);
$time_rate = str_replace(',','.',$time_rate);
$time_rate = floatval($time_rate);

mysqli_query($link, "UPDATE `admin_logic_gw`.`service_list` SET `time_rate` = '$time_rate' WHERE `service_list`.`service_id` = '$service_id'");

if (empty($f_type)) {
    header('Location: service_cat.php');
} else {
    header('Location: service_cat.php?f_type='.$f_type.'&f_brand='.$f_brand.'&f_model='.$f_model);
}
