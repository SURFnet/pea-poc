<?php

declare(strict_types=1);

namespace Tests\Feature\Exception\Handler;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class ValidationExceptionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Route::post('validation-test', function (Request $request): void {
            Validator::make($request->all(), ['email' => ['required', 'email']])->validate();
        });
    }

    /** @test */
    public function gives_the_proper_response_for_a_vistor(): void
    {
        $this
            ->post('/validation-test', [
                'email' => '',
            ])

            ->assertSessionHasErrors(['email' => trans('validation.required')]);
    }

    /** @test */
    public function gives_the_proper_response_for_an_api(): void
    {
        $this
            ->postJson('/validation-test', [
                'email' => '',
            ])

            ->assertStatus(422)
            ->assertJsonValidationErrors(['email' => trans('validation.required')]);
    }
}
