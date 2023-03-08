<?php

namespace Tests\Feature\routes;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class webTest extends TestCase
{
    use DatabaseTransactions;
    
    /**
     * @test
     */
    public function api_routes_are_protected(){

        $routes = collect(Route::getRoutes())
            ->filter(fn($route) => substr($route->uri(), 0, 4) == 'api/' )
            ->map(fn($route) => $route->uri());

        $this->assertEmpty($routes);

        // foreach ($routes as $route) {
        //     $response = $this->get($route);
        //     $response->assertSessionHasNoErrors();
        //     $response->assertRedirect(route('login'));
        // }

    }


}
