<?php

namespace App\Console\Commands;

use App\Models\Admin;
use Illuminate\Console\Command;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create {name : Numele adminului} {email : Email-ul adminului} {password : Parola adminului}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creează un cont nou de admin pentru live chat';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = strtolower(trim($this->argument('email')));

        if (Admin::where('email', $email)->exists()) {
            $this->error('Există deja un admin cu emailul: ' . $email);

            return self::FAILURE;
        }

        Admin::create([
            'name'     => $this->argument('name'),
            'email'    => $email,
            'password' => $this->argument('password'),
        ]);

        $this->info('Admin creat: ' . $this->argument('name') . ' (' . $email . ')');

        return self::SUCCESS;
    }
}
