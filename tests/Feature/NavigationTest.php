<?php

namespace Tests\Feature;

use Tests\TestCase;

class NavigationTest extends TestCase
{
    public function test_home_page_renders_successfully(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('The Sanctuary');
        $response->assertSee('Sistem Online');
        $response->assertSee('EST. 2026 // TOATE DREPTURILE REZERVATE');
    }

    public function test_garaj_page_renders_successfully(): void
    {
        $response = $this->get('/garaj');

        $response->assertStatus(200);
        $response->assertSee('The Iron Sanctuary Auto');
        $response->assertSee('Diagnoză');
        $response->assertSee('Pasul 1');
        $response->assertSee('Vrei o programare?');
    }

    public function test_fitness_page_renders_successfully(): void
    {
        $response = $this->get('/fitness');

        $response->assertStatus(200);
        $response->assertSee('The Iron Sanctuary Fitness');
        $response->assertSee('Forță');
        $response->assertSee('Pasul 1');
        $response->assertSee('Vrei în club?');
    }
}
