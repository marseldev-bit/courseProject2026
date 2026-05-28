<?php
    try { $connect = new PDO ('mysql:host=localhost;dbname=King', 'root',''); }
    catch(PDOException $error) { echo $error; }
?>