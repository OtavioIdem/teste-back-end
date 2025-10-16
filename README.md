<div align="center">
🚀 Gerenciador de Produtos — Laravel + React + Docker

Aplicação completa de gerenciamento de produtos e categorias, com autenticação via Laravel Sanctum, CRUD completo, importação automática da FakeStore API, e interface moderna em React (Vite + Tailwind).
Tudo pronto para rodar em qualquer ambiente Linux Ubuntu via Docker, sem ajustes manuais.

</div>
🧩 Tecnologias Utilizadas
Camada	Stack
🖥️ Frontend	React 18 • Vite 4 • Tailwind CSS 3 • React Router
⚙️ Backend	Laravel 10 • Sanctum • Horizon • GuzzleHTTP
🗄️ Banco de Dados	MySQL 8
🔁 Filas & Cache	Redis 7
🐳 Infraestrutura	Docker Compose (Laravel Sail base)
⚙️ Como rodar o projeto no Ubuntu

Abaixo está o guia definitivo para clonar e subir o projeto em um ambiente Linux limpo, sem precisar editar arquivos manualmente.

🧾 1️⃣ — Clonar o repositório
git clone https://github.com/SEU_USUARIO/teste-back-end.git
cd teste-back-end

🧱 2️⃣ — Criar arquivo .env

O projeto já inclui um .env.

Nenhuma edição é necessária — o .env já está configurado para funcionar no Docker com MySQL, Redis e Horizon.

🐳 3️⃣ — Subir os containers

Todos os serviços (Laravel, MySQL, Redis e Horizon) serão configurados automaticamente.

docker compose up -d --build


Verifique se os serviços estão rodando:

docker compose ps

⚙️ 4️⃣ — Instalar e configurar o backend

Entre no container do Laravel:

docker compose exec laravel.test bash


Execute os comandos de instalação:

composer install
php artisan key:generate
php artisan migrate --seed
exit


✅ Isso irá:

Instalar dependências PHP

Gerar a APP_KEY

Criar e popular o banco de dados

Preparar o Horizon e o Sanctum

🎨 5️⃣ — Instalar e rodar o frontend (Vite + React + Tailwind)

No host (Ubuntu):

npm install
npm run dev


O Vite cuidará automaticamente da compilação do React e do Tailwind.

🌐 6️⃣ — Acessar o sistema

Aplicação (frontend via Laravel):
👉 http://localhost:8080

API (backend):
👉 http://localhost:8080/api

Painel Horizon (filas):
👉 http://localhost:8080/horizon

🔑 Como usar o sistema
👤 Login

Usuário padrão (criado pelo seeder):

Email: admin@example.com
Senha: password


Para autenticar via API:

POST /api/auth/login
Content-Type: application/json

{
  "email": "admin@example.com",
  "password": "password"
}


Resposta:

{
  "token": "eyJ0eXAiOiJKV1QiLCJh...",
  "user": {
    "id": 1,
    "name": "Admin",
    "email": "admin@example.com"
  }
}


O token JWT deve ser usado como Bearer Token nas requisições seguintes:

Authorization: Bearer <seu_token>

🛍️ Gerenciamento de Produtos
📌 Listar produtos
GET /api/products

Filtros disponíveis:
Filtro	Tipo	Descrição
name	string	Busca por nome
category	string	Busca por categoria
has_image	1 / 0	Filtra produtos com ou sem imagem

Exemplo:

GET /api/products?name=shirt&category=fashion&has_image=1

➕ Criar produto
POST /api/products

{
  "name": "Tênis Nike Air",
  "price": 499.90,
  "description": "Tênis esportivo confortável",
  "category": "Calçados",
  "image": "https://fakestoreapi.com/img/81fPKd-2AYL._AC_SL1500_.jpg"
}

✏️ Atualizar produto
PUT /api/products/{id}

🗑️ Deletar produto
DELETE /api/products/{id}

🧩 Gerenciamento de Categorias
Método	Endpoint	Descrição
GET	/api/categories	Listar categorias
POST	/api/categories	Criar categoria
PUT	/api/categories/{id}	Atualizar categoria
DELETE	/api/categories/{id}	Excluir categoria
🔎 Buscar produto por ID
GET /api/products/{id}


Retorna:

{
  "id": 1,
  "name": "Tênis Nike Air",
  "price": 499.90,
  "description": "Tênis esportivo confortável",
  "categories": ["Calçados"],
  "image_url": "https://fakestoreapi.com/img/81fPKd-2AYL._AC_SL1500_.jpg"
}

🔄 Importar produtos da FakeStore API
⌨️ Via comando Artisan

Dentro do container:

docker compose exec laravel.test php artisan products:import


Isso importa todos os produtos da API https://fakestoreapi.com.

📦 Importar um produto específico
docker compose exec laravel.test php artisan products:import --id=1

⚙️ Importar via endpoint REST

Importar todos:

POST /api/import/products


Importar apenas um (por ID externo):

POST /api/import/products/{externalId}?queued=1

🧠 Painel de Filas — Laravel Horizon

O Horizon é usado para processar jobs da fila, como a importação assíncrona.

Painel: http://localhost:8080/horizon

⚠️ Em produção, o acesso é restrito — no ambiente local está liberado.

🧰 Comandos Úteis
Ação	Comando
Subir containers	docker compose up -d --build
Entrar no container	docker compose exec laravel.test bash
Rodar migrations	php artisan migrate
Limpar cache	php artisan optimize:clear
Ver logs do Laravel	docker compose logs -f laravel.test
Ver logs do Horizon	docker compose logs -f horizon
Reiniciar tudo	docker compose down && docker compose up -d
🧾 Troubleshooting
Problema	Causa	Solução
Redis não sobe	container lento	docker compose restart redis
MySQL demora para responder	inicialização lenta	espere 10s e rode php artisan migrate novamente
Horizon não instala no Windows	falta de pcntl/posix	instale via Docker/Linux
Vite mostra tela padrão	abriu a porta errada	use http://localhost:8080
Erro 404 ao recarregar página React	Laravel sem fallback	garanta Route::view('/{any}', 'app') no routes/web.php
💻 Resumo rápido
Passo	Comando
1️⃣ Clonar	git clone ...
2️⃣ Copiar .env	cp .env.example .env
3️⃣ Subir containers	docker compose up -d --build
4️⃣ Instalar backend	docker compose exec laravel.test bash → composer install && php artisan migrate --seed
5️⃣ Instalar frontend	npm install && npm run dev
6️⃣ Acessar app	http://localhost:8080
