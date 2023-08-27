<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PerfilTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_usuario_puede_ver_su_propio_perfil()
    {
        $this->withoutExceptionHandling();
        $userA = User::factory()->create();
        $this->actingAs($userA);
        $response = $this->get('/api/v1/perfil');
        $response->assertStatus(200);
        $response->assertJsonFragment(['email' => $userA->email]);
    }

    public function test_usuarios_pueden_modificar_su_propio_perfil()
    {
        $this->withoutExceptionHandling();
        $userA = User::factory()->create();
        $this->actingAs($userA);
        $response = $this->put('/api/v1/perfil', [
            'name' => 'name_update',
            'email' => 'emai_update@email',
            'current_password' => 'password', // 'password' is the default password for the user created by the factory
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);
        $response->assertStatus(202);
        $this->assertDatabaseHas('users', [
            'name' => 'name_update',
            'email' => 'emai_update@email'
        ]);
    }
}
