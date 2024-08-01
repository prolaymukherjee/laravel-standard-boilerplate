<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\Api\AuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $authService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authService = Mockery::mock(AuthService::class)->makePartial();
    }

    public function it_should_return_user_with_token_on_successful_login()
    {
        $credentials = ['email' => 'test@example.com', 'password' => 'password'];
        $user = User::factory()->create(['email' => $credentials['email']]);

        JWTAuth::shouldReceive('attempt')
            ->once()
            ->with($credentials)
            ->andReturn('fake_token');

        Auth::shouldReceive('user')
            ->once()
            ->andReturn($user);

        $result = $this->authService->login($credentials);

        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals('fake_token', $result->token);
        $this->assertEquals($user->id, $result->id);
    }

    public function it_should_return_null_on_failed_login()
    {
        $credentials = ['email' => 'test@example.com', 'password' => 'invalid_password'];

        JWTAuth::shouldReceive('attempt')
            ->once()
            ->with($credentials)
            ->andReturn(false);

        $result = $this->authService->login($credentials);

        $this->assertNull($result);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
