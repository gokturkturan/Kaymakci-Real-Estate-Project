<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Creates the initial admin account from ADMIN_SEED_EMAIL/ADMIN_SEED_PASSWORD
     * in .env — never hardcode real credentials here, this file is committed.
     *
     * Only runs once: if an account with this email already exists, it's left
     * untouched so re-running the seeder (e.g. a `migrate:fresh --seed` by
     * accident) can never reset a password that was since changed in production.
     */
    public function run(): void
    {
        $email = env('ADMIN_SEED_EMAIL');
        $password = env('ADMIN_SEED_PASSWORD');

        if (!$email || !$password) {
            $this->command?->warn('Skipping AdminSeeder: set ADMIN_SEED_EMAIL and ADMIN_SEED_PASSWORD in .env to create the admin account.');
            return;
        }

        if (User::where('email', $email)->exists()) {
            $this->command?->info("AdminSeeder: {$email} already exists, leaving it untouched.");
            return;
        }

        User::create([
            'name' => 'Admin',
            'email' => $email,
            'password' => Hash::make($password),
            'is_admin' => true,
        ]);
    }
}
