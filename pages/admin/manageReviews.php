<?php
    $sql = "SELECT * FROM reviews";
    $stmt = $connect->prepare($sql);
    $stmt->execute([]);
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $moderate = [];
    $accepted = [];
    $canceled = [];

    if(isset($_POST['search']) and (!empty($_POST['itemId']) or !empty($_POST['userId']))) {
        if(!empty($_POST['itemId']) and empty($_POST['userId'])) {
            foreach($reviews as $review) {
                if($review['item_id'] == $_POST['itemId']) {
                    if($review['status'] == 'На модерации') array_push($moderate, $review);
                    elseif($review['status'] == 'Принят') array_push($accepted, $review);
                    elseif($review['status'] == 'Отклонен') array_push($canceled, $review);
                }
            }
        }
        elseif(!empty($_POST['itemId']) and !empty($_POST['userId'])) {
            foreach($reviews as $review) {
                if($review['user_id'] == $_POST['userId'] and $review['item_id'] == $_POST['itemId']) {
                    if($review['status'] == 'На модерации') array_push($moderate, $review);
                    elseif($review['status'] == 'Принят') array_push($accepted, $review);
                    elseif($review['status'] == 'Отклонен') array_push($canceled, $review);
                }
            }
        }
        elseif(empty($_POST['itemId']) and !empty($_POST['userId'])) {
            foreach($reviews as $review) {
                if($review['user_id'] == $_POST['userId']) {
                    if($review['status'] == 'На модерации') array_push($moderate, $review);
                    elseif($review['status'] == 'Принят') array_push($accepted, $review);
                    elseif($review['status'] == 'Отклонен') array_push($canceled, $review);
                }
            }
        }
    }
    else {
        foreach($reviews as $review) {
            if($review['status'] == 'На модерации') array_push($moderate, $review);
            elseif($review['status'] == 'Принят') array_push($accepted, $review);
            elseif($review['status'] == 'Отклонен') array_push($canceled, $review);
        }
    }

    if(isset($_POST['setModeration'])) {
        $sql = "UPDATE reviews SET status = ? WHERE id = ?";
        $stmt = $connect->prepare($sql);
        $stmt->execute(['На модерации', $_GET['moderation']]);
        echo '<script>location.href="?page=managePanel&reviews"</script>';
    }

    if(isset($_POST['setAccepted'])) {
        $sql = "UPDATE reviews SET status = ? WHERE id = ?";
        $stmt = $connect->prepare($sql);
        $stmt->execute(['Принят', $_GET['accept']]);
        echo '<script>location.href="?page=managePanel&reviews"</script>';
    }

    if(isset($_POST['setCanceled'])) {
        $sql = "UPDATE reviews SET status = ? WHERE id = ?";
        $stmt = $connect->prepare($sql);
        $stmt->execute(['Отклонен', $_GET['cancel']]);
        echo '<script>location.href="?page=managePanel&reviews"</script>';
    }
?>

