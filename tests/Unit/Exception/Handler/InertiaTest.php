<?php

declare(strict_types=1);

namespace Tests\Unit\Exception\Handler;

use App\Exceptions\Handler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Mockery;
use Mockery\LegacyMockInterface;
use stdClass;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tests\TestCase;

class InertiaTest extends TestCase
{
    private Request $request;

    private LegacyMockInterface $response;

    private Handler $handler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = Request::create('/awesome-test', 'GET');
        $this->response = Mockery::mock(stdClass::class);
        $this->handler = $this->app->make(Handler::class);
    }

    /** @test */
    public function will_render_an_inertia_page_if_it_should(): void
    {
        Config::set('app.env', 'production');

        $this->request->headers->add(['X-Inertia' => 'true']);
        $this->request->headers->add(['X-Inertia-Partial-Component' => 'User/Edit']);
        $this->request->headers->add(['X-Inertia-Partial-Data' => 'partial']);

        $response = $this->handler->render($this->request, new NotFoundHttpException());

        $data = json_decode($response->getContent(), true);

        $this->assertEquals('Error', $data['component']);
        $this->assertEquals(404, $data['props']['status']);
        $this->assertEquals('/awesome-test', $data['url']);
    }

    /** @test */
    public function will_not_render_an_inertia_page_if_it_should_not(): void
    {
        Config::set('app.env', 'local');

        $this->request->headers->add(['X-Inertia' => 'true']);
        $this->request->headers->add(['X-Inertia-Partial-Component' => 'User/Edit']);
        $this->request->headers->add(['X-Inertia-Partial-Data' => 'partial']);

        $response = $this->handler->render($this->request, new NotFoundHttpException());

        $data = json_decode($response->getContent(), true);

        $this->assertEmpty($data);
    }

    /** @test */
    public function should_go_to_an_inertia_page_on_production_inside_inertia_with_a_404_exception(): void
    {
        Config::set('app.env', 'production');

        $this->request->headers->add(['X-Inertia' => 'true']);
        $this->response->shouldReceive('getStatusCode')->once()->andReturn(404);

        $this->assertTrue($this->handler->shouldGoToInertiaErrorPage($this->request, $this->response));
    }

    /** @test */
    public function should_not_go_to_an_inertia_page_on_production_outside_of_inertia(): void
    {
        Config::set('app.env', 'production');

        $this->assertFalse($this->handler->shouldGoToInertiaErrorPage($this->request, $this->response));
    }

    /** @test */
    public function should_not_go_to_an_inertia_page_on_local(): void
    {
        Config::set('app.env', 'local');

        $this->request->headers->add(['X-Inertia' => 'true']);

        $this->assertFalse($this->handler->shouldGoToInertiaErrorPage($this->request, $this->response));
    }
}
