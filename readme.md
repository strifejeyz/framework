# Strife PHP Framework Documentation

## Introduction
Strife is a fast and lightweight PHP MVC framework designed to simplify web application development. It provides powerful tools and features for building robust and maintainable applications.

## Features
- **Smart Routing**: Flexible and intuitive routing for handling HTTP requests.
- **Multi-Database Support**: Compatible with various database systems.
- **Query Builder**: Simplifies database interactions.
- **Form Builder**: Easy generation and validation of forms.
- **Migration Management**: Manage database schema changes effortlessly.
- **Encryption & Caching**: Built-in tools for secure and optimized applications.
- **Built-in CLI**: Command-line tools for rapid development.
- **Template Engine**: For clean and maintainable HTML views.

## Requirements
- PHP 7.0 or higher
- A web server (e.g., Apache or Nginx)
- Composer (for dependency management)

## Installation
1. Clone the repository:
   ```bash
   git clone https://github.com/strifejeyz/framework.git
   ```
2. Navigate to the project directory:
   ```bash
   cd framework
   ```
3. Install dependencies:
   ```bash
   composer install
   ```

## Directory Structure
```
/
├── app/
├── kernel/
├── storage/
├── vendor/
├── .gitignore
├── .htaccess
├── composer.json
├── composer.lock
├── favicon.ico
├── index.php
├── LICENSE
├── readme.md
└── yamato
```

## Usage

### Routing
Define routes in `app/routes.php`:
```php
get('/users', 'UsersController@index');
post('/store', 'UsersController@store');
```
- `'/users'`: The route endpoint.
- `UsersController@index`: The class name and method to call.

You can use either `get()` or `post()` when defining routes.

To assign a name to a route, use the following syntax:
```php
get('users-list -> /users', 'UsersController@index');
```

If **File-based routing** is enabled, the `app/routes.php` file is ignored. Instead, you can directly call a class and method. 
For example, if `UsersController` is the class name and `index` is the method, you can invoke the endpoint `/users/index`. 
This accommodates any HTTP request method.

### Controllers
Create controllers in the `app/controllers` directory:
```php
use App\Models\User;

class HomeController
{
    public function index()
    {
        $title = "Home Page";
        $users =  User::get();
        return render('index', compact('title','users'));
    }
}
```

### Models
Define models in the `app/models` directory:
```php
use Kernel\Database\QueryBuilder as Model;

class User extends Model
{
    protected static $table = "users";
}
```

### Views
Place your templates in `app/views`:
```html
<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
</head>
<body>

<ul>
   {{foreach($users as $user)}}
   <li>{{$user->firstname}}</li>
   {{endforeach}}
</ul>

</body>
</html>
```

### Template Engine
The Strife PHP Framework includes a powerful template engine for building clean and maintainable views.

#### Extending and Rendering Templates
- **`@extend('template_name')`**: Extends a base template.
- **`@render('template_name')`**: Renders a specified template.

#### Section Management
- **`@stop()`**: Marks the end of an extended layout/template.
- **`@get('section_name')`**: Similar behavior to include().

### Importing and Conditionals
- **`@import('app\models\Users')`**: Imports a class
- **`{{if(condition)}} ... {{endif}}`**: Executes the enclosed code block if the condition is true.
- **`{{elseif(condition)}} ... {{endelseif}}`**: Checks an alternative condition.
- **`{{else}}`**: Runs an alternative block if the `if` condition fails.
- **`{{endif}}`**: Marks the end of an `if` block.

#### Loops and Iterations
- **`{{for(condition)}} ... {{endfor}}`**: Runs a block of code a specified number of times.
- **`{{do(condition)}} ... {{enddo}}`**: Executes the enclosed block once.
- **`{{while(condition)}} ... {{endwhile}}`**: Loops while the condition is true.
- **`{{foreach(condition)}} ... {{endforeach}}`**: Iterates over an array or object.

