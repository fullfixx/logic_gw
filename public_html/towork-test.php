<?php
require_once "includes/on-call_service.php";
if ($active_rank > 2) {
    header('Location: workarea.php');
}

$filter_status = mysqli_real_escape_string($link, $_GET['status']);
if (count($_GET) > 0) {
    $orders_list = mysqli_query($link, "SELECT * FROM `orders_list` 
    INNER JOIN `order_type_list` ON `orders_list`.`order_type`=`order_type_list`.`order_type_id`
    INNER JOIN `device_type_list` ON `orders_list`.`device_type`=`device_type_list`.`device_type_id`
    INNER JOIN `device_brand_list` ON `orders_list`.`device_brand`=`device_brand_list`.`device_brand_id`
    INNER JOIN `device_model_list` ON `orders_list`.`device_model`=`device_model_list`.`device_model_id`
    INNER JOIN `users` ON `orders_list`.`executor`=`users`.`id`
    INNER JOIN `status_list` ON `orders_list`.`status`=`status_list`.`status_id`
    INNER JOIN `statusclarity_list` ON `orders_list`.`statusclarity`=`statusclarity_list`.`statusclarity_id`
    WHERE `visible`=1 AND `status` = '$filter_status' ORDER BY `orders_list`.`order_priority` ASC, `orders_list`.`data_open` ASC");
} else {
    $orders_list = mysqli_query($link, "SELECT * FROM `orders_list` 
    INNER JOIN `order_type_list` ON `orders_list`.`order_type`=`order_type_list`.`order_type_id`
    INNER JOIN `device_type_list` ON `orders_list`.`device_type`=`device_type_list`.`device_type_id`
    INNER JOIN `device_brand_list` ON `orders_list`.`device_brand`=`device_brand_list`.`device_brand_id`
    INNER JOIN `device_model_list` ON `orders_list`.`device_model`=`device_model_list`.`device_model_id`
    INNER JOIN `users` ON `orders_list`.`executor`=`users`.`id`
    INNER JOIN `status_list` ON `orders_list`.`status`=`status_list`.`status_id`
    INNER JOIN `statusclarity_list` ON `orders_list`.`statusclarity`=`statusclarity_list`.`statusclarity_id`
    WHERE `visible`=1 ORDER BY `orders_list`.`order_priority` ASC, `orders_list`.`data_open` ASC");
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="css/bootstrap.min.css" >
    <link rel="stylesheet" href="css/select2.min.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Logic: Заказ-наряды</title>
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
<?php include "modal_new_order_create.php"; ?>

<header></header>
<main>
    <div class="towork-title">
    <div class="container-fluid">

        <!-- Панель управления (верхняя) -->
        <?php include 'control_panel_top.php'?>

        <div class="row">
            <div class="col-4 text-left">
                <a href="towork.php" class="text-dark"><h3 class="mb-1 mt-2">ЗАКАЗ-НАРЯДЫ</h3></a>
            </div>
            <div class="col-4">
    <!--                <a class="btn btn-secondary my-1" role="button" href="towork.php" title="Все заказы">&#8634;</a>-->
    <!--                <a class="btn btn-primary my-1" role="button" href="towork.php?status=2" title="Заказы в работе">&#9680;</a>-->
    <!--                <a class="btn btn-success my-1" role="button" href="towork.php?status=1" title="Готовые заказы">&#9680;</a>-->
    <!--                <a class="btn btn-danger my-1" role="button" href="towork.php?status=4" title="Приостановленные заказы">&#9680;</a>-->
    <!--                <a class="btn btn-outline-dark my-1" role="button" href="towork.php?status=3" title="Заказы в очереди">&#9680;</a>-->
            </div>
            <div class="col-4">
                <input type="text" class="form-control my-1" placeholder="Фильтр по заказам" id="filter-input">
            </div>
        </div>

        <table class="table table-bordered table-striped table-hover">
            <thead class="thead-dark sticky-top">
            <tr>
                <th class="py-0 px-0 align-middle text-center"><button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#exampleModal">&#10010;</button></th>
                <th>#</th>
                <th>Live</th>
                <th>Open</th>
<!--                <th>Close</th>-->
                <th class="py-0 px-0 align-middle text-center">
                    <a class="btn btn-sm btn-primary my-1" role="button" href="towork.php?status=2" title="Заказы в работе">&#9680;</a>
                    <a class="btn btn-sm btn-success my-1" role="button" href="towork.php?status=1" title="Готовые заказы">&#9680;</a>
                    <a class="btn btn-sm btn-danger my-1" role="button" href="towork.php?status=4" title="Приостановленные заказы">&#9680;</a>
                    <a class="btn btn-sm btn-light my-1" role="button" href="towork.php?status=3" title="Заказы в очереди">&#9680;</a></th>
                <th>Уточнение</th>
                <th>Тип</th>
                <th>!</th>
                <th>Type</th>
                <th>Brand</th>
                <th>Model</th>
                <th>Клиент</th>
                <th>Телефон</th>
                <th>Мастер</th>
                <th>#</th>
                <th>#</th>
            </tr>
            </thead>

            <tbody id="filter-list">
            <?php
            for($i=1; $out = mysqli_fetch_assoc($orders_list); $i++)
            {
                ?>
                <tr>
                    <td><small><?php echo $i; ?></small></td>
                    <td><a href="update.php?id=<?php echo $out['order_num']; ?>" target="_blank"><div style="text-align: right;"><strong>G<?php echo $out['order_num']; ?></strong></div></a></td>
                    <td><?php echo $out['livesklad_num']; ?></td>
                    <td><?php echo date('d.m', strtotime($out['data_open'])); ?></td>
<!--                    <td>--><?php
//                        if ($out['data_close'] == '0000-00-00 00:00:00') {
//                            echo "нет";
//                        } else {
//                        echo date('d.m.Y', strtotime($out['data_close']));
//                        }?>
<!--                        </td>-->
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
                    <td><a href="changeto_towork.php?id=<?php echo $out['order_num']; ?>" title="Взять заказ в работу"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" style="fill: #005cbf"><path fill-rule="evenodd" d="M11.31 2.525a9.648 9.648 0 011.38 0c.055.004.135.05.162.16l.351 1.45c.153.628.626 1.08 1.173 1.278.205.074.405.157.6.249a1.832 1.832 0 001.733-.074l1.275-.776c.097-.06.186-.036.228 0 .348.302.674.628.976.976.036.042.06.13 0 .228l-.776 1.274a1.832 1.832 0 00-.074 1.734c.092.195.175.395.248.6.198.547.652 1.02 1.278 1.172l1.45.353c.111.026.157.106.161.161a9.653 9.653 0 010 1.38c-.004.055-.05.135-.16.162l-1.45.351a1.833 1.833 0 00-1.278 1.173 6.926 6.926 0 01-.25.6 1.832 1.832 0 00.075 1.733l.776 1.275c.06.097.036.186 0 .228a9.555 9.555 0 01-.976.976c-.042.036-.13.06-.228 0l-1.275-.776a1.832 1.832 0 00-1.733-.074 6.926 6.926 0 01-.6.248 1.833 1.833 0 00-1.172 1.278l-.353 1.45c-.026.111-.106.157-.161.161a9.653 9.653 0 01-1.38 0c-.055-.004-.135-.05-.162-.16l-.351-1.45a1.833 1.833 0 00-1.173-1.278 6.928 6.928 0 01-.6-.25 1.832 1.832 0 00-1.734.075l-1.274.776c-.097.06-.186.036-.228 0a9.56 9.56 0 01-.976-.976c-.036-.042-.06-.13 0-.228l.776-1.275a1.832 1.832 0 00.074-1.733 6.948 6.948 0 01-.249-.6 1.833 1.833 0 00-1.277-1.172l-1.45-.353c-.111-.026-.157-.106-.161-.161a9.648 9.648 0 010-1.38c.004-.055.05-.135.16-.162l1.45-.351a1.833 1.833 0 001.278-1.173 6.95 6.95 0 01.249-.6 1.832 1.832 0 00-.074-1.734l-.776-1.274c-.06-.097-.036-.186 0-.228.302-.348.628-.674.976-.976.042-.036.13-.06.228 0l1.274.776a1.832 1.832 0 001.734.074 6.95 6.95 0 01.6-.249 1.833 1.833 0 001.172-1.277l.353-1.45c.026-.111.106-.157.161-.161zM12 1c-.268 0-.534.01-.797.028-.763.055-1.345.617-1.512 1.304l-.352 1.45c-.02.078-.09.172-.225.22a8.45 8.45 0 00-.728.303c-.13.06-.246.044-.315.002l-1.274-.776c-.604-.368-1.412-.354-1.99.147-.403.348-.78.726-1.129 1.128-.5.579-.515 1.387-.147 1.99l.776 1.275c.042.069.059.185-.002.315a8.45 8.45 0 00-.302.728c-.05.135-.143.206-.221.225l-1.45.352c-.687.167-1.249.749-1.304 1.512a11.149 11.149 0 000 1.594c.055.763.617 1.345 1.304 1.512l1.45.352c.078.02.172.09.22.225.09.248.191.491.303.729.06.129.044.245.002.314l-.776 1.274c-.368.604-.354 1.412.147 1.99.348.403.726.78 1.128 1.129.579.5 1.387.515 1.99.147l1.275-.776c.069-.042.185-.059.315.002.237.112.48.213.728.302.135.05.206.143.225.221l.352 1.45c.167.687.749 1.249 1.512 1.303a11.125 11.125 0 001.594 0c.763-.054 1.345-.616 1.512-1.303l.352-1.45c.02-.078.09-.172.225-.22.248-.09.491-.191.729-.303.129-.06.245-.044.314-.002l1.274.776c.604.368 1.412.354 1.99-.147.403-.348.78-.726 1.129-1.128.5-.579.515-1.387.147-1.99l-.776-1.275c-.042-.069-.059-.185.002-.315.112-.237.213-.48.302-.728.05-.135.143-.206.221-.225l1.45-.352c.687-.167 1.249-.749 1.303-1.512a11.125 11.125 0 000-1.594c-.054-.763-.616-1.345-1.303-1.512l-1.45-.352c-.078-.02-.172-.09-.22-.225a8.469 8.469 0 00-.303-.728c-.06-.13-.044-.246-.002-.315l.776-1.274c.368-.604.354-1.412-.147-1.99-.348-.403-.726-.78-1.128-1.129-.579-.5-1.387-.515-1.99-.147l-1.275.776c-.069.042-.185.059-.315-.002a8.465 8.465 0 00-.728-.302c-.135-.05-.206-.143-.225-.221l-.352-1.45c-.167-.687-.749-1.249-1.512-1.304A11.149 11.149 0 0012 1zm2.5 11a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0zm1.5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg></a></td>
                    <td><a href="hide.php?id=<?php echo $out['order_num']; ?>" title="Убрать заказ в архив"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M9.036 7.976a.75.75 0 00-1.06 1.06L10.939 12l-2.963 2.963a.75.75 0 101.06 1.06L12 13.06l2.963 2.964a.75.75 0 001.061-1.06L13.061 12l2.963-2.964a.75.75 0 10-1.06-1.06L12 10.939 9.036 7.976z"></path><path fill-rule="evenodd" d="M12 1C5.925 1 1 5.925 1 12s4.925 11 11 11 11-4.925 11-11S18.075 1 12 1zM2.5 12a9.5 9.5 0 1119 0 9.5 9.5 0 01-19 0z"></path></svg></a></td>
<!--                    <img class="img-fluid mx-auto d-block" src="img/visible.png">-->
                </tr>
            <?php
            }
             ?>
            </tbody>
        </table>

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
