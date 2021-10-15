<?php /** @noinspection ALL */

namespace Kernel;

use Exception;
use DirectoryIterator;
use Kernel\Security\Hash;
use Kernel\Security\Encryption;

/**
 * Class YamatoCLI
 * $command holds anything you type into YamatoCLI
 * then passes it to parseCommand($arg) to look for
 * specific method.
 *
 * @package Kernel
 * @property $command
 */
final class YamatoCLI
{
    private $command = null;


    /**
     * Get the arguments on instantiation
     * also prints yamato ascii art
     *
     * @param $arguments
     */
    public function __construct($arguments = null)
    {
        if (!isset($arguments[1])) {
            echo <<<EOF

           ..      ..   ..     ..    -#      ..
 ,#   x#  - ,#=  ##;;##+,+#   -..x# ++#++ .#+,-#X
  ##  #    ,.##  #.  =#   #+   ,,-#  ,#   #+    #.
   #-#+  #X  +#  #   =#   #+ -#.  #. -#   #=    #.
    ##   #X.,##  #.  +#   #x =#..x#. .#-. .#+,-#x
   ;#     ,,.    .    .   .    ;,      ;,   ,;;
  +X    11/10/2015 - 02/07/2020

 Yamato is a Command Line Interface(CLI) tool that is
 made to interact with the Strife Framework.


     COMMANDS                 ARGUMENTS                 DESCRIPTION
--------------------	--------------------    -----------------------------

{CLEANUP}
  clear:logs                                    clear logs directory
  clear:backups                                 clear backups directory
  clear:cache                                   clear cached pages
  clear:all                                     clear all backups,logs
  
{GENERATORS}
  create:model          [name] [table=null]     create a model class
  create:controller     [name] [empty=bool]     create a controller class
  create:migration      [name] [table]          create a migration class
  create:request        [name]                  create a request class
  create:seeder         [name] [model]          create a database seeder class
  create:key                                    create an application key

{DATABASE}
  db:migrate                                    install all migrations
  db:rollback                                   rollback all migrations
  db:table:up           [class]                 migrate a specific table using model class
  db:table:down         [class]                 rollback a specific table using model class
  db:table:dump         [class]                 view data types of a table 
  db:backup                                     backup table data into JSON file
  db:restore                                    restore last made backup from JSON file
  db:seed                                       perform database seeding

{SECURITY}
  hash:encode           [string]                returns the hash of a given string
  hash:verify           [string] [hashed]       verify whether data matches the hashed value
  encryption:encode     [string] [level=1]      encrypt a string with levels specified
  encryption:decode     [string] [level=1]      decrypt with same level set to string

EOF;

        } else {
            $this->command = $arguments;
            return $this->parseCommand();
        }

        return (true);
    }


