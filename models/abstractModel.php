<?php

namespace dash\models;

use dash\lib\database\databaseHandler;

class abstractModel
{
    const DATA_TYPE_BOOL = \PDO::PARAM_BOOL;
    const DATA_TYPE_STR = \PDO::PARAM_STR;
    const DATA_TYPE_INT = \PDO::PARAM_INT;
    const DATA_TYPE_DECIMAL = 4;
    const DATA_TYPE_DATE = \PDO::PARAM_STR;
    const DATA_TYPE_NULL = \PDO::PARAM_NULL;

    // Static property to hold the connection
    protected static $connection;

    // Method to get or initialize the connection
    protected static function getConnection()
    {
        if (self::$connection === null) {
            self::$connection = databaseHandler::factory();
        }
        return self::$connection;
    }

    // Method to close the connection
    protected static function closeConnection()
    {
        self::$connection = null;
    }

    // Centralized method to handle connection lifecycle
    protected static function executeWithConnection($callback)
    {
        $connection = self::getConnection(); // Ensure connection is initialized
        $result = $callback($connection);
        self::closeConnection();
        return $result;
    }

    private function prepareValues(\PDOStatement &$stmt)
    {
        foreach (static::$tableSchema as $columnName => $type) {
            if ($type == self::DATA_TYPE_DECIMAL) {
                $sanitizedValue = filter_var($this->$columnName, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                $stmt->bindValue(":{$columnName}", $sanitizedValue, $type);
            } else {
                $stmt->bindValue(":{$columnName}", $this->$columnName, $type);
            }
        }
    }

    private static function buildNameParametersSQL()
    {
        $namedParams = '';
        foreach (static::$tableSchema as $columnName => $type) {
            $namedParams .= $columnName . ' = :' . $columnName . ', ';
        }
        return trim($namedParams, ', ');
    }

    private function create()
    {
        $sql = 'INSERT INTO ' . static::$tableName . ' SET ' . self::buildNameParametersSQL();
        $stmt = self::getConnection()->prepare($sql);
        $this->prepareValues($stmt);
        $result = $stmt->execute();
        self::closeConnection();
        return $result;
    }

    private function update()
    {
        $sql = 'UPDATE ' . static::$tableName . ' SET ' . self::buildNameParametersSQL() . ' WHERE ' . static::$primaryKey . ' = ' . $this->{static::$primaryKey};
        $stmt = self::getConnection()->prepare($sql);
        $this->prepareValues($stmt);
        $result = $stmt->execute();
        self::closeConnection();
        return $result;
    }

    public function save()
    {
        $result = $this->{static::$primaryKey} === null ? $this->create() : $this->update();
        self::closeConnection();
        return $result;
    }

    public function delete()
    {
        $sql = 'DELETE FROM ' . static::$tableName . ' WHERE ' . static::$primaryKey . ' = ' . $this->{static::$primaryKey};
        $stmt = self::getConnection()->prepare($sql);
        $result = $stmt->execute();
        self::closeConnection();
        return $result;
    }

    public static function getAll()
    {
        return self::executeWithConnection(function ($connection) {
            $sql = 'SELECT * FROM ' . static::$tableName;
            $stmt = $connection->prepare($sql);
            $stmt->execute();
            $results = $stmt->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, get_called_class(), array_keys(static::$tableSchema));
            return (is_array($results) && !empty($results)) ? $results : false;
        });
    }

    public static function getByPK($pk)
    {
        return self::executeWithConnection(function ($connection) use ($pk) {
            $sql = 'SELECT * FROM ' . static::$tableName . ' WHERE ' . static::$primaryKey . ' = "' . $pk . '"';
            $stmt = $connection->prepare($sql);
            if ($stmt->execute() === true) {
                $obj = $stmt->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, get_called_class(), array_keys(static::$tableSchema));
                return array_shift($obj);
            }
            return false;
        });
    }

    public static function get($sql, $options = array())
    {
        return self::executeWithConnection(function ($connection) use ($sql, $options) {
            $stmt = $connection->prepare($sql);
            if (!empty($options)) {
                foreach ($options as $columnName => $type) {
                    if ($type[0] == 4) {
                        $sanitizedValue = filter_var($type[1], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
                        $stmt->bindValue(":{$columnName}", $sanitizedValue, $type[0]);
                    } else {
                        $stmt->bindValue(":{$columnName}", $type[1], $type[0]);
                    }
                }
            }
            $stmt->execute();
            $results = $stmt->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, get_called_class(), array_keys(static::$tableSchema));
            return (is_array($results) && !empty($results)) ? $results : false;
        });
    }
}
