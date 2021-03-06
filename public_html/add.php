<?php
session_start();
if (!$_SESSION['user']) {
    header('Location: index.php');
}
include "db.php";
$orders_list = mysqli_query($link, "SELECT * FROM `orders_list` 
    INNER JOIN `order_type_list` ON `orders_list`.`order_type`=`order_type_list`.`order_type_id`
    INNER JOIN `device_type_list` ON `orders_list`.`device_type`=`device_type_list`.`device_type_id`
    INNER JOIN `device_brand_list` ON `orders_list`.`device_brand`=`device_brand_list`.`device_brand_id`
    INNER JOIN `device_model_list` ON `orders_list`.`device_model`=`device_model_list`.`device_model_id`
    INNER JOIN `users` ON `orders_list`.`executor`=`users`.`id`
    INNER JOIN `status_list` ON `orders_list`.`status`=`status_list`.`status_id`
    INNER JOIN `statusclarity_list` ON `orders_list`.`statusclarity`=`statusclarity_list`.`statusclarity_id`
    WHERE `visible`=1 ORDER BY `order_type_list`.`order_type_class` ASC, `orders_list`.`order_priority` ASC, `orders_list`.`data_open` ASC");
$executor = mysqli_query($link, "SELECT * FROM `users` WHERE `rank` = '3'");
$device_type = mysqli_query($link, "SELECT * FROM `device_type_list` ORDER BY `device_type_list`.`device_type_name` ASC");
$device_brand = mysqli_query($link, "SELECT * FROM `device_brand_list` ORDER BY `device_brand_list`.`device_brand_name` ASC");
$device_model = mysqli_query($link, "SELECT * FROM `device_model_list` ORDER BY `device_model_list`.`device_model_name` ASC");
$status = mysqli_query($link, "SELECT * FROM `status_list`");
$statusclarity = mysqli_query($link, "SELECT * FROM `statusclarity_list`");
$order_type = mysqli_query($link, "SELECT * FROM `order_type_list`");
$user_full_name = $_SESSION['user']['full_name'];
$user_id = $_SESSION['user']['id'];

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
<header></header>
<main>

    <div class="towork-title">
        <div class="container-fluid">

            <p><div style="text-align: right;">???? ?????????????????? ?? <strong>LOGIC GW</strong> ???? ????????????: <mark><?= $_SESSION['user']['full_name'] ?></mark> <small>(ID: <?= $_SESSION['user']['id'] ?>)</small> | <a href="logout.php" class="logout">??????????</a></div></p>

            <!-- ?????????? ???????????????????? ???????????? ???????????? -->
            <form action="create.php" method="post">

                <div class="form-group">
                    <input class="form-control" autocomplete="off" type="text" name="livesklad_num" value="GO">
                </div>

                <div class="form-group">
                    <input class="form-control" autocomplete="off" placeholder="?????? ??????????????" type="text" name="first_name">
                </div>

                <div class="form-group">
                    <input class="form-control" type="text" autocomplete="off" placeholder="79115002255" maxlength="11" name="phone_n">
                </div>

                <div class="form-group">
                    <select class="form-control" type="number" name="device_type">
                        <option value="1">?????? ????????????????????</option>
                        <?php
                        for ($i=2; $out = mysqli_fetch_assoc($device_type); $i++)
                        {
                            ?>
                            <option value="<?php echo $out['device_type_id']; ?>"><?php echo $out['device_type_name']; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <select class="form-control" type="number" name="device_brand">
                        <option value="1" selected>?????????? ????????????????????</option>
                        <?php
                        for ($i=2; $out = mysqli_fetch_assoc($device_brand); $i++)
                        {
                            ?>
                            <option value="<?php echo $out['device_brand_id']; ?>"><?php echo $out['device_brand_name']; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <select class="form-control" type="number" name="device_model">
                        <option value="1" selected>???????????? ????????????????????</option>
                        <?php
                        for ($i=2; $out = mysqli_fetch_assoc($device_model); $i++)
                        {
                            ?>
                            <option value="<?php echo $out['device_model_id']; ?>"><?php echo $out['device_model_name']; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <select class="form-control" type="number" name="order_type">
                        <?php
                        while($out = mysqli_fetch_assoc($order_type))
                        {
                            ?>
                            <option value="<?php echo $out['order_type_id']; ?>"><?php echo $out['order_type_name']; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <select class="form-control" type="number" name="status">
                        <option value="3">?? ??????????????</option>
                        <?php
                        while($r = mysqli_fetch_assoc($status))
                        {
                            ?>
                            <option value="<?php echo $r['status_id']; ?>"><?php echo $r['status_name']; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <select class="form-control" type="number" name="order_priority">
                        <option value="3">???????????? ??????????????????</option>
                        <option value="2" selected>?????????????? ??????????????????</option>
                        <option value="1">?????????????? ??????????????????</option>
                    </select>
                </div>

                <div class="form-group">
                    <select class="form-control" type="text" name="color">
                        <option value="???????? ???? ????????????">???????? ???? ????????????</option>
                        <option value="????????????">????????????</option>
                        <option value="??????????">??????????</option>
                    </select>
                </div>

                <div class="form-group">
                    <select class="form-control" type="number" name="executor">
                        <?php
                        while($out = mysqli_fetch_assoc($executor))
                        {
                            ?>
                            <option value="<?php echo $out['id']; ?>"><?php echo $out['full_name']; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <select class="form-control" type="number" name="statusclarity">
                        <?php
                        while($out = mysqli_fetch_assoc($statusclarity))
                        {
                            ?>
                            <option value="<?php echo $out['statusclarity_id']; ?>"><?php echo $out['statusclarity_name']; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <textarea class="form-control" type="text" name="comment_order" placeholder="?????????????????????? ?? ???????????? (???? ???????? ??????????????)"></textarea>
                </div>

                <div class="form-group">
                    <input type="hidden" name="rank_id" value="6">
                    <input type="hidden" name="visible" value="1">
                    <button class="btn btn-danger btn-lg btn-block" type="submit">????????????????</button>
                </div>

            </form>

        </div>
    </div>
</main>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/main.js"></script>
</body>
</html>