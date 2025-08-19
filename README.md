# 📚 Biblioteca API  

API RESTful desenvolvida em **Laravel** para gerenciamento de biblioteca, permitindo o cadastro e controle de **autores** e **livros**, com regras de negócio, paginação e filtros.  

## 🚀 Funcionalidades  
- ✅ **CRUD de Autores** (Create, Read, Update, Delete)  
- ✅ **CRUD de Livros** (Create, Read, Update, Delete)  
- ✅ Relacionamento entre autores e livros  
- ✅ Validações robustas via *Form Requests*  
- ✅ Regras de negócio (ex: não permitir exclusão de autor com livros cadastrados)  
- ✅ Paginação e filtros avançados (busca e ordenação)  

## 🔗 Endpoints principais  
### Autores  
- `GET /api/authors` → Listar autores (com paginação e busca)  
- `POST /api/authors` → Criar autor  
- `GET /api/authors/{id}` → Detalhar autor  
- `PUT /api/authors/{id}` → Atualizar autor  
- `DELETE /api/authors/{id}` → Excluir autor (restrição: só se não possuir livros)  
- `GET /api/authors/{id}/books` → Listar livros de um autor  

### Livros  
- `GET /api/books` → Listar livros (com filtros e paginação)  
- `POST /api/books` → Criar livro  
- `GET /api/books/{id}` → Detalhar livro  
- `PUT /api/books/{id}` → Atualizar livro  
- `DELETE /api/books/{id}` → Excluir livro  

## 🛠️ Tecnologias utilizadas  
- [Laravel](https://laravel.com/)  
- [MySQL](https://www.mysql.com/)  
- [Postman](https://www.postman.com/) / [Insomnia](https://insomnia.rest/) para testes manuais  

## ⚙️ Como executar o projeto  

### 1. Clonar o repositório  
```bash
git clone https://github.com/seu-usuario/biblioteca-api.git
cd biblioteca-api
```

### 2. Instalar dependências
```bash
composer install
```
### 3. Configurar o ambiente
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=biblioteca_api
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Executar Migrations e Seeds
```bash
php artisan migrate --seed
```

### 5 Iniciar o servidor
```bash
php artisan serve
```

A API estará disponível em:
👉 http://localhost:8000/api
