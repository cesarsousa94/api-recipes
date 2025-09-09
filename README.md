# RecipesAPI

API (Laravel 12) com:
Autenticação (Sanctum/Bearer)
CRUD de Receitas (criar, listar/pesquisar, editar, excluir, imprimir PDF)
Swagger em /api/documentation
Testes (Feature)
Docker via Laravel Sail

Frontend (Vue 3 + Vite + Tailwind):
Páginas: Login, Cadastro, Lista de Receitas, Nova/Editar, Detalhe/Impressão
Integração com API via Axios
Testes E2E com Cypress

1) Pré-requisitos

Docker Desktop
Node 20+

2) Repositórios (separados, independentes)

* api-recipes/ → Laravel 12
* web-recipes/ → Vue 3 + Tailwind

3) API (Laravel + Sail)
   3.1 Clonar e preparar
   git clone git@github.com:cesarsousa94/api-recipes.git
   cd api-recipes

# instala dependências PHP via Sail (sem PHP local)
cp .env.example .env
cp .env.example .env.testing # para testes
./vendor/bin/sail up -d
./vendor/bin/sail composer install
./vendor/bin/sail artisan key:generate

3.2 Configuração do .env (exemplo)
APP_NAME="Recipes API"
APP_ENV=local
APP_KEY= ###
APP_URL=http://localhost

# DB via Sail
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=recipes
DB_USERNAME=sail
DB_PASSWORD=password

# CORS / Frontend
FRONTEND_URL=http://localhost:5173
SANCTUM_STATEFUL_DOMAINS=localhost:5173
SESSION_DOMAIN=localhost

# Swagger
L5_SWAGGER_GENERATE_ALWAYS=true

3.3 Swagger
./vendor/bin/sail artisan l5-swagger:generate
# Doc em: http://localhost/api/documentation

3.4 Rodar local
./vendor/bin/sail up -d
# API responderá em http://localhost

3.5 Testes
./vendor/bin/sail artisan test

4) Frontend (Vue + Tailwind)
   4.1 Clonar e preparar
   git clone git@seu-git.com:voce/web-recipes.git
   cd web-recipes
   npm i

4.1 Config de ambiente

VITE_API_BASE_URL=http://localhost/api

4.3 Rodar local
npm run dev
# App: http://localhost:5173

Abra o front (/register), crie um usuário

Faça login

Crie uma receita → veja na listagem

Edite/Exclua

Abra “Imprimir PDF” → link aponta para a rota /recipes/{uuid}/print

5) Integração entre Front e API

Base URL do Axios (arquivo src/api/http.js):

Autenticação:

API retorna { token, user } no login

Front salva token no localStorage e envia Authorization: Bearer <token>

