<?php
session_start();
if (!$_SESSION['user']) {
    header('Location: index.php');
}
require_once "on-call_service.php";
if ($active_rank > 2) {
    header('Location: workarea.php');
}
// Каталог отдельных работ

if (count($_GET) > 0) {
    $f_type = mysqli_real_escape_string($link, $_GET['f_type']);
    $f_brand = mysqli_real_escape_string($link, $_GET['f_brand']);
    $f_model = mysqli_real_escape_string($link, $_GET['f_model']);
    $query = "SELECT * FROM `service_list` 
    INNER JOIN `service_group_list` ON `service_list`.`service_group` = `service_group_list`.`service_group_id` 
    INNER JOIN `service_names_list` ON `service_list`.`service_name` = `service_names_list`.`service_names_id`
    INNER JOIN `device_type_list` ON `service_list`.`device_type` = `device_type_list`.`device_type_id`
    INNER JOIN `device_brand_list` ON `service_list`.`device_brand` = `device_brand_list`.`device_brand_id`
    INNER JOIN `device_model_list` ON `service_list`.`device_model` = `device_model_list`.`device_model_id`
    WHERE `device_type_id` = '$f_type' AND `device_brand_id` = '$f_brand' AND `device_model_id` = '$f_model'
    ORDER BY `service_names_list`.`service_names_name` ASC";
} else {
    $f_type = '2';
    $f_brand = '2';
    $f_model = '2';
    $query = "SELECT * FROM `service_list` 
    INNER JOIN `service_group_list` ON `service_list`.`service_group` = `service_group_list`.`service_group_id` 
    INNER JOIN `service_names_list` ON `service_list`.`service_name` = `service_names_list`.`service_names_id`
    INNER JOIN `device_type_list` ON `service_list`.`device_type` = `device_type_list`.`device_type_id`
    INNER JOIN `device_brand_list` ON `service_list`.`device_brand` = `device_brand_list`.`device_brand_id`
    INNER JOIN `device_model_list` ON `service_list`.`device_model` = `device_model_list`.`device_model_id`
    WHERE `device_type_id` = '$f_type' AND `device_brand_id` = '$f_brand' AND `device_model_id` = '$f_model'
    ORDER BY `service_names_list`.`service_names_name` ASC";
}
$device_filter = mysqli_fetch_assoc($device_filter = mysqli_query($link, $query));

// Получаем справочники
$service_group = mysqli_query($link, "SELECT * FROM `service_group_list` ORDER BY `service_group_list`.`service_group_id` ASC");
$service_names = mysqli_query($link, "SELECT * FROM `service_names_list` ORDER BY `service_names_list`.`service_names_name` ASC");
$device_type = mysqli_query($link, "SELECT * FROM `device_type_list` ORDER BY `device_type_list`.`device_type_name` ASC");
$device_brand = mysqli_query($link, "SELECT * FROM `device_brand_list` ORDER BY `device_brand_list`.`device_brand_name` ASC");
$device_model = mysqli_query($link, "SELECT * FROM `device_model_list` ORDER BY `device_model_list`.`device_model_name` ASC");

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="css/bootstrap.min.css" >
    <link rel="stylesheet" href="css/select2.min.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Logic: Работы</title>
</head>
<body>

