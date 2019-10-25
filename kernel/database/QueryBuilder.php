<?php /** @noinspection SpellCheckingInspection */

namespace Kernel\Database;

/**********************************************************
 * Query Builder Class for Strife Framework.
 * provides handy set of methods for interacting with the
 * database and fully implements active record management
 *
 * Author:  Jeyz Strife
 * website: https://github.com/knyteblayde/strife
 * Date:    11/10/15
 */


/**
 * Interface MagicMethods
 *
 * @package Kernel\Database
 */
interface QueryBuilderMagicInterface
{
    public function __construct($data = null);

    public function __invoke();

    public function __call($name, $arguments);

    public static function __callStatic($name, $arguments);

    public function __get($property);

    public function __set($field, $value);
}

/**
 * Interface QueryBuilderInterface
 *
 * @package Kernel\Database
 */
interface QueryBuilderInterface
{
    public static function backup();

    public static function restore();

    public static function transact();

    public static function append($name, $value);

    public static function remove($name);

    public static function commit();

    public static function rollback();

    public static function inTransaction();

    public static function errorInfo();

    public static function quote($string);

    public function save();

    public static function insert($valuePairs = []);

    public static function insertExcept($valuePairs = [], $exception = []);

    public static function update($id, $valuePairs = []);

    public static function delete();

    public static function select($selection);

    public static function where($field, $a = null, $b = null);

    public static function whereIn($column, $values = []);

    public static function orWhere($field, $a = null, $b = null);

    public static function whereBetween($column, $first, $second);

    public static function orWhereBetween($column, $first, $second);

    public static function order($field, $order);

    public static function limit($number, $offset = null);

    public static function find($id);

    public static function findOrFail($id);

    public static function exists();

    public static function lastRow();

    public static function firstRow();

    public static function max($field);

    public static function min($field);

    public static function sum($field);

    public static function avg($field);

    public static function pull($column);

    public static function check();

    public static function lastInsertedId();

    public static function count();

    public static function distinct($fields);

    public static function first();

    public static function increment($field, $int = null);

    public static function decrement($field, $int = null);

    public static function get($fetchMode = PDO::FETCH_OBJ);
}

use ErrorHandler;
use Kernel\FileHandler;
use PDO;
use PDOException;

/**
 * Class QueryBuilder
 *
 * @package Kernel\Database
 */
class QueryBuilder extends Connection implements QueryBuilderInterface, QueryBuilderMagicInterface
{
    /**
     * This will be the query string to be populated
     */
    protected static $query = array();

    /**
     * Holds passed in values from builder
     */
    protected static $values = array();

    /**
     * Container for query result whenever a new instance of this class is generated.
     */
    protected static $result = array();

    /**
     * Table name inherited from model class
     */
    protected static $table = null;

    /**
     * Primary Key per table, you may specify
     * this inside your model class
     */
    protected static $primary_key = null;

    /**
     * Holds dynamically created properties for editing
     * parsed from setter magic method.
     */
    private $fields = array();

    /**
     * Holds result set from find() and first()
     * methods.
     */
    public $original = array();


    /**
     * QueryBuilder constructor.
     * prevents __get() magic method
     * from changing the result set using first() and find()
     * method. store result set in public and static variables.
     *
     * @param $data
     */
    public function __construct($data = null)
    {
        if (!is_null($data)) {
            $this->original = $data;
            self::$result = $data;
        }

        return true;
    }


    /**
     * For whenever class is called like a function
     * return the result set.
     * this will only return value if a successful
     * query returns an object directed to $result
     *
     * @return mixed
     */
    public function __invoke()
    {
        return (self::$result);
    }


    /**
     * Handles dynamically called undefined methods
     * on normal method call.
     *
     * @param $name
     * @param array $arguments
     * @return bool|QueryBuilder|mixed
     * @throws ErrorHandler
     */
    public function __call($name, $arguments = [])
    {
        return self::callParser($name, $arguments);
    }


    /**
     * Handles dynamically called methods
     * on static call point.
     *
     * @param $name
     * @param $arguments
     * @return bool|QueryBuilder|mixed
     */
    public static function __callStatic($name, $arguments)
    {
        return self::callParser($name, $arguments);
    }


