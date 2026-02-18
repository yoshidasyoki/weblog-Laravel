<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use App\Models\User;
use App\Models\Article;
use Illuminate\Support\Facades\Hash;

class ExampleTest extends TestCase
{
    use RefreshDatabase;
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get(route("home"));
        $response->assertStatus(200);
    }
}
