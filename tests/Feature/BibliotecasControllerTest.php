<?php

namespace Tests\Feature;

use App\Models\Biblioteca;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BibliotecasControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_bibliotecas_matching_search()
    {
        Biblioteca::create([
            'created_by' => User::factory()->create()->id,
            'nome' => 'Biblioteca Central',
            'endereco' => 'Rua A',
            'telefone' => '111111111',
            'email' => 'central@example.com',
        ]);

        $response = $this->get(route('bibliotecas.index', ['nome' => 'Central']));

        $response->assertStatus(200);
        $response->assertSee('Biblioteca Central');
    }

    public function test_store_creates_biblioteca()
    {
        $user = User::factory()->create();

        $response = $this->post(route('bibliotecas.store'), [
            'created_by' => $user->id,
            'nome' => 'Biblioteca Nova',
            'endereco' => 'Rua Nova, 100',
        ]);

        $response->assertRedirect(route('bibliotecas.index'));
        $this->assertDatabaseHas('bibliotecas', [
            'nome' => 'Biblioteca Nova',
            'endereco' => 'Rua Nova, 100',
            'created_by' => $user->id,
        ]);
    }

    public function test_edit_returns_biblioteca_form()
    {
        $biblioteca = Biblioteca::create([
            'created_by' => User::factory()->create()->id,
            'nome' => 'Biblioteca Antiga',
            'endereco' => 'Rua Velha',
            'telefone' => '222222222',
            'email' => 'antiga@example.com',
        ]);

        $response = $this->get(route('bibliotecas.edit', $biblioteca->id));

        $response->assertStatus(200);
        $response->assertSee('Biblioteca Antiga');
    }

    public function test_update_changes_biblioteca_data()
    {
        $biblioteca = Biblioteca::create([
            'created_by' => User::factory()->create()->id,
            'nome' => 'Biblioteca Atual',
            'endereco' => 'Rua Atual',
            'telefone' => '333333333',
            'email' => 'atual@example.com',
        ]);

        $response = $this->put(route('bibliotecas.update', $biblioteca->id), [
            'nome' => 'Biblioteca Atualizada',
            'endereco' => 'Rua Atualizada, 10',
            'email' => 'atualizada@example.com',
        ]);

        $response->assertRedirect(route('bibliotecas.index'));
        $this->assertDatabaseHas('bibliotecas', [
            'id' => $biblioteca->id,
            'nome' => 'Biblioteca Atualizada',
            'endereco' => 'Rua Atualizada, 10',
            'email' => 'atualizada@example.com',
        ]);
    }

    public function test_destroy_deletes_biblioteca()
    {
        $biblioteca = Biblioteca::create([
            'created_by' => User::factory()->create()->id,
            'nome' => 'Biblioteca Para Deletar',
            'endereco' => 'Rua X',
            'telefone' => '444444444',
            'email' => 'deletar@example.com',
        ]);

        $response = $this->delete(route('bibliotecas.destroy', $biblioteca->id));

        $response->assertRedirect(route('bibliotecas.index'));
        $this->assertDatabaseMissing('bibliotecas', ['id' => $biblioteca->id]);
    }
}
