<?php namespace App\Models;

use Kernel\Database\QueryBuilder as Model;

/**
 * Class User
 *
 * @package App\Models
 */
class User extends Model
{
    /**
     * table name that will be used by this model
     *
     * you may also specify an Alias
     * e.g. protected static $alias = "U";
     *
     * or specify a primary key like so
     * protected static $primary_key = "U";
     *
     * @var string
     */
    protected static $table = "users";
}

