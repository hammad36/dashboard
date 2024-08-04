
<?php
include("../conn.php");

$id = intval($_GET['inv_number']);
$sql = "DELETE FROM `Invoice` WHERE `inv_number` = $id";
$result = mysqli_query($conn, $sql);
if ($result) {
    header("Location: ilist.php?remove=Record deleted successfully");
    exit();
} else {
    echo "Failed: " . mysqli_error($conn);
}
