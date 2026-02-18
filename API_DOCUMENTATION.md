<<<<<<< HEAD
# 🏥 Farmacia API - REST + JWT + PostgreSQL

API REST completa para gerenciamento de farmácias, produtos, estoques e serviços.

## 📋 Stack Tecnológico

- **Framework:** Laravel 12
- **Banco de Dados:** PostgreSQL
- **Autenticação:** JWT (Firebase PHP-JWT)
- **Arquitetura:** API-First + MVC + REST
- **Frontend:** PWA (desacoplado)
- **Documentação:** OpenAPI/Swagger

---

## 🎯 Arquitetura

```
[Frontend PWA]
   ↓ HTTP + JSON + Bearer Token
[API Controllers]
   ↓
[Services / Domain Logic]
   ↓
[Models + Relations]
   ↓
[PostgreSQL Database]
```

### Princípios Aplicados:

✅ **API-REST**: Recursos baseados em HTTP, uso explícito de métodos (GET, POST, PUT, DELETE)
✅ **Stateless**: Cada request é independente (sem sessões, usar JWT)
✅ **JWT**: Token Bearer em cada requisição (Authorization header)
✅ **MVC**: Models (relações), Controllers (orquestra), Views (frontend externo)
✅ **PostgreSQL**: Banco relacional com indices e constraints

---

## 🗂️ Estrutura de Pastas

```
backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/Api/          # Controllers REST
│   │   ├── Middleware/               # JWT Middleware
│   │   └── Requests/                 # Form Requests (validação)
│   ├── Models/                       # Models com relações
│   ├── Support/
│   │   ├── Jwt/                      # JwtService
│   │   └── OpenApi/                  # Documentação Swagger
│   └── Domain/                       # Lógica de negócio
├── routes/
│   └── api.php                       # Rotas API
├── database/
│   ├── migrations/                   # Estrutura do BD
│   └── seeders/                      # Dados de teste
├── config/
│   ├── jwt.php                       # Configuração JWT
│   └── database.php                  # Configuração BD
└── public/
    └── index.php                     # Entry point
```

---

## 🚀 Setup Inicial

### 1. Clonar e Instalar Dependências

```bash
cd backend
composer install
```

### 2. Configurar Variáveis de Ambiente

```bash
cp .env.example .env
php artisan key:generate
```

**Editar `.env`:**
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=farmacia_db
DB_USERNAME=postgres
DB_PASSWORD=sua_senha

JWT_SECRET=sua-chave-secreta-super-segura-12345
```

### 3. Criar Banco de Dados PostgreSQL

```bash
# Com psql
createdb farmacia_db
```

### 4. Executar Migrations

```bash
php artisan migrate --force
```

### 5. Popular com Dados de Teste

```bash
php artisan db:seed
```

### 6. Gerar Documentação Swagger

```bash
# Se usar L5-Swagger
php artisan l5-swagger:generate
```

### 7. Iniciar Servidor

```bash
php artisan serve
```

API disponível em: `http://localhost:8000/api`

---

## 🔐 Autenticação JWT

### Login (Obter Token)

```bash
POST /api/auth/login
Content-Type: application/json

{
  "email": "admin@farmacia.com",
  "password": "password123"
}
```

**Resposta (200):**
```json
{
  "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
  "type": "bearer",
  "expires_in": 3600
}
```

### Usar Token em Requisições

```bash
GET /api/farmacias
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
```

### Renovar Token

```bash
POST /api/auth/refresh
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
```

### Logout

```bash
POST /api/auth/logout
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
```

---

## 📚 Endpoints Principais

### Farmacias

| Método | Endpoint | Descrição |
|--------|----------|-----------|
| `GET` | `/api/farmacias` | Listar todas as farmacias |
| `POST` | `/api/farmacias` | Criar farmacia |
| `GET` | `/api/farmacias/{id}` | Obter detalhes |
| `PUT` | `/api/farmacias/{id}` | Atualizar |
| `DELETE` | `/api/farmacias/{id}` | Deletar |

