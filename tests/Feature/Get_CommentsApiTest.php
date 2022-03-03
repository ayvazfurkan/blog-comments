<?php

namespace Tests\Feature;
use Tests\TestCase;

class GetCommentsApiTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */

    public function test_the_post_data_api()
    {
        $response = $this->get(route('show_post', $id = 1))
            ->assertStatus(200);

        $this->assertEquals($id, $response->json('data.id'));
    }

    public function test_the_post_data_api_without_id()
    {
        $this->get(route('show_post', $id = 0))
            ->assertStatus(404);
    }

    public function test_the_post_comments_api()
    {
        $this->get(route('show_post_comments', $id = 1))
            ->assertStatus(200);
    }

    public function test_the_post_comments_api_without_id()
    {
        $this->get(route('show_post_comments', $id = 0))
            ->assertStatus(404);
    }
}
