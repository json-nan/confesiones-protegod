<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateUserCommand extends Command
{
    protected $signature = 'create:user {email} {password}';

    protected $description = 'Create a new user';

    public function handle(): void
    {
        $email = $this->argument('email');
        $password = $this->argument('password');

        User::create([
            'name' => $email,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $this->info('User created successfully');
    }
}