<div class="modal fade" id="addService" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Добавление отдельной работы</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&#10006;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="add_service.php" method="post">
                    <div class="form-row">
                        <div class="col-12">
                            <input type="text" name="service_num" class="form-control" placeholder="Артикул отдельной работы" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-row mt-1">
                        <div class="col-12">
                            <select name="service_name" class="form-control" required>
                                <?php foreach ($service_names as $item) {
                                    ?>
                                    <option value="<?php echo $item['service_names_id'] ?>"><?php echo $item['service_names_name'] ?></option>
                                    <?
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row mt-1">
                        <div class="col-12">
                            <textarea name="service_desc" class="form-control" placeholder="Описание работы (не обязательно)"></textarea>
                        </div>
                    </div>
                    <div class="form-row mt-1">
                        <div class="col-12">
                            <select name="service_group" class="form-control">
                                <option value="1">Группа работ 1</option>
                                <?php foreach ($service_group as $item) {
                                    ?>
                                    <option value="<?php echo $item['service_group_id'] ?>"><?php echo $item['service_group_name'] ?></option>
                                    <?
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row mt-1">
                        <div class="col-12">
                            <select name="device_type" class="form-control" required>
                                <option value="<?php echo $device_filter['device_type_id'] ?>"><?php echo $device_filter['device_type_name'] ?></option>
                                <?php foreach ($device_type as $item) {
                                    ?>
                                    <option value="<?php echo $item['device_type_id'] ?>"><?php echo $item['device_type_name'] ?></option>
                                    <?php
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row mt-1">
                        <div class="col-12">
                            <select name="device_brand" class="form-control" required>
                                <option value="<?php echo $device_filter['device_brand_id'] ?>"><?php echo $device_filter['device_brand_name'] ?></option>
                                <?php foreach ($device_brand as $item) {
                                    ?>
                                    <option value="<?php echo $item['device_brand_id'] ?>"><?php echo $item['device_brand_name'] ?></option>
                                    <?php
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row mt-1">
                        <div class="col-12">
                            <select name="device_model" class="form-control" required>
                                <option value="<?php echo $device_filter['device_model_id'] ?>"><?php echo $device_filter['device_model_name'] ?></option>
                                <?php foreach ($device_model as $item) {
                                    ?>
                                    <option value="<?php echo $item['device_model_id'] ?>"><?php echo $item['device_model_name'] ?></option>
                                    <?php
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row mt-1">
                        <div class="col-12">
                            <input type="text" name="time_rate" class="form-control" placeholder="0.00" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="form-row mt-1">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Создать</button>
                        </div>
                    </div>
                </form>

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

            <?php include "control_panel_top.php"?>

            <div class="form-row">
                <div class="col-1">
                    <?php
                    if ($active_rank == 2) {
                        ?>
                        <button type="button" class="btn btn-warning my-1" data-toggle="modal" data-target="#addService">&#10010;</button>
                    <?php
                    }
                    ?>
                </div>
                <div class="col-2">
                    <form action="service_cat.php" method="get">
                    <select type="number" name="f_type" class="form-control my-1">
                        <option value="<?php echo $device_filter['device_type_id'] ?>"><?php echo $device_filter['device_type_name'] ?></option>
                        <?php foreach ($device_type as $item) {
                            ?>
                            <option value="<?php echo $item['device_type_id'] ?>"><?php echo $item['device_type_name'] ?></option>
                        <?php
                        } ?>
                    </select>
                </div>
                <div class="col-2">
                    <select type="number" name="f_brand" class="form-control my-1">
                        <option value="<?php echo $device_filter['device_brand_id'] ?>"><?php echo $device_filter['device_brand_name'] ?></option>
                        <?php foreach ($device_brand as $item) {
                            ?>
                            <option value="<?php echo $item['device_brand_id'] ?>"><?php echo $item['device_brand_name'] ?></option>
                            <?php
                        } ?>
                    </select>
                </div>
                <div class="col-2">
                    <select type="number" name="f_model" class="form-control my-1">
                        <option value="<?php echo $device_filter['device_model_id'] ?>"><?php echo $device_filter['device_model_name'] ?></option>
                        <?php foreach ($device_model as $item) {
                            ?>
                            <option value="<?php echo $item['device_model_id'] ?>"><?php echo $item['device_model_name'] ?></option>
                            <?php
                        } ?>
                    </select>
                </div>
                <div class="col-1">
                    <button type="submit" class="btn btn-primary my-1">GO</button>
                    </form>
                </div>
<!--                <a class="btn btn-warning btn-sm my-1" role="button" href="service_cat.php?f_type=2&f_brand=2&f_model=2">365</a>-->
                <div class="col-4">
                    <input type="text" class="form-control my-1" placeholder="Быстрый поиск" id="filter-input">
                </div>
            </div>

            <table class="table table-bordered table-striped table-hover">
                <thead class="thead-dark">
                <tr>
                    <th class="text-center">ID</th>
                    <th>Наименование работы</th>
                    <th class="text-center">Тип</th>
                    <th class="text-center">Бренд</th>
                    <th class="text-center">Модель</th>
                    <th class="text-right">Н-ч</th>
                    <th class="text-right">Цена</th>
                    <th class="text-center">#</th>
                </tr>
                </thead>

                <tbody id="filter-list">

            <?php
            if ($result = mysqli_query($link, $query)) {
                while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $row['service_id']; ?></td>
                        <td><?php echo $row['service_names_name']; ?></td>
                        <td class="text-center"><?php echo $row['device_type_name']; ?></td>
                        <td class="text-center"><?php echo $row['device_brand_name']; ?></td>
                        <td class="text-center"><?php echo $row['device_model_name']; ?></td>
                        <td>
                            <form action="upd_service_list.php" method="post">
                                <input type="hidden" name="service_id" value="<?php echo $row['service_id']; ?>">
                                <input type="hidden" name="f_type" value="<?php echo $f_type; ?>">
                                <input type="hidden" name="f_brand" value="<?php echo $f_brand; ?>">
                                <input type="hidden" name="f_model" value="<?php echo $f_model; ?>">
                                <input type="text" name="time_rate" class="form-control form-control-sm text-right SaveScroll" value="<?php echo $row['time_rate']; ?>" autocomplete="off" onchange="submit();">

                        </td>
                        <?php
                        $dt_id = $row['device_type_id'];
                        $db_id = $row['device_brand_id'];
                        $dm_id = $row['device_model_id'];
                        $price_class = mysqli_fetch_assoc(mysqli_query($link, "SELECT * FROM `device_class_list` INNER JOIN `price_class` ON `device_class_list`.`device_class_num`=`price_class`.`class_id` WHERE `device_type`='$dt_id' AND `device_brand`='$db_id' AND `device_model`='$dm_id'"));
                        $tr_price = $row['time_rate']*$price_class['price']; ?>
                        <td class="text-right"><?php echo number_format($tr_price, "0", ".", " "); ?></td>
                        <td class="text-center"><button type="submit" class="btn btn-sm btn-success SaveScroll">&#10004;</button></td>
                        </form>
                    </tr>
                    <?php
                }
                mysqli_free_result($result);
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
<script src="js/select2.min.js"></script>
<script src="js/main.js"></script>
<script>
    var pos = localStorage.getItem('my-scroll-pos', 0);
    $(window).scrollTop(pos);

    $("#search-input").on('keyup', function)

    $(document).ready(function() {
        $('.SaveScroll').on("click", function() {

            localStorage.setItem('my-scroll-pos', $(window).scrollTop());

        });

    });

</script>

</body>
</html>