<?php
session_start();
if (!$_SESSION['user']) {
    header('Location: index.php');
}
require_once "on-call_service.php";
if ($active_rank > 2) {
    header('Location: workarea.php');
}
// Каталог (справочник) услуг
// Выводим все добавленные ранее услуги
if (count($_GET) > 0) {
    $f_type = mysqli_real_escape_string($link, $_GET['f_type']);
    $f_brand = mysqli_real_escape_string($link, $_GET['f_brand']);
    $f_model = mysqli_real_escape_string($link, $_GET['f_model']);
    $query = "SELECT * FROM `serviceset_list` 
    INNER JOIN `serviceset_names_list` ON `serviceset_list`.`serviceset_name` = `serviceset_names_list`.`serviceset_names_id`
    INNER JOIN `device_type_list` ON `serviceset_list`.`device_type` = `device_type_list`.`device_type_id`
    INNER JOIN `device_brand_list` ON `serviceset_list`.`device_brand` = `device_brand_list`.`device_brand_id`
    INNER JOIN `device_model_list` ON `serviceset_list`.`device_model` = `device_model_list`.`device_model_id` 
    INNER JOIN `service_list` ON `serviceset_list`.`serviceset_element`=`service_list`.`service_id` 
    WHERE `device_type_id` = '$f_type' AND `device_brand_id` = '$f_brand' AND `device_model_id` = '$f_model'
    GROUP BY `serviceset_names_list`.`serviceset_names_name` 
    ORDER BY `serviceset_names_list`.`serviceset_names_name` ASC";
} else {
    $f_type = '2';
    $f_brand = '2';
    $f_model = '2';
    $query = "SELECT * FROM `serviceset_list` 
    INNER JOIN `serviceset_names_list` ON `serviceset_list`.`serviceset_name` = `serviceset_names_list`.`serviceset_names_id`
    INNER JOIN `device_type_list` ON `serviceset_list`.`device_type` = `device_type_list`.`device_type_id`
    INNER JOIN `device_brand_list` ON `serviceset_list`.`device_brand` = `device_brand_list`.`device_brand_id`
    INNER JOIN `device_model_list` ON `serviceset_list`.`device_model` = `device_model_list`.`device_model_id` 
    INNER JOIN `service_list` ON `serviceset_list`.`serviceset_element`=`service_list`.`service_id` 
    WHERE `device_type_id` = '$f_type' AND `device_brand_id` = '$f_brand' AND `device_model_id` = '$f_model'
    GROUP BY `serviceset_names_list`.`serviceset_names_name` 
    ORDER BY `serviceset_names_list`.`serviceset_names_name` ASC 
    ";
}
$device_filter = mysqli_fetch_assoc($device_filter = mysqli_query($link, $query));

// Подключаем справочники
$serviceset_names_list = mysqli_query($link, "SELECT * FROM `serviceset_names_list` ORDER BY `serviceset_names_list`.`serviceset_names_name` ASC");
$service_list = mysqli_query($link, "SELECT * FROM `service_list` INNER JOIN `service_names_list` ON `service_list`.`service_name`=`service_names_list`.`service_names_id` WHERE `device_type` = '$f_type' AND `device_brand` = '$f_brand' AND `device_model` = '$f_model' ORDER BY `service_names_list`.`service_names_name` ASC");
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
    <title>Logic: Услуги</title>
</head>
<body>

