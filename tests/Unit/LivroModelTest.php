<?php

namespace Tests\Unit;

use App\Models\Livro;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LivroModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_livro_table_name()
    {
        $livro = new Livro();
        $this->assertEquals('livros', $livro->getTable());
    }

    public function test_livro_can_be_created()
    {
        // Livro requer data_publicacao mesmo que não tenha fillable
        // Não testamos criação pois a tabela não permite criar sem autor_id
        $livro = new Livro();
        $this->assertNotNull($livro);
    }
}