### Produtos

| Método | Endpoint | Descrição |
|--------|----------|-----------|
| `GET` | `/api/produtos` | Listar produtos |
| `POST` | `/api/produtos` | Criar produto |
| `GET` | `/api/produtos/{id}` | Detalhes |
| `PUT` | `/api/produtos/{id}` | Atualizar |
| `DELETE` | `/api/produtos/{id}` | Deletar |

### Estoques

| Método | Endpoint | Descrição |
|--------|----------|-----------|
| `GET` | `/api/estoques` | Listar estoques |
| `POST` | `/api/estoques` | Criar registro |
| `PUT` | `/api/estoques/{id}` | Atualizar |
| `POST` | `/api/estoques/{id}/repor` | Repor quantidade |
| `POST` | `/api/estoques/{id}/remover` | Remover quantidade |

### Serviços

| Método | Endpoint | Descrição |
|--------|----------|-----------|
| `GET` | `/api/servicos` | Listar serviços |
| `POST` | `/api/servicos` | Criar serviço |
| `PUT` | `/api/servicos/{id}` | Atualizar |
| `DELETE` | `/api/servicos/{id}` | Deletar |

---

## 📖 Exemplos de Requisições

### Criar Farmacia

```bash
POST /api/farmacias
Authorization: Bearer TOKEN
Content-Type: application/json

{
  "nome": "Farmácia Central",
  "localizacao": "Rua Principal, 123"
}
```

### Criar Produto

```bash
POST /api/produtos
Authorization: Bearer TOKEN
Content-Type: application/json

{
  "nome": "Dipirona 500mg",
  "codigo": "DIP-500-001",
  "preco": 12.50,
  "categoria_id": 1,
  "data_validade": "2025-12-31"
}
```

### Criar Estoque

```bash
POST /api/estoques
Authorization: Bearer TOKEN
Content-Type: application/json

{
  "farmacia_id": 1,
  "produto_id": 1,
  "quantidade": 100,
  "stock_minimo": 10
}
```

### Filtrar Produtos em Falta

```bash
GET /api/produtos?em_falta=true
Authorization: Bearer TOKEN
```

### Repor Estoque

```bash
POST /api/estoques/1/repor
Authorization: Bearer TOKEN
Content-Type: application/json

{
  "quantidade": 50
}
```

---

## 🔒 Modelos e Relações

### User (Usuário)
```php
- Tem muitas Farmacias
- Pertence a uma Role
- Tem muitas Pesquisas
```

### Farmacia
```php
- Pertence a um User
- Tem muitos Estoques
- Tem muitos Produtos (via Estoque)
- Tem muitos Serviços
- Tem muitas Localizações
```

### Produto
```php
- Pertence a uma Categoria
- Tem muitos Estoques
- Está em muitas Farmacias (via Estoque)
```

### Estoque
```php
- Pertence a uma Farmacia
- Pertence a um Produto
- Métodos: repor(), remover()
- Acessors: em_falta, percentual_estoque
```

---

## 🛡️ Validação

Todos os endpoints utilizam **Form Requests** para validação:

```php
// StoreProdutoRequest.php
'nome' => 'required|string|max:255',
'codigo' => 'required|string|unique:produtos|max:100',
'preco' => 'required|numeric|min:0.01',
'categoria_id' => 'required|exists:categorias,id',
```

**Erros de validação (422):**
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": ["O email é obrigatório"],
    "password": ["A senha deve ter no mínimo 6 caracteres"]
  }
}
```

---

## 🗄️ Banco de Dados (PostgreSQL)

### Tabelas Principais

| Tabela | Descrição |
|--------|-----------|
| `users` | Usuários da aplicação |
| `roles` | Papéis/Permissões |
| `farmacias` | Farmácias |
| `localizacoes` | Endereços das farmácias |
| `produtos` | Medicamentos |
| `categorias` | Categorias de produtos |
| `estoques` | Controle de estoque |
| `servicos` | Serviços oferecidos |
| `pesquisas` | Log de pesquisas |

### Indices e Performance

- Full-text search em produtos e farmacias
- Índices em FK (farmacia_id, produto_id, user_id)
- Índice em quantidade para filtros de falta
- Unique constraint em (farmacia_id, produto_id)

---

## 📖 Documentação Swagger

Acesse: `http://localhost:8000/api/documentation`

