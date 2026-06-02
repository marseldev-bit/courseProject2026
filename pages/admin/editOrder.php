<?php
    $errors = [];

    if(isset($_GET['delivery'])) {
        $status = ['В пути', 'Завершен'];
        $sql = "SELECT * FROM ordersDelivery WHERE id = ?";
        $stmt = $connect->prepare($sql);
        $stmt->execute([$_GET['delivery']]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        $orderType = true;
    }
    else {
        $status = ['В пути', 'Можно забирать', 'Завершен'];
        $sql = "SELECT * FROM ordersPickup WHERE id = ?";
        $stmt = $connect->prepare($sql);
        $stmt->execute([$_GET['pickup']]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        $orderType = false;
    }

    $sql = "SELECT * FROM items WHERE id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->execute([$order['item_id']]);
    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->execute([$order['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if(isset($_POST['editData'])) {
        if(empty($_POST['phone'])) $errors['phone'] = 'Укажите номер телефона';
        elseif(strlen($_POST['phone']) > 11 or strlen($_POST['phone']) < 11 or $_POST['phone'][0] != 8 or $_POST['phone'][1] != 9) $errors['phone'] = 'Неверный формат'; 
        if(empty($_POST['quanity'])) $errors['quanity'] = 'Укажите количество товара';
        elseif($_POST['quanity'] < 1) $errors['quanity'] = 'Укажите корректное количество товара';
        if($orderType) {
            if(empty($_POST['address'])) $errors['address'] = 'Укажите адрес';
            if(empty($_POST['picked_date'])) $errors['picked_date'] = 'Укажите выбранную дату доставки';
        }
        else {
            if(empty($_POST['predict_date'])) $errors['predict_date'] = 'Укажите примерную дату доставки';
        }
        if($_POST['status'] == 'Завершен' and empty($_POST['delivery_date'])) $errors['delivery_date'] = 'Укажите дату доставки заказа';

        if(empty($errors)) {
            if($orderType) {
                if(empty($_POST['cooment'])) $comment = null;
                else $comment = $_POST['comment'];
                if(empty($_POST['delivery_date'])) {
                    $sql = "UPDATE ordersDelivery SET status = ?, delivery_date = ?, phone = ?, comment = ?, quanity = ?, address = ?, picked_date = ?, time = ? WHERE id = ?";
                    $stmt = $connect->prepare($sql);
                    $stmt->execute([$_POST['status'], NULL,  $_POST['phone'], $comment, $_POST['quanity'], $_POST['address'], $_POST['picked_date'], $_POST['time'], $order['id']]);
                }
                else {
                    $sql = "UPDATE ordersDelivery SET status = ?, delivery_date = ?, phone = ?, comment = ?, quanity = ?, address = ?, picked_date = ?, time = ? WHERE id = ?";
                    $stmt = $connect->prepare($sql);
                    $stmt->execute([$_POST['status'], $_POST['delivery_date'],  $_POST['phone'], $comment, $_POST['quanity'], $_POST['address'], $_POST['picked_date'], $_POST['time'], $order['id']]);
                }
            }
            else {
                if(empty($_POST['cooment'])) $comment = null;
                else $comment = $_POST['comment'];
                if(empty($_POST['delivery_date'])) {
                    $sql = "UPDATE ordersPickup SET status = ?, phone = ?, comment = ?, quanity = ?, predict_date = ? WHERE id = ?";
                    $stmt = $connect->prepare($sql);
                    $stmt->execute([$_POST['status'],  $_POST['phone'], $comment, $_POST['quanity'], $_POST['predict_date'], $order['id']]);
                }
                else {
                    $sql = "UPDATE ordersPickup SET status = ?, delivery_date = ?, phone = ?, comment = ?, quanity = ?, predict_date = ? WHERE id = ?";
                    $stmt = $connect->prepare($sql);
                    $stmt->execute([$_POST['status'], $_POST['delivery_date'],  $_POST['phone'], $comment, $_POST['quanity'], $_POST['predict_date'], $order['id']]);
                }
            }
            echo '<script>location.href="?page=managePanel&orders"</script>';
        }
    }
?>

<body class="editOrderPage">
    <script src="js/editOrder.js" defer></script>
    <!-- Шапка -->
    <?php include('pages/components/header.php'); ?>
    <!-- Конец шапки -->

    <main class="editOrder cont">
        <h1 class="title">Редактирование данных заказа <span><?= $order['id'] ?></span></h1>
        <form class="orderData" method="post">
            <div class="mainData">
                <h2>Товар: <span><?= $order['item_id'] ?></span> <?= $item['name'] ?></h2>
                <h2>Заказчик: <span><?= $order['user_id'] ?></span> <?= $user['name'] ?></h2>
                <h2>Стоимость заказа: <?= $order['quanity'] * $item['price'] ?>₽</h2>
                <h2>Дата заказа: <?= $order['date'] ?></h2>
                <?php if($orderType) { ?>
                <h2>Тип заказа: Доставка</h2>
                <?php } else { ?>
                <h2>Тип заказа: Самовывоз</h2>
                <?php } ?>
            </div>

            <div class="editData">
                <div class="dataField">
                    <label>Статус:</label>
                    <select class="selectStatus" name="status">
                        <?php if(isset($_POST['status'])) { ?>
                        <option value="<?= $_POST['status'] ?>"><?= $_POST['status'] ?></option>
                        <?php } else { ?>
                        <option selected><?= $order['status'] ?></option>
                        <?php  } ?>
                        <?php foreach($status as $s) {
                            if($s == $order['status'] or (isset($_POST['status']) and $_POST['status'] == $s)) continue; ?>
                            <option value="<?= $s ?>"><?= $s ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="dataField deliveryDate">
                    <label>Дата доставки:</label>
                    <?php if(isset($_POST['delivery_date'])) { ?>
                    <input type="date" name="delivery_date" value="<?= $_POST['delivery_date'] ?>">
                    <?php } elseif(!empty($order['delivery_date'])) { ?>
                    <input type="date" name="delivery_date" value="<?= $order['delivery_date'] ?>">
                    <?php } else { ?>
                    <input type="date" name="delivery_date">
                    <?php } if(isset($errors['delivery_date'])) { ?>
                    <h3><?= $errors['delivery_date'] ?></h3>
                    <?php } ?>
                </div>

                <div class="dataField">
                    <label>Номер телефона:</label>
                    <?php if(isset($_POST['phone'])) { ?>
                    <input type="text" name="phone" value="<?= $_POST['phone'] ?>" placeholder="8 (900)-000-00-00">
                    <?php } else { ?>
                    <input type="text" name="phone" value="<?= $order['phone'] ?>" placeholder="8 (900)-000-00-00">
                    <?php } ?>
                    <?php if(isset($errors['phone'])) { ?>
                    <h3><?= $errors['phone'] ?></h3>
                    <?php } ?>
                </div>

                <div class="dataField">
                    <label>Комментарий:</label>
                    <?php if(isset($_POST['comment'])) { ?>
                    <input type="text" name="comment" value="<?= $_POST['comment'] ?>" placeholder="Комментарий заказчика...">
                    <?php } else { ?>
                    <input type="text" name="comment" value="<?= $order['comment'] ?>" placeholder="Комментарий заказчика...">
                    <?php } ?>
                </div>

                <div class="dataField">
                    <label>Количество товара:</label>
                    <?php if(isset($_POST['quanity'])) { ?>
                    <input type="text" name="quanity" value="<?= $_POST['quanity'] ?>" placeholder="Введите количество...">
                    <?php } else { ?>
                    <input type="text" name="quanity" value="<?= $order['quanity'] ?>" placeholder="Введите количество...">
                    <?php } ?>
                    <?php if(isset($errors['quanity'])) { ?>
                    <h3><?= $errors['quanity'] ?></h3>
                    <?php } ?>
                </div>

                <?php if($orderType) { ?>
                <div class="dataField">
                    <label>Адрес:</label>
                    <?php if(isset($_POST['address'])) { ?>
                    <input type="text" name="address" value="<?= $_POST['address'] ?>" placeholder="Адрес заказчика...">
                    <?php } else { ?>
                    <input type="text" name="address" value="<?= $order['address'] ?>" placeholder="Адрес заказчика...">
                    <?php } ?>
                    <?php if(isset($errors['address'])) { ?>
                    <h3><?= $errors['address'] ?></h3>
                    <?php } ?>
                </div>

                <div class="dataField">
                    <label>Выбранная дата:</label>
                    <?php if(isset($_POST['picked_date'])) { ?>
                    <input type="date" name="picked_date" value="<?= $_POST['picked_date'] ?>">
                    <?php } else { ?>
                    <input type="date" name="picked_date" value="<?= $order['picked_date'] ?>">
                    <?php } ?>
                    <?php if(isset($errors['picked_date'])) { ?>
                    <h3><?= $errors['picked_date'] ?></h3>
                    <?php } ?>
                </div>

                <div class="dataField">
                    <label>Выбранное время:</label>
                    <select name="time">
                        <?php if(isset($_POST['time'])) { ?>
                        <option><?= $_POST['time'] ?></option>
                        <?php } else { ?>
                        <option selected><?= $order['time'] ?></option>
                        <?php  } ?>
                        <option>8:00-12:00</option>
                        <option>12:00-16:00</option>
                        <option>16:00-20:00</option>
                    </select>
                </div>
                <?php } else { ?>
                <div class="dataField">
                    <label>Адрес филиала:</label>
                    <select name="storeAddress">
                        <?php if(isset($_POST['storeAddress'])) { ?>
                        <option selected><?= $_POST['storeAddress'] ?></option>
                        <?php } else { ?>
                        <option selected><?= $order['storeAddress'] ?></option>
                        <?php  } ?>
                        <option>Казань, Баумана 36</option>
                        <option>Москва, Моховая 12</option>
                        <option>СПБ, Большая Пушкарская 9</option>
                    </select>
                </div>

                <div class="dataField">
                    <label>Примерная дата доставки:</label>
                    <?php if(isset($_POST['predict_date'])) { ?>
                    <input type="text" name="predict_date" value="<?= $_POST['predict_date'] ?>">
                    <?php } else { ?>
                    <input type="text" name="predict_date" value="<?= $order['predict_date'] ?>">
                    <?php } ?>
                    <?php if(isset($errors['predict_date'])) { ?>
                    <h3><?= $errors['predict_date'] ?></h3>
                    <?php } ?>
                </div>
                <?php } ?>
            </div>

            <div class="dataOptions">
                <button type="reset">Сбросить</button>
                <button type="submit" name="editData">Сохранить</button>
            </div>
        </form>
    </main>

    <!-- Подвал -->
    <?php include('pages/components/footerBlock.php'); ?>
    <!-- Конец подвала -->
</body>