#!/bin/bash

# 🚀 FARMACIA API - QUICK START SCRIPT

echo "╔════════════════════════════════════════╗"
echo "║   FARMACIA API - QUICK START SETUP    ║"
echo "╚════════════════════════════════════════╝"
echo ""

# 1. Instalar dependências
echo "📦 [1/6] Instalando dependências..."
composer install --quiet

# 2. Gerar chaves
echo "🔑 [2/6] Gerando chaves de aplicação..."
php artisan key:generate --force --quiet
php artisan jwt:secret --force 2>/dev/null || echo "JWT_SECRET=$(php -r 'echo bin2hex(random_bytes(32));')" >> .env

# 3. Criar banco de dados (PostgreSQL)
echo "🗄️  [3/6] Preparando banco de dados..."
read -p "Deseja criar o banco 'farmacia_db'? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    createdb farmacia_db 2>/dev/null || echo "⚠️  Banco pode já existir"
fi

# 4. Executar migrations
echo "🔄 [4/6] Executando migrations..."
php artisan migrate --force --quiet

# 5. Popular com dados
echo "🌱 [5/6] Populando com dados de teste..."
php artisan db:seed --force --quiet

# 6. Instruções finais
echo "✅ [6/6] Setup concluído!"
echo ""
echo "════════════════════════════════════════"
echo "🎉 API FARMÁCIA PRONTA!"
echo "════════════════════════════════════════"
echo ""
echo "📝 PRÓXIMOS PASSOS:"
echo ""
echo "1️⃣  Inicie o servidor:"
echo "   php artisan serve"
echo ""
echo "2️⃣  Faça login para obter token:"
echo "   POST http://localhost:8000/api/auth/login"
echo "   {\"email\": \"admin@farmacia.com\", \"password\": \"password123\"}"
echo ""
echo "3️⃣  Use o token em requisições:"
echo "   GET http://localhost:8000/api/farmacias"
echo "   Header: Authorization: Bearer {token}"
echo ""
echo "4️⃣  Acesse a documentação:"
echo "   GET http://localhost:8000/api/documentation"
echo ""
echo "👤 USUÁRIOS DE TESTE:"
echo "   • admin@farmacia.com (admin)"
echo "   • joao@farmacia.com (gerente)"
echo "   • maria@farmacia.com (gerente)"
echo "   Senha: password123"
echo ""
echo "📚 DOCUMENTAÇÃO:"
echo "   • API_DOCUMENTATION.md - Guia completo"
echo "   • IMPLEMENTATION_CHECKLIST.md - O que foi implementado"
echo "   • Farmacia_API.postman_collection.json - Testes Postman"
echo ""
echo "════════════════════════════════════════"
