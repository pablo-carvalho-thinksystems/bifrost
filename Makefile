DOCKER_COMPOSE=docker compose -f docker/docker-compose.yml
PHP_CONTAINER=php-bifrost
MYSQL_CONTAINER=mysql-bifrost
DB_NAME=bifrost-db
DB_TEST_NAME=bifrost_db_testing
DB_ROOT_USER=root
DB_ROOT_PASSWORD=pass123
PHP_EXEC=docker exec -it $(PHP_CONTAINER) php
MYSQL_EXEC=docker exec -it $(MYSQL_CONTAINER) mysql --user=$(DB_ROOT_USER) --password=$(DB_ROOT_PASSWORD)

build: up migrate migrate-test copy-env
	@echo "✅ Ambiente pronto!"

copy-env:
	@echo "📄 Verificando .env..."
	@if [ ! -f .env ]; then cp .env.example .env; echo "✅ .env criado a partir do .env.example"; else echo "✅ .env já existe"; fi


up:
	@echo "🚀 Subindo os containers..."
	$(DOCKER_COMPOSE) up -d --build --force-recreate

down:
	@echo "🛑 Parando os containers..."
	$(DOCKER_COMPOSE) down

restart: down up

migrate:
	@echo "🔄 Rodando migrations..."
	$(PHP_EXEC) artisan migrate --force
	@echo "🌱 Rodando seeds..."
	$(PHP_EXEC) artisan db:seed --force

migrate-test:
	@echo "📦 Criando banco de testes..."
	$(MYSQL_EXEC) -e "CREATE DATABASE IF NOT EXISTS $(DB_TEST_NAME);"