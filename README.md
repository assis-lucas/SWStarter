# SWStarter

A full-stack starter template with Laravel backend and React frontend, fully containerized with Docker.

## 🚀 Quick Start

**Prerequisites:** Docker and Docker Compose

### Automated Setup (Recommended)

```bash
chmod +x ./sw && ./sw setup
```

This will:
- Copy `.env.example` to `.env`
- Build and start containers
- Install dependencies
- Generate application key
- Run migrations with seeders

### Manual Setup

```bash
# 1. Copy environment file
cp backend/.env.example backend/.env

# 2. Build and start containers
docker compose -f docker-compose.dev.yaml build
docker compose -f docker-compose.dev.yaml up -d

# 3. Install dependencies and setup
docker compose -f docker-compose.dev.yaml exec backend composer install
docker compose -f docker-compose.dev.yaml exec backend php artisan key:generate
docker compose -f docker-compose.dev.yaml exec backend php artisan migrate --seed
docker compose -f docker-compose.dev.yaml exec backend php artisan sync:swapi
```

### Access the Application

- **Frontend**: http://localhost:3000
- **Backend API**: http://localhost:8000
- **Stats**: http://localhost:3000/stats – (endpoint http://localhost:8000/api/stats)
- **Database**: localhost:3306 (user: `laravel`, password: `password`)

## 🛠️ CLI Tool

Use the `sw` command for all operations:

```bash
./sw dev              # Start development mode (with hot reload)
./sw up               # Start production mode
./sw stop             # Stop all containers

./sw artisan <cmd>    # Run Laravel commands
./sw composer <cmd>   # Run Composer commands
./sw npm <cmd>        # Run npm commands

./sw migrate          # Run migrations
./sw sql              # Open MySQL CLI
./sw bash backend     # Access backend container

./sw help             # Show all commands
```

## 📦 Tech Stack

- **Backend**: Laravel 12, PHP 8.4
- **Frontend**: React 19, Vite
- **Database**: MySQL 8.0
- **Caching**: Redis
- **Container**: Docker with Alpine Linux