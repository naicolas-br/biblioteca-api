<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAuthorRequest;
use App\Http\Requests\UpdateAuthorRequest;
use App\Http\Resources\AuthorResource;
use App\Http\Resources\BookResource;
use App\Http\Resources\PaginatedResource;
use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    /**
     * 📋 TODO: Implementar listagem de autores
     * 
     * Funcionalidades esperadas:
     * - Paginação (15 itens por página por padrão)
     * - Busca por nome (?q=nome)
     * - Ordenação (?sort=nome)
     * - Normalização de parâmetros inválidos
     * 
     * Resposta esperada: PaginatedResource com AuthorResource
     * Status: 200 OK
     */
    public function index(Request $request) {
        // Normaliza o número de itens por página, garantindo que esteja entre 1 e 100.
        $perPage = max(1, min(100, (int) $request->input('per_page', 15)));

        //Inicia a query e aplica os scopes de busca e ordenação
        $authors = Author::query()
            ->search($request->input('q')) // Usa o scope de busca
            ->applyOrder($request->input('sort'), $request->input('direction')) // Usa o scope de ordenação
            ->paginate($perPage)
            ->withQueryString(); // Adiciona os parâmetros da request atual aos links de paginação

        //Retorna a coleção de autores paginada e formatada pelo Resource
        return new PaginatedResource(AuthorResource::collection($authors));
    }

    /**
     * 📋 TODO: Implementar criação de autor
     * 
     * Funcionalidades esperadas:
     * - Validação automática via StoreAuthorRequest
     * - Criação do autor no banco
     * - Resposta formatada com AuthorResource
     * 
     * Status: 201 Created
     * Status: 422 Unprocessable Entity (validação)
     */
    public function store(StoreAuthorRequest $request)
    {
        // Os dados já foram validados pelo StoreAuthorRequest.
        // O método validated() retorna um array apenas com os dados 
        // que passaram nas regras de validação ('nome' e 'bio').
        $author = Author::create($request->validated());

        // Retorna o novo autor, formatado pelo AuthorResource,
        // e define o código de status HTTP para 201 Created.
        return (new AuthorResource($author))
                ->response()
                ->setStatusCode(201);
    }

    /**
     * 📋 TODO: Implementar busca de autor por ID
     * 
     * Funcionalidades esperadas:
     * - Buscar autor por ID
     * - Retornar 404 se não encontrado
     * - Resposta formatada com AuthorResource
     * 
     * Status: 200 OK
     * Status: 404 Not Found
     */
    public function show($id) {
        // Use findOrFail para buscar o autor pelo ID.
        // Se o autor não for encontrado, o Laravel automaticamente
        // lançará uma exceção que resulta em uma resposta 404 Not Found.
        $author = Author::findOrFail($id);

        // Se o autor for encontrado, retorne-o formatado pelo AuthorResource.
        // O status 200 OK é o padrão para respostas bem-sucedidas.
        return new AuthorResource($author);
    }

    /**
     * 📋 TODO: Implementar atualização de autor
     * 
     * Funcionalidades esperadas:
     * - Validação automática via UpdateAuthorRequest
     * - Atualização do autor no banco
     * - Retornar 404 se não encontrado
     * - Resposta formatada com AuthorResource
     * 
     * Status: 200 OK
     * Status: 404 Not Found
     * Status: 422 Unprocessable Entity (validação)
     */
    public function update(UpdateAuthorRequest $request, $id)
    {
        // 1. Busca o autor pelo ID. Se não encontrar, retorna 404 automaticamente.
        $author = Author::findOrFail($id);

        // 2. A validação já foi feita pelo UpdateAuthorRequest.
        //    O método validated() retorna os dados que passaram na validação.
        $validatedData = $request->validated();

        // 3. Atualiza o autor no banco de dados com os dados validados.
        $author->update($validatedData);

        // 4. Retorna o autor com os dados atualizados, formatado pelo Resource.
        return new AuthorResource($author);
    }

    /**
     * 📋 TODO: Implementar exclusão de autor
     * 
     * ⚠️ REGRA DE NEGÓCIO IMPORTANTE:
     * - NÃO pode excluir autor que tem livros associados
     * - Deve retornar 409 Conflict nesse caso
     * - Se não tem livros, pode excluir (204 No Content)
     * 
     * Status: 204 No Content (sucesso)
     * Status: 404 Not Found (autor não existe)
     * Status: 409 Conflict (autor tem livros)
     */
    public function destroy($id)
    {
        // 1. Busca o autor pelo ID. Se não encontrar, retorna 404 automaticamente.
        $author = Author::findOrFail($id);

        // 2. Verifica se o autor possui algum livro associado.
        // O método has('books') é uma forma eficiente de fazer essa verificação.
        if ($author->books()->exists()) {
            // 3. Se tiver livros, retorna um erro 409 Conflict com uma mensagem clara.
            return response()->json([
                'message' => 'Não é possível excluir o autor, pois ele possui livros associados.'
            ], 409);
        }

        // 4. Se não tiver livros, prossegue com a exclusão.
        $author->delete();

        // 5. Retorna uma resposta 204 No Content, que é o padrão para exclusões bem-sucedidas.
        return response()->noContent();
    }

    /**
     * 📋 TODO: Implementar listagem de livros do autor
     * 
     * Funcionalidades esperadas:
     * - Buscar autor por ID
     * - Listar livros do autor com paginação
     * - Retornar 404 se autor não existe
     * - Resposta formatada com PaginatedResource
     * 
     * Status: 200 OK
     * Status: 404 Not Found
     */
    public function books($id, Request $request)
    {
        // 1. Busca o autor pelo ID. Se não encontrar, retorna 404 automaticamente.
        $author = Author::findOrFail($id);

        // 2. Normaliza os parâmetros de paginação, como fizemos no index.
        $perPage = max(1, min(100, (int) $request->input('per_page', 15)));

        // 3. Acessa o relacionamento 'books()' do autor para obter uma query
        //    e então aplica a paginação.
        $books = $author->books()->paginate($perPage);

        // 4. Retorna a coleção de livros paginada, formatada pelo PaginatedResource
        //    que por sua vez usará o BookResource para cada livro.
        return new PaginatedResource(BookResource::collection($books));
    }
}