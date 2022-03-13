<?php
session_start();
if (!$_SESSION['user']) {
    header('Location: index.php');
}
require_once 'db.php';

$serviceset_id = mysqli_real_escape_string($link, $_GET['ss_id']);
$f_type = mysqli_real_escape_string($link, $_GET['f_type']);
$f_brand = mysqli_real_escape_string($link, $_GET['f_brand']);
$f_model = mysqli_real_escape_string($link, $_GET['f_model']);

mysqli_query($link, "DELETE FROM `serviceset_list` WHERE `serviceset_id`='$serviceset_id'");
header('Location: serviceset_cat.php?f_type='.$f_type.'&f_brand='.$f_brand.'&f_model='.$f_model);