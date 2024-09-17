<?php
include "../../../DB/Connection.php";
include "../shared/Delete.php";

$handler = new Delete();
$handler->deleteRecord('Invoice', 'inv_number', $_GET['inv_number'], '../../views/invoice/ilist.php');
