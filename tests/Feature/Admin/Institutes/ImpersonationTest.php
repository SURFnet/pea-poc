<?php

declare(strict_types=1);

namespace Tests\Feature\Admin\Institutes;

use App\Actions\Institute\ImpersonateAction;
use App\Models\Institute;
use Illuminate\Auth\Access\AuthorizationException;
use Tests\TestCase;

class ImpersonationTest extends TestCase
{
    /** @test */
    public function a_superadmin_can_login_as_another_institute(): void
    {
        $institute = Institute::factory()->create(['domain' => 'paqt.dev']);

        $this
            ->actingAs($this->admin)
            ->post(route('admin.institutes.impersonate', $institute))

            ->assertRedirect(route('home.index'));

        $this->assertNotEquals($institute->id, $this->admin->institute_id);
        $this->assertEquals($institute->id, $this->admin->impersonated_institute_id);
        $this->assertTrue($this->admin->isImpersonating());
        $this->assertEquals($institute->id, $this->admin->fresh()->institute->id);
    }

    /** @test */
    public function non_superadmins_cannot_impersonate_another_institute(): void
    {
        $institute = Institute::factory()->create(['domain' => 'paqt.dev']);

        $this->withoutExceptionHandling();
        $this->expectException(AuthorizationException::class);

        $this
            ->actingAs($this->informationManager)
            ->post(route('admin.institutes.impersonate', $institute));
    }

    /** @test */
    public function impersonated_institute_is_cleared_upon_logging_out_while_impersonating(): void
    {
        $institute = Institute::factory()->create(['domain' => 'paqt.dev']);

        (new ImpersonateAction())->execute($this->admin, $institute);

        $this
            ->actingAs($this->admin)
            ->post(route('account.logout'));

        $this->assertFalse($this->admin->isImpersonating());
        $this->assertNull($this->admin->impersonated_institute_id);
        $this->assertNotEquals($institute->id, $this->admin->fresh()->institute->id);
    }
}
