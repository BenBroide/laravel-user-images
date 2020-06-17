<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        // Test users
        /** @var \App\User $user */
        $users = factory(\App\User::class, 5)->create()->each(function ($user) {
            $user->images()->saveMany(factory(\App\Image::class, 2)->make());
        });

        // Set email for one test user
        $user = User::findOrFail(1);

        $user->email = 'test-user@example.com';
        $user->save();

        // Create Admin user
        Role::create([ 'name' => 'admin' ]);
        /** @var \App\User $user */
        $admin = factory(\App\User::class)->create([
            'name'  => 'John Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('12345678'),
        ]);


        $admin->assignRole('admin');
    }
}
