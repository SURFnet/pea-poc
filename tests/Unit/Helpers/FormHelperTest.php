<?php

declare(strict_types=1);

namespace Tests\Unit\Helpers;

use App\Helpers\Form;
use Tests\TestCase;

class FormHelperTest extends TestCase
{
    /** @test */
    public function it_can_add_missing_old_values_to_a_list_of_options(): void
    {
        $options = ['name-1' => 'Name 1', 'name-2' => 'Name 2', 'name-3' => 'Name 3'];
        $oldValues = ['name-1', 'extra-name', 'another-name'];

        $expected = [
            'name-1'       => 'Name 1',
            'name-2'       => 'Name 2',
            'name-3'       => 'Name 3',
            'extra-name'   => 'extra-name',
            'another-name' => 'another-name',
        ];
        $result = Form::appendNewOldValues($options, $oldValues);

        $this->assertEquals(json_encode($expected, JSON_PRETTY_PRINT), json_encode($result, JSON_PRETTY_PRINT));
    }

    /** @test */
    public function it_returns_the_original_options_if_old_values_is_empty_or_not_an_array(): void
    {
        $options = ['name-1' => 'Name 1', 'name-2' => 'Name 2', 'name-3' => 'Name 3'];

        $result = Form::appendNewOldValues($options, null);
        $this->assertEquals(json_encode($options, JSON_PRETTY_PRINT), json_encode($result, JSON_PRETTY_PRINT));

        $result = Form::appendNewOldValues($options, []);
        $this->assertEquals(json_encode($options, JSON_PRETTY_PRINT), json_encode($result, JSON_PRETTY_PRINT));

        $result = Form::appendNewOldValues($options, 'some-value');
        $this->assertEquals(json_encode($options, JSON_PRETTY_PRINT), json_encode($result, JSON_PRETTY_PRINT));
    }

    /** @test */
    public function it_can_convert_array_bracket_syntax_to_dot_syntax(): void
    {
        $this->assertEquals('user_name', Form::arrayNameToError('user_name'));
        $this->assertEquals('items.1', Form::arrayNameToError('items[1]'));
        $this->assertEquals('users.1.roles.3', Form::arrayNameToError('users[1][roles][3]'));
    }
}