    /**
     * Serves as a parser for both __call and __callStatic
     * magic methods.
     * Handles dynamically created method that does not exist
     * in this class.
     * handles 'where', 'orWhere', 'increment', 'decrement', and 'pull'
     * triggers when undefined method is called statically or not
     * e.g. User::wherePassword('secret'), $user->wherePassword('secret')
     *
     * @param $name
     * @param $arguments
     * @return bool|QueryBuilder|mixed
     */
    private static function callParser($name, $arguments)
    {
        if (preg_match('/^where/i', $name)) {
            $name = strtolower(preg_replace('/^where/i', '', $name));
            if (count($arguments) > 1) {
                return self::where($name, $arguments[0], $arguments[1]);
            } else {
                return self::where($name, $arguments[0]);
            }
        } elseif (preg_match('/^orWhere/i', $name)) {
            $name = strtolower(preg_replace('/^orWhere/i', '', $name));
            if (count($arguments) > 1) {
                return self::orWhere($name, $arguments[0], $arguments[1]);
            } else {
                return self::orWhere($name, $arguments[0]);
            }
        } elseif (preg_match('/^increment/i', $name)) {
            $name = strtolower(preg_replace('/^increment/i', '', $name));
            $int = isset($arguments[0]) ? $arguments[0] : 1;
            return self::increment($name, $int);
        } elseif (preg_match('/^decrement/i', $name)) {
            $name = strtolower(preg_replace('/^decrement/i', '', $name));
            $int = isset($arguments[0]) ? $arguments[0] : 1;
            return self::decrement($name, $int);
        } elseif (preg_match('/^min/i', $name)) {
            $name = strtolower(preg_replace('/^min/i', '', $name));
            return self::min($name);
        } elseif (preg_match('/^max/i', $name)) {
            $name = strtolower(preg_replace('/^max/i', '', $name));
            return self::max($name);
        } elseif (preg_match('/^sum/i', $name)) {
            $name = strtolower(preg_replace('/^sum/i', '', $name));
            return self::sum($name);
        } elseif (preg_match('/^avg/i', $name)) {
            $name = strtolower(preg_replace('/^avg/i', '', $name));
            return self::avg($name);
        } elseif (preg_match('/^order/i', $name)) {
            $name = strtolower(preg_replace('/^order/i', '', $name));
            return self::order($name, $arguments[0]);
        } else {
            return (false);
        }
    }


    /**
     * Returns a value from $result object if dynamic $property given is
     * a property of it.
     *
     * @param $property
     * @return mixed
     */
    public function __get($property)
    {
        if (property_exists($this->original, $property)) {
            return $this->original->$property;
        } else {
            return (null);
        }
    }


    /**
     * Setter method handles dynamically created properties along
     * with its value passed into it.
     *
     * @param $field
     * @param $value
     */
    public function __set($field, $value)
    {
        $this->fields[$field] = $value;
    }


    /**
     * Backup a database table.
     *
     * @return bool
     */
    public static function backup()
    {
        $filename = storage_dir() . 'backups/' . static::$table . ".json";
        $file = new FileHandler($filename, 'w+');

        if (!empty(self::$result)) {
            $data = json_encode(self::$result);
        } else {
            $data = json_encode(self::get());
        }

        if ($file->write($data . "\n", 'w')) {
            return true;
        } else {
            return (false);
        }
    }


    /**
     * Restore a database table
     *
     * @return bool
     */
    public static function restore()
    {
        $filename = storage_dir() . 'backups/' . static::$table . ".json";
        $result = null;

        if (file_exists($filename)) {
            $file = new FileHandler($filename, 'r');

            foreach (json_decode($file->read()) as $data) {
                if (self::insert((array)$data)) {
                    $result = true;
                } else {
                    $result = false;
                    continue;
                }
            }
            return $result == true ? true : false;
        } else {
            return (false);
        }
    }


    /**
     * Begin transaction, turns off auto-commit mode to prevent changes made to
     * the database until commit() method is called.
     *
     * @return boolean
     */
    public static function transact()
    {
        return self::instance()->beginTransaction();
    }


