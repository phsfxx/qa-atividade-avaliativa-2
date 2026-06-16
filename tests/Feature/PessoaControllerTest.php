<?php

namespace Tests\Feature;

use App\Models\Pessoa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PessoaControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_creates_pessoa_when_passwords_match()
    {
        $response = $this->post(route('pessoas.store'), [
            'name' => 'Joao Silva',
            'email' => 'joao@example.com',
            'telefone' => '11999999999',
            'matricula' => '202600001',
            'password' => 'secret123',
            'confirmPassword' => 'secret123',
        ]);

        $response->assertRedirect(route('pessoas.index'));
        $this->assertDatabaseHas('pessoas', [
            'name' => 'Joao Silva',
            'email' => 'joao@example.com',
            'telefone' => '11999999999',
            'matricula' => '202600001',
        ]);
    }

    public function test_store_returns_error_when_passwords_do_not_match()
    {
        $response = $this->from(route('pessoas.create'))->post(route('pessoas.store'), [
            'name' => 'Maria',
            'email' => 'maria@example.com',
            'telefone' => '11988888888',
            'matricula' => '202600002',
            'password' => 'secret123',
            'confirmPassword' => 'secret456',
        ]);

        $response->assertRedirect(route('pessoas.create'));
        $response->assertSessionHas('error', 'As senhas não coincidem!');
        $this->assertDatabaseMissing('pessoas', ['email' => 'maria@example.com']);
    }

    public function test_update_changes_pessoa_information_and_password_when_passwords_match()
    {
        $pessoa = Pessoa::factory()->create([
            'name' => 'Ana',
            'email' => 'ana@example.com',
            'telefone' => '11977777777',
            'matricula' => '202600003',
            'password' => Hash::make('oldpassword'),
        ]);

        $response = $this->put(route('pessoas.update', $pessoa), [
            'name' => 'Ana Souza',
            'email' => 'ana.souza@example.com',
            'telefone' => '11976666666',
            'matricula' => '202600003',
            'password' => 'newpassword',
            'confirmPassword' => 'newpassword',
        ]);

        $response->assertRedirect(route('pessoas.index'));
        $this->assertDatabaseHas('pessoas', [
            'name' => 'Ana Souza',
            'email' => 'ana.souza@example.com',
            'telefone' => '11976666666',
            'matricula' => '202600003',
        ]);

        $this->assertTrue(Hash::check('newpassword', Pessoa::find($pessoa->id)->password));
    }

    public function test_update_returns_error_when_passwords_do_not_match()
    {
        $pessoa = Pessoa::factory()->create([
            'name' => 'Carlos',
            'email' => 'carlos@example.com',
            'telefone' => '11966666666',
            'matricula' => '202600004',
            'password' => Hash::make('oldpassword'),
        ]);

        $response = $this->from(route('pessoas.edit', $pessoa))->put(route('pessoas.update', $pessoa), [
            'name' => 'Carlos Silva',
            'email' => 'carlos.silva@example.com',
            'telefone' => '11966555555',
            'matricula' => '202600004',
            'password' => 'newpass',
            'confirmPassword' => 'differentpass',
        ]);

        $response->assertRedirect(route('pessoas.edit', $pessoa));
        $response->assertSessionHas('error', 'As senhas não coincidem!');
        $this->assertDatabaseMissing('pessoas', ['email' => 'carlos.silva@example.com']);
    }

    public function test_index_displays_pessoas()
    {
        $pessoa = Pessoa::factory()->create(['name' => 'Pessoa Index']);

        $response = $this->get(route('pessoas.index'));

        $response->assertStatus(200);
        $response->assertViewIs('pessoas.index');
        $response->assertViewHas('pessoas');
        $response->assertSee('Pessoa Index');
    }

    public function test_create_displays_form()
    {
        $response = $this->get(route('pessoas.create'));

        $response->assertStatus(200);
        $response->assertViewIs('pessoas.new');
    }

    public function test_edit_displays_existing_pessoa()
    {
        $pessoa = Pessoa::factory()->create(['name' => 'Pessoa Edit']);

        $response = $this->get(route('pessoas.edit', $pessoa));

        $response->assertStatus(200);
        $response->assertViewIs('pessoas.edit');
        $response->assertViewHas('pessoa', $pessoa);
    }

    public function test_edit_not_found_redirects_with_error()
    {
        $response = $this->get(route('pessoas.edit', 999));

        $response->assertRedirect(route('pessoas.index'));
        $response->assertSessionHas('error', 'Pessoa não encontrada');
    }

    public function test_update_not_found_redirects_with_error()
    {
        $response = $this->from(route('pessoas.edit', 999))->put(route('pessoas.update', 999), [
            'name' => 'Test',
            'email' => 'test@example.com',
        ]);

        $response->assertRedirect(route('pessoas.index'));
        $response->assertSessionHas('error', 'Pessoa não encontrada');
    }

    public function test_update_without_password_change()
    {
        $pessoa = Pessoa::factory()->create([
            'name' => 'Pessoa Sem Senha',
            'email' => 'semseusha@example.com',
            'password' => Hash::make('oldpassword'),
        ]);

        $response = $this->put(route('pessoas.update', $pessoa), [
            'name' => 'Pessoa Sem Senha Atualizada',
            'email' => 'semseusha.atualizada@example.com',
            'telefone' => '11911111111',
            'matricula' => '202600005',
        ]);

        $response->assertRedirect(route('pessoas.index'));
        $this->assertDatabaseHas('pessoas', [
            'id' => $pessoa->id,
            'name' => 'Pessoa Sem Senha Atualizada',
            'email' => 'semseusha.atualizada@example.com',
        ]);
        // Senha deve permanecer a mesma
        $this->assertTrue(Hash::check('oldpassword', Pessoa::find($pessoa->id)->password));
    }
}
