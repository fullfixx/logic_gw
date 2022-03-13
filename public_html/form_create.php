<?php
session_start();
if (!$_SESSION['user']) {
    header('Location: index.php');
}
require_once "db.php";
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
<form action="create.php" method="post">
    <!-- первый ряд -->
    <div class="form-row">
        <div class="col-3">
            <label for="formGroupExampleInput" class="mt-1">Номер LiveSklad:</label>
            <input class="form-control" autocomplete="off" type="text" name="livesklad_num" value="GO">
        </div>
        <div class="col-3">
            <label for="formGroupExampleInput" class="mt-1">Тип заказа:</label>
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
        <div class="col-3">
            <label for="formGroupExampleInput" class="mt-1">Тип устройства:</label>
            <select class="form-control" type="number" name="device_type">
                <option value="1">Тип устройства</option>
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
        <div class="col-3">
            <label for="formGroupExampleInput" class="mt-1">Цвет устройства:</label>
            <select class="form-control" type="text" name="color">
                <option value="цвет не указан">Цвет не указан</option>
                <option value="Черный">Черный</option>
                <option value="Белый">Белый</option>
            </select>
        </div>
    </div>
    <!-- второй ряд -->
    <div class="form-row">
        <div class="col-3">
            <label for="formGroupExampleInput" class="mt-1">Имя клиента:</label>
            <input class="form-control" autocomplete="off" placeholder="Имя клиента" type="text" name="first_name">
        </div>
        <div class="col-3">
            <label for="formGroupExampleInput" class="mt-1">Статус заказа:</label>
            <select class="form-control" type="number" name="status">
                <option value="3">в очереди</option>
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
        <input type="hidden" name="rank_id" value="6">
        <div class="col-3">
            <label for="formGroupExampleInput" class="mt-1">Бренд устройства:</label>
            <select class="form-control" type="number" name="device_brand">
                <option value="1" selected>Бренд устройства</option>
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
        <div class="col-3">
            <label for="formGroupExampleInput" class="mt-1">Мастер:</label>
            <select class="form-control" type="number" name="executor">
                <option value="1">без мастера</option>
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
    </div>
    <!-- третий ряд -->
    <div class="form-row">
        <div class="col-3">
            <label for="formGroupExampleInput" class="mt-1">Телефон клиента:</label>
            <input class="form-control" type="text" autocomplete="off" placeholder="79001110000" maxlength="11" name="phone_n"">
        </div>
        <div class="col-3">
            <label for="formGroupExampleInput" class="mt-1">Приоритет заказа:</label>
            <select class="form-control" type="number" name="order_priority">
                <option value="3">Низкий приоритет</option>
                <option value="2" selected>Средний приоритет</option>
                <option value="1">Высокий приоритет</option>
            </select>
        </div>
        <div class="col-3">
            <label for="formGroupExampleInput" class="mt-1">Модель устройства:</label>
            <select class="form-control" type="number" name="device_model">
                <option value="1" selected>Модель устройства</option>
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
        <div class="col-3">
            <label for="formGroupExampleInput" class="mt-1">Расширенный статус:</label>
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
    </div>
    <!-- четвертый ряд -->
    <div class="form-row">
        <div class="col-6">
            <textarea class="form-control mt-2" type="text" name="comment_order" placeholder="Причина обращения со слов клиента!" wrap></textarea>
        </div>
        <div class="col-6">
            <button class="btn btn-primary mt-2" type="submit">Добавить</button>
        </div>
    </div>
    <input type="hidden" name="visible" value="1">
</form>