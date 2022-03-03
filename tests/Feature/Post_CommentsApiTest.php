<?php

namespace Tests\Feature;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;


class PostCommentsApiTest extends TestCase
{
    use withFaker;
    /**
     * A basic test example.
     *
     * @return void
     */

    public function test_create_new_post_comment()
    {
        $this->post(route('save_post_comment', $id = 1), [
            'username' => $this->faker->userName(),
            'content' => $this->faker->paragraph(5)
        ])
            ->assertStatus(200)

            ->assertJsonStructure([
                'id',
                'username',
                'content',
                'commentable_id',
                'commentable_type',
                'created_at',
                'updated_at'
            ]);
    }

    public function test_create_new_post_comment_without_data()
    {
        $this->post(route('save_post_comment', $id = 0), [])
            ->assertStatus(404);
    }

    public function test_create_new_post_comment_without_id()
    {
        $this->post(route('save_post_comment', $id = 0), [
            'username' => $this->faker->userName(),
            'content' => $this->faker->paragraph(5)
        ])
            ->assertStatus(404);
    }

    public function test_create_new_comment_comment()
    {
        $this->post(route('save_comment_comment', $id = 1), [
            'username' => $this->faker->userName(),
            'content' => $this->faker->paragraph(5)
        ])
            ->assertStatus(200)

            ->assertJsonStructure([
                'id',
                'username',
                'content',
                'commentable_id',
                'commentable_type',
                'created_at',
                'updated_at'
            ]);
    }

    public function test_create_new_comment_comment_without_id()
    {
        $this->post(route('save_comment_comment', $id = 0), [
            'username' => $this->faker->userName(),
            'content' => $this->faker->paragraph(5)
        ])->assertStatus(404);
    }

    public function test_create_new_comment_comment_without_data()
    {
        $this->post(route('save_comment_comment', $id = 1), [])
            ->assertStatus(404);
    }
}
