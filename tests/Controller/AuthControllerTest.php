<?php

namespace App\Tests\Controller;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Hautelook\AliceBundle\PhpUnit\RecreateDatabaseTrait;

class AuthControllerTest extends ApiTestCase
{
    use RecreateDatabaseTrait;

    protected function setUp(): void
    {
        $this->client = $this->createClient();
    }

    public function testRegisterPasswordFail()
    {
        $this->register([
            "email" => "teste@email.com",
            "password" => "123456",
            "confirmPassword" => "654321",
        ]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertJsonEquals([
            "errors" => [
                "confirmPassword" => [
                    'As senhas não coincidem'
                ]
            ],
            "status" => 422,
            "type" => "validation_error",
            "title" => "There was a validation error"
        ]);
    }

    public function testRegisterEmailFail()
    {
        $this->register([
            "email" => "teste",
            "password" => "123456",
            "confirmPassword" => "123456",
        ]);

        $this->assertResponseStatusCodeSame(422);
        $this->assertJsonEquals([
            "errors" => [
                "email" => [
                    "Este valor não é um endereço de e-mail válido."
                ]
            ],
            "status" => 422,
            "type" => "validation_error",
            "title" => "There was a validation error"
        ]);
    }

    public function testLoginFail()
    {
        $this->login([
            "username" => "errado@mail.com",
            "password" => "123456",
        ]);

        $this->assertResponseStatusCodeSame(401);
        $this->assertJsonEquals([
            "code" => 401,
            "message" => "Credenciais inválidas."
        ]);
    }

    public function testRegisterAndLogin()
    {
        $this->register([
            "email" => "teste@email.com",
            "password" => "123456",
            "confirmPassword" => "123456",
        ]);
        $this->assertResponseIsSuccessful();

        $this->login([
            "username" => "teste@email.com",
            "password" => "123456",
        ]);
        $this->assertResponseIsSuccessful();
    }


    private function register(array $data)
    {
        $this->client->request('POST', '/api/auth/register', [
            'json' => $data
        ]);
    }

    private function login(array $data)
    {
        $this->client->request('POST', '/api/auth/login', [
            'json' => $data
        ]);
    }
}
