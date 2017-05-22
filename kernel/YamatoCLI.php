<?php namespace Kernel;

use DirectoryIterator;
use Kernel\Security\Encryption;
use Kernel\Security\Hash;

/**
 * Class YamatoCLI
 *
 * @package Kernel
 */
final class YamatoCLI
{
    /**
     * Holds command array of arguments
     */
    private $command = null;


    /**
     * Get the arguments on instantiation
     *
     * @param $arguments
     */
    public function __construct($arguments = null)
    {
        if (!isset($arguments[1])) {
        /**
         * Yamato Cover
         */

        echo <<<EOF

           ..      ..   ..     ..    -#      ..
 ,#   x#  - ,#=  ##;;##+,+#   -..x# ++#++ .#+,-#X
  ##  #    ,.##  #.  =#   #+   ,,-#  ,#   #+    #.
   #-#+  #X  +#  #   =#   #+ -#.  #. -#   #=    #.
    ##   #X.,##  #.  +#   #x =#..x#. .#-. .#+,-#x
   ;#     ,,.    .    .   .    ;,      ;,   ,;;
  +X

 Yamato is a Command Line Interface(CLI) tool that is
 made to interact with the Strife Framework.


     COMMANDS                 ARGUMENTS                 DESCRIPTION
--------------------	--------------------    -----------------------------

{CLEANUP}
  clear:sessions                                clear sessions directory
  clear:logs                                    clear logs directory
  clear:backups                                 clear backups directory
  clear:all                                     clear all directories(except backups)
  
{GENERATORS}
  create:model          [name] [table=null]     create a model class
  create:controller     [name] [empty=null]     create a controller class
  create:migration      [name] [table=null]     create a migration class
  create:process        [name]                  create a process class
  create:request        [name]                  create a request class
  create:seeder         [name] [model=null]     create a database seeder class
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
                $this->clear('sessions');
                $this->clear('logs');
                return die("all trash cleared.");
                break;

            case 'clear:sessions':
                $this->clear('sessions');
                return die("sessions directory cleared.");
                break;

            case 'clear:logs':
                $this->clear('logs');
                return die("logs directory cleared.");
                break;

            case 'clear:backups':
                $this->clear('backups');
                return die("backups directory cleared.");
                break;



            /*********************************************
             *               Generators                  *
             *********************************************/

            case 'create:model':
                if (isset($cmd[2])) {
                    $option = isset($cmd[3]) ? $cmd[3] : strtolower($cmd[2]);
                    return $this->createModel($cmd[2], $option);
                } else {
                    die("too few arguments, create:model expects [name], [table] is optional");
                }
               break;

            case 'create:controller':
                if (isset($cmd[2])) {
                    $option = isset($cmd[3]) ? $cmd[3] : null;
                    $this->createController($cmd[2], $option);
                } else {
                    die("too few arguments, create:controller expects [name], [empty] is optional");
                }
                break;

            case 'create:migration':
                if (isset($cmd[2]) && isset($cmd[3])) {
                    $this->createMigration($cmd[2], $cmd[3]);
                } else {
                    die("too few arguments, create:migration expects [name] [table]");
                }
                break;

            case 'create:request':
                if (isset($cmd[2])) {
                    $this->createRequest($cmd[2]);
                } else {
                    die("create:request expects 1 parameter [name]");
                }
                break;

            case 'create:process':
                if (isset($cmd[2])) {
                    $this->createProcess($cmd[2]);
                } else {
                    die("create:process expects parameter [name]");
                }
                break;

            case 'create:seeder':
                if (isset($cmd[2]) && isset($cmd[3])) {
                    $this->createSeeder($cmd[2], $cmd[3]);
                } else {
                    die("too few arguments, create:seeder expects [name] [table]");
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
                return $this->tableMigration($cmd[2], 'dump');
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
                    die("hash:verify expects 1 parameter [data]");
                }
                return die(Hash::encode(trim($cmd[2], ' ')));
                break;

            case 'hash:verify':
                if (!isset($cmd[2]) || !isset($cmd[3])) {
                    die("too few arguments, hash:verify expects [data] and [hashed] value");
                }
                return (Hash::verify($cmd[2], $cmd[3])) ? die("true") : die("false");
                break;

            case 'encryption:encode':
                if (isset($cmd[2]) && !isset($cmd[3])) {
                    return die(Encryption::encode($cmd[2]));
                }
                elseif (isset($cmd[2]) && isset($cmd[3])) {
                    return die(Encryption::encode($cmd[2],$cmd[3]));
                } else {
                    die("hash:verify requires 1 parameter [data], [levels] optional");
                }
                break;

            case 'encryption:decode':
                if (isset($cmd[2]) && !isset($cmd[3])) {
                    return die(Encryption::decode($cmd[2]));
                }
                elseif (isset($cmd[2]) && isset($cmd[3])) {
                    return die(Encryption::decode($cmd[2],$cmd[3]));
                } else {
                    die("hash:verify requires 1 parameter [data], [levels] optional");
                }
                break;

            default:
                die("error: unknown command '{$cmd[1]}' read documentation for info.");
                break;
        }

        return (true);
    }


