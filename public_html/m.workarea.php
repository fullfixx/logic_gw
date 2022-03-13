<?php
session_start();
if (!$_SESSION['user']) {
    header('Location: m.index.php');
}
require_once "db.php";
$entered_user = $_SESSION['user']['id'];
$active_rank = $_SESSION['user']['rank'];
$row_post = mysqli_fetch_array(mysqli_query($link,"SELECT count(*) FROM `chat` WHERE `from_user` = '$entered_user' AND `post_read` = 1"));
$count_post = $row_post[0];
$row_complete = mysqli_fetch_array(mysqli_query($link,"SELECT count(*) FROM `orders_list` WHERE `statusclarity` = 2"));
$count_complete = $row_complete[0];

$filter = mysqli_real_escape_string($link, $_GET['filter']);
if (count($_GET) > 0) {
    $orders_list = mysqli_query($link, "SELECT * FROM `orders_list` 
    INNER JOIN `order_type_list` ON `orders_list`.`order_type`=`order_type_list`.`order_type_id`
    INNER JOIN `device_type_list` ON `orders_list`.`device_type`=`device_type_list`.`device_type_id`
    INNER JOIN `device_brand_list` ON `orders_list`.`device_brand`=`device_brand_list`.`device_brand_id`
    INNER JOIN `device_model_list` ON `orders_list`.`device_model`=`device_model_list`.`device_model_id`
    INNER JOIN `users` ON `orders_list`.`executor`=`users`.`id`
    INNER JOIN `status_list` ON `orders_list`.`status`=`status_list`.`status_id`
    INNER JOIN `statusclarity_list` ON `orders_list`.`statusclarity`=`statusclarity_list`.`statusclarity_id`
    WHERE `visible`=1 AND `status`!=1 AND `livesklad_num` LIKE '%$filter%' AND `executor` = '$entered_user' 
    ORDER BY `order_type_list`.`order_type_class` ASC, `orders_list`.`order_priority` ASC, `orders_list`.`data_open` ASC
    ");
} else {
    $orders_list = mysqli_query($link, "SELECT * FROM `orders_list` 
    INNER JOIN `order_type_list` ON `orders_list`.`order_type`=`order_type_list`.`order_type_id`
    INNER JOIN `device_type_list` ON `orders_list`.`device_type`=`device_type_list`.`device_type_id`
    INNER JOIN `device_brand_list` ON `orders_list`.`device_brand`=`device_brand_list`.`device_brand_id`
    INNER JOIN `device_model_list` ON `orders_list`.`device_model`=`device_model_list`.`device_model_id`
    INNER JOIN `users` ON `orders_list`.`executor`=`users`.`id`
    INNER JOIN `status_list` ON `orders_list`.`status`=`status_list`.`status_id`
    INNER JOIN `statusclarity_list` ON `orders_list`.`statusclarity`=`statusclarity_list`.`statusclarity_id`
    WHERE `visible`=1 AND `status`!=1 AND `livesklad_num` LIKE '%G%' AND `executor` = '$entered_user' 
    ORDER BY `order_type_list`.`order_type_class` ASC, `orders_list`.`order_priority` ASC, `orders_list`.`data_open` ASC
    ");
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
    <title>Logic GW</title>
</head>
<body>
<header></header>
<main>
    <div class="towork-title">
        <div class="container-fluid">
            <div style="text-align: right;">Вы работаете в "LOGIC" под именем:<br> <u><?= $_SESSION['user']['full_name'] ?></u> <small>(ID: <?= $_SESSION['user']['id'] ?>)</small> <a href="m.logout.php">Выход</a></div>

            <?php
            if ($count_post > 0) {
                ?>
                <div class="alert alert-warning mt-1" role="alert">
                    <a href="m.chat_personal.php">Новых личных сообщений</a>: <?php echo $count_post; ?><br>
                </div>
                <?php
            }
            ?>
            <!-- Аккордион начало -->
            <div class="form-row mt-1" id="accordion">
                <?php for ($i=1; $out = mysqli_fetch_assoc($orders_list); $i++) {
                    ?>
                    <div class="col-12 card">
                        <div class="row card-header" id="heading<?php echo $i; ?>">
                            <div class="col-12" data-toggle="collapse" data-target="#collapse<?php echo $i; ?>" aria-expanded="false" aria-controls="collapse<?php echo $i; ?>">
                                <div class="form-row">
                                    <div class="col-3"><strong>G<?php echo $out['order_num'] ?></strong></div>
                                    <div class="col-8"><?php echo $out['device_brand_name']; ?></div>
                                    <div class="col-1
                                    <?php
                                        if ($out['status_id'] == 2) {
                                            echo " table-primary border border-primary";
                                        }
                                    if ($out['status_id'] == 4) {
                                        echo " table-danger border border-danger";
                                    } else {
                                        echo " border border-secondary";
                                    }
                                        ?>
                                                        ">&nbsp;</div>

                                </div>
                                <div class="form-row mt-1">
                                    <div class="col-3"><small><?php echo date('d.m', strtotime($out['data_open'])); ?></small></div>
                                    <div class="col-8"><?php echo $out['device_model_name']; ?></div>
                                    <div class="col-1
                                    <?php
                                    if ($out['statusclarity_id'] == 2) {
                                        echo " table-primary border border-primary";
                                    }
                                    if ($out['statusclarity_id'] == 11) {
                                        echo " table-danger border border-danger";
                                    } else {
                                        echo " border border-secondary";
                                    }
                                    ?>
                                                        ">&nbsp;</div>
                                </div>
                            </div>
                        </div>
                        <div id="collapse<?php echo $i; ?>" class="collapse hide" aria-labelledby="heading<?php echo $i; ?>" data-parent="#accordion">
                            <div class="card-body">
                                <a class="btn btn-primary btn-sm btn-block" href="m.workarea_update.php?id=<?php echo $out['order_num']; ?>" role="button">УЗНАТЬ БОЛЬШЕ</a>
                                <hr>
                                <p><em><?php echo $out['comment_order']; ?></em></p>
                                <a class="SaveScroll btn btn-success btn-sm btn-block" href="m.changeto_towork.php?id=<?php echo $out['order_num']; ?>" role="button">ВЗЯТЬ В РАБОТУ</a>
                            </div>
                        </div>
                    </div>
                <?
                } ?>
            </div>
            <!-- Аккордион конец -->

        </div>
    </div>
</main>
<!-- Футер -->
<div class="navbar-fixed-bottom row-fluid">
    <div class="navbar-inner">
        <div class="container-fluid">
            <footer>
                <div class="text-white bg-dark my-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <a class="btn btn-secondary btn-sm btn-block" role="button" href="m.workarea.php?filter=pre" title="Предзапись">предзапись</a><br>
                            </div>
                            <div class="col-6">
                                <a class="btn btn-warning btn-sm btn-block" role="button" href="m.workarea.php" title="в мастерской">мастерская</a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <a href="monitor_sp.php" class="btn btn-danger btn-sm btn-block" target="_blank" role="button" title="Заказы запчастей">Заказы запчастей</a><br>
                            </div>
                        </div>

                    </div>
                </div>
            </footer>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/select2.min.js"></script>
<script src="js/main.js"></script>
<script>
    var pos = localStorage.getItem('my-scroll-pos', 0);
    $(window).scrollTop(pos);
    $(document).ready(function() {
        $('.SaveScroll').on("click", function() {

            localStorage.setItem('my-scroll-pos', $(window).scrollTop());

        });

    });
    $("#search-input").on('keyup', function)
</script>

</body>
</html>
