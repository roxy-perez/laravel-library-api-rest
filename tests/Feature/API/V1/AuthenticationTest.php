<?php

namespace Tests\Feature\API\V1;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

#[Group('v1')]
#[Group('v1:authentication')]
class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function an_unauthenticated_user_cannot_access_the_api(): void
    {
        $this->getJson(route('authors.index'))
            ->assertUnauthorized();
    }

    #[Test]
    public function an_user_can_register(): void
    {
        $response = $this->postJson(route('register'), [
            'name' => 'Jane Doe',
            'email' => 'test@test.com',
            'password' => 'password',
            'device_name' => 'Test'
        ])->assertOk();

        $this->assertArrayHasKey('token', $response->json('data'));
        $this->assertArrayHasKey('token_type', $response->json('data'));
    }

    #[Test]
    public function an_user_can_login(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson(route('login'), [
            'email' => $user->email,
            'password' => 'password',
            'device_name' => 'Test'
        ])->assertOk();

        $this->assertArrayHasKey('token', $response->json('data'));
        $this->assertArrayHasKey('token_type', $response->json('data'));

    }

    #[Test]
    public function an_user_can_logout()
    {
        $user = User::factory()->create();
        $token = $user->createToken('Test')->plainTextToken;

        $this->withToken($token)
            ->postJson(route('logout'))
            ->assertOk();

        $this->assertEmpty($user->tokens);
    }
}