    /**
     * Clear garbage files inside storage.
     *
     * @param $folder
     * @var $filename
     * @return boolean
     */
    private function clear($folder)
    {
        $container = storage_dir() . $folder;
        $handle = new DirectoryIterator($container);

        foreach ($handle as $file) {
            if ($file->getFilename() == '.' || $file->getFilename() == '..') {
                continue;
            }
            if (is_file("{$container}/{$file->getFilename()}")) {
                unlink("{$container}/{$file->getFilename()}");
            }
        }

        return true;
    }


    /**
     * Create new model class
     *
     * @param $name
     * @param $table
     * @var $container
     * @var $name
     * @var $data
     * @var $file
     * @return string
     */
    private function createModel($name, $table)
    {
        $container = app_dir() . 'models';
        $data = <<<EOF
<?php namespace App\Models;

use Kernel\Database\QueryBuilder as Model;

class {$name} extends Model
{
    protected static \$table = "{$table}";
}
EOF;

        if (file_exists("{$container}/{$name}.php")) {
            return die("model '{$name}' already exists");
        }

        $file = fopen("{$container}/{$name}.php", 'x');
        fwrite($file, $data);
        exec('composer dump-autoload');

        return die("'{$name}' model class created.");
    }


    /**
     * Create a new controller class
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
    private function createController($name, $option = null)
    {
        $container = app_dir() . 'controllers';
        $name = preg_replace('/controller/i', 'Controller', ucfirst($name));
        $append = <<<EOF

    /**
     * Controller Index
     *
     * @return view
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
     * @return view
     * */
    public function create()
    {

    }


    /**
     * Store the resource
     *
     * @return view
     * */
    public function store()
    {

    }


    /**
     * Edit a resource
     *
     * @param \$id
     * @return view
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
        $methods = ($option == 'empty') ? '' : $append;

        $sub = preg_replace('/(.*)\/(.*)/', '$2', $name);
        $data = <<<EOF
<?php

class {$sub}
{
    {$methods}
}

EOF;

        if (file_exists("{$container}/{$name}.php")) {
            return die("controller '{$name}' already exists");
        }

        $file = fopen("{$container}/{$name}.php", 'x');
        fwrite($file, $data);

        return die("'{$sub}' class created.");
    }


    /**
     * Create a migration class
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
        $container = app_dir() . 'migrations';
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
     * @return void
     */
    public function up()
    {
        return \$this->install();
    }


