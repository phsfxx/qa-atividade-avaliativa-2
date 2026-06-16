<?php

namespace Tests\Unit;

use App\Models\Autor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AutorModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_autor_can_be_created()
    {
        $autor = Autor::create([
            'nome' => 'Paulo Coelho',
            'nacionalidade' => 'Brasileiro',
        ]);

        $this->assertNotNull($autor);
        $this->assertEquals('Paulo Coelho', $autor->nome);
    }

    public function test_autor_table_name()
    {
        $autor = new Autor();
        $this->assertEquals('autores', $autor->getTable());
    }
}
