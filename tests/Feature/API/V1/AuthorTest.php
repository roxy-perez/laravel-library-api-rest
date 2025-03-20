<?php

namespace Tests\Feature\API\V1;

use App\Models\Author;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[Group('v1')]
#[Group('v1:authors')]
class AuthorTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function authors_can_be_listed(): void
    {
        $token = User::factory()->create()->createToken('Test')->plainTextToken;

        Author::factory()->count(10)->create();

        $response = $this->withToken($token)
            ->getJson(route('authors.index'))
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
    public function an_author_can_be_retrieved(): void
    {
        $token = User::factory()->create()->createToken('Test')->plainTextToken;

        $author = Author::factory()->create();

        $response = $this->withToken($token)
            ->getJson(route('authors.show', $author->id))
            ->assertOk();

        $response->assertJson([
            'data' => [
                'id' => $author->id,
                'name' => $author->name,
                'created_at' => $author->created_at->format('Y-m-d H:i:s'),
            ],
        ]);
    }

    #[Test]
    public function an_author_can_be_created(): void
    {
        $token = User::factory()->create()->createToken('Test')->plainTextToken;

        $response = $this->withToken($token)
            ->postJson(route('authors.store'), [
                'name' => 'J. K. Rowling',
            ])
            ->assertCreated();

        $this->assertDatabaseHas('authors', [
            'name' => 'J. K. Rowling',
        ]);

        $response->assertJson([
            'data' => [
                'name' => 'J. K. Rowling',
            ],
        ]);
    }

    #[Test]
    public function an_author_can_be_updated(): void
    {
        $token = User::factory()->create()->createToken('Test')->plainTextToken;

        $author = Author::factory()->create();

        $response = $this->withToken($token)
            ->putJson(route('authors.update', $author->id), [
                'name' => 'J. K. Rowling',
            ])
            ->assertOk();

        $this->assertDatabaseHas('authors', [
            'id' => $author->id,
            'name' => 'J. K. Rowling',
        ]);

        $response->assertJson([
            'data' => [
                'id' => $author->id,
                'name' => 'J. K. Rowling',
            ],
        ]);
    }

    #[Test]
    public function an_author_can_be_deleted(): void
    {
        $token = User::factory()->create()->createToken('Test')->plainTextToken;

        $author = Author::factory()->create();

        $response = $this->withToken($token)
            ->deleteJson(route('authors.destroy', $author))
            ->assertOk();

        $this->assertDatabaseMissing('authors', [
            'id' => $author->id,
        ]);

        $this->assertDatabaseCount('authors', 0);
    }
}
