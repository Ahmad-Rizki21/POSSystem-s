# Docker Deployment for POS System

This guide explains how to deploy the POS System using Docker containers.

## Prerequisites

- Docker Engine
- Docker Compose

## Deployment Steps

### 1. Clone or copy the project

```bash
git clone <repository-url>
cd pos-system
```

### 2. Build and run the containers

```bash
docker-compose up -d
```

This will:
- Build the PHP-FPM application container
- Start MySQL database container
- Start Nginx web server container

### 3. Run database migrations

After the containers are up, run migrations:

```bash
docker-compose exec app php artisan migrate --force
```

### 4. Seed the database (optional)

If you need to seed the database with initial data:

```bash
docker-compose exec app php artisan db:seed --force
```

## Services

- Web Application: http://localhost (Nginx on port 80)
- Database: MySQL on port 3306 (internal to containers)
- PHP-FPM: on port 9000 (internal to containers)

## Configuration

The Docker setup includes:

- PHP 8.3 with required extensions
- MySQL 8.0 with utf8mb4 support
- Nginx with optimized configuration for Laravel
- Supervisor for queue workers
- Production-optimized Laravel settings

## Environment Variables

The application uses these environment variables (set in docker-compose.yml):

- `APP_ENV=production`
- `APP_DEBUG=false`
- `DB_HOST=pos-system-db`
- `DB_DATABASE=pos_system`
- `DB_USERNAME=pos_user`
- `DB_PASSWORD=pos_password`

## Useful Commands

```bash
# View logs
docker-compose logs -f

# Execute commands in app container
docker-compose exec app php artisan <command>

# Stop containers
docker-compose down

# Stop and remove containers, networks, and volumes
docker-compose down -v

# Build containers again
docker-compose build

# Restart services
docker-compose restart
```

## Production Notes

- The application is configured for production mode
- Assets are built during image creation
- Configuration is cached for performance
- Database migrations should be run manually after deployment
- The database volume is persisted across container restarts