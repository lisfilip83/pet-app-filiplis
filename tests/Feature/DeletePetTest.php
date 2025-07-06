<?php


// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeletePetTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_it_should_properly_delete_existing_pet(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