    /**
     * Command Parser
     *
     * @return mixed
     */
    private function parseCommand()
    {
        $cmd = $this->command;

        switch ($cmd[1]) {

            /****************************************************
             *                   Cleaning                       *
             ***************************************************/

            case 'clear:all':
                $this->clear('backups');
                $this->clear('logs');
                return die("backups,logs,cache were cleared.\n");
                break;

            case 'clear:logs':
                $this->clear('logs');
                return die("logs directory cleared.\n");
                break;

            case 'clear:cache':
                $this->clear('cache');
                return die("cached pages cleared.\n");
                break;

            case 'clear:backups':
                $this->clear('backups');
                return die("backup directory cleared.\n");
                break;


            /*********************************************
             *               Generators                  *
             *********************************************/

            case 'create:model':
                if (isset($cmd[2])) {
                    $option = isset($cmd[3]) ? $cmd[3] : strtolower($cmd[2]);
                    return $this->createModel($cmd[2], $option);
                } else {
                    die("too few arguments, create:model expects [name], [table] is optional.\n");
                }
                break;

            case 'create:controller':
                if (isset($cmd[2])) {
                    $option = isset($cmd[3]) ? $cmd[3] : "false";
                    $this->createController($cmd[2], $option);
                } else {
                    die("too few arguments, create:controller expects [name], [empty](bool) is optional.\n");
                }
                break;

            case 'create:migration':
                if (isset($cmd[2]) && isset($cmd[3])) {
                    $this->createMigration($cmd[2], $cmd[3]);
                } else {
                    die("too few arguments, create:migration expects [name] [table].\n");
                }
                break;

            case 'create:request':
                if (isset($cmd[2])) {
                    $this->createRequest($cmd[2]);
                } else {
                    die("create:request expects 1 parameter [name].\n");
                }
                break;

            case 'create:seeder':
                if (isset($cmd[2]) && isset($cmd[3])) {
                    $this->createSeeder($cmd[2], $cmd[3]);
                } else {
                    die("too few arguments, create:seeder expects [name] [model].\n");
                }
                break;

            case 'create:key':
                return die(Hash::generateSalt());
                break;


            /********************************************************
             *             Database and Migrations                  *
             ********************************************************/

            case 'db:migrate':
                return $this->migrate('up');
                break;

            case 'db:rollback':
                return $this->migrate('down');
                break;

            case 'db:table:up':
                return $this->tableMigration($cmd[2], 'up');
                break;

            case 'db:table:down':
                return $this->tableMigration($cmd[2], 'down');
                break;

            case 'db:table:dump':
                return $this->tableDump($cmd[2]);
                break;

            case 'db:backup':
                return $this->backup();
                break;

            case 'db:restore':
                return $this->restore();
                break;

            case 'db:seed':
                return $this->seed();
                break;


            /****************************************************
             *                    Security                      *
             ***************************************************/

            case 'hash:encode':
                if (!isset($cmd[2])) {
                    die("hash:verify expects 1 parameter [data].\n");
                }
                return die(Hash::encode(trim($cmd[2], ' ')));
                break;

            case 'hash:verify':
                if (!isset($cmd[2]) || !isset($cmd[3])) {
                    die("too few arguments, hash:verify expects [data] and [hashed] value.\n");
                }
                return (Hash::verify($cmd[2], $cmd[3])) ? die("true") : die("false");
                break;

            case 'encryption:encode':
                if (isset($cmd[2]) && !isset($cmd[3])) {
                    return die(Encryption::encode($cmd[2]));
                } elseif (isset($cmd[2]) && isset($cmd[3])) {
                    return die(Encryption::encode($cmd[2], $cmd[3]));
                } else {
                    die("hash:verify requires 1 parameter [data], [levels] optional.\n");
                }
                break;

            case 'encryption:decode':
                if (isset($cmd[2]) && !isset($cmd[3])) {
                    return die(Encryption::decode($cmd[2]));
                } elseif (isset($cmd[2]) && isset($cmd[3])) {
                    return die(Encryption::decode($cmd[2], $cmd[3]));
                } else {
                    die("hash:verify requires 1 parameter [data], [levels] optional.\n");
                }
                break;

            default:
                die("error: unknown command '{$cmd[1]}' read documentation for info.\n");
                break;
        }

        return (true);
    }


    /**
     * Clear garbage files inside storage.
     *
     * @param $folder
     * @return boolean
     * @var $filename
     */
    private function clear($folder)
    {
        $container = storage_path() . $folder;

        try {
            $handle = new DirectoryIterator($container);

            foreach ($handle as $file):
                if ($file->getFilename() == '.' || $file->getFilename() == '..'):
                    continue;
                endif;
                if (is_file("{$container}/{$file->getFilename()}")):
                    unlink("{$container}/{$file->getFilename()}");
                endif;
            endforeach;

            return true;
        }
        catch (Exception $e) {
            return die($e->getMessage());
        }
    }


    /**
     * Create new model class
     *
     * @param $name
     * @param $table
     * @return string
     * @var $name
     * @var $data
     * @var $file
     * @var $container
     */
    private function createModel($name, $table)
    {
        $container = models_path();
        $data = <<<EOF
<?php namespace App\Models;

use Kernel\Database\QueryBuilder as Model;

/**
 * Class $name
 *
 * @package App\Models
 */
class {$name} extends Model
{
    protected static \$table = "{$table}";
}
EOF;

        if (file_exists("{$container}/{$name}.php")):
            return die("model '{$name}' already exists.\n");
        endif;

        $file = fopen("{$container}/{$name}.php", 'x');
        fwrite($file, $data);
        exec('composer dump-autoload');

        return die("'{$name}' model class created.\n");
    }


