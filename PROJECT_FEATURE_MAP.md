# Mapa de Funcionalidades do Projeto

## Rotas principais

- `/` - página inicial (`welcome`)
- `GET /bibliotecas` - lista bibliotecas
- `GET /bibliotecas/new` - formulário de criação de biblioteca
- `POST /bibliotecas/create` - cria biblioteca
- `GET /bibliotecas/edit/{id}` - formulário de edição de biblioteca
- `PUT /bibliotecas/update/{id}` - atualiza biblioteca
- `DELETE /bibliotecas/delete/{id}` - exclui biblioteca
- `GET /bibliotecas/{biblioteca}/pessoas/add` - formulário para associar pessoa a biblioteca
- `POST /bibliotecas/{biblioteca}/pessoas` - associa pessoa a biblioteca
- `resource('users')` - CRUD de usuários
- `resource('pessoas')` - CRUD de pessoas
- `resource('livros')` - rota de livros (controlador vazio)
- `resource('autores')` - rota de autores (controlador vazio)

## Controladores e funcionalidades implementadas

### `BibliotecasController`
- `index` - busca bibliotecas pelo nome
- `create` - exibe formulário com usuários
- `store` - cria biblioteca
- `edit` - exibe formulário de edição
- `update` - atualiza biblioteca com dados parciais
- `destroy` - exclui biblioteca

### `BibliotecaPessoaController`
- `create` - lista pessoas não associadas à biblioteca
- `store` - associa pessoa a biblioteca, evitando duplicatas

### `UserController`
- CRUD completo de usuários
- criação, edição, atualização de `role`, exclusão

### `PessoaController`
- CRUD parcial de pessoas
- criação com validação de senha igual
- edição com atualização opcional de senha
- `destroy` ainda não implementado

### `LivroController` e `AutorController`
- controladores criados, mas sem implementações de método

## Principais modelos e relacionamentos

### `App\Models\User`
- `belongsToMany(Biblioteca::class, 'biblioteca_user')`
- `roleInBiblioteca($biblioteca)`
- verificações de papel: `owner`, `admin`, `editor`, `viewer`

### `App\Models\Biblioteca`
- `belongsTo(User::class, 'created_by')`
- `belongsToMany(User::class, 'biblioteca_user')`
- `belongsToMany(Pessoa::class, 'biblioteca_pessoa')`

### `App\Models\Pessoa`
- `belongsToMany(Biblioteca::class, 'biblioteca_pessoa')`

### `App\Models\Livro` e `App\Models\Autor`
- `Livro` pertence a `Autor`
- `Autor` tem muitos `Livro`

## Testes criados

### Unidade
- `tests/Unit/UserModelTest.php`
  - valida `roleInBiblioteca`
  - valida métodos de papel (`owner`, `admin`, etc.)

### Feature
- `tests/Feature/PessoaControllerTest.php`
  - criação de pessoa com senhas iguais
  - rejeição quando senhas divergem
  - atualização de pessoa com senha
  - atualização com senha incompatível

- `tests/Feature/UserControllerTest.php`
  - lista usuários
  - cria usuário
  - edita usuário
  - atualiza usuário
  - exclui usuário

- `tests/Feature/BibliotecasControllerTest.php`
  - busca por bibliotecas
  - cria biblioteca
  - edita biblioteca
  - atualiza biblioteca
  - exclui biblioteca

- `tests/Feature/BibliotecaPessoaControllerTest.php`
  - exibe pessoas disponíveis para associação
  - associa pessoa à biblioteca

## Status atual

- `18` testes criados
- `55` asserções
- Todos executados com sucesso no container Docker do projeto

## Recomendações futuras

- Implementar `destroy` em `PessoaController`
- Implementar métodos em `LivroController` e `AutorController`
- Criar testes de cobertura para `Livro` e `Autor` após implementação
- Adicionar testes de casos de erro adicionais para `BibliotecasController` e `UserController`
