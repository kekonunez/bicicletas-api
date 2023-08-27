<?php

namespace Tests\Feature;

use App\Models\Alquiler;
use App\Models\Bicicleta;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AlquilerTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_usuario_puede_ver_alquileres()
    {
        $this->withoutExceptionHandling();
        $user = User::where('es_admin', false)->first();
        $this->actingAs($user);
        $response = $this->get('/api/v1/alquileres');
        $response->assertStatus(200);
    }

    public function test_usuario_puede_ver_solamente_sus_propios_alquileres()
    {
        // Crear usuarios y alquileres en la base de datos de prueba
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $alquilerA = Alquiler::factory()->create(['user_id' => $userA->id]);
        $alquilerB = Alquiler::factory()->create(['user_id' => $userB->id]);

        // Actuar como el usuario A
        $this->actingAs($userA);

        // Hacer la solicitud GET a la ruta de alquileres
        $response = $this->get('/api/v1/alquileres');

        // Verificar que la respuesta tiene el código de estado correcto
        $response->assertStatus(200);

        // Verificar que el alquiler del usuario A esté presente en la respuesta
        $response->assertJsonFragment(['id' => $alquilerA->id]);


        // Verificar que el alquiler del usuario B no esté presente en la respuesta
        $response->assertJsonMissing(['id' => $alquilerB->id]);
    }

    public function test_usuario_admin_puede_comenzar_alquiler()
    {
        $this->withoutExceptionHandling();
        $user = User::where('es_admin', true)->first();
        $this->actingAs($user);
        $bicicleta = Bicicleta::factory()->create();
        $response = $this->post('api/v1/alquileres/inicio', [
            'hora_comienzo' => '2021-05-01 10:00:00',
            'hora_final' => null,
            'precio_total' => null,
            'user_id' => 1,
            'bicicleta_id' => $bicicleta->id
        ]);
        $response->assertStatus(201);
    }

    public function test_usuario_puede_comenzar_alquiler()
    {
        $this->withoutExceptionHandling();
        $user = User::where('es_admin', false)->first();
        $this->actingAs($user);
        $bicicleta = Bicicleta::factory()->create();
        $response = $this->post('api/v1/alquileres/inicio', [
            'hora_comienzo' => '2021-05-01 10:00:00',
            'hora_final' => null,
            'precio_total' => null,
            'user_id' => 1,
            'bicicleta_id' => $bicicleta->id
        ]);
        $response->assertStatus(201);
    }

    public function test_usuario_puede_finalizar_alquiler_propio()
    {
        $this->withoutExceptionHandling();
        $user = User::where('es_admin', false)->first();
        $this->actingAs($user);
        $alquilerA = Alquiler::factory()->create(['user_id' => $user->id]);
        $response = $this->put('api/v1/alquileres/finalizar/' . $alquilerA->id, []);
        $response->assertStatus(200);
    }

    public function test_usuario_no_puede_finalizar_alquiler_ajeno()
    {
        $this->withoutExceptionHandling();
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $this->actingAs($userA);
        $alquilerB = Alquiler::factory()->create(['user_id' => $userB->id]);
        $response = $this->put('api/v1/alquileres/finalizar/' . $alquilerB->id, []);
        $response->assertStatus(422);
    }

    public function test_precio_total_en_alquiler_de_dos_horas()
    {
        $PRECIO_POR_HORA = 10;
        $PRECIO_TOTAL = 20;
        $this->withoutExceptionHandling();
        $user = User::find(1);
        $this->actingAs($user);
        $bicicleta = Bicicleta::factory()->create(['precio_por_hora' => $PRECIO_POR_HORA]);

        $alquiler = Alquiler::factory()->create([
            'hora_comienzo' => now()->subHour(2),
            'hora_final' => null,
            'precio_total' => null,
            'user_id' => 1,
            'bicicleta_id' => $bicicleta->id
        ]);

        $response = $this->put('api/v1/alquileres/finalizar/' . $alquiler->id);
        $response->assertStatus(200);
        $alquiler->refresh();
        $this->assertEquals($PRECIO_TOTAL, $alquiler->precio_total);
    }
}
