<?php
$connect = mysqli_connect("localhost", "root", "", "mmit_admission");
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}
?>