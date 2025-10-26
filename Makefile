.PHONY: install up down restart logs build rebuild clean ps help

# Load environment
-include .env
export

# Install everything on fresh Ubuntu host
install:
	@echo "Installing Fenixlight..."
	@command -v docker >/dev/null 2>&1 || { \
		echo "Docker not found. Installing..."; \
		curl -fsSL https://get.docker.com -o get-docker.sh; \
		sh get-docker.sh; \
		rm get-docker.sh; \
		systemctl enable docker; \
		systemctl start docker; \
		echo "Docker installed!"; \
	}
	@echo "Checking Docker Compose..."
	@docker compose version >/dev/null 2>&1 || { \
		echo "Docker Compose not found. Installing..."; \
		apt-get update; \
		apt-get install -y docker-compose-plugin; \
	}
	@echo "Setting up environment..."
	@if [ -f .env.encrypted ] && [ -n "$$PASSWORD" ]; then \
		echo "Decrypting .env from .env.encrypted..."; \
		openssl enc -aes-256-cbc -d -pbkdf2 -in .env.encrypted -out .env -k "$$PASSWORD" 2>/dev/null || echo "Decryption failed, using .env.example"; \
	fi
	@test -f .env || cp .env.example .env
	@echo "Creating writable directories..."
	@mkdir -p www/wa-cache www/wa-log www/wa-data/public
	@chmod -R 777 www/wa-cache www/wa-log www/wa-data/public
	@echo "Building and starting containers..."
	$(MAKE) rebuild
	@echo ""
	@echo "✅ Installation complete!"
	@echo "Site running at: https://fenixlight.by/"

up:
	docker compose up -d
	@echo "Fenixlight: https://fenixlight.by/"

down:
	docker compose down

restart:
	docker compose restart

logs:
	docker compose logs -f

logs-web:
	docker compose logs -f web

logs-db:
	docker compose logs -f db

logs-nginx:
	docker compose logs -f nginx

build:
	docker compose build

rebuild:
	docker compose down && docker compose up -d --build

clean:
	docker compose down -v
	@echo "Warning: All data volumes removed!"

ps:
	docker compose ps

# Database operations
db-shell:
	docker exec -it fenixlight-db mariadb -u $(DB_USER) -p$(DB_PASSWORD) $(DB_NAME)

db-backup:
	@mkdir -p backups
	docker exec fenixlight-db mariadb-dump -u $(DB_USER) -p$(DB_PASSWORD) $(DB_NAME) | gzip > backups/db_$(shell date +%Y%m%d_%H%M%S).sql.gz
	@echo "Backup created in backups/"

db-restore:
	@echo "Restoring from $(FILE)"
	gunzip < $(FILE) | docker exec -i fenixlight-db mariadb -u $(DB_USER) -p$(DB_PASSWORD) $(DB_NAME)

db-export-schema:
	@mkdir -p migrations/schema
	docker exec fenixlight-db mariadb-dump -u $(DB_USER) -p$(DB_PASSWORD) --no-data fenix_russia_ru > migrations/schema/schema_$(shell date +%Y%m%d_%H%M%S).sql
	@echo "Schema exported to migrations/schema/"

