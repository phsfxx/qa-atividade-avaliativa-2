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
        $this->assertDatabaseHas('pessoas', [ wdawwad 
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
}
