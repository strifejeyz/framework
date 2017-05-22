<?php namespace Kernel\Database;

use Exception;
use PDOException;

/**
 * Migration Manager
 *
 * Class Migration
 *
 * @package Kernel\Database
 */
abstract class Migration extends Connection
{

    /**
     * property that will hold the table fields
     */
    protected $fields = array();

    /**
     * Sets a field to be an auto increment of type
     *
     * @param $name
     * @param $attributes
     * @return string
     **/
    protected function increments($name, $attributes = [])
    {
        $length = 11;
        if (array_key_exists('length', $attributes)) {
            $length = $attributes['length'];
        }

        return $this->fields[$name] = "$name INT($length) UNSIGNED AUTO_INCREMENT PRIMARY KEY";
    }


    /**
     * Parse data types for both text and integer
     *
     * @param $type
     * @param $name
     * @param $attributes array
     * @return bool
     */

    public function parseDataType($type, $name, $attributes = [])
    {
        $attr = [
            'length' => 255,
            'unique' => '',
            'point' => '',
            'null' => 'NOT NULL',
            'primary' => '',
            'default' => '',
        ];


        if ($type == 'INT') {
            $attr['length'] = 11;
        } elseif ($type == 'TINYINT') {
            $attr['length'] = 4;
        }
        elseif ($type == 'DECIMAL') {
            $attr['length'] = 30;
        }

        if (array_key_exists('length', $attributes)) {
            $attr['length'] = $attributes['length'];
        }
        if (array_key_exists('null', $attributes)) {
            if ($attributes['null'] == true || $attributes['null'] == 'null') {
                $attr['null'] = "NULL";
            }
        }
        if (array_key_exists('unique', $attributes)) {
            if ($attributes['unique'] == true) {
                $attr['unique'] = ",UNIQUE($name)";
            }
        }
        if (array_key_exists('point', $attributes)) {
            $attr['point'] = ",{$attributes['point']}";
        }
        if (array_key_exists('primary', $attributes)) {
            if ($attributes['primary'] == true) {
                $attr['primary'] = ",PRIMARY KEY ($name)";
            }
        }
        if (array_key_exists('default', $attributes)) {
            $default = trim($attributes['default'], "'");
            $attr['default'] = " DEFAULT '{$default}'";
        }

        $command = "{$name} {$type}({$attr['length']}{$attr['point']}) {$attr['null']}{$attr['unique']}{$attr['primary']}{$attr['default']}";

        return ($this->fields[$name] = $command);
    }





    /***************************************************************
     * String Data Types
     *
     */

    /**
     * @param $name
     * @param $attributes array
     * @return string
     **/
    protected function char($name, $attributes = [])
    {
        return $this->parseDataType('CHAR', $name, $attributes);
    }

    /**
     * @param $name
     * @param $attributes array
     * @return string
     */
    protected function varchar($name, $attributes = [])
    {
        return $this->parseDataType('VARCHAR', $name, $attributes);
    }


    /**
     * @param $name
     * @param $attributes array
     * @return string
     **/
    protected function tinytext($name, $attributes = [])
    {
        $null = "NOT NULL";
        if (array_key_exists('null', $attributes)) {
            if ($attributes['null'] == true || $attributes['null'] == 'null') {
                $null = "NULL";
            }
        }
        return $this->fields[$name] = "{$name} TINYTEXT {$null}";
    }

    /**
     * @param $name
     * @param $attributes
     * @return string
     **/
    protected function text($name, $attributes = [])
    {
        $null = "NOT NULL";
        if (array_key_exists('null', $attributes)) {
            if ($attributes['null'] == true || $attributes['null'] == 'null') {
                $null = "NULL";
            }
        }
        return $this->fields[$name] = "{$name} TEXT {$null}";
    }


    /**
     * @param $name
     * @param $attributes array
     * @return string
     */
    protected function blob($name, $attributes = [])
    {
        $null = "NOT NULL";
        if (array_key_exists('null', $attributes)) {
            if ($attributes['null'] == true || $attributes['null'] == 'null') {
                $null = "NULL";
            }
        }
        return $this->fields[$name] = "{$name} BLOB {$null}";
    }


    /**
     * @param $name
     * @param $attributes array
     * @return string
     */
    protected function mediumtext($name, $attributes = [])
    {
        $null = "NOT NULL";
        if (array_key_exists('null', $attributes)) {
            if ($attributes['null'] == true || $attributes['null'] == 'null') {
                $null = "NULL";
            }
        }
        return $this->fields[$name] = "{$name} MEDIUMTEXT {$null}";
    }


    /**
     * @param $name
     * @param $attributes array
     * @return string
     */
    protected function mediumblob($name, $attributes = [])
    {
        $null = "NOT NULL";
        if (array_key_exists('null', $attributes)) {
            if ($attributes['null'] == true || $attributes['null'] == 'null') {
                $null = "NULL";
            }
        }
        return $this->fields[$name] = "{$name} MEDIUMBLOB {$null}";
    }


    /**
     * @param $name
     * @param $attributes array
     * @return string
     */
    protected function longtext($name, $attributes = [])
    {
        $null = "NOT NULL";
        if (array_key_exists('null', $attributes)) {
            if ($attributes['null'] == true || $attributes['null'] == 'null') {
                $null = "NULL";
            }
        }
        return $this->fields[$name] = "{$name} LONGTEXT {$null}";
    }


