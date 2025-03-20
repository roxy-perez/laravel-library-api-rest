<?php

namespace Tests\Feature\API\V1;

use Tests\TestCase;
use App\Models\User;
use App\Models\Genre;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;

#[Group('v1')]
#[Group('v1:genres')]
class GenreTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function an_unauthenticated_user_cannot_access(): void
    {
        $this->getJson(route('genres.index'))
            ->assertUnauthorized();
    }

    #[Test]
    public function genres_can_be_listed(): void
    {
        $token = User::factory()->create()->createToken('Test')->plainTextToken;

        Genre::factory()->count(10)->create();

        $response = $this->withToken($token)
            ->getJson(route('genres.index'))
            ->assertOk();

        $this->assertCount(10, $response->json('data'));

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'created_at'
                ]
            ]
        ]);
    }

    #[Test]
    public function an_genre_can_be_retrieved(): void
    {
        $token = User::factory()->create()->createToken('Test')->plainTextToken;

        $genre = Genre::factory()->create();

        $response = $this->withToken($token)
            ->getJson(route('genres.show', $genre->id))
            ->assertOk();

        $response->assertJson([
            'data' => [
                'id' => $genre->id,
                'name' => $genre->name,
                'created_at' => $genre->created_at->format('Y-m-d H:i:s'),
            ],
        ]);
    }

    #[Test]
    public function an_genre_can_be_created(): void
    {
        $token = User::factory()->create()->createToken('Test')->plainTextToken;

        $response = $this->withToken($token)
            ->postJson(route('genres.store'), [
                'name' => 'Fantasy',
            ])
            ->assertCreated();

        $this->assertDatabaseHas('genres', [
            'name' => 'Fantasy',
        ]);

        $response->assertJson([
            'data' => [
                'name' => 'Fantasy',
            ],
        ]);
    }

    #[Test]
    public function an_genre_can_be_updated(): void
    {
        $token = User::factory()->create()->createToken('Test')->plainTextToken;

        $genre = Genre::factory()->create();

        $response = $this->withToken($token)
            ->putJson(route('genres.update', $genre->id), [
                'name' => 'Fantasy',
            ])
            ->assertOk();

        $response->assertJson([
            'data' => [
                'name' => 'Fantasy',
            ],
        ]);

        $this->assertDatabaseHas('genres', [
            'id' => $genre->id,
            'name' => 'Fantasy',
        ]);
    }

    #[Test]
    public function an_genre_can_be_deleted(): void
    {
        $token = User::factory()->create()->createToken('Test')->plainTextToken;

        $genre = Genre::factory()->create();

        $this->withToken($token)
            ->deleteJson(route('genres.destroy', $genre->id))
            ->assertOk();

        $this->assertDatabaseMissing('genres', [
            'id' => $genre->id,
        ]);
    }
}
