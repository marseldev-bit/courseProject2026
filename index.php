<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/images/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="pages/style.css">
    <script src="js/header.js" defer></script>
    <script src="js/order.js" defer></script>
    <script src="js/profile.js" defer></script>
    <script src="js/managePanel.js" defer></script>
    <script src="js/main.js" defer></script>
    <title>Кинг</title>
</head>

<?php
    session_start();
    include('php/connectDB.php');
    include('php/loginControl.php');
    if(isset($_GET['page'])) {
        if(isset($_SESSION['userID'])) {
            if($_GET['page'] == 'profile') include('pages/user/profile.php');
            elseif($_GET['page'] == 'managePanel' and $USER['isAdmin']) include('pages/admin/managePanel.php');
        }
        elseif(!isset($_SESSION['userID'])) {
            if($_GET['page'] == 'reg') include('pages/user/reg.php');
            elseif($_GET['page'] == 'login') include('pages/user/login.php');
        }
        
        elseif($_GET['page'] == 'order') include('pages/order/order.php');
        if($_GET['page'] == 'catalog') include('pages/items/catalog.php');
        elseif($_GET['page'] == 'category') include('pages/items/category.php');
        elseif($_GET['page'] == 'item') include('pages/items/item.php');
        if($_GET['page'] == 'editItem') include('pages/admin/editItem.php');
        if($_GET['page'] == 'editPromotion') include('pages/admin/editPromotion.php');
        if($_GET['page'] == 'createPromotion') include('pages/admin/createPromotion.php');
        if($_GET['page'] == 'createItem') include('pages/admin/createItem.php');
    }
    else include('pages/components/main.php');
?>

</html>