<div class="modal fade" id="addService" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Добавление работы для услуги</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&#10006;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="add_serviceset.php" method="post">
                    <div class="form-row mt-1">
                        <div class="col-12">
                            <input type="text" name="serviceset_num" class="form-control" placeholder="Артикул услуги" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-row mt-1">
                        <div class="col-12">
                            <select type="number" name="serviceset_name" class="form-control" required>
                                <?php foreach ($serviceset_names_list as $item) {
                                    ?>
                                    <option value="<?php echo $item['serviceset_names_id'] ?>"><?php echo $item['serviceset_names_id'] ?> - <?php echo $item['serviceset_names_name'] ?></option>
                                <?php
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row mt-1">
                        <div class="col-12">
                            <select type="number" name="serviceset_element" class="form-control" required>
                                <?php foreach ($service_list as $item) {
                                    ?>
                                    <option value="<?php echo $item['service_id'] ?>"><?php echo $item['service_names_name'] ?>: <?php echo $item['time_rate'] ?></option>
                                <?php
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
                            <input type="number" name="serviceset_element_qty" class="form-control" value="1" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="form-row mt-1">
                        <div class="col-12">
                            <input type="hidden" name="f_type" value="<?php echo $f_type; ?>">
                            <input type="hidden" name="f_brand" value="<?php echo $f_brand; ?>">
                            <input type="hidden" name="f_model" value="<?php echo $f_model; ?>">
                            <button type="submit" class="btn btn-primary">Добавить</button>
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
                    <form action="serviceset_cat.php" method="get">
                        <select type="number" name="f_type" class="form-control my-1">
                            <option value="<?php echo $device_filter['device_type_id'] ?>"><?php echo $device_filter['device_type_name'] ?></option>
                            <?php foreach ($device_type as $row_type) {
                                ?>
                                <option value="<?php echo $row_type['device_type_id'] ?>"><?php echo $row_type['device_type_name'] ?></option>
                                <?php
                            } ?>
                        </select>
                </div>
                <div class="col-2">
                    <select type="number" name="f_brand" class="form-control my-1">
                        <option value="<?php echo $device_filter['device_brand_id'] ?>"><?php echo $device_filter['device_brand_name'] ?></option>
                        <?php foreach ($device_brand as $out_brand) {
                            ?>
                            <option value="<?php echo $out_brand['device_brand_id'] ?>"><?php echo $out_brand['device_brand_name'] ?></option>
                            <?php
                        } ?>
                    </select>
                </div>
                <div class="col-2">
                    <select type="number" name="f_model" class="form-control my-1">
                        <option value="<?php echo $device_filter['device_model_id'] ?>"><?php echo $device_filter['device_model_name'] ?></option>
                        <?php foreach ($device_model as $out_model) {
                            ?>
                            <option value="<?php echo $out_model['device_model_id'] ?>"><?php echo $out_model['device_model_name'] ?></option>
                            <?php
                        } ?>
                    </select>
                </div>
                <div class="col-1">
                    <button type="submit" class="btn btn-primary my-1">GO</button>
                    </form>
                </div>
                <div class="col-4">
                   &nbsp;
                </div>
            </div>

            <div class="form-row" id="accordion">
                <div class="col-12 card bg-dark text-white font-weight-bold">
                    <div class="row card-header" id="heading">
                        <div class="col-12" data-toggle="collapse" data-target="#collapse" aria-expanded="false" aria-controls="collapse">
                            <div class="form-row">
                                <div class="col-2">Бренд</div>
                                <div class="col-2">Модель</div>
                                <div class="col-6">Наименование услуги</div>
                                <div class="col-1">Н-ч</div>
                                <div class="col-1">Цена</div>
                            </div>
                        </div>
                    </div>
                    <div id="collapse" class="collapse hide" aria-labelledby="heading" data-parent="#accordion">
                        <div class="card-body bg-light text-dark font-weight-normal">
                            Услуги состоят из отдельных работ. Норма времени на оказание услуги является суммой норм времени на проведение каждой отдельной работы.
                            Нормы времени на работы настраиваются в соотвесттвующем разделе (см. "<a href="service_cat.php">Работы</a>" в верхнем меню).
                            Цена за оказанную услугу формируется путем умножения суммы нормо-часов на стоимость 1 нормо-часа. Стоимость 1 нормо-часа зависит от класса устройства (см. "<a
                                    href="device_class_cat.php">Классы</a>" в верхнем меню)
                        </div>
                    </div>
                </div>
                <?php
                if ($result = mysqli_query($link, $query)) {
                    for ($i=1; $row = mysqli_fetch_assoc($result); $i++) {
                        $serviceset_name = $row['serviceset_name'];
                        $dtype = $row['device_type'];
                        $dbrand = $row['device_brand'];
                        $dmodel = $row['device_model'];
                        $amount = mysqli_query($link, "SELECT SUM(`time_rate`*`serviceset_element_qty`) AS `sum` FROM `serviceset_list` INNER JOIN `service_list` ON `serviceset_list`.`serviceset_element`=`service_list`.`service_id` WHERE `serviceset_name`='$serviceset_name' AND `serviceset_list`.`device_type`='$dtype' AND `serviceset_list`.`device_brand`='$dbrand' AND `serviceset_list`.`device_model`='$dmodel'");
                        $amount = mysqli_fetch_assoc($amount);
                        $class_list = mysqli_query($link, "SELECT * FROM `device_class_list` WHERE `device_type`='$f_type' AND `device_brand`='$f_brand' AND `device_model`='$f_model'");
                        $class_list = mysqli_fetch_assoc($class_list);
                        $device_class_num = $class_list['device_class_num'];
                        $dclass = mysqli_query($link, "SELECT * FROM `price_class` WHERE `class_id`='$device_class_num'");
                        $dclass = mysqli_fetch_assoc($dclass);
                        ?>
                <div class="col-12 card">
                    <div class="row card-header" id="heading<?php echo $i; ?>">
                            <div class="col-12" data-toggle="collapse" data-target="#collapse<?php echo $i; ?>" aria-expanded="false" aria-controls="collapse<?php echo $i; ?>">
                                <div class="form-row">
                                    <div class="col-2"><?php echo $row['device_brand_name']; ?></div>
                                    <div class="col-2"><?php echo $row['device_model_name']; ?></div>
                                    <div class="col-6"><?php echo $row['serviceset_names_name']; ?></div>
                                    <div class="col-1"><?php echo $amount['sum']; ?></div>
                                    <div class="col-1"><?php echo $dclass['price']*$amount['sum']; ?></div>
                                </div>
                            </div>
                    </div>

                    <div id="collapse<?php echo $i; ?>" class="collapse hide" aria-labelledby="heading<?php echo $i; ?>" data-parent="#accordion">
                        <div class="card-body">
                            <ul>
                                <?php
                                $query_sub = "SELECT * FROM `serviceset_list` 
    INNER JOIN `serviceset_names_list` ON `serviceset_list`.`serviceset_name` = `serviceset_names_list`.`serviceset_names_id` 
    INNER JOIN `device_type_list` ON `serviceset_list`.`device_type` = `device_type_list`.`device_type_id` 
    INNER JOIN `device_brand_list` ON `serviceset_list`.`device_brand` = `device_brand_list`.`device_brand_id` 
    INNER JOIN `device_model_list` ON `serviceset_list`.`device_model` = `device_model_list`.`device_model_id` 
    INNER JOIN `service_list` ON `serviceset_list`.`serviceset_element`=`service_list`.`service_id` 
    INNER JOIN `service_names_list` ON `service_list`.`service_name`=`service_names_list`.`service_names_id` 
    WHERE `serviceset_name` = '$serviceset_name' AND `serviceset_list`.`device_type`='$f_type' AND `serviceset_list`.`device_brand`='$f_brand' AND `serviceset_list`.`device_model`='$f_model'
    ORDER BY `service_names_name` ASC";
                                if ($result_sub = mysqli_query($link, $query_sub)) {
                                    while ($row_sub = mysqli_fetch_assoc($result_sub)) {
                                        ?>
                                        <div class="row">
                                        <li><?php echo $row_sub['service_names_name']; ?>:&nbsp;&nbsp;&nbsp;<?php echo $row_sub['time_rate']; ?>&#215;<?php echo $row_sub['serviceset_element_qty']; ?> &#61; <?php echo $row_sub['time_rate']*$row_sub['serviceset_element_qty']; ?> Н&middot;ч
                                            <a href="s_del_from_ss.php?ss_id=<?php echo $row_sub['serviceset_id']; ?>&f_type=<?php echo $f_type; ?>&f_brand=<?php echo $f_brand; ?>&f_model=<?php echo $f_model; ?>"><span style="color: red">&#215;</span></a></li>
                                        </div>
                                        <?php
                                    }
                                    mysqli_free_result($result_sub);
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>


                        <?php
                    }
                    mysqli_free_result($result);
                }
                ?>
            </div>

        </div>
    </div>
</main>
<!-- Футер -->
<?php include 'footer.php'?>

</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/select2.min.js"></script>
<script src="js/main.js"></script>
</body>
</html>
