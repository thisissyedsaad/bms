<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('users')->insert([
	        'name' => 'admin',
	        'email' => 'admin@admin.com',
            'password' => Hash::make('admin123'),
            'email_verified_at' => Carbon::now()->format('Y-m-d H:i:s'),
	        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
    		'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'is_active' => true
        ]);

        DB::table('users')->insert([
	        'name' => 'user',
	        'email' => 'user@user.com',
            'password' => Hash::make('user123'),
            'email_verified_at' => Carbon::now()->format('Y-m-d H:i:s'),
	        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
    		'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'is_active' => true
        ]);
    }
}
