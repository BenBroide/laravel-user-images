<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class UsersTableSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        // Reset cached roles and permissions
        app()['cache']->forget( 'spatie.permission.cache' );

        /** @var \App\User $user */
        $users = factory( \App\User::class, 5 )->create()->each(function ($user) {
            $user->images()->saveMany(factory(\App\Image::class,5)->make());
        });

        Role::create( [ 'name' => 'admin' ] );
        /** @var \App\User $user */
        $admin = factory( \App\User::class )->create( [
            'name'  => 'John Admin',
            'email' => 'admin@example.com',
        ] );


        $admin->assignRole( 'admin' );
    }
}
