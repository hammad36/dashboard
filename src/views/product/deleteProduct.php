<?php
include "../../../DB/Connection.php";
include "../../classes/shared/Delete.php";

$handler = new Delete();
$handler->deleteRecord('Product', 'pro_id', $_GET['id'], 'plist.php');
