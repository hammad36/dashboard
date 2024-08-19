<?php
include "../../../DB/Connection.php";
include "../../classes/shared/Delete.php";

$handler = new Delete();
$handler->deleteRecord('product', 'pro_id', $_GET['id'], 'plist.php');