A documentação é gerada automaticamente com anotações OpenAPI nos controllers:

```php
/**
 * @OA\Get(
 *     path="/api/farmacias",
 *     summary="Listar todas as farmacias",
 *     tags={"Farmacias"}
 * )
 */
public function index() { ... }
```

---

## 🧪 Testes (Seeders)

**Usuários de teste:**

| Email | Senha | Role |
|-------|-------|------|
| admin@farmacia.com | password123 | admin |
| joao@farmacia.com | password123 | gerente |
| maria@farmacia.com | password123 | gerente |

**Dados iniciais:**
- 2 Farmacias
- 5 Produtos
- 9 Registros de Estoque
- 5 Serviços

---

## 🚀 Deployment

### Requisitos Produção

```
- PHP 8.2+
- PostgreSQL 13+
- Redis (cache/sessions)
- Nginx ou Apache
```

### Variáveis Críticas

```env
APP_ENV=production
APP_DEBUG=false
DB_PASSWORD=senhaforte123
JWT_SECRET=chave-secreta-muito-segura-change-me
```

### Build

```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 📦 Dependências Principais

```json
{
  "php": "^8.2",
  "laravel/framework": "^12.0",
  "firebase/php-jwt": "^7.0"
}
```

---

## 🤝 Contribuindo

Padrões de código:

- PSR-12 (PHP Coding Standards)
- Nomes descritivos (português aceitável em comentários)
- Type hints obrigatórios
- Documentação OpenAPI em controllers

---

## 📞 Suporte

Para dúvidas ou problemas, abra uma issue no repositório.

---

## 📄 Licença

MIT License - veja LICENSE.md

---

**Última atualização:** Janeiro 2026
=======
# 🏥 Farmacia API - REST + JWT + PostgreSQL

API REST completa para gerenciamento de farmácias, produtos, estoques e serviços.

## 📋 Stack Tecnológico

- **Framework:** Laravel 12
- **Banco de Dados:** PostgreSQL
- **Autenticação:** JWT (Firebase PHP-JWT)
- **Arquitetura:** API-First + MVC + REST
- **Frontend:** PWA (desacoplado)
- **Documentação:** OpenAPI/Swagger

---

## 🎯 Arquitetura

```
[Frontend PWA]
   ↓ HTTP + JSON + Bearer Token
[API Controllers]
   ↓
[Services / Domain Logic]
   ↓
[Models + Relations]
   ↓
[PostgreSQL Database]
```

### Princípios Aplicados:

✅ **API-REST**: Recursos baseados em HTTP, uso explícito de métodos (GET, POST, PUT, DELETE)
✅ **Stateless**: Cada request é independente (sem sessões, usar JWT)
✅ **JWT**: Token Bearer em cada requisição (Authorization header)
✅ **MVC**: Models (relações), Controllers (orquestra), Views (frontend externo)
✅ **PostgreSQL**: Banco relacional com indices e constraints

---

## 🗂️ Estrutura de Pastas

```
backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/Api/          # Controllers REST
│   │   ├── Middleware/               # JWT Middleware
│   │   └── Requests/                 # Form Requests (validação)
│   ├── Models/                       # Models com relações
│   ├── Support/
│   │   ├── Jwt/                      # JwtService
│   │   └── OpenApi/                  # Documentação Swagger
│   └── Domain/                       # Lógica de negócio
├── routes/
│   └── api.php                       # Rotas API
├── database/
│   ├── migrations/                   # Estrutura do BD
│   └── seeders/                      # Dados de teste
├── config/
│   ├── jwt.php                       # Configuração JWT
│   └── database.php                  # Configuração BD
└── public/
    └── index.php                     # Entry point
