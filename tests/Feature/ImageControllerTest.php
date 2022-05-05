<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Image;
use App\Models\Restaurant;
use App\Models\Area;
use App\Models\Genre;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;


class ImageControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    //index
    //ユーザーが認証されていない
    public function test_index_image_not_authenticated()
    {
        $response = $this->get('/api/images');
        $response->assertStatus(409);
        $response->assertJsonFragment([
            'message' => 'Your email address is not verified.'
        ]);
    }

    //index
    //ユーザーがメール認証されていない
    public function test_index_image_not_email_verified()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($user)->get('/api/images');
        $response->assertStatus(409);
        $response->assertJsonFragment([
            'message' => 'Your email address is not verified.'
        ]);
    }

    //index
    //ユーザー認証されているがowner権限がない
    public function test_index_image_unauthorized()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/api/images');
        $response->assertStatus(403);
        $response->assertJsonFragment([
            'message' => '権限がありません'
        ]);
    }

    //index
    //owner権限のあるuserが認証されている
    public function test_index_image_authorized()
    {
        $restaurant = Restaurant::factory()->for(Area::factory()->create())->for(Genre::factory()->create())->create();

        $owner = User::factory()->for($restaurant)->create([
            'authority' => 'owner',
        ]);

        $item = Image::factory()->for($restaurant)->create();
        $response = $this->actingAs($owner)->get('/api/images');
        $response->assertStatus(200);
        $response->assertJsonFragment([
            'image_url' => $item->image_url,
            'restaurant_id' => $item->restaurant_id
        ]);
    }

    //create
    //ユーザーが認証されていない
    public function test_create_image_not_authenticated()
    {
        $response = $this->post('/api/images');
        $response->assertStatus(409);
        $response->assertJsonFragment([
            'message' => 'Your email address is not verified.'
        ]);
    }

    //create
    //ユーザーがメール認証されていない
    public function test_create_image_not_email_verified()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($user)->post('/api/images');
        $response->assertStatus(409);
        $response->assertJsonFragment([
            'message' => 'Your email address is not verified.'
        ]);
    }

    //create
    //ユーザー認証されているがowner権限がない
    public function test_create_image_unauthorized()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/api/images');
        $response->assertStatus(403);
        $response->assertJsonFragment([
            'message' => '権限がありません'
        ]);
    }

    // public function test_create_image()
    // {
        // $owner = User::factory()->create([
        //     'authority' => 'owner',
        // ]);

        // Storage::fake('disk');
        // $data = [
        //     'uploadimage' => 'uploadimg.jpg'
        // ];
        // $response = $this->actingAs($owner)->post('/api/images', $data);
        // $response = $this->actingAs($owner)->json('POST', '/api/images', [
        //     UploadedFile::fake()->image('uploadimg.jpg'),
        // ]);
        // Storage::disk('disk')->assertExists('uploadimg.jpg');
        // $response->assertStatus(201);
    // }
}
