<?php namespace App\Models;

use Kernel\Database\QueryBuilder as Model;

/**
 * Class Tokens
 *
 * @package App\Models
 */
class Tokens extends Model
{
    /**
     * table name that will be used by this model
     *
     * @var string
     */
    protected static $table = "tokens";


    /**
     * If you don't want to use standard 'id'
     * then put your primary key here.
     */
    protected static $primary_key = "id";

}