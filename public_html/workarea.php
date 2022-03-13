<?php
session_start();
if (!$_SESSION['user']) {
    header('Location: index.php');
}
require_once "db.php";
$entered_user = $_SESSION['user']['id'];
$active_rank = $_SESSION['user']['rank'];
$row_post = mysqli_fetch_array(mysqli_query($link,"SELECT count(*) FROM `chat` WHERE `from_user` = '$entered_user' AND `post_read` = 1"));
$count_post = $row_post[0];
$orders_list = mysqli_query($link, "SELECT * FROM `orders_list` 
    INNER JOIN `order_type_list` ON `orders_list`.`order_type`=`order_type_list`.`order_type_id`
    INNER JOIN `device_type_list` ON `orders_list`.`device_type`=`device_type_list`.`device_type_id`
    INNER JOIN `device_brand_list` ON `orders_list`.`device_brand`=`device_brand_list`.`device_brand_id`
    INNER JOIN `device_model_list` ON `orders_list`.`device_model`=`device_model_list`.`device_model_id`
    INNER JOIN `users` ON `orders_list`.`executor`=`users`.`id`
    INNER JOIN `status_list` ON `orders_list`.`status`=`status_list`.`status_id`
    INNER JOIN `statusclarity_list` ON `orders_list`.`statusclarity`=`statusclarity_list`.`statusclarity_id`
    WHERE `visible`=1 AND `status`!=1 AND `executor` = '$entered_user' 
    ORDER BY `order_type_list`.`order_type_class` ASC, `orders_list`.`order_priority` ASC, `orders_list`.`data_open` ASC
    ");
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
<!-- Модальное окно для уведомлений -->
<?php
if ($count_post > 0) {
    ?>
    <div id="notice" class="fixmodal">
        <div class="fixmodal-content">
            <span class="close-notice">&times;</span>
            <a href="chat_personal.php">Новых личных сообщений</a>: <?php echo $count_post; ?><br>
        </div>
    </div>
    <?php
}
?>

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
<!--                    <img src="img/button_off.png" class="status-filters ml-1" alt="Все заказы" onclick="filterTo('')">-->
<!--                    <img src="img/button_blue.png" class="status-filters ml-1" alt="Заказы в работе" onclick="filterTo('в работе')">-->
<!--                    <img src="img/button_green.png" class="status-filters ml-1" alt="Готовые заказы" onclick="filterTo('готов')">-->
<!--                    <img src="img/button_red.png" class="status-filters ml-1" alt="Приостановленные заказы" onclick="filterTo('приостановлен')">-->
<!--                    <img src="img/button_gray.png" class="status-filters ml-1" alt="Заказы в очереди" onclick="filterTo('в очереди')">-->
                </div>
                <div class="col-6">
                    <input type="text" class="form-control" placeholder="Фильтр по заказам" id="filter-input">
                </div>
            </div>

            <table class="table table-bordered table-striped table-hover">
                <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>Live</th>
                    <th>Open</th>
                    <th>Статус</th>
                    <th>Уточнение</th>
                    <th>Тип</th>
                    <th>Type</th>
                    <th>Brand</th>
                    <th>Model</th>
                    <th>Мастер</th>
                    <th>#</th>
                </tr>
                </thead>

                <tbody id="filter-list">
                <?php
                for($i=1; $out = mysqli_fetch_assoc($orders_list); $i++)
                {
                    ?>
                    <tr>
                        <td><a href="workarea_update.php?id=<?php echo $out['order_num']; ?>"><div style="text-align: right;"><strong>G<?php echo $out['order_num']; ?></strong></div></a></td>
                        <td><?php echo $out['livesklad_num']; ?></td>
                        <td><?php echo date('d.m', strtotime($out['data_open'])); ?></td>
                        <td class="status-color"><?php echo $out['status_name']; ?></td>
                        <td class="statusclarity-color"><?php echo $out['statusclarity_name']; ?></td>
                        <td><a href="#" title="Посмотреть причину обращения" data-toggle="modal" data-target="#Modal<?php echo $i; ?>"><div><?php echo $out['order_type_name']; ?></div></a>
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
                        <td><?php echo $out['device_type_name']; ?></td>
                        <td><?php echo $out['device_brand_name']; ?></td>
                        <td><?php echo $out['device_model_name']; ?></td>
                        <td><?php echo $out['full_name']; ?></td>
                        <td><a href="changeto_towork.php?id=<?php echo $out['order_num']; ?>" title="Взять заказ в работу"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill-rule="evenodd" d="M20.322.75a10.75 10.75 0 00-7.373 2.926l-1.304 1.23A23.743 23.743 0 0010.103 6.5H5.066a1.75 1.75 0 00-1.5.85l-2.71 4.514a.75.75 0 00.49 1.12l4.571.963c.039.049.082.096.129.14L8.04 15.96l1.872 1.994c.044.047.091.09.14.129l.963 4.572a.75.75 0 001.12.488l4.514-2.709a1.75 1.75 0 00.85-1.5v-5.038a23.741 23.741 0 001.596-1.542l1.228-1.304a10.75 10.75 0 002.925-7.374V2.499A1.75 1.75 0 0021.498.75h-1.177zM16 15.112c-.333.248-.672.487-1.018.718l-3.393 2.262.678 3.223 3.612-2.167a.25.25 0 00.121-.214v-3.822zm-10.092-2.7L8.17 9.017c.23-.346.47-.685.717-1.017H5.066a.25.25 0 00-.214.121l-2.167 3.612 3.223.679zm8.07-7.644a9.25 9.25 0 016.344-2.518h1.177a.25.25 0 01.25.25v1.176a9.25 9.25 0 01-2.517 6.346l-1.228 1.303a22.248 22.248 0 01-3.854 3.257l-3.288 2.192-1.743-1.858a.764.764 0 00-.034-.034l-1.859-1.744 2.193-3.29a22.248 22.248 0 013.255-3.851l1.304-1.23zM17.5 8a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zm-11 13c.9-.9.9-2.6 0-3.5-.9-.9-2.6-.9-3.5 0-1.209 1.209-1.445 3.901-1.49 4.743a.232.232 0 00.247.247c.842-.045 3.534-.281 4.743-1.49z"></path></svg></a></td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>

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