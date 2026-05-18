<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = \App\Models\User::firstOrCreate(
            ['email' => 'admin@carrent.az'],
            [
                'name' => 'Admin',
                'password' => 'password',
                'phone' => '+994501234567',
                'is_active' => true,
            ]
        );
        $admin->syncRoles(['admin']);

        $agent = \App\Models\User::firstOrCreate(
            ['email' => 'agent@carrent.az'],
            [
                'name' => 'Agent Rəsul',
                'password' => 'password',
                'phone' => '+994507654321',
                'is_active' => true,
            ]
        );
        $agent->syncRoles(['agent']);

        $this->command->info('Admin: admin@carrent.az / password');
        $this->command->info('Agent: agent@carrent.az / password');
    }
}
