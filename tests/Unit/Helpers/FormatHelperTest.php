<?php

declare(strict_types=1);

namespace Tests\Unit\Helpers;

use App\Helpers\Format;
use Tests\TestCase;

class FormatHelperTest extends TestCase
{
    /** @test */
    public function it_can_convert_numbers_to_formatted_strings(): void
    {
        $this->assertEquals('', Format::numberToString(null));
        $this->assertEquals('3', Format::numberToString(3, 0));
        $this->assertEquals('15,00', Format::numberToString(15, 2));
        $this->assertEquals('15,00', Format::numberToString(15.0));
        $this->assertEquals('230,556', Format::numberToString(230.5555, 3));
        $this->assertEquals('3.000,00', Format::numberToString(3000));
        $this->assertEquals('1.234,12', Format::numberToString(1234.1234));
        $this->assertEquals('32.475.893,7', Format::numberToString(32475893.6534, 1));
    }

    /** @test */
    public function it_can_convert_numbers_to_euro_formatted_strings(): void
    {
        $this->assertEquals('€ 3', Format::numberToEuroString(3, 0));
        $this->assertEquals('€ 15,00', Format::numberToEuroString(15, 2));
        $this->assertEquals('€ 15,00', Format::numberToEuroString(15.0));
        $this->assertEquals('€ 230,556', Format::numberToEuroString(230.5555, 3));
        $this->assertEquals('€ 3.000,00', Format::numberToEuroString(3000));
        $this->assertEquals('€ 1.234,12', Format::numberToEuroString(1234.1234));
        $this->assertEquals('€ 32.475.893,7', Format::numberToEuroString(32475893.6534, 1));
    }

    /** @test */
    public function it_can_convert_formatted_strings_to_numbers(): void
    {
        // This helper decides on its own what the separators are, based on the input.

        // In these cases, it uses dot for thousands separators and comma for decimal separators:
        $this->assertEquals(null, Format::stringToNumber(''));
        $this->assertEquals(3, Format::stringToNumber('3'));
        $this->assertEquals(15, Format::stringToNumber('15,00'));
        $this->assertEquals(230.556, Format::stringToNumber('230,556'));
        $this->assertEquals(3000, Format::stringToNumber('3.000,00'));
        $this->assertEquals(32475893.7, Format::stringToNumber('32.475.893,7'));
        $this->assertEquals(1234.12, Format::stringToNumber('1.234,12'));
        $this->assertEquals(1234567, Format::stringToNumber('1.234.567'));

        // In these cases, it reverses the separators:
        $this->assertEquals(1.234, Format::stringToNumber('1.234'));
        $this->assertEquals(122345.15, Format::stringToNumber('122,345.15'));
    }
}
