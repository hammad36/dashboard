<?php
include "../../../DB/Connection.php";
include "../shared/Delete.php";

$handler = new Delete();
$handler->deleteRecord('Product', 'pro_id', $_GET['id'], '../../views/product/plist.php');
