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
            'nome' => 'João',
            'nacionalidade' => 'Brasileiro',
            'data_nascimento' => '1980-10-10',
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
            'nome' => 'Maria',
            'nacionalidade' => 'Portuguesa',
            'data_nascimento' => '1990-05-20',
        ]);

        $response->assertRedirect(route('autores.index'));
        $this->assertDatabaseHas('autores', [
            'nome' => 'Maria',
            'nacionalidade' => 'Portuguesa',
            'data_nascimento' => '1990-05-20',
        ]);
    }

    public function test_edit_displays_existing_autor()
    {
        $autor = Autor::create([
            'nome' => 'Pedro',
            'nacionalidade' => 'Argentino',
            'data_nascimento' => '1975-12-12',
        ]);

        $response = $this->get(route('autores.edit', $autor));

        $response->assertStatus(200);
        $response->assertViewIs('autores.edit');
        $response->assertViewHas('autor', $autor);
    }

    public function test_update_changes_autor_data()
    {
        $autor = Autor::create([
            'nome' => 'Roberto',
            'nacionalidade' => 'Espanhol',
            'data_nascimento' => '1965-03-03',
        ]);

        $response = $this->put(route('autores.update', $autor), [
            'nome' => 'Roberto Silva',
            'nacionalidade' => 'Espanhol',
            'data_nascimento' => '1965-03-03',
        ]);

        $response->assertRedirect(route('autores.index'));
        $this->assertDatabaseHas('autores', [
            'id' => $autor->id,
            'nome' => 'Roberto Silva',
        ]);
    }
}
