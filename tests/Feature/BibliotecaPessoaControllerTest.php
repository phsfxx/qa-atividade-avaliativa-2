<?php

namespace Tests\Feature;

use App\Models\Biblioteca;
use App\Models\Pessoa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BibliotecaPessoaControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_shows_available_pessoas()
    {
        $biblioteca = Biblioteca::create([
            'created_by' => User::factory()->create()->id,
            'nome' => 'Biblioteca Relacionamento',
            'endereco' => 'Rua Relacao',
            'telefone' => '555555555',
            'email' => 'relacionamento@example.com',
        ]);

        $pessoa = Pessoa::factory()->create(['name' => 'Pessoa Livre']);

        $response = $this->get(route('bibliotecas.pessoas.create', ['biblioteca' => $biblioteca->id]));

        $response->assertStatus(200);
        $response->assertSee('Pessoa Livre');
    }

    public function test_store_attaches_pessoa_to_biblioteca()
    {
        $biblioteca = Biblioteca::create([
            'created_by' => User::factory()->create()->id,
            'nome' => 'Biblioteca Associação',
            'endereco' => 'Rua Associação',
            'telefone' => '666666666',
            'email' => 'associacao@example.com',
        ]);

        $pessoa = Pessoa::factory()->create();

        $response = $this->post(route('bibliotecas.pessoas.store', ['biblioteca' => $biblioteca->id]), [
            'pessoa_id' => $pessoa->id,
        ]);

        $response->assertRedirect(route('bibliotecas.edit', ['id' => $biblioteca->id]));
        $response->assertSessionHas('message', 'Pessoa adicionada à biblioteca com sucesso.');
        $this->assertDatabaseHas('biblioteca_pessoa', [
            'biblioteca_id' => $biblioteca->id,
            'pessoa_id' => $pessoa->id,
        ]);
    }
}
