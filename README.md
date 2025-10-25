# Fenixlight.by Docker Infrastructure

## Quick Start
```bash
cp .env.example .env
make ssl-copy-from-prod  # or make ssl-generate-self-signed
make up
```

Access: https://fenixlight.by/

## Structure
```
├── docker/              # Dockerfile
├── config/              # PHP, Apache configs (versioned)
├── nginx/               # Nginx config (versioned)
├── migrations/          # Database migrations (versioned)
│   ├── schema/         # DDL changes
│   └── data/           # DML changes
├── ssl/                # SSL certificates (gitignored)
├── backups/            # Database backups (gitignored)
├── .env                # Environment (gitignored)
└── www/ (external)     # Website files (separate)
```

## Commands
- `make help` - Show all commands
- `make up/down/restart` - Container management
- `make db-backup` - Backup database
- `make migrate` - Run migrations
- `make php-upgrade` - Change PHP version

## Website Files
Website code lives in `../fenixlight/www/` (not in git, too large)
Only infrastructure/config is versioned.
