<div class="orderForm orderPickup">
    <div class="orderAddress">
        <div class="orderField">
            <label>Выберите магазин</label>
            <select name="location">
                <option selected disabled>Укажите точку...</option>
                <option>Казань, Баумана 36</option>
                <option>Москва, Моховая 12</option>
                <option>СПБ, Большая Пушкарская 9</option>
            </select>
            <?php if(isset($errors['location'])) { ?>
            <h2><?= $errors['location'] ?></h2>
            <?php } ?>
        </div>

        <div class="orderField">
            <label>Примерная дата поступления</label>
            <p><?= $predictDate ?></p>
        </div>
    </div>

    <div class="orderInfo">
        <div class="orderField">
            <label>Укажите телефон для связи</label>
            <input type="tel" placeholder="8 (900) 000-00-00" name="phone"
            <?php if(isset($_POST['phone'])) { ?>value="<?= $_POST['phone'] ?>"<?php } ?>>
            <?php if(isset($errors['phone'])) { ?>
            <h2><?= $errors['phone'] ?></h2>
            <?php } ?>
        </div>

        <div class="orderField">
            <label>Комментарий (не обязательно)</label>
            <input type="text" placeholder="Дополнительная информация..." name="comment"
            <?php if(isset($_POST['comment'])) { ?>value="<?= $_POST['comment'] ?>"<?php } ?>>
        </div>
    </div>
</div>