migrate:
	@echo "Running schema migrations..."
	@for file in migrations/schema/*.sql; do \
		if [ -f "$$file" ] && [ "$$(basename $$file)" != "schema_initial.sql" ]; then \
			echo "Applying $$file..."; \
			docker exec -i fenixlight-db mariadb -u $(DB_USER) -p$(DB_PASSWORD) $(DB_NAME) < $$file; \
		fi \
	done
	@echo "Running data migrations..."
	@for file in migrations/data/*.sql; do \
		if [ -f "$$file" ]; then \
			echo "Applying $$file..."; \
			docker exec -i fenixlight-db mariadb -u $(DB_USER) -p$(DB_PASSWORD) $(DB_NAME) < $$file; \
		fi \
	done

migrate-create:
	@read -p "Migration description: " desc; \
	filename="migrations/schema/$(shell date +%Y%m%d_%H%M%S)_$$desc.sql"; \
	echo "-- Migration: $$desc" > $$filename; \
	echo "-- Date: $(shell date +%Y-%m-%d)" >> $$filename; \
	echo "" >> $$filename; \
	echo "Creating $$filename"

# SSL operations
ssl-generate-self-signed:
	@mkdir -p ssl
	openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
		-keyout ssl/fenixlight.key \
		-out ssl/fenixlight.pem \
		-subj "/C=BY/ST=Minsk/L=Minsk/O=Fenixlight/CN=fenixlight.by"
	openssl dhparam -out ssl/dhparam.pem 2048
	@echo "Self-signed certificates generated in ssl/"

ssl-copy-from-prod:
	@mkdir -p ssl
	scp instr:/etc/ssl/fenixlight/fenixlight.key ssl/
	scp instr:/etc/ssl/fenixlight/fenixlight.pem ssl/
	scp instr:/etc/ssl/certs/dhparam.pem ssl/
	@echo "SSL certificates copied from production"

# PHP version management
php-upgrade:
	@read -p "New PHP version (current: $(PHP_VERSION)): " version; \
	sed -i.bak "s/PHP_VERSION=.*/PHP_VERSION=$$version/" .env; \
	sed -i.bak "s/FROM php:.*/FROM php:$$version-apache/" docker/Dockerfile; \
	echo "Updated to PHP $$version. Run 'make rebuild' to apply"

# Container access
web-shell:
	docker exec -it fenixlight-web bash

nginx-shell:
	docker exec -it fenixlight-nginx sh

# Help
help:
	@echo "Fenixlight Development Environment"
	@echo ""
	@echo "Setup:"
	@echo "  make install         - Install Docker and setup everything (Ubuntu)"
	@echo ""
	@echo "Container Management:"
	@echo "  make up              - Start all containers"
	@echo "  make down            - Stop all containers"
	@echo "  make restart         - Restart containers"
	@echo "  make rebuild         - Rebuild and restart"
	@echo "  make logs            - View all logs"
	@echo "  make ps              - Show container status"
	@echo ""
	@echo "Database:"
	@echo "  make db-shell        - Access database CLI"
	@echo "  make db-backup       - Create database backup"
	@echo "  make db-export-schema - Export schema only"
	@echo "  make migrate         - Run migrations"
	@echo "  make migrate-create  - Create new migration"
	@echo ""
	@echo "SSL:"
	@echo "  make ssl-generate-self-signed - Generate dev certificates"
	@echo "  make ssl-copy-from-prod       - Copy from production"
	@echo ""
	@echo "PHP:"
	@echo "  make php-upgrade     - Upgrade PHP version"
	@echo ""
	@echo "Environment:"
	@echo "  PASSWORD=pass make env-encrypt  - Encrypt .env for git"
	@echo "  PASSWORD=pass make env-decrypt  - Decrypt .env.encrypted"

# Environment encryption (requires PASSWORD env var)
env-encrypt:
	@test -n "$(PASSWORD)" || (echo "Error: PASSWORD not set. Use: PASSWORD=yourpass make env-encrypt" && exit 1)
	@echo "Encrypting .env file..."
	@openssl enc -aes-256-cbc -salt -pbkdf2 -in .env -out .env.encrypted -k "$(PASSWORD)"
	@echo "✅ .env encrypted to .env.encrypted"
	@echo "You can now commit .env.encrypted to git"

env-decrypt:
	@test -n "$(PASSWORD)" || (echo "Error: PASSWORD not set. Use: PASSWORD=yourpass make env-decrypt" && exit 1)
	@echo "Decrypting .env.encrypted..."
	@openssl enc -aes-256-cbc -d -pbkdf2 -in .env.encrypted -out .env -k "$(PASSWORD)"
	@echo "✅ .env decrypted from .env.encrypted"
