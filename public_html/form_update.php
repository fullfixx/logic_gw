<?php

?>
<form action="upd_script.php" method="post">
<div class="form-row mt-1 mt-3">
    <div class="col-6">
        <label>№ LiveSklad:</label>
    </div>
    <div class="col-6">
        <input class="form-control form-control-sm" type="text" autocomplete="off" name="livesklad_num" value="<?php echo $out['livesklad_num']; ?>">
    </div>
</div>
    <div class="form-row mt-1 mt-1">
        <div class="col-6">
            <label>Имя клиента:</label>
        </div>
        <div class="col-6">
            <input class="form-control form-control-sm" type="text" autocomplete="off" value="<?php echo $out['first_name']; ?>" name="first_name">
        </div>
    </div>
    <div class="form-row mt-1">
        <div class="col-6">
            <label>Телефон:</label><a target="_blank" href="https://wa.me/<?php echo $out['phone_n']; ?>"><img src="img/icon_whatsapp.png" alt="Написать в WhatsApp"></a>
        </div>
        <div class="col-6">
            <input class="form-control form-control-sm" type="text" autocomplete="off" value="<?php echo $out['phone_n']; ?>" maxlength="11" name="phone_n" id="phone_n">
        </div>
    </div>
    <div class="form-row mt-1">
        <div class="col-6">
            <label>Тип заказа:</label>
        </div>
        <div class="col-6">

            <select class="form-control form-control-sm" type="number" name="order_type" onchange="submit();">
                <option value="<?php echo $out['order_type_id']; ?>"><?php echo $out['order_type_name']; ?></option>
                <?php
                while($r = mysqli_fetch_assoc($order_type))
                {
                    ?>
                    <option value="<?php echo $r['order_type_id']; ?>"><?php echo $r['order_type_name']; ?></option>
                    <?php
                }
                ?>
            </select>
        </div>
    </div>
    <div class="form-row mt-1">
        <div class="col-6">
            <label>Статус заказа:</label>
        </div>
        <div class="col-6">
            <select class="form-control form-control-sm" type="text" name="status" onchange="submit();">
                <option value="<?php echo $out['status_id']; ?>"><?php echo $out['status_name']; ?></option>
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
    </div>
    <div class="form-row mt-1">
        <div class="col-6">
            <label>Дополн.статус:</label>
        </div>
        <div class="col-6">
            <select class="form-control form-control-sm" type="number" name="statusclarity" onchange="submit();">
                <option value="<?php echo $out['statusclarity_id']; ?>"><?php echo $out['statusclarity_name']; ?></option>
                <?php
                while($r = mysqli_fetch_assoc($statusclarity))
                {
                    ?>
                    <option value="<?php echo $r['statusclarity_id']; ?>"><?php echo $r['statusclarity_name']; ?></option>
                    <?php
                }
                ?>
            </select>
        </div>
    </div>
    <div class="form-row mt-1">
        <div class="col-6">
            <label>Приоритет:</label>
        </div>
        <div class="col-6">
            <select class="form-control form-control-sm" type="number" name="order_priority" onchange="submit();">
                <option value="<?php echo $out['order_priority']; ?>">Не изменять</option>
                <option value="3">Низкий</option>
                <option value="2">Средний</option>
                <option value="1">Высокий</option>
            </select>
        </div>
    </div>
    <div class="form-row mt-1">
        <div class="col-6">
            <label>Тип устройства:</label>
        </div>
        <div class="col-6">
            <select class="form-control form-control-sm" type="number" name="device_type" onchange="submit();">
                <option value="<?php echo $out['device_type_id']; ?>"><?php echo $out['device_type_name']; ?></option>
                <?php
                while($r = mysqli_fetch_assoc($device_type))
                {
                    ?>
                    <option value="<?php echo $r['device_type_id']; ?>"><?php echo $r['device_type_name']; ?></option>
                    <?php
                }
                ?>
            </select>
        </div>
    </div>

    <div class="form-row mt-1">
        <div class="col-6">
            <label>Бренд:</label>
        </div>
        <div class="col-6">
            <select class="form-control form-control-sm" type="number" name="device_brand" onchange="submit();">
                <option value="<?php echo $out['device_brand_id']; ?>"><?php echo $out['device_brand_name']; ?></option>
                <?php
                while($r = mysqli_fetch_assoc($device_brand))
                {
                    ?>
                    <option value="<?php echo $r['device_brand_id']; ?>"><?php echo $r['device_brand_name']; ?></option>
                    <?php
                }
                ?>
            </select>
        </div>
    </div>


    <div class="form-row mt-1">
        <div class="col-6">
            <label>Модель:</label>
        </div>
        <div class="col-6">
            <select class="form-control form-control-sm" type="number" name="device_model" onchange="submit();">
                <option value="<?php echo $out['device_model_id']; ?>"><?php echo $out['device_model_name']; ?></option>
                <?php
                while($r = mysqli_fetch_assoc($device_model))
                {
                    ?>
                    <option value="<?php echo $r['device_model_id']; ?>"><?php echo $r['device_model_name']; ?></option>
                    <?php
                }
                ?>
            </select>
        </div>
    </div>
    <div class="form-row mt-1">
        <div class="col-6">
            <label>Цвет:</label>
        </div>
        <div class="col-6">
            <select class="form-control form-control-sm" type="text" name="color" onchange="submit();">
                <option value="<?php echo $out['color']; ?>"><?php echo $out['color']; ?></option>
                <option value="Черный">Черный</option>
                <option value="Белый">Белый</option>
            </select>
        </div>
    </div>
    <div class="form-row mt-1">
        <div class="col-6">
            <label>Мастер:</label>
        </div>
        <div class="col-6">

            <select class="form-control form-control-sm" type="number" name="executor" onchange="submit();">
                <option value="<?php echo $out['id']; ?>" selected><?php echo $out['full_name']; ?></option>
                <?php
                while($r = mysqli_fetch_assoc($executor))
                {
                    ?>
                    <option value="<?php echo $r['id']; ?>"><?php echo $r['full_name']; ?></option>
                    <?php
                }
                ?>
            </select>
        </div>
    </div>

    <div class="form-row mt-1">
        <div class="col-12">
            <textarea class="form-control form-control-sm" name="comment_order" placeholder="Причина обращения со слов клиента"><?php echo $out['comment_order']; ?></textarea>
        </div>
    </div>
    <div class="form-row mt-1">
        <div class="col-12">
            <textarea class="form-control form-control-sm" name="recommendation" placeholder="Рекомендации для клиента"><?php echo $out['recommendation']; ?></textarea>
        </div>
    </div>
    <div class="form-row mt-2">
        <div class="col-12">
            <input type="hidden" name="rank_id" value="6">
            <input type="hidden" name="order_num" value="<?php echo $order_num; ?>">
            <button class="btn btn-sm btn-danger" type="submit">Сохранить</button>
        </div>
    </div>
    <div class="form-row mt-1">
        <div class="col-12">
            <span style="float: right; font-size: 0.8em;"><?php echo $full_date; ?></span>
        </div>
    </div>
</form>