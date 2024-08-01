<?php

namespace Tests\Feature;

use App\Constants\Messages as MessagesConstant;
use App\Contracts\Services\AuthServiceInterface;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $authServiceMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->authServiceMock = Mockery::mock(AuthServiceInterface::class);

        $this->app->instance(AuthServiceInterface::class, $this->authServiceMock);
    }

    public function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    public function testLoginSuccess()
    {
        $user = User::factory()->create();

        $this->authServiceMock
            ->shouldReceive('login')
            ->once()
            ->with(['email' => 'test@example.com', 'password' => 'password'])
            ->andReturn($user);

        $requestData = [
            'email' => 'test@example.com',
            'password' => 'password',
        ];

        $response = $this->postJson('/api/login', $requestData);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJson([
                'statusCode' => Response::HTTP_CREATED,
                'error' => false,
                'errorMessage' => null,
                'errorBags' => null,
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
            ]);
    }

    public function testLoginFailure()
    {
        $this->authServiceMock
            ->shouldReceive('login')
            ->once()
            ->with(['email' => 'test@example.com', 'password' => 'password'])
            ->andReturn(null);

        $requestData = [
            'email' => 'test@example.com',
            'password' => 'password',
        ];

        $response = $this->postJson('/api/login', $requestData);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED)
            ->assertJson([
                'statusCode' => Response::HTTP_UNAUTHORIZED,
                'error' => true,
                'errorMessage' => MessagesConstant::NOT_MATCH_ERROR,
                'errorBags' => null,
                'data' => null,
            ]);
    }
}