<div class="manageReviews cont manageSection">
    <div class="catalogHeader">
        <form class="filters" method="post">
            <div class="search">
                <input name="itemId" type="text" placeholder="ID товара..."
                <?php if(!empty($_POST['Id'])) { ?>value="<?= $_POST['Id'] ?>"<?php } ?>>
            </div>

            <div class="search">
                <input name="userId" type="number" placeholder="ID пользователя..."
                <?php if(!empty($_POST['userId'])) { ?>value="<?= $_POST['userId'] ?>"<?php } ?>>
            </div>

            <button name="search">Применить</button>
        </form>
    </div>

    <?php if(empty($moderate) and empty($accepted) and empty($canceled)) { ?>
    <h2 class="cont emptyReviews">Отзывов нет</h2>
    <?php } else {
        if(!empty($moderate)) { ?>
    <div class="userReviewsBlock">
        <p>На модерации</p>
        <div class="userReviewsBody">
            <?php $count = 0;
            foreach($moderate as $m) {
                $sql = "SELECT * FROM items WHERE id = ?";
                $stmt = $connect->prepare($sql);
                $stmt->execute([$m['item_id']]);
                $item = $stmt->fetch(PDO::FETCH_ASSOC);

                $sql = "SELECT imgPath FROM itemGallery WHERE item_id = ?";
                $stmt = $connect->prepare($sql);
                $stmt->execute([$m['item_id']]);
                $img = $stmt->fetch(PDO::FETCH_ASSOC);

                $sql = "SELECT * FROM users WHERE id = ?";
                $stmt = $connect->prepare($sql);
                $stmt->execute([$m['user_id']]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if($count > 0) { ?>
                <div class="separateLine"></div>
                <?php } ?>
            <div class="userReview">
                <div class="userReviewBody">
                    <div class="userReviewBodyBlock">
                        <img src="<?= $img['imgPath'] ?>" alt="">
                        <div class="userReviewName">
                            <h1><span class="id"><?= $item['id'] ?></span><?= $item['name'] ?></h1>
                            
                            <h1>Отзыв от: <span class="id"><?= $user['id'] ?></span><?= $user['name'] ?></h1>
                        </div>
                    </div>
                    <div class="userReviewRating">
                        <div class="userReviewRate">
                                <h3><?= $m['rate'] ?></h3>
                                <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M15.1614 6.96023C15.2304 6.61566 14.9547 6.20219 14.6101 6.20219L10.6821 5.65089L8.89039 2.06743C8.82147 1.9296 8.75256 1.86069 8.61474 1.79178C8.27017 1.58504 7.8567 1.72286 7.64996 2.06743L5.92714 5.65089L1.99912 6.20219C1.79238 6.20219 1.65456 6.2711 1.58564 6.40893C1.30999 6.68458 1.30999 7.09805 1.58564 7.3737L4.41106 10.1302L3.72194 14.0582C3.72194 14.1961 3.72194 14.3339 3.79085 14.4717C3.99759 14.8163 4.41106 14.9541 4.75563 14.7474L8.27017 12.8867L11.7847 14.7474C11.8536 14.8163 11.9915 14.8163 12.1293 14.8163H12.2671C12.6117 14.7474 12.8873 14.4028 12.8184 13.9893L12.1293 10.0613L14.9547 7.30479C15.0925 7.23588 15.1614 7.09805 15.1614 6.96023Z" fill="#FFF8E4"/>
                                </svg>
                            </div>
                        <h2>Текст отзыва:</h2>
                    </div>
                    <p><?= $m['text'] ?></p>
                </div>

                <div class="userReviewOptions">
                    <a href="?page=managePanel&reviews&accept=<?= $m['id'] ?>">Принять</a>
                    <a class="cancel" href="?page=managePanel&reviews&cancel=<?= $m['id'] ?>">Отклонить</a>
                </div>
            </div>

            <div class="reviewMobile">
                <div class="reviewMobileHeader">
                    <img src="<?= $img['imgPath'] ?>" alt="">
                    <h1><span class="id"><?= $item['id'] ?></span><?= $item['name'] ?></h1>
                </div>
                <div class="reviewMobileAuthor">
                    <h2>Автор:</h2>
                    <h1><span class="id"><?= $user['id'] ?></span><?= $user['name'] ?></h1>
                </div>
                <div class="reviewMobileRate">
                    <h2>Оценка:</h2>
                    <div class="userReviewRate">
                                <h3><?= $m['rate'] ?></h3>
                                <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M15.1614 6.96023C15.2304 6.61566 14.9547 6.20219 14.6101 6.20219L10.6821 5.65089L8.89039 2.06743C8.82147 1.9296 8.75256 1.86069 8.61474 1.79178C8.27017 1.58504 7.8567 1.72286 7.64996 2.06743L5.92714 5.65089L1.99912 6.20219C1.79238 6.20219 1.65456 6.2711 1.58564 6.40893C1.30999 6.68458 1.30999 7.09805 1.58564 7.3737L4.41106 10.1302L3.72194 14.0582C3.72194 14.1961 3.72194 14.3339 3.79085 14.4717C3.99759 14.8163 4.41106 14.9541 4.75563 14.7474L8.27017 12.8867L11.7847 14.7474C11.8536 14.8163 11.9915 14.8163 12.1293 14.8163H12.2671C12.6117 14.7474 12.8873 14.4028 12.8184 13.9893L12.1293 10.0613L14.9547 7.30479C15.0925 7.23588 15.1614 7.09805 15.1614 6.96023Z" fill="#FFF8E4"/>
                                </svg>

                    </div>
                </div>
                <div class="reviewMobileDescription">
                    <h2>Текст отзыва:</h2>
                    <p><?= $m['text'] ?></p>
                </div>
                <div class="userReviewOptions">
                    <a href="?page=managePanel&reviews&accept=<?= $m['id'] ?>">Принять</a>
                    <a class="cancel" href="?page=managePanel&reviews&cancel=<?= $m['id'] ?>">Отклонить</a>
                </div>
            </div>
            <?php $count += 1; } ?>
        </div>
    </div>
    <?php } 
    
    if(!empty($accepted)) { ?>
    <div class="userReviewsBlock">
        <p>Подтвержденные</p>
        <div class="userReviewsBody">
            <?php $count = 0;
            foreach($accepted as $a) {
                $sql = "SELECT * FROM items WHERE id = ?";
                $stmt = $connect->prepare($sql);
                $stmt->execute([$a['item_id']]);
                $item = $stmt->fetch(PDO::FETCH_ASSOC);

                $sql = "SELECT imgPath FROM itemGallery WHERE item_id = ?";
                $stmt = $connect->prepare($sql);
                $stmt->execute([$a['item_id']]);
                $img = $stmt->fetch(PDO::FETCH_ASSOC);

                $sql = "SELECT * FROM users WHERE id = ?";
                $stmt = $connect->prepare($sql);
                $stmt->execute([$a['user_id']]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if($count > 0) { ?>
                <div class="separateLine"></div>
                <?php } ?>
            <div class="userReview">
                <div class="userReviewBody">
                    <div class="userReviewBodyBlock">
                        <img src="<?= $img['imgPath'] ?>" alt="">
                        <div class="userReviewName">
                            <h1><span class="id"><?= $item['id'] ?></span><?= $item['name'] ?></h1>
                            
                            <h1>Отзыв от: <span class="id"><?= $user['id'] ?></span><?= $user['name'] ?></h1>
                        </div>
                    </div>
                    <div class="userReviewRating">
                        <div class="userReviewRate">
                                <h3><?= $a['rate'] ?></h3>
                                <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M15.1614 6.96023C15.2304 6.61566 14.9547 6.20219 14.6101 6.20219L10.6821 5.65089L8.89039 2.06743C8.82147 1.9296 8.75256 1.86069 8.61474 1.79178C8.27017 1.58504 7.8567 1.72286 7.64996 2.06743L5.92714 5.65089L1.99912 6.20219C1.79238 6.20219 1.65456 6.2711 1.58564 6.40893C1.30999 6.68458 1.30999 7.09805 1.58564 7.3737L4.41106 10.1302L3.72194 14.0582C3.72194 14.1961 3.72194 14.3339 3.79085 14.4717C3.99759 14.8163 4.41106 14.9541 4.75563 14.7474L8.27017 12.8867L11.7847 14.7474C11.8536 14.8163 11.9915 14.8163 12.1293 14.8163H12.2671C12.6117 14.7474 12.8873 14.4028 12.8184 13.9893L12.1293 10.0613L14.9547 7.30479C15.0925 7.23588 15.1614 7.09805 15.1614 6.96023Z" fill="#FFF8E4"/>
                                </svg>
                            </div>
                        <h2>Текст отзыва:</h2>
                    </div>
                    <p><?= $a['text'] ?></p>
                </div>

                <div class="userReviewOptions">
                    <a href="?page=managePanel&reviews&moderation=<?= $a['id'] ?>">Вернуть на модерацию</a>
                    <a class="cancel" href="?page=managePanel&reviews&cancel=<?= $a['id'] ?>">Отклонить</a>
                </div>
            </div>

            <div class="reviewMobile">
                <div class="reviewMobileHeader">
                    <img src="<?= $img['imgPath'] ?>" alt="">
                    <h1><span class="id"><?= $item['id'] ?></span><?= $item['name'] ?></h1>
                </div>
                <div class="reviewMobileAuthor">
                    <h2>Автор:</h2>
                    <h1><span class="id"><?= $user['id'] ?></span><?= $user['id'] ?></h1>
                </div>
                <div class="reviewMobileRate">
                    <h2>Оценка:</h2>
                    <div class="userReviewRate">
                                <h3><?= $a['rate'] ?></h3>
                                <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M15.1614 6.96023C15.2304 6.61566 14.9547 6.20219 14.6101 6.20219L10.6821 5.65089L8.89039 2.06743C8.82147 1.9296 8.75256 1.86069 8.61474 1.79178C8.27017 1.58504 7.8567 1.72286 7.64996 2.06743L5.92714 5.65089L1.99912 6.20219C1.79238 6.20219 1.65456 6.2711 1.58564 6.40893C1.30999 6.68458 1.30999 7.09805 1.58564 7.3737L4.41106 10.1302L3.72194 14.0582C3.72194 14.1961 3.72194 14.3339 3.79085 14.4717C3.99759 14.8163 4.41106 14.9541 4.75563 14.7474L8.27017 12.8867L11.7847 14.7474C11.8536 14.8163 11.9915 14.8163 12.1293 14.8163H12.2671C12.6117 14.7474 12.8873 14.4028 12.8184 13.9893L12.1293 10.0613L14.9547 7.30479C15.0925 7.23588 15.1614 7.09805 15.1614 6.96023Z" fill="#FFF8E4"/>
                                </svg>

                    </div>
                </div>
                <div class="reviewMobileDescription">
                    <h2>Текст отзыва:</h2>
                    <p><?= $a['text'] ?></p>
                </div>
                <div class="userReviewOptions">
                    <a href="?page=managePanel&reviews&moderation=<?= $a['id'] ?>">Вернуть на модерацию</a>
                    <a class="cancel" href="?page=managePanel&reviews&cancel=<?= $a['id'] ?>">Отклонить</a>
                </div>
            </div>
            <?php $count += 1; } ?>
        </div>
    </div>
    <?php }

    if(!empty($canceled)) { ?>
    <div class="userReviewsBlock">
        <p>Отклоненные</p>
        <div class="userReviewsBody">
            <?php $count = 0;
            foreach($canceled as $c) {
                $sql = "SELECT * FROM items WHERE id = ?";
                $stmt = $connect->prepare($sql);
                $stmt->execute([$c['item_id']]);
                $item = $stmt->fetch(PDO::FETCH_ASSOC);

                $sql = "SELECT imgPath FROM itemGallery WHERE item_id = ?";
                $stmt = $connect->prepare($sql);
                $stmt->execute([$c['item_id']]);
                $img = $stmt->fetch(PDO::FETCH_ASSOC);

                $sql = "SELECT * FROM users WHERE id = ?";
                $stmt = $connect->prepare($sql);
                $stmt->execute([$c['user_id']]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if($count > 0) { ?>
                <div class="separateLine"></div>
                <?php } ?>
            <div class="userReview">
                <div class="userReviewBody">
                    <div class="userReviewBodyBlock">
                        <img src="<?= $img['imgPath'] ?>" alt="">
                        <div class="userReviewName">
                            <h1><span class="id"><?= $item['id'] ?></span><?= $item['name'] ?></h1>
                            
                            <h1>Отзыв от: <span class="id"><?= $user['id'] ?></span><?= $user['name'] ?></h1>
                        </div>
                    </div>
                    <div class="userReviewRating">
                        <div class="userReviewRate">
                                <h3><?= $c['rate'] ?></h3>
                                <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M15.1614 6.96023C15.2304 6.61566 14.9547 6.20219 14.6101 6.20219L10.6821 5.65089L8.89039 2.06743C8.82147 1.9296 8.75256 1.86069 8.61474 1.79178C8.27017 1.58504 7.8567 1.72286 7.64996 2.06743L5.92714 5.65089L1.99912 6.20219C1.79238 6.20219 1.65456 6.2711 1.58564 6.40893C1.30999 6.68458 1.30999 7.09805 1.58564 7.3737L4.41106 10.1302L3.72194 14.0582C3.72194 14.1961 3.72194 14.3339 3.79085 14.4717C3.99759 14.8163 4.41106 14.9541 4.75563 14.7474L8.27017 12.8867L11.7847 14.7474C11.8536 14.8163 11.9915 14.8163 12.1293 14.8163H12.2671C12.6117 14.7474 12.8873 14.4028 12.8184 13.9893L12.1293 10.0613L14.9547 7.30479C15.0925 7.23588 15.1614 7.09805 15.1614 6.96023Z" fill="#FFF8E4"/>
                                </svg>
                            </div>
                        <h2>Текст отзыва:</h2>
                    </div>
                    <p><?= $c['text'] ?></p>
                </div>

                <div class="userReviewOptions">
                    <a href="?page=managePanel&reviews&moderation=<?= $c['id'] ?>">Вернуть на модерацию</a>
                    <a href="?page=managePanel&reviews&accept=<?= $c['id'] ?>">Принять</a>
                </div>
            </div>

            <div class="reviewMobile">
                <div class="reviewMobileHeader">
                    <img src="<?= $img['imgPath'] ?>" alt="">
                    <h1><span class="id"><?= $item['id'] ?></span><?= $item['name'] ?></h1>
                </div>
                <div class="reviewMobileAuthor">
                    <h2>Автор:</h2>
                    <h1><span class="id"><?= $user['id'] ?></span><?= $user['id'] ?></h1>
                </div>
                <div class="reviewMobileRate">
                    <h2>Оценка:</h2>
                    <div class="userReviewRate">
                                <h3><?= $c['rate'] ?></h3>
                                <svg width="17" height="17" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M15.1614 6.96023C15.2304 6.61566 14.9547 6.20219 14.6101 6.20219L10.6821 5.65089L8.89039 2.06743C8.82147 1.9296 8.75256 1.86069 8.61474 1.79178C8.27017 1.58504 7.8567 1.72286 7.64996 2.06743L5.92714 5.65089L1.99912 6.20219C1.79238 6.20219 1.65456 6.2711 1.58564 6.40893C1.30999 6.68458 1.30999 7.09805 1.58564 7.3737L4.41106 10.1302L3.72194 14.0582C3.72194 14.1961 3.72194 14.3339 3.79085 14.4717C3.99759 14.8163 4.41106 14.9541 4.75563 14.7474L8.27017 12.8867L11.7847 14.7474C11.8536 14.8163 11.9915 14.8163 12.1293 14.8163H12.2671C12.6117 14.7474 12.8873 14.4028 12.8184 13.9893L12.1293 10.0613L14.9547 7.30479C15.0925 7.23588 15.1614 7.09805 15.1614 6.96023Z" fill="#FFF8E4"/>
                                </svg>

                    </div>
                </div>
                <div class="reviewMobileDescription">
                    <h2>Текст отзыва:</h2>
                    <p><?= $c['text'] ?></p>
                </div>
                <div class="userReviewOptions">
                    <a href="?page=managePanel&reviews&moderation=<?= $c['id'] ?>">Вернуть на модерацию</a>
                    <a href="?page=managePanel&reviews&accept=<?= $c['id'] ?>">Принять</a>
                </div>
            </div>
            <?php $count += 1; } ?>
        </div>
    </div>
    <?php } } ?>
</div>

<?php if(isset($_GET['moderation'])) { ?>
<div class="modalExit active auth reviewStatus">
        <form method="post" class="modalExitWindow">
            <a href="?page=managePanel&reviews"><p>⨉</p></a>
            <div class="modalExitBody">
                <h1>Комментарий на модерацию</h1>
                <h2>Подтвердите возвращение комментария <span>ID: <?= $_GET['moderation'] ?></span> на модерацию</h2>
                <div class="modalExitOptions">
                    <a href="?page=managePanel&reviews">Отмена</a>
                    <button name="setModeration">Подтвердить</button>
                </div>
            </div>
        </form>
    </div>
<?php } ?>

<?php if(isset($_GET['accept'])) { ?>
<div class="modalExit active auth reviewStatus">
        <form method="post" class="modalExitWindow">
            <a href="?page=managePanel&reviews"><p>⨉</p></a>
            <div class="modalExitBody">
                <h1>Подтверждение комментария</h1>
                <h2>Подтвердите публикацию комментария <span>ID: <?= $_GET['accept'] ?></span></h2>
                <div class="modalExitOptions">
                    <a href="?page=managePanel&reviews">Отмена</a>
                    <button name="setAccepted">Подтвердить</button>
                </div>
            </div>
        </form>
    </div>
<?php } ?>

<?php if(isset($_GET['cancel'])) { ?>
<div class="modalExit active auth reviewStatus">
        <form method="post" class="modalExitWindow">
            <a href="?page=managePanel&reviews"><p>⨉</p></a>
            <div class="modalExitBody">
                <h1>Отклонение комментария</h1>
                <h2>Подтвердите отклонение комментария <span>ID: <?= $_GET['cancel'] ?></span></h2>
                <div class="modalExitOptions">
                    <a href="?page=managePanel&reviews">Отмена</a>
                    <button name="setCanceled">Подтвердить</button>
                </div>
            </div>
        </form>
    </div>
<?php } ?>