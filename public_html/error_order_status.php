<?php
session_start();
if (!$_SESSION['user']) {
    header('Location: index.php');
}
require_once "on-call_service.php";
$order_num = mysqli_real_escape_string($link, $_GET['id']);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="css/bootstrap.min.css" >
    <link rel="stylesheet" href="css/select2.min.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Ошибка закрытия G<?php echo $order_num; ?></title>
</head>
<body>

<header></header>
<main>
    <div class="archive-bg">
        <div class="container-fluid">

            <!-- Панель управления (верхняя) -->
            <?php include 'control_panel_top.php'?>

<h4>Перед выдачей заказа сперва переведите его в статус <mark>Готов</mark>. Перейти к заказу <a href="update.php?id=<?php echo $order_num; ?>">G<?php echo $order_num; ?></a></h4>

        </div>
    </div>
</main>
<!-- Футер -->
<?php include 'footer.php'?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/select2.min.js"></script>
<script src="js/js.cookie-2.2.1.min.js"></script>
<script src="js/main.js"></script>
</body>
</html>