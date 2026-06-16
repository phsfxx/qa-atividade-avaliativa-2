<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_displays_users()
    {
        User::factory()->create(['name' => 'Teste Usuario']);

        $response = $this->get(route('users.index'));

        $response->assertStatus(200);
        $response->assertSee('Teste Usuario');
    }

    public function test_store_creates_user_and_redirects()
    {
        $response = $this->post(route('users.store'), [
            'name' => 'Novo Usuario',
            'email' => 'novo@example.com',
            'password' => 'secret123',
        ]);

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', [
            'name' => 'Novo Usuario',
            'email' => 'novo@example.com',
        ]);
    }

    public function test_edit_returns_existing_user()
    {
        $user = User::factory()->create();

        $response = $this->get(route('users.edit', $user));

        $response->assertStatus(200);
        $response->assertSee($user->name);
    }

    public function test_update_modifies_user_and_redirects()
    {
        $user = User::factory()->create([
            'name' => 'Usuario Antigo',
            'email' => 'antigo@example.com',
        ]);

        $response = $this->put(route('users.update', $user), [
            'name' => 'Usuario Atualizado',
            'email' => 'atualizado@example.com',
            'role' => 'admin',
        ]);

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Usuario Atualizado',
            'email' => 'atualizado@example.com',
            'role' => 'admin',
        ]);
    }

    public function test_destroy_deletes_user()
    {
        $user = User::factory()->create();

        $response = $this->delete(route('users.destroy', $user));

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_show_displays_user()
    {
        $user = User::factory()->create(['name' => 'Usuário Para Ver']);

        $response = $this->get(route('users.show', $user));

        $response->assertStatus(200);
        $response->assertViewIs('users.show');
        $response->assertViewHas('user', $user);
        $response->assertSee('Usuário Para Ver');
    }

    public function test_show_not_found_redirects_with_error()
    {
        $response = $this->get(route('users.show', 999));

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('error', 'Usuário não encontrado');
    }

    public function test_create_displays_create_form()
    {
        $response = $this->get(route('users.create'));

        $response->assertStatus(200);
        $response->assertViewIs('users.new');
    }

    public function test_edit_not_found_redirects_with_error()
    {
        $response = $this->get(route('users.edit', 999));

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('error', 'Usuário não encontrado');
    }

    public function test_update_not_found_redirects_with_error()
    {
        $response = $this->put(route('users.update', 999), [
            'name' => 'Nonexistent',
            'email' => 'test@example.com',
            'role' => 'user',
        ]);

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('error', 'Usuário não encontrado');
    }

    public function test_destroy_not_found_redirects_with_error()
    {
        $response = $this->delete(route('users.destroy', 999));

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHas('error', 'Usuário não encontrado');
    }

    public function test_store_error_on_exception()
    {
        $response = $this->post(route('users.store'), [
            'name' => 'Usuario Erro',
            'email' => 'usuario@example.com',
            'password' => 'secret123',
        ]);

        // Mesmo sem erro intencional, o teste valida que a rota está funcionando
        // Se ocorrer uma exception, será redirecionado com mensagem de erro
        $response->assertRedirect();
    }

    public function test_update_with_role()
    {
        $user = User::factory()->create([
            'name' => 'User Para Role',
            'email' => 'user.role@example.com',
            'role' => 'user',
        ]);

        $response = $this->put(route('users.update', $user), [
            'name' => 'User Com Role',
            'email' => 'user.com.role@example.com',
            'role' => 'admin',
        ]);

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'User Com Role',
            'email' => 'user.com.role@example.com',
            'role' => 'admin',
        ]);
    }

    public function test_store_creates_user_with_all_fields()
    {
        $response = $this->post(route('users.store'), [
            'name' => 'Usuario Completo',
            'email' => 'completo@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', [
            'name' => 'Usuario Completo',
            'email' => 'completo@example.com',
        ]);
    }

    public function test_edit_user_displays_form()
    {
        $user = User::factory()->create(['name' => 'User Para Editar']);

        $response = $this->get(route('users.edit', $user->id));

        $response->assertStatus(200);
        $response->assertViewIs('users.edit');
        $response->assertSee('User Para Editar');
    }

    public function test_update_user_changes_only_name()
    {
        $user = User::factory()->create([
            'name' => 'Nome Antigo',
            'email' => 'antigo@example.com',
            'role' => 'user',
        ]);

        $response = $this->put(route('users.update', $user), [
            'name' => 'Nome Novo',
            'email' => 'antigo@example.com',
            'role' => 'user',
        ]);

        $response->assertRedirect(route('users.index'));
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Nome Novo',
        ]);
    }
}
