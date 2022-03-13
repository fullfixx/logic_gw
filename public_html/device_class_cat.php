<?php
session_start();
if (!$_SESSION['user']) {
    header('Location: index.php');
}
require_once "on-call_service.php";
// Каталог (справочник) классов устройств

$device_type = "SELECT * FROM `device_type_list`";
$device_brand = "SELECT * FROM `device_brand_list`";
$device_model = "SELECT * FROM `device_model_list`";
$device_class = "SELECT * FROM `price_class` ORDER BY `price_class`.`class_id` ASC";
$device_class = mysqli_query($link, $device_class);
$query = "SELECT * FROM `device_class_list` 
INNER JOIN `device_type_list` ON `device_class_list`.`device_type`=`device_type_list`.`device_type_id`
INNER JOIN `device_brand_list` ON `device_class_list`.`device_brand`=`device_brand_list`.`device_brand_id`
INNER JOIN `device_model_list` ON `device_class_list`.`device_model`=`device_model_list`.`device_model_id`
INNER JOIN `price_class` ON `device_class_list`.`device_class_num`=`price_class`.`class_id`
ORDER BY `device_brand_name` ASC, `device_model_name` ASC;
";
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

<div class="modal fade" id="addClassDevice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Добавление класса к новому устройству</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="add_device_class.php" method="post">
                    <div class="form-row mt-2">
                        <div class="col-12">
                            <select type="number" name="device_type" class="form-control">
                                <?php
                                if ($result = mysqli_query($link, $device_type)) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        ?>
                                        <option value="<?php echo $row['device_type_id'] ?>"><?php echo $row['device_type_name'] ?></option>
                                        <?php
                                    }
                                    mysqli_free_result($result);
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row mt-2">
                        <div class="col-12">
                            <select type="number" name="device_brand" class="form-control">
                                <?php
                                if ($result = mysqli_query($link, $device_brand)) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        ?>
                                        <option value="<?php echo $row['device_brand_id'] ?>"><?php echo $row['device_brand_name'] ?></option>
                                        <?php
                                    }
                                    mysqli_free_result($result);
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row mt-2">
                        <div class="col-12">
                            <select type="number" name="device_model" class="form-control">
                                <?php
                                if ($result = mysqli_query($link, $device_model)) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        ?>
                                        <option value="<?php echo $row['device_model_id'] ?>"><?php echo $row['device_model_name'] ?></option>
                                        <?php
                                    }
                                    mysqli_free_result($result);
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row mt-2">
                        <div class="col-12">
                            <select type="number" name="device_class_num" class="form-control" placeholder="Класс" required>
                                <?php foreach ($device_class as $out_class) {
                                    ?>
                                    <option value="<?php echo $out_class['class_id'] ?>"><?php echo $out_class['class_num'] ?></option>
                                <?php
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row mt-2">
                        <div class="col-12">
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
                <div class="col-6">
                    <button type="button" class="btn btn-warning my-1" data-toggle="modal" data-target="#addClassDevice">&#10010;</button>
                </div>
                <div class="col-6">
                    <input type="text" class="form-control my-1" placeholder="Фильтр по устройствам" id="filter-input">
                </div>
            </div>

            <table class="table table-bordered table-striped table-hover">
                <thead class="thead-dark">
                <tr>
                    <th>Тип</th>
                    <th>Бренд</th>
                    <th>Модель</th>
                    <th>Класс</th>
                </tr>
                </thead>

                <tbody id="filter-list">

                <?php
                if ($result = mysqli_query($link, $query)) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                        <tr>
                            <td><?php echo $row['device_type_name']; ?></td>
                            <td><?php echo $row['device_brand_name']; ?></td>
                            <td><?php echo $row['device_model_name']; ?></td>
                            <td><?php echo $row['class_num']; ?></td>
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

    $("#search-input").on('keyup', function)

</script>

</body>
</html>