    /**
     * Create a new controller class
     * By default, second parameter is false
     * means, it will generate a class along
     * with predefined methods. If you want
     * to specify an empty class, use yamato
     * like this:
     * php yamato create:controller MyController true
     * the code above returns an empty class.
     *
     * @param $name
     * @param $option
     * @var $container
     * @var $name
     * @var $append
     * @var $methods
     * @var $data
     * @var $file
     */
    private function createController($name, $option = "false")
    {
        $container = controllers_path();
        $name = preg_replace('/controller/i', 'Controller', ucfirst($name));
        $append = <<<EOF

    /**
     * Controller Index
     *
     * @return mixed
     **/
    public function index()
    {

    }


    /**
     * Fetch resource
     *
     * @return mixed
     **/
    public function fetch()
    {

    }


    /**
     * Show all/a resource(s)
     *
     * @param \$id
     * @return mixed
     **/
    public function show(\$id)
	{

	}


    /**
     * Create a resource
     *
     * @return mixed
     * */
    public function create()
    {

    }


    /**
     * Store the resource
     *
     * @return mixed
     * */
    public function store()
    {

    }


    /**
     * Edit a resource
     *
     * @param \$id
     * @return mixed
     */
    public function edit(\$id)
    {

    }


    /**
     * update the resource
     *
     * @return mixed
     */
    public function update()
    {

    }


    /**
     * Destroy a resource
     *
     * @param \$id
     */
    public function destroy(\$id)
    {

    }


EOF;

        /**
         * if $option is 'empty', return an empty class
         */
        $methods = ($option == "true") ? '' : $append;

        $sub = preg_replace('/(.*)\/(.*)/', '$2', $name);
        $data = <<<EOF
<?php

class {$sub}
{
    {$methods}
}

EOF;

        if (file_exists("{$container}/{$name}.php")):
            return die("controller '{$name}' already exists.\n");
        endif;

        $file = fopen("{$container}/{$name}.php", 'x');
        fwrite($file, $data);

        return die("'{$sub}' class created.\n");
    }


    /**
     * Create a migration class
     *
     * @param $name
     * @param $table
     * @var $container
     * @var $name
     * @var $append
     * @var $methods
     * @var $data
     * @var $file
     */
    private function createMigration($name, $table)
    {
        $container = migrations_path();
        $name = preg_replace('/migration/i', 'Migration', ucfirst($name));
        $data = <<<EOF
<?php namespace App\Migrations;

use Kernel\Database\Migration;

class {$name} extends Migration
{

    /**
     * name of the table to migrate
     **/
    protected \$table = "{$table}";


    /**
     * field names and data types for this table
     */
    public function __construct()
    {
        \$this->increments('id');
    }


    /**
     * Install the migration
     *
     * @return boolean
     */
    public function up()
    {
        return \$this->install();
    }


    /**
     * Drop the table
     *
     * @return boolean
     */
    public function down()
    {
        return \$this->uninstall();
    }

}
EOF;

        if (file_exists("{$container}/{$name}.php")):
            return die("migration '{$name}' already exists.\n");
        endif;

        $file = fopen("{$container}/{$name}.php", 'x');
        fwrite($file, $data);
        exec('composer dump-autoload');

        return die("'{$name}' class created.\n");
    }


    /**
     * Create a request class
     *
     * @param $name
     * @var $container
     * @var $name
     * @var $append
     * @var $methods
     * @var $data
     * @var $file
     */
    private function createRequest($name)
    {
        $container = requests_path();
        $name = preg_replace('/request/i', 'Request', ucfirst($name));
        $data = <<<EOF
<?php namespace App\Requests;

use Kernel\Requests\HTTPRequest;

/**
 * Class $name
 *
 * @package App\Requests
 */
class {$name} extends HTTPRequest
{
    /**
     * Rules to be followed by request
     */
    protected \$rules = [];
}
EOF;

        if (file_exists("{$container}/{$name}.php")):
            return die("request '{$name}' already exists.\n");
        endif;

        $file = fopen("{$container}/{$name}.php", 'x');
        fwrite($file, $data);
        exec('composer dump-autoload');

        return die("'{$name}' class created.\n");
    }


    /**
     * @param $name
     * @param $model
     */
    private function createSeeder($name, $model)
    {
        if (!file_exists(models_path() . "$model.php")):
            return die("Model '$model' does not exist\n");
        endif;
        $container = seeders_path();
        $name = preg_replace('/seeder/i', 'Seeder', ucfirst($name));
        $model = ucfirst($model);
        $data = <<<EOF
<?php namespace App\Seeders;

use App\Models\\{$model};

/**
 * Class {$name}
 *
 * @package App\Seeders
 */
class {$name}
{
    /**
     * Seed the database table
     */
    public function __construct()
    {
        $model::insert([

        ]);
    }
}
EOF;

        if (file_exists("{$container}/{$name}.php")):
            return die("seeder '{$name}' already exists");
        endif;

        $file = fopen("{$container}/{$name}.php", 'x');
        fwrite($file, $data);
        exec('composer dump-autoload');

        return die("'{$name}' class created.\n");
    }


