# 🏆 MELHORES PRÁTICAS APLICADAS - FARMACIA API

## 📋 Índice
1. [Arquitetura REST](#arquitetura-rest)
2. [Padrão MVC](#padrão-mvc)
3. [Segurança JWT](#segurança-jwt)
4. [Qualidade de Código](#qualidade-de-código)
5. [Performance](#performance)
6. [Documentação](#documentação)
7. [Testes](#testes)
8. [Banco de Dados](#banco-de-dados)

---

## 🔹 Arquitetura REST

### ✅ Implementado

**1. Recursos (Nouns)**
```php
/api/farmacias          // Recurso: farmacias
/api/produtos           // Recurso: produtos
/api/estoques           // Recurso: estoques
/api/servicos           // Recurso: serviços
```

**2. Métodos HTTP Semânticos**
```php
GET    /api/farmacias        // Listar (coleção)
POST   /api/farmacias        // Criar (novo recurso)
GET    /api/farmacias/1      // Obter (um recurso)
PUT    /api/farmacias/1      // Atualizar (completo)
DELETE /api/farmacias/1      // Deletar (remove)
```

**3. Status HTTP Corretos**
```php
200  // OK - Sucesso em GET, PUT
201  // Created - POST bem-sucedido
204  // No Content - DELETE bem-sucedido
400  // Bad Request - Erro de sintaxe/formato
401  // Unauthorized - Sem autenticação
403  // Forbidden - Sem autorização
404  // Not Found - Recurso não existe
422  // Unprocessable Entity - Erro de validação
500  // Internal Server Error - Erro do servidor
```

**4. Versionamento Opcional**
```php
// Estrutura pronta para expansão
/api/v1/farmacias      // Versão atual
/api/v2/farmacias      // Versão futura
```

**5. Pagination**
```php
GET /api/farmacias?page=1&per_page=15
Response: {
  "data": [...],
  "meta": {
    "current_page": 1,
    "total": 50,
    "last_page": 4,
    "per_page": 15
  }
}
```

**6. Filtros Query**
```php
GET /api/produtos?search=dipirona&categoria_id=1&em_falta=true
GET /api/estoques?farmacia_id=1&em_falta=true
GET /api/farmacias?search=central
```

---

## 🔹 Padrão MVC

### ✅ Implementado

**Model** - Lógica de dados e negócio
```php
// app/Models/Produto.php
class Produto extends Model {
    // Relações (ORM Eloquent)
    public function categoria() { ... }
    public function estoques() { ... }
    
    // Accessors (computados)
    public function getEmFaltaAttribute() { ... }
    
    // Métodos de negócio
    public function estaVencido() { ... }
}
```

**Controller** - Orquestrante de requests
```php
// app/Http/Controllers/Api/ProdutoController.php
class ProdutoController extends Controller {
    // Recebe request
    public function store(StoreProdutoRequest $request) {
        // Valida (Form Request faz isso)
        // Processa (Model)
        // Responde (JSON)
    }
}
```

**View** - Frontend desacoplado
```
Frontend PWA (Vue/React/Next.js)
    ↓ HTTP JSON
Backend API (Laravel)
    ↓ REST
PostgreSQL Database
```

---

## 🔹 Segurança JWT

### ✅ Implementado

**1. Stateless (Sem Sessão)**
```php
// Cada request é independente
POST /api/auth/login
Response: { token: "eyJhbGc..." }

GET /api/farmacias
Header: Authorization: Bearer eyJhbGc...
```

**2. JWT Structure**
```
Header.Payload.Signature
eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOjEsInJvbGUiOiJhZG1pbiIsImlhdCI6MTYwLCJleHAiOjE2MDAwfQ.SIGNATURE
```

**3. Payload**
```php
{
  "sub": 1,              // User ID
  "email": "admin@...",
  "name": "Admin",
  "role": "admin",       // Papel/Permissão
  "iat": 1609459200,     // Emitido em
  "exp": 1609462800      // Expira em
}
```

**4. Renovação de Token**
```php
POST /api/auth/refresh
Header: Authorization: Bearer eyJhbGc... (token atual)
Response: { token: "novo_token" }
```

**5. Logout (Invalidação)**
```php
POST /api/auth/logout
Header: Authorization: Bearer eyJhbGc...
// Token é invalidado no frontend
```

**6. Autorização por Role**
```php
// Middleware verifica role do JWT
if ($payload->role === 'admin') {
    // Permite acesso irrestrito
}
```

---

## 🔹 Qualidade de Código

### ✅ Implementado

**1. Type Hints (PHP 8)**
```php
public function store(StoreProdutoRequest $request): JsonResponse
{
    $produto = Produto::create($request->validated());
    return response()->json($produto, 201);
}
```

**2. Documentação com Docblocks**
```php
/**
 * Repor estoque de um produto
 * 
 * @param int $quantidade Quantidade a repor
 * @return void
 * @throws Exception
 */
public function repor(int $quantidade): void
{
    $this->quantidade += $quantidade;
    $this->save();
}
```

**3. OpenAPI/Swagger**
```php
/**
 * @OA\Get(
 *     path="/api/farmacias",
 *     summary="Listar todas as farmacias",
 *     tags={"Farmacias"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(response=200, description="Lista de farmacias")
 * )
 */
public function index() { ... }
```

**4. SOLID Principles**

- **S** (Single Responsibility)
  - ProdutoController: Apenas controla Produtos
  - StoreProdutoRequest: Apenas valida entrada
  - JwtService: Apenas gerencia JWT
  
- **O** (Open/Closed)
  - Extensível sem modificar código existente
  - Policies para novos tipos de autorização
  
- **L** (Liskov Substitution)
  - Models herdam corretamente de Model
  - Controllers herdam de Controller base
  
- **I** (Interface Segregation)
  - Validadores específicos por operação
  - Requests separados para Store vs Update
  
- **D** (Dependency Injection)
  - JwtService injetado via type hint
  - Mail, Cache, etc injetáveis

**5. DRY (Don't Repeat Yourself)**
```php
// Base controller com métodos comuns
class Controller {
    protected function successResponse($data) { ... }
    protected function errorResponse($msg) { ... }
}

// Controllers reutilizam
class ProdutoController extends Controller {
    public function store(...) {
        return $this->successResponse($produto, 'Criado', 201);
    }
}
```

**6. KISS (Keep It Simple, Stupid)**
- Código limpo e legível
- Métodos curtos e focados
- Nomes descritivos

---

## 🔹 Performance

### ✅ Implementado

**1. Eager Loading**
```php
// Evita N+1 queries
Farmacia::with(['usuario', 'estoques.produto'])->get();

// vs (problema)
$farmacias = Farmacia::all();
foreach ($farmacias as $f) {
    $f->usuario; // Query aqui! (problema)
}
```

**2. Índices no Banco**
```sql
-- Full-text search
CREATE INDEX idx_produtos_nome ON produtos USING GIN(to_tsvector('portuguese', nome));
CREATE INDEX idx_farmacias_nome ON farmacias USING GIN(to_tsvector('portuguese', nome));

-- Busca rápida
CREATE INDEX idx_usuarios_email ON users(email);
CREATE INDEX idx_estoques_farmacia ON estoques(farmacia_id);
CREATE INDEX idx_produtos_categoria ON produtos(categoria_id);
```

**3. Query Scopes (Reutilizáveis)**
```php
// No Model
public function scopeComEstoque($query) {
    return $query->where('quantidade', '>', 'stock_minimo');
}

// No Controller
$produtos = Produto::comEstoque()->paginate();
```

**4. Paginação Padrão**
```php
GET /api/farmacias?per_page=15&page=2
// Evita carregar milhares de registros
```

**5. Cache (Estrutura Pronta)**
```php
// config/cache.php já configurado
CACHE_DRIVER=redis

// Uso: cache()->remember('key', 3600, fn() => query);
```

---

## 🔹 Documentação

### ✅ Implementado

**1. API_DOCUMENTATION.md**
- Setup inicial
- Endpoints com exemplos
- Filtros e query parameters
- Erros esperados
- Modelos e relações

**2. OpenAPI/Swagger**
```php
/**
 * @OA\Get(
 *     path="/api/produtos",
 *     summary="Listar produtos",
 *     @OA\Parameter(name="search", in="query", schema={"type":"string"}),
 *     @OA\Response(response=200, description="Lista")
 * )
 */
```

Gerada automaticamente em: `/api/documentation`

**3. Postman Collection**
- `Farmacia_API.postman_collection.json`
- Todos os endpoints
- Exemplos de request/response
- Variáveis de ambiente

**4. IMPLEMENTATION_CHECKLIST.md**
- Tudo que foi implementado
- Status de cada componente
- Próximos passos opcionais

**5. SUMMARY.md**
- Resumo executivo
- O que foi criado
- Como usar

---

## 🔹 Testes

### ✅ Estrutura Pronta

**1. Seeders com Dados de Teste**
```bash
php artisan db:seed

// Cria:
// - 3 Roles
// - 7 Usuários (1 admin, 2 gerentes, 5 comuns)
// - 6 Categorias
// - 5 Produtos
// - 2 Farmácias
// - 9 Estoques (alguns em falta)
// - 5 Serviços
```

**2. Usuários de Teste**
```
admin@farmacia.com / password123 (admin)
joao@farmacia.com / password123 (gerente)
maria@farmacia.com / password123 (gerente)
```

**3. Postman Collection**
- Pronto para testar todos os endpoints
- Variáveis de ambiente
- Exemplos reais

**4. PHPUnit (Estrutura)**
```
tests/
├── Feature/
│   ├── AuthTest.php           # Testes de autenticação
│   ├── ProdutoTest.php        # Testes de produtos
│   └── FarmaciaTest.php       # Testes de farmácias
└── Unit/
    ├── JwtServiceTest.php     # Testes JWT
    └── EstoqueTest.php        # Testes de lógica
```

---

## 🔹 Banco de Dados

### ✅ Implementado

**1. Normalização (3NF)**
```
Users → Roles (1:1)
Farmacias → Users (N:1)
Produtos → Categorias (N:1)
Estoques → Farmacias, Produtos (N:N)
```

**2. Constraints**
```sql
-- Foreign Keys
ALTER TABLE farmacias ADD CONSTRAINT fk_user 
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;

-- Unique
ALTER TABLE produtos ADD UNIQUE(codigo);
ALTER TABLE estoques ADD UNIQUE(farmacia_id, produto_id);

-- Check
ALTER TABLE estoques ADD CHECK (quantidade >= 0);
```

**3. Soft Deletes**
```php
// Farmacias e Produtos podem ser "deletados" sem perder dados
$farmacia->delete();  // Marca deleted_at
$farmacia->restore(); // Restaura

// Consultas automáticas excluem soft-deleted
Farmacia::all();      // Não inclui deletados
Farmacia::withTrashed(); // Inclui deletados
```

**4. Timestamps**
```php
// Todos os models têm created_at, updated_at
$produto->created_at;  // Quando foi criado
$produto->updated_at;  // Quando foi editado
```

**5. Índices de Performance**
```php
// Migration cria índices automaticamente
->index() // Índice simples
->unique() // Índice único
->fullText() // Full-text search

// Resultado: Queries rápidas mesmo com milhões de registros
```

---

## 🎯 Resultado Final

Uma API **profissional, segura, documentada e escalável**, pronta para:

✅ Desenvolvimento imediato  
✅ Integração com frontend PWA  
✅ Deployment em produção  
✅ Expansão e manutenção  
✅ Colaboração em equipe  

---

**Desenvolvido seguindo:**
- Laravel Best Practices
- REST API Standards (RFC 7231)
- OpenAPI 3.0 Specification
- SOLID Principles
- Clean Code Principles
- PostgreSQL Best Practices
- JWT Security Standards

**Documentação:** Janeiro 2026
