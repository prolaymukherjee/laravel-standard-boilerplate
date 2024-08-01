<?php

namespace Tests\Unit;

use App\Constants\Messages as MessagesConstant;
use App\Contracts\Services\AuthServiceInterface;
use App\Http\Controllers\Api\AuthController;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Mockery;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
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
        $user = User::factory()->make([
            'id' => 1,
            'email_verified_at' => now(),
        ]);

        $this->authServiceMock
            ->shouldReceive('login')
            ->once()
            ->with(['email' => 'test@example.com', 'password' => 'password'])
            ->andReturn($user);

        $requestData = [
            'email' => 'test@example.com',
            'password' => 'password',
        ];

        $request = new LoginRequest($requestData);

        $validator = Validator::make($requestData, $request->rules());
        $request->setValidator($validator);

        $controller = new AuthController($this->authServiceMock);

        $response = $controller->login($request);

        $this->assertEquals(Response::HTTP_CREATED, $response->status());
        $this->assertEquals([
            'statusCode' => Response::HTTP_CREATED,
            'error' => false,
            'errorMessage' => null,
            'errorBags' => null,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at->toISOString(),  // Include this line to match the response
            ],
        ], $response->getData(true));
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

        $request = new LoginRequest($requestData);

        $validator = Validator::make($requestData, $request->rules());
        $request->setValidator($validator);

        $controller = new AuthController($this->authServiceMock);

        $response = $controller->login($request);

        $this->assertEquals(Response::HTTP_UNAUTHORIZED, $response->status());
        $this->assertEquals([
            'statusCode' => Response::HTTP_UNAUTHORIZED,
            'error' => true,
            'errorMessage' => MessagesConstant::NOT_MATCH_ERROR,
            'errorBags' => null,
            'data' => null,
        ], $response->getData(true));
    }
}
