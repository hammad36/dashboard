<?php
include "../../../DB/Connection.php";
include "../../classes/shared/Delete.php";

$handler = new Delete('localhost', 'hammad', 'My@2530', 'dash');
$handler->deleteRecord('Invoice', 'inv_number', $_GET['inv_number'], 'ilist.php');