    /**
     * Drop the table
     *
     * @return void
     */
    public function down()
    {
        return \$this->uninstall();
    }

}
EOF;

        if (file_exists("{$container}/{$name}.php")) {
            return die("migration '{$name}' already exists");
        }

        $file = fopen("{$container}/{$name}.php", 'x');
        fwrite($file, $data);
        exec('composer dump-autoload');

        return die("'{$name}' class created.");
    }


    /**
     * Create a request class
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
        $container = app_dir() . 'requests';
        $name = preg_replace('/request/i', 'Request', ucfirst($name));
        $data = <<<EOF
<?php namespace App\Requests;

use Kernel\Requests\HTTPRequest;

class {$name} extends HTTPRequest
{

    /**
     * This is the route that will be used
     * to redirect when errors are present
     */
    protected \$route = '/';


    /**
     * Rules to be followed by request
     */
    protected \$rules = [];

}
EOF;

        if (file_exists("{$container}/{$name}.php")) {
            return die("request '{$name}' already exists");
        }

        $file = fopen("{$container}/{$name}.php", 'x');
        fwrite($file, $data);
        exec('composer dump-autoload');

        return die("'{$name}' class created.");
    }


    /**
     * @param $name
     * @param $model
     */
    private function createSeeder($name, $model)
    {
        if (!file_exists(app_dir() . "models/$model.php")) {
            return die("Model '$model' does not exist");
        }
        $container = app_dir() . 'seeders/';
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

        if (file_exists("{$container}/{$name}.php")) {
            return die("seeder '{$name}' already exists");
        }

        $file = fopen("{$container}/{$name}.php", 'x');
        fwrite($file, $data);
        exec('composer dump-autoload');

        return die("'{$name}' class created.");
    }


    /**
     * Create a process class
     * @param $name
     * @var $container
     * @var $name
     * @var $append
     * @var $methods
     * @var $data
     * @var $file
     */
    private function createProcess($name)
    {
        $container = app_dir() . 'processes';
        $name = preg_replace('/process/i', 'Process', ucfirst($name));
        $data = <<<EOF
<?php

/**
 * TODO: Execute a process to separate the logic from it's controller.
 * Class {$name}
 */
class {$name}
{
    /**
     * Execute the Process
     *
     * @todo execute
     * @param \$callback
     * @return mixed
     */
    public function execute(\$callback = "")
    {
        //logic here

        //return callback on fail
        return \$callback();
    }
}
EOF;

        if (file_exists("{$container}/{$name}.php")) {
            return die("process '{$name}' already exists");
        }

        $file = fopen("{$container}/{$name}.php", 'x');
        fwrite($file, $data);
        exec('composer dump-autoload');

        return die("'{$name}' class created.");
    }


    /**
     * Install/Uninstall all migrations
     *
     * @param $action
     * @var $container
     * @var $migration
     * @return mixed
     */
    private function migrate($action)
    {
        $directory = app_dir() . 'migrations';
        $container = new DirectoryIterator($directory);
        $message = ($action == 'down') ? "database rolled back." : "database successfully migrated.";

        foreach ($container as $handle) {
            if (is_file("{$directory}/{$handle->getFilename()}")) {
                $migration = "App\\Migrations\\" . $handle->getBasename('.php');
                $migration = new $migration();
                $migration->$action();
            }
        }

        return die($message);
    }


    /**
     * Backup database's current state
     *
     * @return string
     */
    private function backup() {
        $container = app_dir() . 'models/';
        $iterator = new DirectoryIterator($container);

        foreach ($iterator as $it) {
            if (!is_file($container . $it->getFilename())) {
                continue;
            }

            $model =  'App\Models\\' . str_replace('.php', '', $it->getFilename());
            $model = new $model();
            $model->backup();
        }

        return die("Database tables backed up.");
    }


    /**
     * Restore last backed up table state
     *
     * @return string
     */
    private function restore() {
        $container = app_dir() . 'models/';
        $iterator = new DirectoryIterator($container);

        foreach ($iterator as $it) {
            if (!is_file($container . $it->getFilename())) {
                continue;
            }

            $model =  'App\Models\\' . str_replace('.php', '', $it->getFilename());
            $model = new $model();
            $model->restore();
        }

        return die("Database restored.");
    }


    /**
     * Perform database seeding
     *
     * @return string
     */
    private function seed() {
        $container = app_dir() . 'seeders/';
        $iterator = new DirectoryIterator($container);

        foreach ($iterator as $it) {
            if (!is_file($container . $it->getFilename())) {
                continue;
            }

            $seeder =  'App\Seeders\\' . str_replace('.php', '', $it->getFilename());
            new $seeder();
        }

        return die("Seeding completed.");
    }


    /**
     * Install/Uninstall a migration table
     *
     * @param $name
     * @param $action
     * @var $container
     * @var $migration
     * @return mixed
     */
    private function tableMigration($name, $action)
    {
        $className = "App\\Migrations\\" . $name;

        if (file_exists("./app/migrations/{$name}.php")) {
            if (class_exists($className)) {
                $migration = new $className();
                $migration->$action();

                $message = ($action == 'down') ?
                    "table rolled back." : "table successfully migrated.";
            } else {
                return die("'{$name}' class does not exist.");
            }
        } else {
            return die("'{$name}' file does not exist.");
        }

        return die($message);
    }
}