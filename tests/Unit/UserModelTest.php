<?php

namespace Tests\Unit;

use App\Models\Biblioteca;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_returns_the_user_role_for_a_biblioteca()
    {
        $user = User::factory()->create();
        $biblioteca = Biblioteca::create([
            'created_by' => $user->id,
            'nome' => 'Biblioteca Teste',
            'endereco' => 'Rua Teste, 123',
            'telefone' => '123456789',
            'email' => 'biblioteca@teste.com',
        ]);

        $biblioteca->users()->attach($user, ['role' => 'owner']);

        $this->assertSame('owner', $user->roleInBiblioteca($biblioteca));
        $this->assertTrue($user->isOwnerOfBiblioteca($biblioteca));
        $this->assertFalse($user->isAdminOfBiblioteca($biblioteca));
        $this->assertFalse($user->isEditorOfBiblioteca($biblioteca));
        $this->assertFalse($user->isViewerOfBiblioteca($biblioteca));
    }

    public function test_has_biblioteca_role_returns_false_when_no_role_is_assigned()
    {
        $user = User::factory()->create();
        $biblioteca = Biblioteca::create([
            'created_by' => $user->id,
            'nome' => 'Biblioteca Sem Role',
            'endereco' => 'Rua Teste, 456',
            'telefone' => '987654321',
            'email' => 'semrole@teste.com',
        ]);

        $this->assertNull($user->roleInBiblioteca($biblioteca));
        $this->assertFalse($user->hasBibliotecaRole($biblioteca, 'owner'));
        $this->assertFalse($user->isAdminOfBiblioteca($biblioteca));
    }
}
