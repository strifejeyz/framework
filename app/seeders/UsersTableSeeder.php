<?php namespace App\Seeders;

use App\Models\User;
use Kernel\Security\Hash;

/**
 * Class UsersTableSeeder
 *
 * @package App\Seeders
 */
class UsersTableSeeder
{
    /**
     * Seed the database table
     */
    public function __construct()
    {
        User::insert([
            'firstname' => 'John',
            'lastname' => 'Doe',
            'username' => 'username',
            'password' => Hash::encode('password'),
            'created' => time(),
            'updated' => time(),
        ]);
    }
}