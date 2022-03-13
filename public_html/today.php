<?php
session_start();
if (!$_SESSION['user']) {
    header('Location: index.php');
}
require_once "on-call_service.php";
if ($active_rank > 2) {
    header('Location: workarea.php');
}
$today = date('Y-m-d');
$orders_list = mysqli_query($link, "SELECT * FROM `orders_list` 
    INNER JOIN `order_type_list` ON `orders_list`.`order_type`=`order_type_list`.`order_type_id`
    INNER JOIN `device_type_list` ON `orders_list`.`device_type`=`device_type_list`.`device_type_id`
    INNER JOIN `device_brand_list` ON `orders_list`.`device_brand`=`device_brand_list`.`device_brand_id`
    INNER JOIN `device_model_list` ON `orders_list`.`device_model`=`device_model_list`.`device_model_id`
    INNER JOIN `users` ON `orders_list`.`executor`=`users`.`id`
    INNER JOIN `status_list` ON `orders_list`.`status`=`status_list`.`status_id`
    INNER JOIN `statusclarity_list` ON `orders_list`.`statusclarity`=`statusclarity_list`.`statusclarity_id`
    WHERE `data_open` LIKE '%$today%' OR `data_close` LIKE '%$today%'");

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="css/bootstrap.min.css" >
    <link rel="stylesheet" href="css/style.css">
    <title>Logic GW</title>
</head>
<body>
<!-- Модальное окно для уведомлений -->
<?php
if ($count_post > 0 or $count_complete > 0) {
    ?>
    <div id="notice" class="fixmodal">
        <div class="fixmodal-content">
            <span class="close-notice">&times;</span>
            <a href="chat_personal.php">Новых личных сообщений</a>: <?php echo $count_post; ?><br>
            <a href="towork.php?status=2">Завершенных заказов в мастерской</a>: <?php echo $count_complete; ?><br>
        </div>
    </div>
    <?php
}
?>

<!-- Модальное окно для создания нового заказа -->
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
    <div class="container-fluid">

        <!-- Панель управления (верхняя) -->
        <?php include 'control_panel_top.php'?>

        <div class="row">
            <div class="col-4">
                <h3>СЕГОДНЯ</h3>
            </div>
            <div class="col-4">
                
            </div>
            <div class="col-4">
                <input type="text" class="form-control" placeholder="Фильтр по заказам" id="filter-input">
            </div>
        </div>
        <table class="table table-bordered table-striped table-hover">
            <thead class="thead-dark sticky-top">
            <tr>
                <th>#</th>
                <th>Live</th>
                <th>Open</th>
                <th>Close</th>
                <th>Статус</th>
                <th>Уточнение</th>
                <th>Тип</th>
                <th>!</th>
                <th>Type</th>
                <th>Brand</th>
                <th>Model</th>
                <th>Клиент</th>
                <th>Телефон</th>
                <th>Мастер</th>
            </tr>
            </thead>

            <tbody id="filter-list">
            <?php
            for($i=1; $out = mysqli_fetch_assoc($orders_list); $i++)
            {
                ?>
                <tr>
                    <td><a href="update.php?id=<?php echo $out['order_num']; ?>" target="_blank"><div style="text-align: right;"><strong>G<?php echo $out['order_num']; ?></strong></div></a></td>
                    <td><?php echo $out['livesklad_num']; ?></td>
                    <td><?php echo date('d.m', strtotime($out['data_open'])); ?></td>
                    <td><?php echo ($out['status'] == '5') ? date('d.m', strtotime($out['data_close'])) : "-" ?></td>
                    <td class="status-color"><?php echo $out['status_name']; ?></td>
                    <td class="statusclarity-color"><?php echo $out['statusclarity_name']; ?></td>
                    <td><a href="#" data-toggle="modal" data-target="#Modal<?php echo $i; ?>"><?php echo $out['order_type_name']; ?></a>
                        <!-- Modal -->
                        <div class="modal fade" id="Modal<?php echo $i; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLongTitle">Причина обращения в ремонт</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <?php echo $out['comment_order']; ?>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td><?php echo $out['order_priority']; ?></td>
                    <td><?php echo $out['device_type_name']; ?></td>
                    <td><?php echo $out['device_brand_name']; ?></td>
                    <td><?php echo $out['device_model_name']; ?></td>
                    <td><?php echo $out['first_name']; ?></td>
                    <td><?php echo $out['phone_n']; ?></td>
                    <td><?php echo $out['full_name']; ?></td>
                    </tr>
                <?php
            }
            ?>
            </tbody>
        </table>
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