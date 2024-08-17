<?php
include_once "product.php";
class productRetrieve extends product
{
    public function getProduct($id)
    {
        $sql = "SELECT * FROM `product` WHERE `pro_id` = ? LIMIT 1";
        $params = [$id];
        $types = "i";

        return $this->fetchResults($sql, $params, $types);
    }
}
