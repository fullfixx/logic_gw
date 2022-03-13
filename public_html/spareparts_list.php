<?php
session_start();
if (!$_SESSION['user']) {
    header('Location: index.php');
}
require_once "on-call_service.php";
if ($active_rank > 2) {
    header('Location: workarea.php');
}
// Каталог запчастей
// Получаем данные для основной таблицы
if (count($_GET) > 0) {
    $hard_filter = mysqli_real_escape_string($link, $_GET['hard_filter']);
    $query = "SELECT * FROM `spareparts`
        INNER JOIN `spareparts_names_list` ON `spareparts`.`sparepart_name`=`spareparts_names_list`.`spareparts_names_id`
        INNER JOIN `spareparts_brands_list` ON `spareparts`.`sparepart_brand`=`spareparts_brands_list`.`spareparts_brands_id`
        WHERE `sparepart_num` LIKE '%$hard_filter%' OR `spareparts_names_name` LIKE '%$hard_filter%'
        ORDER BY `spareparts`.`sparepart_num` ASC";
} else {
    $hard_filter = 'колод';
    $query = "SELECT * FROM `spareparts`
        INNER JOIN `spareparts_names_list` ON `spareparts`.`sparepart_name`=`spareparts_names_list`.`spareparts_names_id`
        INNER JOIN `spareparts_brands_list` ON `spareparts`.`sparepart_brand`=`spareparts_brands_list`.`spareparts_brands_id`
        WHERE `spareparts_names_name` LIKE '%$hard_filter%'
        ORDER BY `spareparts`.`sparepart_num` ASC";
}

//Получаем справочники
$sp_names = mysqli_query($link, "SELECT * FROM `spareparts_names_list` ORDER BY `spareparts_names_list`.`spareparts_names_name` ASC");
$sp_brands = mysqli_query($link, "SELECT * FROM `spareparts_brands_list` ORDER BY `spareparts_brands_list`.`spareparts_brands_name` ASC");
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="css/bootstrap.min.css" >
    <link rel="stylesheet" href="css/select2.min.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Logic: Запчасти</title>
</head>
<body>

<div class="modal fade" id="addService" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Добавление новой услуги в БД</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="add_sparepart.php" method="post">
                    <div class="form-row mt-1">
                        <div class="col-12">
                            <input type="text" name="sparepart_num" class="form-control" placeholder="Артикул запчасти" required>
                        </div>
                    </div>
                    <div class="form-row mt-1">
                        <div class="col-12">
                            <select type="number" name="sparepart_name" class="form-control" required>
                                <?php foreach ($sp_names as $item) {
                                    ?>
                                    <option value="<?php echo $item['spareparts_names_id']; ?>"><?php echo $item['spareparts_names_name']; ?></option>
                                    <?
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row mt-1">
                        <div class="col-12">
                            <select type="number" name="sparepart_brand" class="form-control" required>
                                <option value="1">Gowheel</option>
                                <?php foreach ($sp_brands as $item) {
                                    ?>
                                    <option value="<?php echo $item['spareparts_brands_id']; ?>"><?php echo $item['spareparts_brands_name']; ?></option>
                                    <?
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row mt-1">
                        <div class="col-12">
                            <textarea name="cross_reference" class="form-control" placeholder="Артикулы аналогов через запятую"></textarea>
                        </div>
                    </div>
                    <div class="form-row mt-1">
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
                    <?php
                    $entered_user = $_SESSION['user']['rank'];
                    if ($entered_user == 2) {
                        ?>
                        <button type="button" class="btn btn-warning my-1" data-toggle="modal" data-target="#addService">&#10010;</button>
                        <?php
                    }
                    ?>

                </div>
                <div class="col-3">
                    <form action="spareparts_list.php" method="get">
                    <input type="text" class="form-control my-1" name="hard_filter" placeholder="Жесткий фильтр">
                    </form>
                </div>
                <div class="col-3">
                    <input type="text" class="form-control my-1" placeholder="Живой поиск" id="filter-input">
                </div>
            </div>

            <table class="table table-bordered table-striped table-hover">
                <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Артикул запчасти</th>
                    <th>Наименование запчасти</th>
                    <th>Бренд</th>
                    <th>Cross-Reference</th>
                </tr>
                </thead>

                <tbody id="filter-list">

                <?php
                if ($result = mysqli_query($link, $query)) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                        <tr>
                            <td><?php echo $row['sparepart_id']; ?></td>
                            <td><?php echo $row['sparepart_num']; ?></td>
                            <td><?php echo $row['spareparts_names_name']; ?></td>
                            <td><?php echo $row['spareparts_brands_name']; ?></td>
                            <td><?php echo $row['cross_reference']; ?></td>
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