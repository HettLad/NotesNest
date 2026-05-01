<?php
    $note_id = $_REQUEST["note_id"];
    $conn = new mysqli("localhost", "root", "", "notesnest_db");
    $query = "UPDATE tbl_notes SET isApproved = 1 WHERE note_id = $note_id";
    $conn->query($query);
    header('location: contentManagment.php');
?>