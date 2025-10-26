# Fenixlight.by

E-commerce website for Fenix flashlights in Belarus. Built on Webasyst framework.

## Tech Stack

- **PHP**: 7.4.33
- **Database**: MariaDB 11.4
- **Web Server**: Apache 2.4 + Nginx (reverse proxy)
- **SSL**: HTTPS enabled
- **Cache**: File-based + OPcache

## Quick Start

### Fresh Ubuntu Server

```bash
git clone <repo-url> fenixlight
cd fenixlight
make install
```

### Local Development

```bash
git clone <repo-url> fenixlight
cd fenixlight
cp .env.example .env
make ssl-generate-self-signed  # or make ssl-copy-from-prod
make up
```

Access: https://fenixlight.by/

## Commands

```bash
make install    # Install Docker + build + start (Ubuntu only)
make up         # Start containers
make down       # Stop containers
make restart    # Restart containers
make rebuild    # Rebuild images and restart
make logs       # View all logs
make help       # Show all commands
```

## Database

**Auto-configured on first run:**
- Database: `fenix_russia_ru`
- User: `fenixrussiaru`
- Password: `7P1a4N2o`

**Snapshot:** `migrations/init-database.sql.gz` (auto-imported)

```bash
make db-backup        # Create backup
make db-shell         # Access database CLI
make db-export-schema # Export schema only
```

## SSL Certificates

Development: `make ssl-generate-self-signed`  
Production: Add real certificates to `ssl/` folder

## Migrations

- `migrations/schema/` - Database schema changes
- `migrations/data/` - Data migrations

```bash
make migrate        # Run all migrations
make migrate-create # Create new migration
```

## Admin

Admin panel: https://fenixlight.by/webasyst/

## Structure

```
├── www/              # Website code (Webasyst)
├── docker/           # Dockerfile
├── config/           # PHP, Apache configs
├── nginx/            # Nginx config
├── ssl/              # SSL certificates (gitignored)
├── migrations/       # Database dumps & migrations
└── backups/          # Database backups (gitignored)
```

## Automated Backups

### S3 Configuration

Add to `.env`:
```bash
AWS_ACCESS_KEY_ID=your_key
AWS_SECRET_ACCESS_KEY=your_secret
AWS_REGION=us-east-1
S3_BUCKET=your-bucket-name
```

### Backup Schedule
- **Daily at 02:00 UTC**
- **First run**: Full backup (database + www + configs + ssl)
- **Subsequent**: Incremental (only changed files)
- **Local retention**: 7 days
- **S3 retention**: 30 days

### Manual Backup
```bash
make db-backup  # Database only
docker exec fenixlight-backup /backup-entrypoint.sh  # Trigger backup now
```

### Backup Contents
- Database dump (gzipped)
- Website files (www/)
- Configurations (config/, nginx/)
- SSL certificates

## Environment Encryption

The `.env` file contains secrets (DB passwords, AWS keys) and is encrypted for git.

### Encrypt for commit
```bash
PASSWORD=your-secret-password make env-encrypt
git add .env.encrypted
git commit -m "Update environment"
```

### Decrypt on server
```bash
PASSWORD=your-secret-password make env-decrypt
# or auto-decrypt during install:
PASSWORD=your-secret-password make install
```

**Files:**
- `.env` - Actual config (gitignored)
- `.env.encrypted` - Encrypted version (committed to git)
- `.env.example` - Template without secrets (committed to git)
