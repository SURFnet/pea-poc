<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Auth\Role;
use App\Models\Institute;
use App\Models\User;

class UserSeeder extends BaseSeeder
{
    private int $usersPerInstitute = 5;

    public function handle(): void
    {
        Institute::each(function (Institute $institute): void {
            $users = User::factory()->count($this->usersPerInstitute)->create([
                'institute_id' => fn () => Institute::inRandomOrder()->first(),
            ]);

            $institute->users()->saveMany($users);

            $this->advanceProgressBar();
        });

        foreach (Role::toArray() as $role) {
            $this->command->info("Creating a user with role:$role");

            User::factory()->create([
                'name'         => "PAQT $role",
                'roles'        => [$role],
                'institute_id' => Institute::first(),
            ]);
        }
    }

    protected function getProgressBarCount(): int
    {
        return Institute::count();
    }
}