    /**
     * @param $name
     * @param $attributes array
     * @return string
     */
    protected function longblob($name, $attributes = [])
    {
        $null = "NOT NULL";
        if (array_key_exists('null', $attributes)) {
            if ($attributes['null'] == true || $attributes['null'] == 'null') {
                $null = "NULL";
            }
        }
        return $this->fields[$name] = "{$name} LONGBLOB {$null}";
    }



    /*****************************************************************
     * Integer Data Types
     **/


    /**
     * @param $name
     * @param $attributes array
     * @return string
     **/
    protected function tinyint($name, $attributes = [])
    {
        return $this->fields[$name] = $this->parseDataType("TINYINT", $name, $attributes);
    }


    /**
     * @param $name
     * @param $attributes array
     * @return string
     **/
    protected function smallint($name, $attributes = [])
    {
        return $this->fields[$name] = $this->parseDataType("SMALLINT", $name, $attributes);
    }


    /**
     * @param $name
     * @param $attributes array
     * @return string
     **/
    protected function mediumint($name, $attributes = [])
    {
        return $this->fields[$name] = $this->parseDataType("MEDIUMINT", $name, $attributes);
    }

    /**
     * @param $name
     * @param $attributes array
     * @return string
     **/
    protected function int($name, $attributes = [])
    {
        return $this->fields[$name] = $this->parseDataType("INT", $name, $attributes);
    }

    /**
     * @param $name
     * @param $attributes array
     * @return string
     **/
    protected function bigint($name, $attributes = [])
    {
        return $this->fields[$name] = $this->parseDataType("BIGINT", $name, $attributes);
    }

    /**
     * @param $name
     * @param $attributes array
     * @return string
     **/
    protected function float($name, $attributes = [])
    {
        return $this->fields[$name] = $this->parseDataType("FLOAT", $name, $attributes);
    }


    /**
     * @param $name
     * @param $attributes array
     * @return string
     **/
    protected function double($name, $attributes = [])
    {
        return $this->fields[$name] = $this->parseDataType("DOUBLE", $name, $attributes);
    }

    /**
     * @param $name
     * @param $attributes array
     * @return string
     **/
    protected function decimal($name, $attributes = [])
    {
        return $this->fields[$name] = $this->parseDataType("DECIMAL", $name, $attributes);
    }



    /**
     * Time and Date Data Types
     **/

    /**
     * @param $name
     * @param $nullable
     * @return string
     **/
    protected function date($name, $nullable = "NOT NULL")
    {
        $nullable = ($nullable == "null") ? strtoupper($nullable) : $nullable;
        return $this->fields[$name] = "$name DATE $nullable";
    }

    /**
     * @param $name
     * @param string $nullable
     * @return string
     */
    protected function datetime($name, $nullable = "NOT NULL")
    {
        $nullable = ($nullable == "null") ? strtoupper($nullable) : $nullable;
        return $this->fields[$name] = "$name DATETIME $nullable";
    }

    /**
     * @param $name
     * @param string $nullable
     * @return string
     */
    protected function timestamp($name, $nullable = "NOT NULL")
    {
        $nullable = ($nullable == "null") ? strtoupper($nullable) : $nullable;
        return $this->fields[$name] = "$name TIMESTAMP $nullable";
    }

    /**
     * @param $name
     * @param string $nullable
     * @return string
     */
    protected function time($name, $nullable = "NOT NULL")
    {
        $nullable = ($nullable == "null") ? strtoupper($nullable) : $nullable;
        return $this->fields[$name] = "$name TIME $nullable";
    }

    /**
     * @param $name
     * @param string $nullable
     * @return string
     */
    protected function year($name, $nullable = "NOT NULL")
    {
        $nullable = ($nullable == "null") ? strtoupper($nullable) : $nullable;
        return $this->fields[$name] = "$name YEAR $nullable";
    }


    /**
     * Installation of migration creates a table depending on
     * the migration class' shared $table property along with
     * the fields that should have been setup beforehand
     *
     * @var $values
     * @var $params
     * @return void
     * @throws Exception
     **/
    protected function install()
    {
        try {
            if (empty($this->fields)) throw new Exception("Cannot use an value for reading");

            $values = array_values($this->fields);
            $params = "";

            for ($i = 0; $i < count($values); $i++) {
                $params .= "$values[$i],";
            }

            self::$pdo->exec("DROP TABLE IF EXISTS {$this->table}");
            self::$pdo->exec("CREATE TABLE {$this->table}(" . trim($params, ',') . ")");

            return ("Table '{$this->table}' migrated.");
        } catch (PDOException $e) {
            print $e->getMessage();
        }
    }


    /**
     * Drop the table
     *
     * @var $message
     * @return void
     **/
    protected function uninstall()
    {
        try {
            self::$pdo->exec("DROP TABLE IF EXISTS {$this->table}");

            return ("Table '{$this->table}' rolled back.");
        } catch (PDOException $e) {
            print $e->getMessage();
        }
    }


    /**
     * Return the array containing values
     * formulated through its migration class.
     *
     * @return array
     */
    public function dump()
    {
        return dump($this->fields);
    }
}