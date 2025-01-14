<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Roles extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            ['type' => 'admin', 'description' => 'System Administrator', 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'librarian', 'description' => 'Librarian Administrator', 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'member', 'description' => 'Public Member', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}