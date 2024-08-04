<?php
include("../conn.php");

$id = intval($_GET['id']);
$sql = "DELETE FROM `product` WHERE `pro_id` = $id";
$result = mysqli_query($conn, $sql);
if ($result) {
    header("Location: plist.php?remove=Record deleted successfully");
    exit();
} else {
    echo "Failed: " . mysqli_error($conn);
}
