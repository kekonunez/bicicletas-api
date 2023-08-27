<?php

namespace Tests\Feature;

use App\Models\Alquiler;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class BicicletaTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_usuarios_pueden_ver_bicicletas()
    {
        $this->withoutExceptionHandling();
        $user = User::where('es_admin', false)->first();
        $this->actingAs($user);
        $response = $this->get('/api/v1/bicicletas');
        $response->assertStatus(200);
    }

    public function test_usuario_admin_puede_crear_bicicleta()
    {
        $this->withoutExceptionHandling();
        $user = User::where('es_admin', true)->first();
        $this->actingAs($user);
        $response = $this->post('/api/v1/bicicletas', [
            'modelo' => 'modelo',
            'marca' => 'color',
            'precio_por_hora' => 10,
            'foto_url' => 'foto_url'
        ]);
        $response->assertStatus(201);
    }

    public function test_usuario_admin_puede_modificar_bicicleta()
    {
        $this->withoutExceptionHandling();
        $user = User::where('es_admin', true)->first();
        $this->actingAs($user);
        $response = $this->put('/api/v1/bicicletas/1', [
            'modelo' => 'modelo_update',
            'marca' => 'color_update',
            'precio_por_hora' => 101,
            'foto_url' => 'foto_url_update'
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('bicicletas', [
            'modelo' => 'modelo_update',
            'marca' => 'color_update',
            'precio_por_hora' => 101,
            'foto_url' => 'foto_url_update'
        ]);
    }

    public function test_usuario_admin_puede_eliminar_bicicleta()
    {
        $this->withoutExceptionHandling();
        $user = User::where('es_admin', true)->first();
        $this->actingAs($user);
        $this->assertDatabaseHas('bicicletas', [
            'id' => 1
        ]);
        DB::table('alquileres')->truncate(); // Eliominamos todas los alquileres para que no hayan claves foráneas que impidan la eliminación
        $response = $this->delete('/api/v1/bicicletas/1');
        $response->assertStatus(204);
        $this->assertDatabaseMissing('bicicletas', [
            'id' => 1
        ]);
    }

    public function test_usuario_no_puede_eliminar_bicicleta()
    {
        $this->withoutExceptionHandling();
        $user = User::where('es_admin', false)->first();
        $this->actingAs($user);
        $response = $this->post('/api/v1/bicicletas', [
            'modelo' => 'modelo',
            'marca' => 'color',
            'precio_por_hora' => 10,
            'foto_url' => 'foto_url'
        ]);
        $response->assertStatus(403);
    }

    public function test_usuario_no_puede_modificar_bicicleta()
    {
        $this->withoutExceptionHandling();
        $user = User::where('es_admin', false)->first();
        $this->actingAs($user);
        $response = $this->put('/api/v1/bicicletas/1', [
            'modelo' => 'modelo_update',
            'marca' => 'color_update',
            'precio_por_hora' => 101,
            'foto_url' => 'foto_url_update'
        ]);
        $response->assertStatus(403);
    }

    public function test_usuario_no_puede_borrar_bicicleta()
    {
        $this->withoutExceptionHandling();
        $user = User::where('es_admin', false)->first();
        $this->actingAs($user);
        $response = $this->delete('/api/v1/bicicletas/1');
        $response->assertStatus(403);
    }
}