    /**
     * Append a value to result set
     * remember that it does not reflect the real value
     * from the database. it is just added dynamically to result set
     *
     * @param string $name
     * @param mixed $value
     * @return boolean
     */
    public static function append($name, $value)
    {
        if (self::$result->$name = $value) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * Append a value to result set
     * will just simply remove field from result set.
     * does not affect real value from database
     *
     * @param string $name
     * @return boolean
     */
    public static function remove($name)
    {
        if (array_key_exists($name, self::$result)) {
            unset(self::$result->$name);
            return (true);
        } else {
            return (null);
        }
    }


    /**
     * Turns back on the auto-commit mode and changes to database are not held back.
     *
     * @return boolean
     */
    public static function commit()
    {
        return self::instance()->commit();
    }


    /**
     * Roll back changes made to database right after the begin transaction and a query next
     * to it is present then brings back on auto-commit mode.
     *
     * @return boolean
     */
    public static function rollback()
    {
        return self::instance()->rollback();
    }


    /**
     * Check whether a transaction is currently active
     * returns boolean.
     *
     * @return boolean
     */
    public static function inTransaction()
    {
        return self::instance()->inTransaction();
    }


    /**
     * Return array of last error on operation if present.
     *
     * @return mixed
     */
    public static function errorInfo()
    {
        self::setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);

        return self::instance()->errorInfo();
    }


    /**
     * Wrap the string with quotes.
     *
     * @param $string
     * @return string
     */
    public static function quote($string)
    {
        return self::instance()->quote($string);
    }


    /**
     * Insert query, Accepts array key => value
     * pairs and binds each fields to raw query
     *
     * @param array $valuePairs
     * @return mixed
     */
    public static function insert($valuePairs = [])
    {
        try {
            if (empty($valuePairs)) {
                throw new ErrorHandler("insert() expects one argument, array of field => value pairs.");
            }

            $keys = array_keys($valuePairs);
            $values = array_values($valuePairs);
            $params = "";
            $fields = "";

            for ($i = 0; $i < count($valuePairs); $i++) {
                $params .= ("?,");
                $fields .= ("{$keys[$i]},");
            }

            $params = trim($params, ',');
            $fields = trim($fields, ',');
            $query = "INSERT INTO " . static::$table . "({$fields}) VALUES({$params});";
            $stmt = self::instance()->prepare($query);

            return $stmt->execute($values);
        } catch (ErrorHandler $e) {
            return print $e->message();
        }
    }


    /**
     * Insert all from a set of array values
     * except for those in the exceptions.
     *
     * @param array $valuePairs
     * @param array $exceptions
     * @return bool
     */
    public static function insertExcept($valuePairs = [], $exceptions = [])
    {
        try {
            if (empty($valuePairs) || empty($exceptions)) {
                throw new ErrorHandler("method requires 2 arguments to be array and not null.");
            }

            $values = array_values($exceptions);

            for ($i = 0; $i < count($exceptions); $i++) {
                if (array_key_exists($values[$i], $valuePairs)) {
                    unset($valuePairs[$values[$i]]);
                }
            }

            return self::insert($valuePairs);
        } catch (ErrorHandler $e) {
            return print $e->message();
        }
    }


