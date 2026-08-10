<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

class AuditSecurity extends Command
{
    protected $signature = 'audit:security';

    protected $description = 'Run a basic application security verification audit.';

    public function handle(): int
    {
        $this->info('Running security verification audit...');

        $checks = [
            $this->checkAppKey(),
            $this->checkDebugMode(),
            $this->checkAdminUser(),
            $this->checkUserColumns(),
            $this->checkRouteMiddleware(),
            $this->checkUploadSurface(),
        ];

        $this->table(['Check', 'Result', 'Details'], $checks);

        $failed = collect($checks)->contains(fn ($check) => $check[1] === 'FAIL');

        if ($failed) {
            $this->error('Security audit found blocking issues.');

            return self::FAILURE;
        }

        $this->info('Security audit completed without blocking issues.');

        return self::SUCCESS;
    }

    private function checkAppKey(): array
    {
        return config('app.key')
            ? ['APP_KEY', 'OK', 'Application key is configured.']
            : ['APP_KEY', 'FAIL', 'APP_KEY is missing.'];
    }

    private function checkDebugMode(): array
    {
        return config('app.debug')
            ? ['APP_DEBUG', app()->environment('production') ? 'FAIL' : 'WARN', 'Debug mode is enabled.']
            : ['APP_DEBUG', 'OK', 'Debug mode is disabled.'];
    }

    private function checkAdminUser(): array
    {
        if (! Schema::hasColumn('users', 'role')) {
            return ['Admin user', 'FAIL', 'users.role column is missing.'];
        }

        return User::where('role', 'admin')->exists()
            ? ['Admin user', 'OK', 'At least one admin user exists.']
            : ['Admin user', 'FAIL', 'No admin user exists.'];
    }

    private function checkUserColumns(): array
    {
        $required = ['username', 'role', 'profile_photo_path', 'password'];
        $missing = collect($required)->reject(fn ($column) => Schema::hasColumn('users', $column))->values();

        return $missing->isEmpty()
            ? ['User columns', 'OK', 'Required user account columns are present.']
            : ['User columns', 'FAIL', 'Missing: '.$missing->join(', ')];
    }

    private function checkRouteMiddleware(): array
    {
        $publicNames = ['login', 'register', 'password.request', 'password.email', 'password.reset', 'password.store'];
        $unguarded = [];

        foreach (Route::getRoutes() as $route) {
            $name = $route->getName();
            if (
                ! $name
                || in_array($name, $publicNames, true)
                || str_starts_with($name, 'debugbar.')
                || str_starts_with($name, 'livewire.')
                || str_starts_with($name, 'storage.')
                || $route->uri() === 'up'
            ) {
                continue;
            }

            $middleware = $route->gatherMiddleware();
            if (! in_array('auth', $middleware, true) && ! in_array('auth:web', $middleware, true)) {
                $unguarded[] = $name;
            }
        }

        return empty($unguarded)
            ? ['Route middleware', 'OK', 'Named application routes are auth protected where expected.']
            : ['Route middleware', 'WARN', 'Review unguarded routes: '.implode(', ', array_slice($unguarded, 0, 8))];
    }

    private function checkUploadSurface(): array
    {
        $files = [
            app_path('Livewire/Products/ProductForm.php'),
            app_path('Livewire/Users/UserForm.php'),
            app_path('Livewire/Profile/EditProfile.php'),
        ];

        $missing = collect($files)
            ->filter(fn ($file) => file_exists($file))
            ->reject(fn ($file) => str_contains(file_get_contents($file), "'image'") && str_contains(file_get_contents($file), 'max:2048'))
            ->map(fn ($file) => basename($file))
            ->values();

        return $missing->isEmpty()
            ? ['Uploads', 'OK', 'Known image upload forms validate image type and size.']
            : ['Uploads', 'WARN', 'Review upload validation in: '.$missing->join(', ')];
    }
}
