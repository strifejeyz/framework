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
     * @param bool $fetchRows
     * @param int $fetchMode
     * @return void
     */
    public static function query($query, $values = null, $fetchRows = true, $fetchMode = PDO::FETCH_OBJ)
    {
        if (!empty($values)) {
            $stmt = self::instance()->prepare($query);
            $stmt->execute($values);
        } else {
            $stmt = self::instance()->query($query);
        }

        if ($fetchRows == true) {
            return $stmt->fetchAll($fetchMode);
        } else {
            return $stmt->fetch($fetchMode);
        }
    }


    /**
     * Performs a raw query and only returns
     * 1 row of data with fetch()
     *
     * @param $query
     * @param null $values
     * @param int $fetchMode
     * @return void
     */
    public static function row($query, $values = null, $fetchMode = PDO::FETCH_OBJ)
    {
        return self::query($query, $values, false, $fetchMode);
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

