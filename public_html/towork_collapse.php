<?php
session_start();
if (!$_SESSION['user']) {
    header('Location: index.php');
}
require_once "db.php";
$orders_list = mysqli_query($link, "SELECT * FROM `orders_list` 
    INNER JOIN `order_type_list` ON `orders_list`.`order_type`=`order_type_list`.`order_type_id`
    INNER JOIN `device_type_list` ON `orders_list`.`device_type`=`device_type_list`.`device_type_id`
    INNER JOIN `device_brand_list` ON `orders_list`.`device_brand`=`device_brand_list`.`device_brand_id`
    INNER JOIN `device_model_list` ON `orders_list`.`device_model`=`device_model_list`.`device_model_id`
    INNER JOIN `executor_list` ON `orders_list`.`executor`=`executor_list`.`executor_id`
    INNER JOIN `status_list` ON `orders_list`.`status`=`status_list`.`status_id`
    INNER JOIN `statusclarity_list` ON `orders_list`.`statusclarity`=`statusclarity_list`.`statusclarity_id`
    WHERE `visible`=1 ORDER BY `order_type_list`.`order_type_class` ASC, `orders_list`.`order_priority` ASC, `orders_list`.`data_open` ASC");
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="css/bootstrap.min.css" >
    <link rel="stylesheet" href="css/select2.min.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Logic GW</title>
</head>
<body>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Создание нового заказа</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <?php include "form_create.php"; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>

<header></header>
<main>
    <div class="towork-title">
        <div class="container-fluid">

            <!-- Панель управления (верхняя) -->
            <?php include 'control_panel_top.php'?>

            <div class="row">
                <div class="col-3">
                    <h3>АКТИВНЫЕ ЗАКАЗЫ</h3>
                </div>
                <div class="col-3">
                    <img src="img/button_off.png" class="status-filters ml-1" alt="Все заказы" onclick="filterTo('')">
                    <img src="img/button_blue.png" class="status-filters ml-1" alt="Заказы в работе" onclick="filterTo('в работе')">
                    <img src="img/button_green.png" class="status-filters ml-1" alt="Готовые заказы" onclick="filterTo('готов')">
                    <img src="img/button_red.png" class="status-filters ml-1" alt="Приостановленные заказы" onclick="filterTo('приостановлен')">
                    <img src="img/button_gray.png" class="status-filters ml-1" alt="Заказы в очереди" onclick="filterTo('в очереди')">
                </div>
                <div class="col-6">
                    <input type="text" class="form-control" placeholder="Фильтр по заказам" id="filter-input">
                </div>
            </div>

            <!-- Начало аккордеона -->
            <div id="filter-list">
            <div id="accordion">
                <?php
                for($i=1; $out = mysqli_fetch_assoc($orders_list); $i++)
                {
                ?>
                <div class="card">
                    <div class="card-header row" id="heading<?php echo $i; ?>">
                        <div class="col-2">
                            <a class="btn btn-link" data-toggle="collapse" data-target="#collapse<?php echo $i; ?>" aria-expanded="false" aria-controls="collapse<?php echo $i; ?>">G<?php echo $out['order_num']; ?></a>
                        </div>
                        <div class="col-2">
                            <?php echo $out['order_type_name']; ?>
                        </div>
                        <div class="col-2">
                            <?php echo $out['device_brand_name']; ?>
                        </div>
                        <div class="col-2">
                            <?php echo $out['device_model_name']; ?>
                        </div>
                        <div class="col-2">
                            <?php echo $out['status_name']; ?>
                        </div>
                        <div class="col-2">
                            <?php echo $out['first_name']; ?>
                        </div>
                    </div>

                    <div id="collapse<?php echo $i; ?>" class="collapse" aria-labelledby="heading<?php echo $i; ?>" data-parent="#accordion">
                        <div class="card-body">
                            <?php echo $out['comment_order']; ?>
                        </div>
                    </div>
                </div>
                    <?php
                }
                ?>
            </div>
            </div>
            <!-- Конец аккордеона -->

        </div>
</main>
<div class="navbar-fixed-bottom row-fluid">
    <div class="navbar-inner">
        <div class="container-fluid">
            <footer>

                <div class="text-white bg-dark my-3">
                    <div class="card-footer">Рабочая среда "LOGIC GW" для Parts GoWheel.Ru</div>
                    <div class="card-body">
                        <h5 class="card-title"></h5>
                        <p class="card-text">Создание заказ-нарядов и администрирование. Находится в разработке</p>
                    </div>
                </div>

            </footer>
        </div>
    </div>
</div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/select2.min.js"></script>
<script src="js/main.js"></script>
<script>
    $(#search-input).on('keyup', function)
</script>

</body>
</html>