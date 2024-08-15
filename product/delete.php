<?php
include "../DB/Connection.php";
$dbConnection = Connection::getInstance('localhost', 'hammad', 'My@2530', 'dash');
$conn = $dbConnection->getConnection();

$id = intval($_GET['id']);
$sql = "DELETE FROM `product` WHERE `pro_id` = $id";
$result = mysqli_query($conn, $sql);
if ($result) {
    header("Location: plist.php?remove=Record deleted successfully");
    exit();
} else {
    echo "Failed: " . mysqli_error($conn);
}
