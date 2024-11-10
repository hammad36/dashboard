<?php

namespace dash\models;

use dash\lib\database\databaseHandler;

abstract class abstractModel
{
    const DATA_TYPE_BOOL    = \PDO::PARAM_BOOL;
    const DATA_TYPE_STR     = \PDO::PARAM_STR;
    const DATA_TYPE_INT     = \PDO::PARAM_INT;
    const DATA_TYPE_DECIMAL = 4;
    const DATA_TYPE_DATE    = \PDO::PARAM_STR;
    const DATA_TYPE_NULL    = \PDO::PARAM_NULL;

    protected static $connection;

    protected static function getConnection()
    {
        if (self::$connection === null) {
            self::$connection = databaseHandler::factory();
        }
        return self::$connection;
    }

    protected static function closeConnection()
    {
        self::$connection = null;
    }

    protected static function executeWithConnection($callback)
    {
        $connection = self::getConnection();
        $result = $callback($connection);
        self::closeConnection();
        return $result;
    }

    protected function bindValues(\PDOStatement &$stmt)
    {
        foreach (static::$tableSchema as $columnName => $type) {
            $value = $this->$columnName;

            if ($type === self::DATA_TYPE_DECIMAL) {
                $value = filter_var($value, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            }

            $stmt->bindValue(":{$columnName}", $value, $type);
        }
    }

    protected static function buildNamedParameters()
    {
        return implode(', ', array_map(fn($col) => "$col = :$col", array_keys(static::$tableSchema)));
    }

    protected function executeSaveQuery($sql)
    {
        $stmt = self::getConnection()->prepare($sql);
        $this->bindValues($stmt);
        $stmt->execute();
        return $stmt;
    }

    protected function create()
    {
        $sql = 'INSERT INTO ' . static::$tableName . ' SET ' . self::buildNamedParameters();
        $stmt = $this->executeSaveQuery($sql);

        if ($stmt->rowCount() > 0) {
            $this->{static::$primaryKey} = self::getConnection()->lastInsertId();
            return true;
        }

        return false;
    }

    protected function update()
    {
        $sql = 'UPDATE ' . static::$tableName . ' SET ' . self::buildNamedParameters() . ' WHERE ' . static::$primaryKey . ' = :' . static::$primaryKey;
        $stmt = self::getConnection()->prepare($sql);
        $this->bindValues($stmt);
        $stmt->bindValue(':' . static::$primaryKey, $this->{static::$primaryKey}, self::DATA_TYPE_INT);
        return $stmt->execute();
    }

    public function save()
    {
        return $this->{static::$primaryKey} === null ? $this->create() : $this->update();
    }

    public function delete()
    {
        $sql = 'DELETE FROM ' . static::$tableName . ' WHERE ' . static::$primaryKey . ' = :' . static::$primaryKey;
        $stmt = self::getConnection()->prepare($sql);
        $stmt->bindValue(':' . static::$primaryKey, $this->{static::$primaryKey}, self::DATA_TYPE_INT);
        return $stmt->execute();
    }

    public static function getAll()
    {
        return self::executeWithConnection(function ($connection) {
            $sql = 'SELECT * FROM ' . static::$tableName;
            $stmt = $connection->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, get_called_class());
        });
    }


    public static function getByPK($pk)
    {
        return self::executeWithConnection(function ($connection) use ($pk) {
            $sql = 'SELECT * FROM ' . static::$tableName . ' WHERE ' . static::$primaryKey . ' = :pk';
            $stmt = $connection->prepare($sql);
            $stmt->bindValue(':pk', $pk, self::DATA_TYPE_INT);
            if ($stmt->execute() === true) {
                if (method_exists(get_called_class(), '__construct')) {
                    $obj = $stmt->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, get_called_class(), array_keys(static::$tableSchema));
                } else {
                    $obj = $stmt->fetchAll(\PDO::FETCH_CLASS, get_called_class());
                }
                return !empty($obj) ? array_shift($obj) : false;
            }
            return false;
        });
    }

    public static function get($sql, $params = [])
    {
        return self::executeWithConnection(function ($connection) use ($sql, $params) {
            $stmt = $connection->prepare($sql);

            foreach ($params as $columnName => $type) {
                $value = ($type[0] === self::DATA_TYPE_DECIMAL)
                    ? filter_var($type[1], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION)
                    : $type[1];

                $stmt->bindValue(":{$columnName}", $value, $type[0]);
            }

            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, get_called_class());
        });
    }

    public static function getLastAddedElement($orderByColumn = 'inv_number', $orderDirection = 'DESC')
    {
        return self::executeWithConnection(function ($connection) use ($orderByColumn, $orderDirection) {
            $sql = "SELECT * FROM " . static::$tableName . " ORDER BY $orderByColumn $orderDirection LIMIT 1";

            try {
                $stmt = $connection->prepare($sql);
                $stmt->execute();
                $result = $stmt->fetch(\PDO::FETCH_ASSOC);

                if (!$result) {
                    error_log("No invoice found in getLastAddedElement().");
                }

                return $result ?: null;
            } catch (\PDOException $e) {
                error_log("Error fetching last added invoice: " . $e->getMessage());
                return null;
            }
        });
    }




    // New Method: Get count of all rows in a table
    public static function countAll()
    {
        return self::executeWithConnection(function ($connection) {
            $sql = 'SELECT COUNT(*) AS count FROM ' . static::$tableName;
            $stmt = $connection->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch();
            return $result ? $result['count'] : 0;
        });
    }

    // New Method: Get count of rows that meet specific criteria
    public static function countWhere($condition, $params = [])
    {
        return self::executeWithConnection(function ($connection) use ($condition, $params) {
            $sql = 'SELECT COUNT(*) AS count FROM ' . static::$tableName . ' WHERE ' . $condition;
            $stmt = $connection->prepare($sql);

            foreach ($params as $column => $value) {
                $stmt->bindValue(":$column", $value);
            }

            $stmt->execute();
            $result = $stmt->fetch();
            return $result ? $result['count'] : 0;
        });
    }

    // New Method: Execute custom SQL queries
    public static function executeQuery($sql, $params = [])
    {
        return self::executeWithConnection(function ($connection) use ($sql, $params) {
            $stmt = $connection->prepare($sql);

            foreach ($params as $column => $value) {
                $stmt->bindValue(":$column", $value);
            }

            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        });
    }
}
