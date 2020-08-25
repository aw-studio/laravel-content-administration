<?php

namespace Ignite\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class LitGuard extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lit:guard';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $config = require config_path('auth.php');
        $replace = file_get_contents(config_path('auth.php'));

        if (! array_key_exists('lit', $config['guards'])) {
            $this->info('generating lit guard');
            $replace = str_replace(
                "'guards' => [",
                "'guards' => [
        'lit' => [
            'driver' => 'session',
            'provider' => 'lit_users',
        ],",
                $replace
            );
        }

        if (! array_key_exists('lit_users', $config['providers'])) {
            $this->info('generating lit_users provider');
            $replace = str_replace(
                "'providers' => [",
                "'providers' => [
        'lit_users' => [
            'driver' => 'eloquent',
            'model' => Lit\User\Models\User::class,
        ],",
                $replace
            );
        }

        if (! array_key_exists('lit_users', $config['passwords'])) {
            $this->info('generating lit_users broker');
            $replace = str_replace(
                "'passwords' => [",
                "'passwords' => [
        'lit_users' => [
            'provider' => 'lit_users',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],",
                $replace
            );
        }

        File::put(config_path('auth.php'), $replace);
    }
}