### Example Usage
#### Extending a Template
```php
@extend('layouts/frontend')

<p>Welcome back, user!</p>

@stop
```

#### Conditional Rendering
```php
{{if($userIsLoggedIn)}}
    <p>Welcome back, user!</p>
{{else}}
    <p>Please log in.</p>
{{endif}}
```

#### Loop Example
```php
{{foreach($items as $item)}}
    <p>{{ $item }}</p>
{{endforeach}}
```
Use these functions to build flexible and dynamic views efficiently.


### Query Builder
This guide explains how to use the most common methods in the Query Builder class for running queries.

#### `where`
Filters results based on a condition.

```php
$query = Users::where('column_name', '=', 'value');
```
- **Parameters**:
   - `field`: The column to filter.
   - `a`: The comparison operator (e.g., `=`).
   - `b`: The value to compare against.

#### `join`
Performs an inner join with another table.

```php
$query = Users::join('another_table')->on('table.id = another_table.foreign_id');
```
- **Parameters**:
   - `table`: The table to join.
- **Chaining**:
   - Use `on` to specify the join condition.

#### `get`
Fetches the results of the query.

```php
$results = Users::get();
```
- **Parameters**:
   - `fetchMode` (optional): Defaults to `PDO::FETCH_OBJ`.

#### `select`
Specifies the columns to retrieve.

```php
$query = Users::select(['column1', 'column2']);
```
- **Parameters**:
   - `selection`: An array of column names to select.

#### `order`
Sorts the results.

```php
$query = Users::order('column_name', 'ASC');
```
- **Parameters**:
   - `field`: The column to sort by.
   - `order`: `ASC` for ascending or `DESC` for descending.

#### `limit`
Limits the number of results.

```php
$query = Users::limit(10, 5);
```
- **Parameters**:
   - `number`: The maximum number of results.
   - `offset` (optional): The starting point for the results.

#### `first`
Fetches the first result of the query.

```php
$firstResult = Users::first();
```

#### `count`
Counts the number of results.

```php
$total = Users::count();
```

#### Example Usage
Here is an example combining some of these methods:

```php
$results = Users::select(['id', 'name'])
    ->where('status', '=', 'active')
    ->order('created_at', 'DESC')
    ->limit(10)
    ->get();
```

### Database Migrations
Generate a migration:
```bash
php yamato create:migration create_users_table
```
Edit the migration file and run:
```bash
php yamato db:migrate
```

## Command-Line Interface (CLI)
Use Yamato CLI for various tasks:
```bash
php yamato
```

### Common Commands

#### Cleanup
- `php yamato clear:logs`: Clear logs directory.
- `php yamato clear:cache`: Clear cached pages.
- `php yamato clear:all`: Clear all backups, logs, and cache.

#### Generators
- `php yamato create:model [name] [table=null]`: Create a model class.
- `php yamato create:controller [name] [empty=bool]`: Create a controller class.
- `php yamato create:migration [name] [table]`: Create a migration class.
- `php yamato create:request [name]`: Create a request class.
- `php yamato create:key`: Generate an application key.

#### Database
- `php yamato db:migrate`: Install all migrations.
- `php yamato db:rollback`: Rollback all migrations.
- `php yamato db:backup`: Backup table data into a JSON file.
- `php yamato db:restore`: Restore the last made backup.
- `php yamato db:seed`: Perform database seeding.

#### Security
- `php yamato hash:encode [string]`: Returns the hash of a given string.
- `php yamato encryption:encode [string] [level=1]`: Encrypt a string.
- `php yamato encryption:decode [string] [level=1]`: Decrypt a string.

## Contributing
Contributions are welcome! Please follow these steps:
1. Fork the repository.
2. Create a new branch for your feature or bugfix.
3. Submit a pull request with a detailed description of your changes.

## License
Strife is open-source software licensed under the [MIT License](LICENSE).

## Support
For questions or support, please open an issue on [GitHub](https://github.com/strifejeyz/framework/issues).
