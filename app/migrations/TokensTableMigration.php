<?php namespace App\Migrations;

use Kernel\Database\Migration;

class TokensTableMigration extends Migration
{

    /**
     * name of the table to migrate
     **/
    protected $table = "tokens";


    /**
     * field names and data types for this table
     */
    public function __construct()
    {
        $this->increments('id');
        $this->int('user_id');
        $this->varchar('reset_token');
        $this->int('token_expiration');
        $this->tinyint('failed_login');
        $this->tinyint('reset_attempts');
        $this->int('created');
        $this->int('updated');
    }


    /**
     * Install the migration
     *
     * @return void
     * @throws \Exception
     */
    public function up()
    {
        return $this->install();
    }


    /**
     * Drop the table
     *
     * @return void
     */
    public function down()
    {
        return $this->uninstall();
    }

}