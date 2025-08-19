<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\BookResource;
use App\Http\Resources\PaginatedResource;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * 📋 TODO: Implementar listagem de livros
     * 
     * Funcionalidades esperadas:
     * - Paginação (15 itens por página por padrão)
     * - Filtros múltiplos:
     *   - ?q=termo (busca em título e gênero)
     *   - ?author_id=1 (filtrar por autor)
     *   - ?disponivel=true (filtrar por disponibilidade)
     *   - ?ano_de=1800&ano_ate=1900 (filtrar por faixa de anos)
     * - Ordenação (?sort=titulo)
     * - Eager loading do autor quando necessário
     * 
     * Resposta esperada: PaginatedResource com BookResource
     * Status: 200 OK
     */
    public function index(Request $request)
    {
        // Normaliza o número de itens por página, garantindo que esteja entre 1 e 100.
        $perPage = max(1, min(100, (int) $request->input('per_page', 15)));

        $books = Book::query()
            // Faz o Eager Loading do autor para evitar o problema de N+1 queries.
            ->with('author')

            // Aplica os filtros condicionalmente, apenas se eles existirem na request.
            ->when($request->input('q'), function ($query, $term) {
                // Assume que o Model Book tem um scope 'search'
                $query->search($term);
            })
            ->when($request->input('author_id'), function ($query, $authorId) {
                // Assume que o Model Book tem um scope 'byAuthor'
                $query->byAuthor($authorId);
            })
            ->when($request->has('disponivel'), function ($query) use ($request) {
                // Assume que o Model Book tem um scope 'byAvailability'
                $query->byAvailability($request->boolean('disponivel'));
            })
            ->when($request->input('ano_de') || $request->input('ano_ate'), function ($query) use ($request) {
                // Assume que o Model Book tem um scope 'byYearRange'
                $query->byYearRange($request->input('ano_de'), $request->input('ano_ate'));
            })

            // Aplica a ordenação.
            // TODO: É uma boa prática criar um scope 'orderBy' no Model Book,
            // assim como você fez no Author, para validar os campos.
            ->applyOrder($request->input('sort', 'titulo'), $request->input('direction', 'asc'))

            // Executa a paginação e mantém os parâmetros de filtro nos links.
            ->paginate($perPage)
            ->withQueryString();

        // Retorna a resposta paginada usando os Resources.
        return new PaginatedResource(BookResource::collection($books));
    }

    /**
     * 📋 TODO: Implementar criação de livro
     * 
     * Funcionalidades esperadas:
     * - Validação automática via StoreBookRequest
     * - Criação do livro no banco
     * - Resposta formatada com BookResource
     * - Tratamento automático de título duplicado (409 via FormRequest)
     * 
     * Status: 201 Created
     * Status: 409 Conflict (título duplicado para mesmo autor)
     * Status: 422 Unprocessable Entity (validação)
     */
    public function store(StoreBookRequest $request)
    {
        // TODO: Implementar aqui
        //
        // Dicas:
        // - Os dados já estão validados pelo StoreBookRequest
        // - A validação de título duplicado é automática (retorna 409)
        // - Use Book::create() para criar
        // - Use BookResource para formatar resposta
        // - Retorne status 201
        // - Considere carregar o autor com with('author') se necessário
        //
        // Exemplo:
        // $book = Book::create($request->validated());
        // $book->load('author'); // Carregar dados do autor
        // return response()->json([
        //     'data' => new BookResource($book)
        // ], 201);
        
        return response()->json([
            'message' => 'TODO: Implementar criação de livro',
            'endpoint' => 'POST /api/books',
            'documentation' => 'Consulte docs/API_ENDPOINTS.md',
            'validation' => 'Título único por autor já validado automaticamente'
        ], 501);
    }

    /**
     * 📋 TODO: Implementar busca de livro por ID
     * 
     * Funcionalidades esperadas:
     * - Buscar livro por ID
     * - Carregar dados do autor (eager loading)
     * - Retornar 404 se não encontrado
     * - Resposta formatada com BookResource
     * 
     * Status: 200 OK
     * Status: 404 Not Found
     */
    public function show($id)
    {
        // TODO: Implementar aqui
        //
        // Dicas:
        // - Use Book::with('author')->findOrFail() para busca com autor
        // - Use BookResource para formatar resposta
        //
        // Exemplo:
        // $book = Book::with('author')->findOrFail($id);
        // return response()->json([
        //     'data' => new BookResource($book)
        // ]);
        
        return response()->json([
            'message' => 'TODO: Implementar busca de livro',
            'endpoint' => "GET /api/books/{$id}",
            'documentation' => 'Consulte docs/API_ENDPOINTS.md'
        ], 501);
    }

    /**
     * 📋 TODO: Implementar atualização de livro
     * 
     * Funcionalidades esperadas:
     * - Validação automática via UpdateBookRequest
     * - Atualização do livro no banco
     * - Retornar 404 se não encontrado
     * - Resposta formatada com BookResource
     * - Tratamento de título duplicado (409 via FormRequest)
     * 
     * Status: 200 OK
     * Status: 404 Not Found
     * Status: 409 Conflict (título duplicado)
     * Status: 422 Unprocessable Entity (validação)
     */
    public function update(UpdateBookRequest $request, $id)
    {
        // TODO: Implementar aqui
        //
        // Dicas:
        // - Use Book::findOrFail() para busca com 404 automático
        // - Use $book->update() para atualizar
        // - Use BookResource para formatar resposta
        // - Carregue o autor se necessário
        //
        // Exemplo:
        // $book = Book::findOrFail($id);
        // $book->update($request->validated());
        // $book->load('author'); // Recarregar com dados do autor
        // return response()->json([
        //     'data' => new BookResource($book)
        // ]);
        
        return response()->json([
            'message' => 'TODO: Implementar atualização de livro',
            'endpoint' => "PUT /api/books/{$id}",
            'documentation' => 'Consulte docs/API_ENDPOINTS.md'
        ], 501);
    }

    /**
     * 📋 TODO: Implementar exclusão de livro
     * 
     * Funcionalidades esperadas:
     * - Buscar livro por ID
     * - Retornar 404 se não encontrado
     * - Excluir livro
     * - Resposta sem conteúdo (204)
     * 
     * Status: 204 No Content (sucesso)
     * Status: 404 Not Found (livro não existe)
     */
    public function destroy($id)
    {
        // TODO: Implementar aqui
        //
        // Dicas:
        // - Use Book::findOrFail() para busca
        // - Use $book->delete() para excluir
        // - Retorne response()->noContent() para 204
        //
        // Exemplo:
        // $book = Book::findOrFail($id);
        // $book->delete();
        // return response()->noContent(); // 204
        
        return response()->json([
            'message' => 'TODO: Implementar exclusão de livro',
            'endpoint' => "DELETE /api/books/{$id}",
            'documentation' => 'Consulte docs/API_ENDPOINTS.md'
        ], 501);
    }
}