```

---

## 🚀 Setup Inicial

### 1. Clonar e Instalar Dependências

```bash
cd backend
composer install
```

### 2. Configurar Variáveis de Ambiente

```bash
cp .env.example .env
php artisan key:generate
```

**Editar `.env`:**
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=farmacia_db
DB_USERNAME=postgres
DB_PASSWORD=sua_senha

JWT_SECRET=sua-chave-secreta-super-segura-12345
```

### 3. Criar Banco de Dados PostgreSQL

```bash
# Com psql
createdb farmacia_db
```

### 4. Executar Migrations

```bash
php artisan migrate --force
```

### 5. Popular com Dados de Teste

```bash
php artisan db:seed
```

### 6. Gerar Documentação Swagger

```bash
# Se usar L5-Swagger
php artisan l5-swagger:generate
```

### 7. Iniciar Servidor

```bash
php artisan serve
```

API disponível em: `http://localhost:8000/api`

---

## 🔐 Autenticação JWT

### Login (Obter Token)

```bash
POST /api/auth/login
Content-Type: application/json

{
  "email": "admin@farmacia.com",
  "password": "password123"
}
```

**Resposta (200):**
```json
{
  "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
  "type": "bearer",
  "expires_in": 3600
}
```

### Usar Token em Requisições

```bash
GET /api/farmacias
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
```

### Renovar Token

```bash
POST /api/auth/refresh
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
```

### Logout

```bash
POST /api/auth/logout
Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
```

---

## 📚 Endpoints Principais

### Farmacias

| Método | Endpoint | Descrição |
|--------|----------|-----------|
| `GET` | `/api/farmacias` | Listar todas as farmacias |
| `POST` | `/api/farmacias` | Criar farmacia |
| `GET` | `/api/farmacias/{id}` | Obter detalhes |
| `PUT` | `/api/farmacias/{id}` | Atualizar |
| `DELETE` | `/api/farmacias/{id}` | Deletar |

### Produtos

| Método | Endpoint | Descrição |
|--------|----------|-----------|
| `GET` | `/api/produtos` | Listar produtos |
| `POST` | `/api/produtos` | Criar produto |
| `GET` | `/api/produtos/{id}` | Detalhes |
| `PUT` | `/api/produtos/{id}` | Atualizar |
| `DELETE` | `/api/produtos/{id}` | Deletar |

### Estoques

| Método | Endpoint | Descrição |
|--------|----------|-----------|
| `GET` | `/api/estoques` | Listar estoques |
| `POST` | `/api/estoques` | Criar registro |
| `PUT` | `/api/estoques/{id}` | Atualizar |
| `POST` | `/api/estoques/{id}/repor` | Repor quantidade |
| `POST` | `/api/estoques/{id}/remover` | Remover quantidade |

### Serviços

| Método | Endpoint | Descrição |
|--------|----------|-----------|
| `GET` | `/api/servicos` | Listar serviços |
| `POST` | `/api/servicos` | Criar serviço |
| `PUT` | `/api/servicos/{id}` | Atualizar |
| `DELETE` | `/api/servicos/{id}` | Deletar |

---

## 📖 Exemplos de Requisições

### Criar Farmacia

```bash
POST /api/farmacias
Authorization: Bearer TOKEN
Content-Type: application/json

{
  "nome": "Farmácia Central",
  "localizacao": "Rua Principal, 123"
}
```

### Criar Produto

```bash
POST /api/produtos
Authorization: Bearer TOKEN
Content-Type: application/json

{
  "nome": "Dipirona 500mg",
  "codigo": "DIP-500-001",
  "preco": 12.50,
  "categoria_id": 1,
  "data_validade": "2025-12-31"
}
```

### Criar Estoque

```bash
POST /api/estoques
Authorization: Bearer TOKEN
Content-Type: application/json

{
  "farmacia_id": 1,
  "produto_id": 1,
  "quantidade": 100,
  "stock_minimo": 10
}
```

### Filtrar Produtos em Falta