    /**
     * This method will carry out all the composed query
     * saves the $fields if keys reflects the fields
     * in the database.
     */
    public function save()
    {
        try {
            if (!empty($this->fields)) {
                if (static::$primary_key) {
                    $primary_key = static::$primary_key;
                } else {
                    $primary_key = self::$primary_key;
                }

                if (self::update($this->fields, $this->original->$primary_key)) {
                    foreach ($this->fields as $field => $value) {
                        if (property_exists($this->original, $field)) {
                            $this->original->$field = self::find($this->original->$primary_key)->pull($field);
                        }
                    }
                    $this->fields = [];
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return print $e->getMessage();
        }
    }


    /**
     * Update the given set of field => value pairs
     * from $fields array.
     *
     * @param array $valuePairs
     * @param null $id
     * @return mixed
     */
    public static function update($valuePairs = [], $id = null)
    {
        try {
            if (empty($valuePairs)) {
                return false;
            } else {
                if (isset(static::$primary_key)) {
                    $primary_key = static::$primary_key;
                } else {
                    $primary_key = self::$primary_key;
                }

                if (array_key_exists($primary_key, self::$result)) {
                    $id = self::$result->$primary_key;
                } else {
                    if (is_null($id)) {
                        throw new ErrorHandler("Second argument is required if result set is null.");
                    }
                }

                $keys = array_keys($valuePairs);
                $values = array_values($valuePairs);
                $params = null;

                foreach ($keys as $key) {
                    $params .= ($key . "=?,");
                }

                $params = trim($params, ',');
                $stmt = self::instance()->prepare("UPDATE " . self::$table . " SET {$params} WHERE {$primary_key}={$id}");

                return $stmt->execute($values);
            }
        } catch (ErrorHandler $e) {
            return print $e->message();
        }
    }


    /**
     * Deletes a single row(if given param is single id).
     * if more than one param supplied, it will delete it recursively.
     * it is also possible to be chained with where statement
     *
     * @method delete($id = null)
     * @param null $id
     * @return mixed
     * @throws ErrorHandler
     */
    public static function delete($id = null)
    {
        try {
            self::$table = static::$table;
            $stmt = null;
            if (isset(static::$primary_key)) {
                $primary_key = static::$primary_key;
            } else {
                $primary_key = self::$primary_key;
            }

            if (is_null($id) && empty(self::$result)) {
                throw new ErrorHandler("delete() requires an existing object or pass in a primary key if none.");
            } else {
                if (!empty(self::$query)) {
                    self::$query['select'] = 'DELETE';
                    $stmt = self::instance()->prepare(self::parseQuery());
                    $result = $stmt->execute(array_values(self::$values));
                    self::$query = [];
                    self::$values = [];
                    return ($result);
                } else {
                    $id = (!is_null($id)) ? $id : self::$result->$primary_key;
                }
            }
            if (count(func_get_args()) > 1) {
                foreach (func_get_args() as $arg) {
                    $stmt = self::instance()->exec("DELETE FROM " . static::$table . " WHERE {$primary_key}={$arg};");
                }
            } else {
                $stmt = self::instance()->exec("DELETE FROM " . static::$table . " WHERE {$primary_key}={$id};");
            }
            return ($stmt);
        } catch (PDOException $e) {
            return print $e->getMessage();
        }
    }


    /**
     * Select database columns.
     * if number of arguments supplied is greater than one, it will be handled
     * recursively, otherwise treats the single argument as the selection.
     * e.g. select('name,age') or select('name','age') or select('*')
     *
     * @param $columns
     * @return mixed
     */
    public static function select($columns)
    {
        try {
            if (empty($columns)) {
                throw new ErrorHandler("method expects 1 argument (string) column(s) in the database.");
            }

            self::$table = static::$table;
            $selection = "";

            if (count(func_get_args()) > 1) {
                foreach (func_get_args() as $arg) {
                    $selection .= $arg . ",";
                }
            } else {
                $selection = $columns;
            }

            self::$query['select'] = trim($selection, ',');

            return new self;
        } catch (ErrorHandler $e) {
            return print $e->message();
        }
    }


    /**
     * where statement, can be chained next to it.
     *
     * @param $field
     * @param null $a
     * @param null $b
     * @return mixed
     */
    public static function where($field, $a = null, $b = null)
    {
        self::$table = static::$table;

        if (!is_null($b)) {
            $param = "{$a} ?";
            self::$values[] = $b;
        } else {
            $param = "= ?";
            self::$values[] = $a;
        }

        if (isset(self::$query['where']) && preg_match('/WHERE/i', self::$query['where'])) {
            self::$query['where'] = self::$query['where'] . " AND {$field} {$param}";
        } else {
            self::$query['where'] = "WHERE {$field} {$param}";
        }

        return new self;
    }


    /**
     * Selects a row with value that is equal to
     * second argument.
     *
     * @param $column
     * @param array $values
     * @return mixed
     */
    public static function whereIn($column, $values = [])
    {
        self::$table = static::$table;
        $params = "";

        foreach ($values as $value) {
            $params .= "?,";
            self::$values[] = $value;
        }

        $params = trim($params, ',');

        if (isset(self::$query['where']) && preg_match('/WHERE/i', self::$query['where'])) {
            self::$query['where'] = self::$query['where'] . " AND {$column} IN ({$params})";
        } else {
            self::$query['where'] = "WHERE {$column} IN ({$params})";
        }

        return new self;
    }


    /**
     * Selects a row with value that is equal to
     * second argument.
     *
     * @param $column
     * @param $first
     * @param $second
     * @return mixed
     */
    public static function whereBetween($column, $first, $second)
    {
        self::$table = static::$table;
        self::$values[] = $first;
        self::$values[] = $second;

        if (isset(self::$query['where']) && preg_match('/WHERE/i', self::$query['where'])) {
            self::$query['where'] = self::$query['where'] . " AND {$column} BETWEEN ? AND ?";
        } else {
            self::$query['where'] = "WHERE {$column} BETWEEN ? AND ?";
        }
        return new self;
    }


    /**
     * Selects a row with value that is equal to
     * second argument.
     *
     * @param $column
     * @param $first
     * @param $second
     * @return mixed
     */
    public static function orWhereBetween($column, $first, $second)
    {
        try {
            self::$table = static::$table;
            self::$values[] = $first;
            self::$values[] = $second;

            if (isset(self::$query['where']) && preg_match('/WHERE/i', self::$query['where'])) {
                self::$query['where'] = self::$query['where'] . " OR {$column} BETWEEN ? AND ?";
            } else {
                throw new ErrorHandler("orWhereBetween() should be called next to where() method.");
            }
            return new self;

        } catch (ErrorHandler $e) {
            return print $e->message();
        }
    }


    /**
     * Similar to WHERE but prepends an OR.
     *
     * @param $field
     * @param null $a
     * @param null $b
     * @return mixed
     */
    public static function orWhere($field, $a = null, $b = null)
    {
        try {
            self::$table = static::$table;

            if (!is_null($b)) {
                $param = "{$a} ?";
                self::$values[] = $b;
            } else {
                $param = "= ?";
                self::$values[] = $a;
            }

            if (isset(self::$query['where']) && preg_match('/WHERE/i', self::$query['where'])) {
                self::$query['where'] = self::$query['where'] . " OR {$field} {$param}";
            } else {
                throw new ErrorHandler("orWhere() should be called next to where() method.");
            }
            return new self;
        } catch (ErrorHandler $e) {
            return print $e->message();
        }
    }


    /**
     * ORDER BY statement
     *
     * @param $field
     * @param $order
     * @return mixed
     */
    public static function order($field, $order)
    {
        try {
            if (!preg_match('/DESC/i', $order) && !preg_match('/ASC/i', $order)) {
                throw new ErrorHandler("Second argument passed to order() method should be '<b>ASC</b>' or '<b>DESC</b>'");
            }

            self::$table = static::$table;
            self::$query['order'] = "ORDER BY $field " . strtoupper($order);

            return new self;
        } catch (ErrorHandler $e) {
            return print $e->message();
        }
    }


    /**
     * limit statement where $limit is the number
     * of rows to be returned.
     *
     * @param $limit
     * @param $offset null
     * @return mixed
     * @throws ErrorHandler
     */
    public static function limit($limit, $offset = null)
    {
        try {
            if (!is_numeric($limit)) {
                throw new ErrorHandler("Argument passed in limit() method should be numeric.");
            }

            self::$table = static::$table;

            if (is_null($offset)) {
                self::$query['limit'] = "LIMIT {$limit}";
            } else {
                self::$query['limit'] = "LIMIT {$limit}, {$offset}";
            }

            return new self;
        } catch (PDOException $e) {
            return print $e->getMessage();
        }
    }


    /**
     * Print the constructed $query
     *
     * @return string
     */
    public static function check()
    {
        return die("<code>" . strtolower(self::parseQuery()) . "</code>");
    }


    /**
     * find a single row from database using the given id
     * Fetch the single row from database and set $result
     * from it, return new instance of class itself along with
     * its preserved properties for it
     * to be able to use all the methods and for method chaining
     * like User::first()->delete()
     * Note: you cannot store new instance of this class in a
     * session variable. refer to __invoke method
     * to get the original values.
     *
     * @param $id
     * @return self|bool
     */
    public static function find($id)
    {
        try {
            if (!is_numeric($id)) {
                throw new ErrorHandler("required parameter should be numeric, " . gettype($id) . " given.");
            }

            self::$table = static::$table;
            self::$primary_key = static::$primary_key;

            if (isset(static::$primary_key)) {
                $primary_key = static::$primary_key;
            } else {
                $primary_key = self::$primary_key;
            }

            $stmt = self::instance()->prepare("SELECT * FROM " . static::$table . " WHERE {$primary_key}=? LIMIT 1");
            $stmt->execute([$id]);
            $result = $stmt->fetch(PDO::FETCH_OBJ);

            if ($result) {
                self::$result = $result;
                return new self($result);
            } else {
                return (null);
            }
        } catch (ErrorHandler $e) {
            return print $e->message();
        }
    }


    /**
     * Quite similar to Find() but this will
     * throw a 404 page when data isn't found.
     *
     * @param $id
     * @return self|bool
     */
    public static function findOrFail($id)
    {
        if (!is_numeric($id)) {
            return page_error(404);
        } else {
            self::$table = static::$table;
            self::$primary_key = static::$primary_key;

            if (isset(static::$primary_key)) {
                $primary_key = static::$primary_key;
            } else {
                $primary_key = self::$primary_key;
            }

            $stmt = self::instance()->prepare("SELECT * FROM " . static::$table . " WHERE {$primary_key}=? LIMIT 1");
            $stmt->execute([$id]);
            $result = $stmt->fetch(PDO::FETCH_OBJ);

            if ($result != false) {
                self::$result = $result;
                return new self($result);
            } else {
                return page_error(404);
            }
        }
    }


    /**
     * Pull a single/multiple rows from database.
     * will return object if multiple arguments supplied
     *
     * @param $column
     * @return mixed
     */
    public static function pull($column)
    {
        try {
            $stmt = self::instance()->prepare(self::parseQuery());
            $stmt->execute(array_values(self::$values));
            self::$values = [];
            self::$query = [];
            $result = $stmt->fetch(PDO::FETCH_OBJ);

            if (!$result) {
                return (false);
            } else {
                if (count(func_get_args()) > 1) {
                    $object = new \ArrayObject();

                    foreach (func_get_args() as $arg) {
                        if (property_exists($result, $arg)) {
                            $object->append($result->$arg);
                        } else {
                            continue;
                        }
                    }
                    return ($object);
                } else {
                    if (property_exists($result, $column)) {
                        return ($result->$column);
                    } else {
                        return (null);
                    }
                }
            }
        } catch (PDOException $e) {
            return print $e->getMessage();
        }
    }


    /**
     * Determines whether a value exists
     * in the database.
     *
     * @return bool
     */
    public static function exists()
    {
        try {
            $stmt = self::instance()->prepare(self::parseQuery());
            $stmt->execute(array_values(self::$values));

            return ($stmt->rowCount() == 0) ? false : true;
        } catch (PDOException $e) {
            return print $e->getMessage();
        }
    }


    /**
     * Return that last row on the database
     *
     * @return QueryBuilder
     */
    public static function lastRow()
    {
        try {

            self::$table = static::$table;
            if (isset(static::$primary_key)) {
                $primary_key = static::$primary_key;
            } else {
                $primary_key = self::$primary_key;
            }

            $stmt = self::instance()->prepare("SELECT * FROM " . static::$table . " ORDER BY {$primary_key} DESC LIMIT 1");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_OBJ);

            if ($result) {
                return new self($result);
            } else {
                return (null);
            }
        } catch (PDOException $e) {
            return print $e->getMessage();
        }
    }


    /**
     * Return that first row on the database
     *
     * @return mixed
     */
    public static function firstRow()
    {
        try {
            self::$table = static::$table;
            self::$primary_key = static::$primary_key;

            if (isset(static::$primary_key)) {
                $primary_key = static::$primary_key;
            } else {
                $primary_key = self::$primary_key;
            }
            $stmt = self::instance()->prepare("SELECT * FROM " . static::$table . " ORDER BY {$primary_key} ASC LIMIT 1");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_OBJ);

            if ($result) {
                return new self($result);
            } else {
                return (null);
            }
        } catch (PDOException $e) {
            return print $e->getMessage();
        }
    }


