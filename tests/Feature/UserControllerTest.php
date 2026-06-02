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
}
