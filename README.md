<div align="center">
🚀 Teste Prático — Laravel 10.10 + React (Vite + Tailwind)

Gerenciamento de biblioteca com Docker, API Laravel, Frontend React,
importação de produtos via API externa, e filas com Redis + Horizon.

</div>
🧩 Tecnologias Principais
Camada	Stack
🖥️ Frontend	React 18 • Vite 4 • Tailwind CSS 3 • React Router DOM 6
⚙️ Backend	Laravel 10.10 • Sanctum • Horizon • GuzzleHTTP
🗄️ Banco	MySQL 8
🔁 Filas & Cache	Redis 7
🐳 Infraestrutura	Docker Compose (Laravel Sail)
🧱 Arquitetura
📦 projeto/
├── app/                    → Controllers, Services, Jobs, Commands
├── resources/
│   ├── views/app.blade.php → Shell do React SPA
│   ├── css/app.css         → Estilos Tailwind
│   └── js/                 → Frontend React + Vite
│       ├── app.jsx
│       ├── bootstrap.js
│       └── pages/
│           ├── Login.jsx
│           ├── Products.jsx
│           └── ProductForm.jsx
├── routes/
│   ├── api.php             → Rotas da API (protegidas por Sanctum)
│   └── web.php             → Rotas do SPA (deep link)
├── compose.yaml            → Docker Compose (Laravel + MySQL + Redis + Horizon)
├── .env                    → Variáveis de ambiente
└── README.md


⚙️ Configuração de Ambiente
🧾 .env principal (renomear o arquivo .env.config)
APP_NAME=Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8080
APP_PORT=8080

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=password

QUEUE_CONNECTION=redis
REDIS_HOST=redis
REDIS_CLIENT=phpredis
REDIS_PASSWORD=null
REDIS_PORT=6379

# FRONTEND (Vite)
VITE_API_URL=http://localhost:8080/api
VITE_PORT=5173

# Sessões SPA (opcional)
SANCTUM_STATEFUL_DOMAINS=localhost:5173,localhost:8080
SESSION_DOMAIN=localhost

# Configuração do Docker (Sail)
WWWUSER=1000
WWWGROUP=1000
MYSQL_EXTRA_OPTIONS=

🐳 Subindo o Projeto com Docker
🏗️ 1️⃣ — Build & subir containers
docker compose up -d --build
docker compose ps

🧰 2️⃣ — Entrar no container laravel.test
docker compose exec laravel.test bash

⚙️ 3️⃣ — Configurar o backend
composer install
php artisan key:generate
php artisan migrate

# Dependências principais
composer require laravel/sanctum:^3.3 laravel/horizon:^5.20 guzzlehttp/guzzle:^7.9
php artisan horizon:install


🔐 Permissões (se necessário)

chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
exit


✅ Reinicie os serviços

docker compose up -d

🎨 Frontend (React + Vite + Tailwind)

Na raiz do projeto:

npm install
npm install react-router-dom
npm run dev


🔗 Acesse o app em: http://localhost:8080

(não use a porta 5173 diretamente — o Laravel injeta os assets do Vite)

🌐 URLs Importantes
Serviço	URL
🖥️ Aplicação (SPA via Laravel)	http://localhost:8080

⚙️ API	http://localhost:8080/api

🧠 Horizon (filas)	http://localhost:8080/horizon

⚡ Vite (dev server)	http://localhost:5173
 (somente em modo dev)
🔐 Autenticação (Sanctum)

Login

POST /api/auth/login
{
  "email": "admin@example.com",
  "password": "password"
}


Logout

POST /api/auth/logout


Editar Perfil

PUT /api/auth/profile
{ "name": "Novo Nome", "phone": "11999999999" }

🛍️ CRUD de Produtos e Categorias
Método	Endpoint	Descrição
GET	/api/products	Lista de produtos (filtros: name, category, has_image)
GET	/api/products/{id}	Detalhes de produto
POST	/api/products	Criação
PUT	/api/products/{id}	Atualização
DELETE	/api/products/{id}	Exclusão
GET	/api/categories	Lista categorias
POST	/api/categories	Criar categoria

Payload:

{
  "name": "product name",
  "price": 109.95,
  "description": "Descrição do produto...",
  "category": "test",
  "image": "https://fakestoreapi.com/img/81fPKd-2AYL._AC_SL1500_.jpg"
}

🔄 Importação de Produtos (FakeStore API)
📦 Via Artisan
# Importar todos os produtos
docker compose exec laravel.test php artisan products:import

# Importar produto específico (id externo)
docker compose exec laravel.test php artisan products:import --id=1

# Enfileirar importação (fila/Horizon)
docker compose exec laravel.test php artisan products:import --id=1 --queued

⚙️ Via API REST
POST /api/import/products
POST /api/import/products/{externalId}?queued=1

🧠 Horizon (Filas Redis)

Processa jobs (importações, emails, etc.)

Painel de monitoramento: /horizon

Rodando em container dedicado: horizon

Restringir acesso (produção):
routes/horizon.php

Horizon::auth(fn ($request) => app()->environment('local'));

🧾 Comandos Úteis
# status dos containers
docker compose ps

# logs
docker compose logs -f laravel.test
docker compose logs -f mysql
docker compose logs -f redis
docker compose logs -f horizon

# shell no container
docker compose exec laravel.test bash

# caches Laravel
docker compose exec laravel.test php artisan optimize:clear
docker compose exec laravel.test php artisan config:cache
docker compose exec laravel.test php artisan route:cache

🧰 Troubleshooting (erros comuns)
Problema	Causa	Solução
🧩 Horizon não instala no Windows	Falta pcntl/posix	Instale dentro do container
🔄 Redis “Starting”	Cache preso	docker compose restart redis
🐬 MySQL não conecta	.env incorreto	DB_HOST=mysql, user sail, senha password
🧱 Tela “Laravel + Vite”	Abriu porta errada	Use http://localhost:8080
🚫 404 ao atualizar rota React	Laravel não mapeou view	Garanta Route::view('/{any}', 'app')
⚠️ Permissões storage/cache	Permissões no host	chmod -R 775 storage bootstrap/cache
✅ Checklist Final

 Containers sobem (laravel.test, mysql, redis, horizon)

 .env configurado (APP_URL, DB_*, VITE_API_URL)

 composer install + php artisan key:generate

 php artisan migrate

 npm install + npm run dev

 App acessível em http://localhost:8080

 CRUD e importador funcionando

 (Opcional) Filas/Horizon ativos

☁️ Deploy (produção Ubuntu)
docker compose up -d --build

docker compose exec laravel.test bash -lc '
composer install --no-dev --optimize-autoloader &&
php artisan key:generate &&
php artisan migrate --force &&
php artisan config:cache &&
php artisan route:cache &&
php artisan view:cache
'

docker compose restart horizon


Para compilar o front:

npm run build


O Laravel servirá os assets via @vite(manifest.json) automaticamente.

<div align="center">

🧡 Feito com Laravel, React e muito café ☕
💬 Qualquer dúvida, verifique os logs:

docker compose logs -f laravel.test

</div>