    /**
     * Returns the smallest value of a column
     *
     * @param $field
     * @return mixed
     */
    public static function min($field)
    {
        try {
            if (!is_string($field)) {
                throw new ErrorHandler("required parameter should be string, " . gettype($field) . " given.");
            }

            self::$table = static::$table;
            $stmt = self::instance()->prepare("SELECT MIN({$field}) AS {$field} FROM " . static::$table . ";");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_OBJ);

            if ($result) {
                return ($result->{$field});
            } else {
                return (null);
            }
        } catch (ErrorHandler $e) {
            return print $e->message();
        }
    }


    /**
     * Returns the largest value of a column
     *
     * @param $field
     * @return mixed
     */
    public static function max($field)
    {
        try {
            if (!is_string($field)) {
                throw new ErrorHandler("required parameter should be string, " . gettype($field) . " given.");
            } else {
                self::$table = static::$table;
                $stmt = self::instance()->prepare("SELECT MAX({$field}) AS {$field} FROM " . static::$table . ";");
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_OBJ);

                if ($result) {
                    return ($result->{$field});
                } else {
                    return (null);
                }
            }

        } catch (ErrorHandler $e) {
            return print $e->message();
        }
    }


    /**
     * Returns the sum of a column
     *
     * @param $field
     * @return mixed
     */
    public static function sum($field)
    {
        try {
            if (!is_string($field)) {
                throw new ErrorHandler("required parameter should be string, " . gettype($field) . " given.");
            } else {
                self::$table = static::$table;
                $stmt = self::instance()->prepare("SELECT SUM({$field}) AS {$field} FROM " . static::$table . ";");
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_OBJ);

                if ($result) {
                    return ($result->{$field});
                } else {
                    return (null);
                }
            }
        } catch (ErrorHandler $e) {
            return print $e->message();
        }
    }


    /**
     * Returns the ave of a column
     *
     * @param $field
     * @return mixed
     */
    public static function avg($field)
    {
        try {
            if (!is_string($field)) {
                throw new ErrorHandler("required parameter should be string, " . gettype($field) . " given.");
            } else {
                self::$table = static::$table;
                $stmt = self::instance()->prepare("SELECT AVG({$field}) AS {$field} FROM " . static::$table . ";");
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_OBJ);

                if ($result) {
                    return ($result->{$field});
                } else {
                    return (null);
                }
            }
        } catch (ErrorHandler $e) {
            return print $e->message();
        }
    }


    /**
     * Return that last fed id
     * from database.
     * NOTE: this can only be fetched right after the active query
     * is being carried out.
     *
     * @return mixed
     */
    public static function lastInsertedId()
    {
        try {
            return self::instance()->lastInsertId();
        } catch (PDOException $e) {
            return print $e->getMessage();
        }
    }


    /**
     * Count rows returned from query
     *
     * @return mixed
     */
    public static function count()
    {
        try {
            $stmt = self::instance()->prepare(self::parseQuery());
            $stmt->execute(array_values(self::$values));
            self::$values = [];
            self::$query = [];

            return $stmt->rowCount();
        } catch (PDOException $e) {
            return print $e->getMessage();
        }
    }


    /**
     * Get distinctive values
     *
     * @param $fields
     * @return self|bool
     */
    public static function distinct($fields)
    {
        self::$table = static::$table;
        $selection = "";
        if (count(func_get_args()) > 1) {
            foreach (func_get_args() as $arg) {
                $selection .= $arg . ",";
            }
        } else {
            $selection = $fields;
        }

        self::$query['select'] = "DISTINCT " . trim($selection, ',');

        return new self;
    }


    /**
     * Fetch the single row from database and set $result
     * from it, return new instance of class itself along with
     * its preserved properties for it
     * to be able to use all the methods and for method chaining
     * like User::first()->delete()
     * Note: you cannot store new instance of this class in a
     * session variable. refer to __invoke method
     * to get the original values.
     *
     * @return self|bool
     */
    public static function first()
    {
        try {
            self::$table = static::$table;
            self::$primary_key = static::$primary_key;
            $stmt = self::instance()->prepare(self::parseQuery());
            $stmt->execute(array_values(self::$values));
            self::$values = [];
            self::$query = [];
            $result = $stmt->fetch(PDO::FETCH_OBJ);

            if ($result) {
                return new self($result);
            } else {
                return (null);
            }
        } catch (PDOException $e) {
            return print $e->getMessage();
        }
    }


    /**
     * Increment a value, assuming the field's
     * default value is integer.
     *
     * @param $field
     * @param null $int
     * @return mixed
     * @var bool $stmt
     * @var int $value
     */
    public static function increment($field, $int = null)
    {
        try {
            if (!empty(self::$result)) {
                if (property_exists(self::$result, $field)) {
                    if (!is_numeric(self::$result->$field)) {
                        throw new ErrorHandler("Field '$field' is not of a numeric type, cannot do an increment.");
                    }
                } else {
                    throw new ErrorHandler("Field '$field' does not exist in \"" . self::$table . "\"'s table.");
                }

                if (isset(static::$primary_key)) {
                    $primary_key = static::$primary_key;
                } else {
                    $primary_key = self::$primary_key;
                }

                if (is_null($int)) {
                    $value = (self::$result->$field + 1);
                } else {
                    $value = (self::$result->$field + $int);
                }

                $stmt = self::instance()->exec("UPDATE " . self::$table . " SET {$field}={$value} WHERE {$primary_key}=" . self::$result->$primary_key);
                return ($stmt);
            } else {
                return false;
            }
        } catch (ErrorHandler $e) {
            return print $e->message();
        }
    }


    /**
     * Decrement a value, assuming the field's
     * default value is integer.
     *
     * @param $field
     * @param null $int
     * @return mixed
     * @var bool $stmt
     * @var int $value
     */
    public static function decrement($field, $int = null)
    {
        try {
            if (!empty(self::$result)) {
                if (property_exists(self::$result, $field)) {
                    if (!is_numeric(self::$result->$field)) {
                        throw new ErrorHandler("Field '$field' is not of a numeric type, cannot do an increment.");
                    }
                } else {
                    throw new ErrorHandler("Field '$field' does not exist in \"" . self::$table . "\"'s table.");
                }

                if (isset(static::$primary_key)) {
                    $primary_key = static::$primary_key;
                } else {
                    $primary_key = self::$primary_key;
                }

                if (is_null($int)) {
                    $value = (self::$result->$field - 1);
                } else {
                    $value = (self::$result->$field - $int);
                }

                $stmt = self::instance()->exec("UPDATE " . self::$table . " SET {$field}={$value} WHERE {$primary_key}=" . self::$result->$primary_key);
                return ($stmt) ? true : false;
            } else {
                return false;
            }
        } catch (ErrorHandler $e) {
            return print $e->message();
        }
    }


    /**
     * Executes a constructed query string or
     * uses default selection if not present.
     * returns default object
     *
     * @param int $fetchMode
     * @return mixed
     * @throws PDOException
     * @var $stmt
     */
    public static function get($fetchMode = PDO::FETCH_OBJ)
    {
        try {
            $stmt = self::instance()->prepare(self::parseQuery());
            $stmt->execute(array_values(self::$values));
            self::$values = [];
            self::$query = [];

            return $stmt->fetchAll($fetchMode);
        } catch (PDOException $e) {
            return print $e->getMessage();
        }
    }


    /**
     * Parse the query string
     *
     * @return string
     * @var $whereStmt
     * @var $order
     * @var $limit
     * @var $selection
     */
    private static function parseQuery()
    {
        if (isset(self::$query['select'])) {
            if (preg_match('/DELETE/i', self::$query['select'])) {
                $selection = self::$query['select'];
            } else {
                $selection = "SELECT " . self::$query['select'];
            }
        } else {
            $selection = "SELECT *";
        }

        $where = (isset(self::$query['where'])) ? self::$query['where'] : '';
        $order = (isset(self::$query['order'])) ? self::$query['order'] : '';
        $limit = (isset(self::$query['limit'])) ? self::$query['limit'] : '';

        return ("{$selection} FROM " . static::$table . " {$where} {$order} {$limit}");
    }
}