<?php
session_start();
if (!$_SESSION['user']) {
    header('Location: index.php');
}

require_once "db.php";
$entered_user = $_SESSION['user']['id'];
$active_rank = $_SESSION['user']['rank'];
if ($active_rank > 2) {
    header('Location: workarea.php');
}
$orders_list = mysqli_query($link, "SELECT * FROM `orders_list` 
    INNER JOIN `order_type_list` ON `orders_list`.`order_type`=`order_type_list`.`order_type_id`
    INNER JOIN `device_type_list` ON `orders_list`.`device_type`=`device_type_list`.`device_type_id`
    INNER JOIN `device_brand_list` ON `orders_list`.`device_brand`=`device_brand_list`.`device_brand_id`
    INNER JOIN `device_model_list` ON `orders_list`.`device_model`=`device_model_list`.`device_model_id`
    INNER JOIN `users` ON `orders_list`.`executor`=`users`.`id`
    INNER JOIN `status_list` ON `orders_list`.`status`=`status_list`.`status_id`
    INNER JOIN `statusclarity_list` ON `orders_list`.`statusclarity`=`statusclarity_list`.`statusclarity_id`
    WHERE `visible`=2");

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

<!-- Модальное окно для создания нового заказа -->
<?php include "modal_new_order_create.php"; ?>

<header></header>
<main>
<div class="container-fluid">

    <!-- Панель управления (верхняя) -->
    <?php include 'control_panel_top.php'?>

    <div class="row">
        <div class="col-4">
            <h3>АРХИВ</h3>
        </div>
        <div class="col-8">
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
            <th>#</th>
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
                <td><?php echo date('d.m.Y', strtotime($out['data_close'])); ?></td>
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
                <td><a href="active.php?id=<?php echo $out['order_num']; ?>" title="Вернуть заказ в активные"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" style="fill: #28a745;"><path fill-rule="evenodd" d="M1 12C1 5.925 5.925 1 12 1s11 4.925 11 11-4.925 11-11 11S1 18.075 1 12zm8.036-4.024a.75.75 0 00-1.06 1.06L10.939 12l-2.963 2.963a.75.75 0 101.06 1.06L12 13.06l2.963 2.964a.75.75 0 001.061-1.06L13.061 12l2.963-2.964a.75.75 0 10-1.06-1.06L12 10.939 9.036 7.976z"></path></svg></a></td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>

















<!--        --><?php
//        while($out = mysqli_fetch_assoc($orders_list))
//        {
//            ?>
<!---->
<!--        <tr>-->
<!--            <td><a href="update.php?id=--><?php //echo $out['order_num']; ?><!--"><strong>G--><?php //echo $out['order_num']; ?><!--</strong></a></td>-->
<!--            <td>--><?php //echo $out['livesklad_num']; ?><!--</td>-->
<!--            <td class="status-color">--><?php //echo $out['status_name']; ?><!--</td>-->
<!--            <td>--><?php //echo $out['statusclarity_name']; ?><!--</td>-->
<!--            <td>--><?php //echo $out['order_type_name']; ?><!--</td>-->
<!--            <td>--><?php //echo $out['order_priority']; ?><!--</td>-->
<!--            <td>--><?php //echo $out['device_type_name']; ?><!--</td>-->
<!--            <td>--><?php //echo $out['device_brand_name']; ?><!--</td>-->
<!--            <td>--><?php //echo $out['device_model_name']; ?><!--</td>-->
<!--            <td>--><?php //echo $out['first_name']; ?><!--</td>-->
<!--            <td>--><?php //echo $out['phone_n']; ?><!--</td>-->
<!--            <td>--><?php //echo $out['data_close']; ?><!--</td>-->
<!--            <td>--><?php //echo $out['full_name']; ?><!--</td>-->
<!--            <td><a href="active.php?id=--><?php //echo $out['order_num']; ?><!--" title="Вернуть заказ в активные"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" style="fill: #28a745;"><path fill-rule="evenodd" d="M1 12C1 5.925 5.925 1 12 1s11 4.925 11 11-4.925 11-11 11S1 18.075 1 12zm8.036-4.024a.75.75 0 00-1.06 1.06L10.939 12l-2.963 2.963a.75.75 0 101.06 1.06L12 13.06l2.963 2.964a.75.75 0 001.061-1.06L13.061 12l2.963-2.964a.75.75 0 10-1.06-1.06L12 10.939 9.036 7.976z"></path></svg></a></td>-->
<!--        </tr>-->
<!---->
<!--        --><?php
//        }
//         ?>
<!--        </tbody>-->
<!--    </table>-->

</div>
</main>
<!-- Футер -->
<?php include 'footer.php'?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/main.js"></script>
</body>
</html>