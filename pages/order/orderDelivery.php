<div class="orderForm orderDelivery active">
    <div class="orderAddress">
        <div class="orderField">
            <label>Укажите адрес доставки</label>
            <input type="text" placeholder="Город, улица, дом, квартира..." name="address"
            <?php if(isset($_POST['address'])) { ?>value="<?= $_POST['address'] ?>"<?php } ?>>
            <?php if(isset($errors['address'])) { ?>
            <h2><?= $errors['address'] ?></h2>
            <?php } ?>
        </div>

        <div class="orderField">
            <label>Укажите дату и время</label>
            <div class="orderDate">
                <input type="date" name="date"
                <?php if(isset($_POST['date'])) { ?>value="<?= $_POST['date'] ?>"<?php } ?>>
                <select name="time">
                    <option>8:00-12:00</option>
                    <option>12:00-16:00</option>
                    <option>16:00-20:00</option>
                </select>
            </div>
            <?php if(isset($errors['date'])) { ?>
            <h2><?= $errors['date'] ?></h2>
            <?php } ?>
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