<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_login()
    {
        $this->withoutExceptionHandling();
        $response = $this->post('api/v1/auth/login', [
            'email' => 'admin@admin',
            'password' => 'admin'
        ]);
        $response->assertStatus(201);
    }

    public function test_registro()
    {
        $this->withoutExceptionHandling();
        $response = $this->post('/api/v1/auth/registro', [
            'name' => 'name',
            'email' => 'email@email',
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);
        $response->assertStatus(201);
    }

    public function test_logout()
    {
        $this->withoutExceptionHandling();
        $response = $this->post('api/v1/auth/login', [
            'email' => 'admin@admin',
            'password' => 'admin'
        ]);

        $response->assertStatus(201);

        $token = $response->json('access_token');

        // Realizar la solicitud para cerrar sesión
        $response = $this->post('/api/v1/auth/logout', [], [
            'Authorization' => 'Bearer ' . $token,
        ]);

        // Verificar que la respuesta tiene el código de estado correcto
        $response->assertNoContent();
    }
}