```bash
GET /api/produtos?em_falta=true
Authorization: Bearer TOKEN
```

### Repor Estoque

```bash
POST /api/estoques/1/repor
Authorization: Bearer TOKEN
Content-Type: application/json

{
  "quantidade": 50
}
```

---

## 🔒 Modelos e Relações

### User (Usuário)
```php
- Tem muitas Farmacias
- Pertence a uma Role
- Tem muitas Pesquisas
```

### Farmacia
```php
- Pertence a um User
- Tem muitos Estoques
- Tem muitos Produtos (via Estoque)
- Tem muitos Serviços
- Tem muitas Localizações
```

### Produto
```php
- Pertence a uma Categoria
- Tem muitos Estoques
- Está em muitas Farmacias (via Estoque)
```

### Estoque
```php
- Pertence a uma Farmacia
- Pertence a um Produto
- Métodos: repor(), remover()
- Acessors: em_falta, percentual_estoque
```

---

## 🛡️ Validação

Todos os endpoints utilizam **Form Requests** para validação:

```php
// StoreProdutoRequest.php
'nome' => 'required|string|max:255',
'codigo' => 'required|string|unique:produtos|max:100',
'preco' => 'required|numeric|min:0.01',
'categoria_id' => 'required|exists:categorias,id',
```

**Erros de validação (422):**
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": ["O email é obrigatório"],
    "password": ["A senha deve ter no mínimo 6 caracteres"]
  }
}
```

---

## 🗄️ Banco de Dados (PostgreSQL)

### Tabelas Principais

| Tabela | Descrição |
|--------|-----------|
| `users` | Usuários da aplicação |
| `roles` | Papéis/Permissões |
| `farmacias` | Farmácias |
| `localizacoes` | Endereços das farmácias |
| `produtos` | Medicamentos |
| `categorias` | Categorias de produtos |
| `estoques` | Controle de estoque |
| `servicos` | Serviços oferecidos |
| `pesquisas` | Log de pesquisas |

### Indices e Performance

- Full-text search em produtos e farmacias
- Índices em FK (farmacia_id, produto_id, user_id)
- Índice em quantidade para filtros de falta
- Unique constraint em (farmacia_id, produto_id)

---

## 📖 Documentação Swagger

Acesse: `http://localhost:8000/api/documentation`

A documentação é gerada automaticamente com anotações OpenAPI nos controllers:

```php
/**
 * @OA\Get(
 *     path="/api/farmacias",
 *     summary="Listar todas as farmacias",
 *     tags={"Farmacias"}
 * )
 */
public function index() { ... }
```

---

## 🧪 Testes (Seeders)

**Usuários de teste:**

| Email | Senha | Role |
|-------|-------|------|
| admin@farmacia.com | password123 | admin |
| joao@farmacia.com | password123 | gerente |
| maria@farmacia.com | password123 | gerente |

**Dados iniciais:**
- 2 Farmacias
- 5 Produtos
- 9 Registros de Estoque
- 5 Serviços

---

## 🚀 Deployment

### Requisitos Produção

```
- PHP 8.2+
- PostgreSQL 13+
- Redis (cache/sessions)
- Nginx ou Apache
```

### Variáveis Críticas

```env
APP_ENV=production
APP_DEBUG=false
DB_PASSWORD=senhaforte123
JWT_SECRET=chave-secreta-muito-segura-change-me
```

### Build

```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 📦 Dependências Principais

```json
{
  "php": "^8.2",
  "laravel/framework": "^12.0",
  "firebase/php-jwt": "^7.0"
}
```

---

## 🤝 Contribuindo

Padrões de código:

- PSR-12 (PHP Coding Standards)
- Nomes descritivos (português aceitável em comentários)
- Type hints obrigatórios
- Documentação OpenAPI em controllers

---

## 📞 Suporte

Para dúvidas ou problemas, abra uma issue no repositório.

---

## 📄 Licença

MIT License - veja LICENSE.md

---

**Última atualização:** Janeiro 2026
>>>>>>> master
