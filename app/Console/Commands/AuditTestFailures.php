<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AuditTestFailures extends Command
{
    protected $signature = 'audit:test-failures {--run : Run the current PHPUnit suite after the static audit}';

    protected $description = 'Explain known auth/profile/root-route test failures and optionally run php artisan test.';

    public function handle(): int
    {
        $this->info('Auditing known test failure areas...');

        $issues = [];

        if (str_contains($this->testFile('Feature/Auth/AuthenticationTest.php'), "'email' => \$user->email")) {
            $issues[] = [
                'AuthenticationTest::users_can_authenticate_using_the_login_screen',
                'Test posts email/password, but LoginRequest validates and authenticates username/password.',
                'Update the test to post username, or change LoginRequest to accept email login.',
            ];
        }

        if (! str_contains($this->testFile('Feature/Auth/RegistrationTest.php'), "'username' =>")) {
            $issues[] = [
                'RegistrationTest::new_users_can_register',
                'Test omits username, but registration requires username.',
                'Update the test payload to include username, or make username optional/generated.',
            ];
        }

        if (str_contains($this->testFile('Feature/ExampleTest.php'), 'assertStatus(200)')) {
            $issues[] = [
                'ExampleTest::the_application_returns_a_successful_response',
                'The root route is protected by auth and redirects guests to login.',
                'Expect a redirect in the test, or move the root route outside auth middleware.',
            ];
        }

        $profileTest = $this->testFile('Feature/ProfileTest.php');
        if (str_contains($profileTest, "->patch('/profile'") || str_contains($profileTest, "->delete('/profile'")) {
            $issues[] = [
                'ProfileTest update/delete tests',
                'The app exposes GET /profile as a Livewire profile page, but no PATCH /profile or DELETE /profile routes are registered.',
                'Update profile tests for Livewire, or register matching PATCH/DELETE profile routes.',
            ];
        }

        if (empty($issues)) {
            $this->info('No known auth/profile/root-route test mismatches found.');
        } else {
            $this->table(['Test area', 'Current mismatch', 'Suggested action'], $issues);
        }

        if ($this->option('run')) {
            $this->newLine();
            $this->info('Running php artisan test...');
            $exitCode = $this->call('test');

            return $exitCode === self::SUCCESS ? self::SUCCESS : self::FAILURE;
        }

        return empty($issues) ? self::SUCCESS : self::FAILURE;
    }

    private function testFile(string $relativePath): string
    {
        $path = base_path('tests/'.$relativePath);

        return file_exists($path) ? file_get_contents($path) : '';
    }
}
