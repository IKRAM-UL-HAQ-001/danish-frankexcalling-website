<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RehashPasswords extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'passwords:rehash';

    /**
     * The console command description.
     */
    protected $description = 'One-time command: rehash all plain-text user passwords to bcrypt';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $users = User::all();
        $count = 0;

        foreach ($users as $user) {
            // Skip already-hashed passwords (bcrypt hashes start with $2y$)
            if (!str_starts_with($user->password, '$2y$') && !str_starts_with($user->password, '$argon')) {
                $user->password = Hash::make($user->password);
                $user->saveQuietly();
                $count++;
                $this->line("  ✔ Re-hashed password for user: {$user->name}");
            }
        }

        $this->info("Done. Re-hashed {$count} user password(s).");
        return Command::SUCCESS;
    }
}
