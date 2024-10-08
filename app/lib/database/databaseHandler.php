<?php

abstract class databaseHandler
{
    const DATABASE_DRIVER_PDO = 1;
    const DATABASE_DRIVER_MYSQLI = 2;

    private function __construct() {}

    abstract protected static function init();

    abstract protected static function getInstance();

    public static function factory() {}
}
