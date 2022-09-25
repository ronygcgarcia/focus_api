<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Genre;
use App\Models\RoleRoute;
use App\Models\Route;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $roleLibrarian = Role::create(['name' => 'librarian']);
        Role::create(['name' => 'student']);
        $user = User::create([
            'first_name' => 'Rony',
            'last_name' => 'Garcia',
            'email' => 'ronygcgarcia@gmail.com',
            'password' => Hash::make('admin')
        ]);

        $user->assignRole($roleLibrarian);

        Genre::create([
            'name' => 'Horror'
        ]);
        Genre::create([
            'name' => 'Drama'
        ]);
        Genre::create([
            'name' => 'Thriller'
        ]);

        // auth
        Route::create([
            'name' => 'Books',
            'uri' => '/',
            'icon' => 'book',
            'orden' => '1'
        ]);

        // student and librarian
        Route::create([
            'name' => 'Checkouts',
            'uri' => '/checkouts',
            'icon' => 'checkout',
            'orden' => '2'
        ]);

        // librarian
        Route::create([
            'name' => 'Users',
            'uri' => '/users',
            'icon' => 'user',
            'orden' => '3'
        ]);

        RoleRoute::insert([
            [
                'route_id' => 1,
                'role_id' => 1
            ],
            [
                'route_id' => 1,
                'role_id' => 2
            ],
            [
                'route_id' => 2,
                'role_id' => 2
            ],
            [
                'route_id' => 2,
                'role_id' => 1
            ],
            [
                'route_id' => 3,
                'role_id' => 1
            ]
        ]);
    }
}
