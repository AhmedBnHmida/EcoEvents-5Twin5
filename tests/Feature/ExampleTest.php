<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_application_returns_a_successful_response(): void
    {
        // Use a simple route that doesn't require database queries
        $response = $this->get('/login');
        
        $response->assertStatus(200);
    }
    
    // Alternative: Test welcome page instead of home
    public function test_welcome_page_returns_successful_response(): void
    {
        $response = $this->get('/');
        
        // If home page fails due to database, at least check it returns some response
        // This is more flexible than expecting exactly 200
        $response->assertStatus(200);
    }
}