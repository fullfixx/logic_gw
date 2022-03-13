<?php
session_start();
if (!$_SESSION['user']) {
    header('Location: index.php');
}
require_once "db.php";
$device_type = mysqli_real_escape_string($link, $_POST['device_type']);
$device_brand = mysqli_real_escape_string($link, $_POST['device_brand']);
$device_model = mysqli_real_escape_string($link, $_POST['device_model']);
$device_class_num = mysqli_real_escape_string($link, $_POST['device_class_num']);
$query = "INSERT INTO `admin_logic_gw`.`device_class_list` (`device_class_id`, `device_type`, `device_brand`, `device_model`, `device_class_num`) VALUES (NULL, ?, ?, ?, ?)";
$stmt = mysqli_stmt_init($link);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_bind_param($stmt, "ssss", $device_type, $device_brand, $device_model, $device_class_num);
    mysqli_stmt_execute($stmt);
} else {
    echo "STMT error";
}
header('Location: device_class_cat.php');