    /**
     * Install/Uninstall all migrations
     *
     * @param $action
     * @return mixed
     * @var $migration
     * @var $container
     */
    private function migrate($action)
    {
        $directory = migrations_path();

        try {
            $container = new DirectoryIterator($directory);
            $message = ($action == 'down') ? "database rolled back.\n" : "database successfully migrated.\n";

            foreach ($container as $handle) {
                if (is_file("{$directory}/{$handle->getFilename()}") && $handle->getFilename() !== '.htaccess') {
                    $migration = self::FixNamespace(MIGRATIONS_PATH, $handle->getBasename('.php'));
                    $migration = new $migration();
                    $migration->$action();
                }
            }

            return die($message);
        }
        catch (Exception $e) {
            return die($e->getMessage());
        }
    }


    /**
     * Backup database's current state
     *
     * @return string
     */
    private function backup()
    {
        $container = models_path();

        try {
            $iterator = new DirectoryIterator($container);

            foreach ($iterator as $handle):
                if (!is_file($container . $handle->getFilename())):
                    continue;
                endif;

                $model = self::FixNamespace(MODELS_PATH, $handle->getBasename('.php'));
                $model = new $model();
                $model->backup();
            endforeach;

            return die("Database tables backed up.\n");
        }
        catch (Exception $e) {
            return die($e->getMessage());
        }
    }


    /**
     * Restore last backed up table state
     *
     * @return string
     */
    private function restore()
    {
        $container = models_path();

        try {
            $iterator = new DirectoryIterator($container);

            foreach ($iterator as $handle):
                if (!is_file($container . $handle->getFilename())):
                    continue;
                endif;
                $model = self::FixNamespace(MODELS_PATH, $handle->getBasename('.php'));
                $model = new $model();
                $model->restore();
            endforeach;

            return die("Database restored.\n");
        }
        catch (Exception $e) {
            return die ($e->getMessage());
        }
    }


    /**
     * Perform database seeding
     *
     * @return string
     */
    private function seed()
    {
        $container = seeders_path();

        try {
            $iterator = new DirectoryIterator($container);

            foreach ($iterator as $handle):
                if (!is_file($container . $handle->getFilename())):
                    continue;
                endif;

                $seeder = self::FixNamespace(SEEDERS_PATH, $handle->getBasename('.php'));
                new $seeder();
            endforeach;

            return die("Seeding completed.\n");
        }
        catch (Exception $e) {
            return die($e->getMessage());
        }
    }


    /**
     * Install/Uninstall a migration table
     *
     * @param $className
     * @param $action
     * @return mixed
     */
    private function tableMigration($className, $action)
    {
        $filePath = migrations_path() . $className;

        if (file_exists($filePath . ".php")):
            $class = self::FixNamespace(MIGRATIONS_PATH, $className);
            if (class_exists($class)):
                $migration = new $class();
                $migration->$action();
                $message = ($action == 'down') ? "table rolled back.\n" : "table successfully migrated.\n";
            else:
                return die("'{$className}' class does not exist.\n");
            endif;
        else:
            return die("'{$className}' file does not exist.\n");
        endif;

        return die($message);
    }


    /**
     * Dumps data types of a migration class.
     * e.g. php yamato db:table:dump MigrationClassName
     *
     * @param $className
     * @return string
     */
    private function tableDump($className)
    {
        $filePath = migrations_path() . $className;

        if (file_exists($filePath . ".php")):
            $class = self::FixNamespace(MIGRATIONS_PATH, $className);
            if (class_exists($class)):
                $migration = new $class();
                return $migration->dump();
            else:
                return die("'{$className}' class does not exist.\n");
            endif;
        else:
            return die("'{$className}' file does not exist.\n");
        endif;
    }


    /**
     * This will fix any Namespacing coming from
     * a forward slash path as well as fixes any
     * non uppercase first letter.
     *
     *
     * @param $path
     * @param $class
     * @return string
     */
    private static function FixNamespace($path, $class)
    {
        $namespace = explode('/', trim($path, '/'));
        $container = null;

        foreach ($namespace as $dir):
            $container .= ucfirst($dir) . '\\';
        endforeach;

        return ($container . $class);
    }
}