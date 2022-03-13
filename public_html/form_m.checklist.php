<form action="m.changeto_completed.php" method="post" class="was-validated">
    <div class="custom-control custom-checkbox mb-3">
        <input type="hidden" name="order_num" value="<?php echo $order_num; ?>">
        <input type="checkbox" class="custom-control-input" id="customControlValidation1" required>
        <label class="custom-control-label" for="customControlValidation1">Узел складывания на наличие люфтов проверил, замок закрыл</label>
        <div class="invalid-feedback">Необходимо подвердить</div>
    </div>
    <div class="custom-control custom-checkbox mb-3">
        <input type="checkbox" class="custom-control-input" id="customControlValidation2" required>
        <label class="custom-control-label" for="customControlValidation2">Тормоза исправны, привод затянут</label>
        <div class="invalid-feedback">Необходимо подвердить</div>
    </div>
    <div class="custom-control custom-checkbox mb-3">
        <input type="checkbox" class="custom-control-input" id="customControlValidation3" required>
        <label class="custom-control-label" for="customControlValidation3">Точки крепления крышек корпуса и шасси затянуты</label>
        <div class="invalid-feedback">Необходимо подвердить</div>
    </div>
    <div class="custom-control custom-checkbox mb-3">
        <input type="checkbox" class="custom-control-input" id="customControlValidation4" required>
        <label class="custom-control-label" for="customControlValidation4">Давление в колесах в пределах нормы</label>
        <div class="invalid-feedback">Необходимо подвердить</div>
    </div>
    <button class="btn btn-primary" type="submit">Завершить работы</button>
</form>