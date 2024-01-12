<?php

namespace Database\Seeders;

use Database\Seeders\Traits\DisableForeignKeys;
use Database\Seeders\Traits\TruncateTable;
use Database\Seeders\UpdateSeeder\NotificationUpdateSeeder;
use Database\Seeders\UpdateSeeder\RolePermissionUpdateSeeder;
use Illuminate\Database\Seeder;

class AppVersionUpdateSeeder extends Seeder
{
    use TruncateTable, DisableForeignKeys;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        $this->call(StatusUpdateSeeder::class);
        $this->call(NotificationUpdateSeeder::class);
        $this->call(RolePermissionUpdateSeeder::class);
    }
}
