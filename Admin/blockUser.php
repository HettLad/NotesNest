<?php
    $user_id = $_REQUEST["user_id"];
    $conn = new mysqli("localhost", "root", "", "notesnest_db");
    $query = "UPDATE tbl_user SET isBlocked = 1 WHERE user_id = $user_id";
    $conn->query($query);
    header('location: userManagment.php');
?>