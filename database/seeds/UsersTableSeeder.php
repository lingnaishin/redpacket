<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert(
            [
                [
                    'name' => 'User 1',
                    'email' => 'user1@aaa.com',
                    'password' => 'abc',
                    'balance' => 500
                ],
                [
                    'name' => 'User 2',
                    'email' => 'user2@aaa.com',
                    'password' => 'abc',
                    'balance' => 0
                ],
                [
                    'name' => 'User 3',
                    'email' => 'user3@aaa.com',
                    'password' => 'abc',
                    'balance' => 100
                ],
                [
                    'name' => 'User 4',
                    'email' => 'user4@aaa.com',
                    'password' => 'abc',
                    'balance' => 200
                ],
                [
                    'name' => 'User 5',
                    'email' => 'user5@aaa.com',
                    'password' => 'abc',
                    'balance' => 300
                ]
            ]
        );
    }
}
