<?php
    $sql = "SELECT * FROM promotions";
    $stmt = $connect->prepare($sql);
    $stmt->execute([]);
    $promotions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if(isset($_POST['deletePromotion'])) {
        $sql = "SELECT imgPath FROM promotions WHERE id = ?";
        $stmt = $connect->prepare($sql);
        $stmt->execute([$_POST['deletePromotionId']]);
        $imgPath = $stmt->fetch(PDO::FETCH_ASSOC);

        $path = $_SERVER['DOCUMENT_ROOT'].'/'.$imgPath['imgPath'];
        if(file_exists($path)) unlink($path);

        $sql = "DELETE FROM promotions WHERE id = ?";
        $stmt = $connect->prepare($sql);
        $stmt->execute([$_POST['deletePromotionId']]);
        echo '<script>location.href="?page=managePanel&promotions"</script>';
    }
?>

<div class="managePromotions cont manageSection">
    <div class="catalogHeader">
                <form class="filters" method="post">
                    <div class="search">
                        <input type="text" placeholder="Название акции..." name="promName"
                        <?php if(isset($_POST['promName'])) { ?>value="<?= $_POST['promName'] ?>"<?php } ?>>
                        <button name="searchPromotion"><img src="../assets/images/search.png"></button>
                    </div>

                    <div class="search">
                        <input type="number" placeholder="ID акции..." name="promId"
                        <?php if(isset($_POST['promId'])) { ?>value="<?= $_POST['promId'] ?>"<?php } ?>>
                        <button name="searchPromotion"><img src="../assets/images/search.png"></button>
                    </div>

                    <a href="?page=createPromotion">Добавить акцию</a>
                </form>
            </div>

    <div class="managePromotionsBlock">
        <?php foreach($promotions as $prom) {
            if(isset($_POST['searchPromotion'])) {
                if(!empty($_POST['promName']) and strpos(mb_strtolower(trim($prom['title'])), mb_strtolower(trim($_POST['promName']))) === false) continue;
                if(!empty($_POST['promId']) and $_POST['promId'] != $prom['id']) continue;
            } ?>
        <div class="managePromotion">
            <div class="prom">
                    <img src="<?= $prom['imgPath'] ?>" alt="Акция">
                    <div class="promText">
                        <div class="promInfo">
                            <h1><?= $prom['title'] ?></h1>
                            <p><?= $prom['description'] ?></p>
                        </div>
                        <h2>до <?= $prom['date'] ?></h2>
                    </div>
                </div>
            <div class="promOptions">
                <a href="?page=editPromotion&id=<?= $prom['id'] ?>">Редактировать</a>
                <a href="?page=managePanel&promotions&deletePromotion=<?= $prom['id'] ?>">Удалить</a>
            </div>
        </div>
        <?php } ?>
    </div>
</div>

<?php if(isset($_GET['deletePromotion'])) { 
    $sql = "SELECT * FROM promotions WHERE id = ?";
    $stmt = $connect->prepare($sql);
    $stmt->execute([$_GET['deletePromotion']]);
    $promName = $stmt->fetch(PDO::FETCH_ASSOC); ?>
<div class="modalExit active deleteCategory">
        <form method="post" class="modalExitWindow">
            <a href="?page=managePanel&promotions"><p>⨉</p></a>
            <div class="modalExitBody">
                <h1>Удалить акцию "<?= $promName['title'] ?>"?</h1>
                <div class="modalExitOptions">
                    <a href="?page=managePanel&promotions">Отмена</a>
                    <input type="hidden" value="<?= $_GET['deletePromotion'] ?>" name="deletePromotionId">
                    <button name="deletePromotion">Удалить</button>
                </div>
            </div>
        </form>
    </div>
<?php } ?>