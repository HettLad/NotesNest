<?php
    $id = $_REQUEST["note_id"];
    $conn = new mysqli("localhost","root","","notesnest_db");
    $qry0 = "DELETE FROM tbl_rating WHERE note_id = '$id'";
    $qry1 = "DELETE FROM tbl_note_tags WHERE note_id = $id";
    $qry2 = "DELETE FROM tbl_notes WHERE note_id = $id";
    $conn->query($qry0);
    $conn->query($qry1);
    $res = $conn->query($qry2);
    echo $qry . "<br>";
    echo $id;

    if($res){
        header("location: ./user_Profile.php");
    }
?>