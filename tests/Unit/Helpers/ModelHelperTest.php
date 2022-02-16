<?php

declare(strict_types=1);

namespace Tests\Unit\Helpers;

use App\Helpers\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;

class ModelHelperTest extends TestCase
{
    /** @test */
    public function it_can_convert_a_collection_of_models_to_a_key_value_list_without_sorting(): void
    {
        $models = new Collection([
            User::factory()->create(['external_id' => uniqid('fake_', true), 'name' => 'name-4']),
            User::factory()->create(['external_id' => uniqid('fake_', true), 'name' => 'name-2']),
            User::factory()->create(['external_id' => uniqid('fake_', true), 'name' => 'name-3']),
            User::factory()->create(['external_id' => uniqid('fake_', true), 'name' => 'name-1']),
        ]);

        $expected = [
            $models[0]->external_id => 'name-4',
            $models[1]->external_id => 'name-2',
            $models[2]->external_id => 'name-3',
            $models[3]->external_id => 'name-1',
        ];

        $result = Model::asSelect($models, 'external_id', 'name', false);

        $this->assertEquals(json_encode($expected, JSON_PRETTY_PRINT), json_encode($result, JSON_PRETTY_PRINT));
    }

    /** @test */
    public function it_can_convert_a_collection_of_models_to_a_key_value_list_with_sorting_by_label(): void
    {
        $models = new Collection([
            User::factory()->create(['external_id' => uniqid('fake_', true), 'name' => 'name-1']),
            User::factory()->create(['external_id' => uniqid('fake_', true), 'name' => 'name-4']),
            User::factory()->create(['external_id' => uniqid('fake_', true), 'name' => 'name-2']),
            User::factory()->create(['external_id' => uniqid('fake_', true), 'name' => 'name-3']),
        ]);

        $expected = [
            $models[0]->external_id => 'name-1',
            $models[2]->external_id => 'name-2',
            $models[3]->external_id => 'name-3',
            $models[1]->external_id => 'name-4',
        ];

        $result = Model::asSelect($models, 'external_id', 'name', true);

        $this->assertEquals(json_encode($expected, JSON_PRETTY_PRINT), json_encode($result, JSON_PRETTY_PRINT));
    }
}
