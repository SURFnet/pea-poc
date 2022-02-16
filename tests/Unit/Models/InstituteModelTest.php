<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Models\Category;
use App\Models\Institute;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Tests\TestCase;

class InstituteModelTest extends TestCase
{
    /** @test */
    public function institute_categories_relationship_is_ordered_by_created_at_asc_by_default(): void
    {
        $institute = Institute::factory()->create(['domain' => '::domain::']);

        Category::factory()->count(5)
            ->state(new Sequence(
                ['name' => '::category-1::', 'created_at' => Carbon::now()->subHour()],
                ['name' => '::category-2::', 'created_at' => Carbon::now()->subWeek()],
                ['name' => '::category-3::', 'created_at' => Carbon::now()->subDay()],
                ['name' => '::category-4::', 'created_at' => Carbon::now()->subMonth()],
                ['name' => '::category-5::', 'created_at' => Carbon::now()->subYear()],
            ))->for($institute)->create();

        $this->assertTrue($institute->categories[0]->name === '::category-5::');
        $this->assertTrue($institute->categories[1]->name === '::category-4::');
        $this->assertTrue($institute->categories[2]->name === '::category-2::');
        $this->assertTrue($institute->categories[3]->name === '::category-3::');
        $this->assertTrue($institute->categories[4]->name === '::category-1::');
    }
}
