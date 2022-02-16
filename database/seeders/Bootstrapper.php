<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Institute;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class Bootstrapper extends Seeder
{
    public function run(): void
    {
        $this->linkStorage();
        $this->bootstrapApplication();
        $this->createSuperAdmin();
    }

    private function linkStorage(): void
    {
        $this->command->info('Linking storage');

        Artisan::call('storage:link');
    }

    private function bootstrapApplication(): void
    {
        $this->command->info('Running the application bootstrapper');

        Artisan::call('bootstrap:application');

        $this->command->info('Finished application bootstrapping');
    }

    private function createSuperAdmin(): void
    {
        $this->command->info('Creating the super admin');

        User::factory()
            ->superAdmin()
            ->make(['name' => 'PAQT Admin'])
            ->institute()
            ->associate(Institute::where('domain', 'eduid.nl')->firstOrFail())
            ->save();
    }
}
