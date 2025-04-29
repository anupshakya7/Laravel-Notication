<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'=>'Anup Shakya',
            'email'=>'anupshk7@gmail.com',
            'phone_number'=>'952124577',
            'password'=>Hash::make('password'),
        ]);

        User::create([
            'name'=>'DEV 2',
            'email'=>'dev2@krizmatic.com',
            'phone_number'=>'952124523',
            'password'=>Hash::make('password'),
        ]);

        User::create([
            'name'=>'ADMIN',
            'email'=>'admin@admin.com',
            'phone_number'=>'952124522',
            'password'=>Hash::make('password'),
        ]);

        User::create([
            'name'=>'MANAGER',
            'email'=>'manager@manager.com',
            'phone_number'=>'952124525',
            'password'=>Hash::make('password'),
        ]);

        User::create([
            'name'=>'TEST',
            'email'=>'test@test.com',
            'phone_number'=>'952124521',
            'password'=>Hash::make('password'),
        ]);
    }
}
