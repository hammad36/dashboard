<?php
include "../../../DB/Connection.php";
include "../../classes/shared/Delete.php";

$handler = new Delete('localhost', 'hammad', 'My@2530', 'dash');
$handler->deleteRecord('product', 'pro_id', $_GET['id'], 'plist.php');
