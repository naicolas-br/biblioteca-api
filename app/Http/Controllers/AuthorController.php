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
    public function show($id)
    {
        // TODO: Implementar aqui
        //
        // Dicas:
        // - Use Author::findOrFail() para busca com 404 automático
        // - Use AuthorResource para formatar resposta
        //
        // Exemplo:
        // $author = Author::findOrFail($id);
        // return response()->json([
        //     'data' => new AuthorResource($author)
        // ]);
        
        return response()->json([
            'message' => 'TODO: Implementar busca de autor',
            'endpoint' => "GET /api/authors/{$id}",
            'documentation' => 'Consulte docs/API_ENDPOINTS.md'
        ], 501);
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
        // TODO: Implementar aqui
        //
        // Dicas:
        // - Use Author::findOrFail() para busca com 404 automático
        // - Use $author->update() para atualizar
        // - Use AuthorResource para formatar resposta
        //
        // Exemplo:
        // $author = Author::findOrFail($id);
        // $author->update($request->validated());
        // return response()->json([
        //     'data' => new AuthorResource($author)
        // ]);
        
        return response()->json([
            'message' => 'TODO: Implementar atualização de autor',
            'endpoint' => "PUT /api/authors/{$id}",
            'documentation' => 'Consulte docs/API_ENDPOINTS.md'
        ], 501);
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
        // TODO: Implementar aqui
        //
        // ⚠️ ATENÇÃO: Esta é a parte mais importante!
        // 
        // Dicas:
        // - Use Author::findOrFail() para busca
        // - Verifique se tem livros: $author->books()->count() > 0
        // - Se tiver livros, retorne 409 com mensagem explicativa
        // - Se não tiver, use $author->delete() e retorne 204
        //
        // Exemplo:
        // $author = Author::findOrFail($id);
        // 
        // if ($author->books()->count() > 0) {
        //     return response()->json([
        //         'message' => 'Não é possível excluir autor que possui livros associados.',
        //         'status' => 409
        //     ], 409);
        // }
        // 
        // $author->delete();
        // return response()->noContent(); // 204
        
        return response()->json([
            'message' => 'TODO: Implementar exclusão de autor com regra de negócio',
            'endpoint' => "DELETE /api/authors/{$id}",
            'documentation' => 'Consulte docs/BUSINESS_RULES.md',
            'important' => 'Não esqueça da regra: não excluir autor com livros!'
        ], 501);
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
        // TODO: Implementar aqui
        //
        // Dicas:
        // - Use Author::findOrFail() para busca
        // - Use $author->books()->paginate() para livros paginados
        // - Use PaginatedResource com BookResource::collection()
        // - Considere usar with('author') no eager loading se necessário
        //
        // Exemplo:
        // $author = Author::findOrFail($id);
        // $books = $author->books()
        //                ->orderBy($request->sort ?? 'titulo')
        //                ->paginate($request->per_page ?? 15);
        // 
        // return new PaginatedResource(BookResource::collection($books));
        
        return response()->json([
            'message' => 'TODO: Implementar listagem de livros do autor',
            'endpoint' => "GET /api/authors/{$id}/books",
            'documentation' => 'Consulte docs/API_ENDPOINTS.md'
        ], 501);
    }
}