<?php namespace App\Models;

use Kernel\Database\QueryBuilder as Model;

/**
 * Class User
 *
 * @package App\Models
 */
class User extends Model
{
    /**========================================
     * You may also specify an alias
     * e.g. protected static $alias = "U";
     * or specify a primary key like so:
     * protected static $primary_key = "user_id";
     *
     * @var string
     */
    protected static $table = "users";
}

