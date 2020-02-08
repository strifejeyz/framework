<?php namespace Kernel\Database;

use PDO;
use PDOException;

/**
 * Database raw query builder.
 *
 * Class Database
 *
 * @package Kernel\Database
 */
class Database extends QueryBuilder
{
    /**
     * Default table
     */
    protected static $table;

    /**
     * Performs a raw query, returns
     * multiple rows with fetchAll()
     *
     * @param $query
     * @param array $values
     * @param int $fetchMode
     * @return void
     */
    public static function raw($query, $values = null, $fetchMode = PDO::FETCH_OBJ)
    {
        if (!empty($values)) {
            $stmt = self::instance()->prepare($query);
            $stmt->execute($values);
        } else {
            $stmt = self::instance()->query($query);
        }

        return $stmt->fetchAll($fetchMode);
    }


    /**
     * Performs a raw query
     * and only returns single row
     * with fetch()
     *
     * @param $query
     * @param array $values
     * @param int $fetchMode
     * @return void
     */
    public static function pull($query, $values = null, $fetchMode = PDO::FETCH_OBJ)
    {
        if (!empty($values)) {
            $stmt = self::instance()->prepare($query);
            $stmt->execute($values);
        } else {
            $stmt = self::instance()->query($query);
        }

        return $stmt->fetch($fetchMode);
    }


    /**
     * Performs a query, accepts
     * full query string
     *
     * @param $query
     * @param $fetchMode
     * @return boolean
     */
    public static function singular($query, $fetchMode = PDO::FETCH_OBJ)
    {
        $stmt = self::instance()->query($query);
        return $stmt->fetchAll($fetchMode);
    }


    /**
     * Performs a raw query, accepts
     * full query string
     *
     * @param $table
     * @return Database
     */
    public static function table($table)
    {
        self::$table = $table;

        return new self;
    }


    /**
     * Create a database
     *
     * @param $database
     * @return boolean
     * @throws PDOException
     */
    public static function create($database)
    {
        try {
            return self::instance()->exec("CREATE DATABASE {$database}");
        } catch (PDOException $e) {
            echo $e->getMessage();
        }

        return (true);
    }


    /**
     * Drop a database
     *
     * @param $database
     * @return boolean
     * @throws PDOException
     */
    public static function drop($database)
    {
        try {
            return self::instance()->exec("DROP DATABASE {$database}");
        } catch (PDOException $e) {
            return print $e->getMessage();
        }
    }
}

