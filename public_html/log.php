<?php
session_start();
if (!$_SESSION['user']) {
    header('Location: index.php');
}
require_once "on-call_service.php";
$query_log = "SELECT * FROM mysql.general_log";
$result_log = mysqli_query($link, $query_log);

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="css/bootstrap.min.css" >
    <link rel="stylesheet" href="css/select2.min.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Logic: Заказы</title>
</head>
<body>
<header></header>
<main>
    <div class="towork-title">
        <div class="container-fluid">

            <!-- Панель управления (верхняя) -->
            <?php include 'control_panel_top.php'?>



        </div>
    </div>
</main>
<!-- Футер -->
<?php include 'footer.php'?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/main.js"></script>
<script>
    $("#search-input").on('keyup', function)
</script>
</body>
</html>
