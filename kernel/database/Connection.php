<?php namespace Kernel\Database;

use PDO;
use PDOException;

/**
 * Connection class provides a single
 * instance of the connection to its child
 * classes.
 *
 * Class Connection
 *
 * @package Kernel\Database
 */
class Connection
{

    /**
     * Holds the PDO object
     **/
    protected static $pdo = null;

    /**
     * Connection Credentials
     **/
    private static $cxn = array();


    /**
     * Returns single instance of
     * PDO object.
     *
     * @return mixed
     **/
    public static function instance()
    {
        if (is_null(self::$pdo)) {
            return user_error('Connection is not initialized.', E_USER_ERROR);
        } else {
            return (self::$pdo);
        }
    }


    /**
     * Set connection credentials
     * as array() and store to self::$cxn
     *
     * @param $cxn = []
     * @param $alias
     * @return mixed
     **/
    public static function parameters($alias, $cxn = array())
    {
        self::$cxn[$alias] = $cxn;

        return new self;
    }


    /**
     * Initialize connection: set self::$pdo
     * to new instance of PDO object.
     * can create another instance of connection
     * as the instance name(alias) given as first parameter
     * that will be used to connect to.
     * second param will be the array of connection parameters.
     *
     * @todo connect to database
     * @param int transact
     * @param string $alias
     * @return mixed
     **/
    public static function initialize($alias)
    {
        try {
            if (array_key_exists($alias, self::$cxn)) {
                self::$pdo = new PDO(
                    self::$cxn[$alias]['driver'] . ":hostname=" .
                    self::$cxn[$alias]['hostname'] . ";dbname=" .
                    self::$cxn[$alias]['database'] . ";port=" .
                    self::$cxn[$alias]['port'] . ";charset=" .
                    self::$cxn[$alias]['charset'],
                    self::$cxn[$alias]['username'],
                    self::$cxn[$alias]['password']
                );
                return self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } else {
                return user_error("Connection instance '{$alias}' does not exist.", E_USER_ERROR);
            }
        } catch (PDOException $e) {
            return print $e->getMessage();
        }
    }


    /**
     * Set PDO's attribute.
     *
     * @param $attribute
     * @param $value
     * @return mixed
     **/
    public static function setAttribute($attribute, $value)
    {
        return self::$pdo->setAttribute($attribute, $value);
    }


    /**
     * Destroy Connection
     *
     * @return mixed
     **/
    public static function close()
    {
        return self::$pdo = null;
    }

}
