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
	@if [ -n "$$PASSWORD" ] && ([ -f .env.encrypted ] || [ -f sa.json.encrypted ]); then \
		echo "Decrypting sensitive files..."; \
		$(MAKE) decrypt PASSWORD="$$PASSWORD" 2>/dev/null || echo "Decryption failed, check PASSWORD"; \
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

backup-shell:
	docker exec -it fenixlight-backup bash

# GCP Cloud Storage operations
gcs-list-backups:
	@test -n "$(GCS_BUCKET)" || (echo "Error: GCS_BUCKET not set in .env" && exit 1)
	gsutil ls "gs://$(GCS_BUCKET)/fenixlight/"

gcs-download-backup:
	@test -n "$(GCS_BUCKET)" || (echo "Error: GCS_BUCKET not set in .env" && exit 1)
	@test -n "$(BACKUP_DATE)" || (echo "Error: BACKUP_DATE not set. Use: make gcs-download-backup BACKUP_DATE=20241026_020000" && exit 1)
	@mkdir -p downloads
	gsutil -m rsync -r "gs://$(GCS_BUCKET)/fenixlight/$(BACKUP_DATE)/" downloads/$(BACKUP_DATE)/
	@echo "Backup downloaded to downloads/$(BACKUP_DATE)/"

gcs-cleanup-old:
	@test -n "$(GCS_BUCKET)" || (echo "Error: GCS_BUCKET not set in .env" && exit 1)
	@echo "Cleaning up GCS backups older than 30 days..."
	@CUTOFF_DATE=$$(date -d '30 days ago' +%Y%m%d 2>/dev/null || date -v-30d +%Y%m%d); \
	gsutil ls "gs://$(GCS_BUCKET)/fenixlight/" | grep -E '/[0-9]{8}_[0-9]{6}/$$' | while read backup_path; do \
		backup_date=$$(basename "$$backup_path" | cut -d'_' -f1); \
		if [ "$$backup_date" \< "$$CUTOFF_DATE" ]; then \
			echo "Removing old backup: $$backup_path"; \
			gsutil -m rm -r "$$backup_path"; \
		fi; \
	done

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
	@echo "GCP Cloud Storage:"
	@echo "  make gcs-list-backups           - List all GCS backups"
	@echo "  make gcs-download-backup BACKUP_DATE=... - Download specific backup"
	@echo "  make gcs-cleanup-old            - Remove backups older than 30 days"
	@echo ""
	@echo "SSL:"
	@echo "  make ssl-generate-self-signed - Generate dev certificates"
	@echo "  make ssl-copy-from-prod       - Copy from production"
	@echo ""
	@echo "PHP:"
	@echo "  make php-upgrade     - Upgrade PHP version"
	@echo ""
	@echo "Security & Encryption:"
	@echo "  PASSWORD=pass make encrypt        - Encrypt all sensitive files"
	@echo "  PASSWORD=pass make decrypt        - Decrypt all sensitive files"

# Security & Encryption (requires PASSWORD env var)
encrypt:
	@test -n "$(PASSWORD)" || (echo "Error: PASSWORD not set. Use: PASSWORD=yourpass make encrypt" && exit 1)
	@echo "Encrypting sensitive files..."
	@if [ -f .env ]; then \
		echo "Encrypting .env..."; \
		openssl enc -aes-256-cbc -salt -pbkdf2 -in .env -out .env.encrypted -k "$(PASSWORD)"; \
		echo "✅ .env encrypted"; \
	fi
	@if [ -f sa.json ]; then \
		echo "Encrypting sa.json..."; \
		openssl enc -aes-256-cbc -salt -pbkdf2 -in sa.json -out sa.json.encrypted -k "$(PASSWORD)"; \
		echo "✅ sa.json encrypted"; \
	fi
	@echo "✅ All sensitive files encrypted and ready for git commit"

decrypt:
	@test -n "$(PASSWORD)" || (echo "Error: PASSWORD not set. Use: PASSWORD=yourpass make decrypt" && exit 1)
	@echo "Decrypting sensitive files..."
	@if [ -f .env.encrypted ]; then \
		echo "Decrypting .env..."; \
		openssl enc -aes-256-cbc -d -pbkdf2 -in .env.encrypted -out .env -k "$(PASSWORD)"; \
		echo "✅ .env decrypted"; \
	fi
	@if [ -f sa.json.encrypted ]; then \
		echo "Decrypting sa.json..."; \
		openssl enc -aes-256-cbc -d -pbkdf2 -in sa.json.encrypted -out sa.json -k "$(PASSWORD)"; \
		echo "✅ sa.json decrypted"; \
	fi
	@echo "✅ All sensitive files decrypted"
