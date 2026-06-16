<?php

namespace Tests\Feature;

use App\Models\Autor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AutorControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_displays_autores()
    {
        Autor::create([
            'nome' => 'João Silva',
            'nacionalidade' => 'Brasileiro',
        ]);

        $response = $this->get(route('autores.index'));

        $response->assertStatus(200);
        $response->assertViewIs('autores.index');
        $response->assertViewHas('autores');
    }

    public function test_create_displays_autor_form()
    {
        $response = $this->get(route('autores.create'));

        $response->assertStatus(200);
        $response->assertViewIs('autores.create');
    }

    public function test_store_creates_autor()
    {
        $response = $this->post(route('autores.store'), [
            'nome' => 'Maria Santos',
            'nacionalidade' => 'Portuguesa',
        ]);

        $response->assertRedirect(route('autores.index'));
        $this->assertDatabaseHas('autores', [
            'nome' => 'Maria Santos',
            'nacionalidade' => 'Portuguesa',
        ]);
    }

    public function test_edit_displays_existing_autor()
    {
        $autor = Autor::create([
            'nome' => 'Pedro Costa',
            'nacionalidade' => 'Argentino',
        ]);

        $response = $this->get(route('autores.edit', $autor));

        $response->assertStatus(200);
        $response->assertViewIs('autores.edit');
        $response->assertViewHas('autor', $autor);
    }

    public function test_update_changes_autor_data()
    {
        $autor = Autor::create([
            'nome' => 'Roberto Garcia',
            'nacionalidade' => 'Espanhol',
        ]);

        $response = $this->put(route('autores.update', $autor), [
            'nome' => 'Roberto Silva',
            'nacionalidade' => 'Espanhol',
        ]);

        $response->assertRedirect(route('autores.index'));
        $this->assertDatabaseHas('autores', [
            'id' => $autor->id,
            'nome' => 'Roberto Silva',
        ]);
    }
}
