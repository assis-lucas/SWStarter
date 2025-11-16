#!/bin/bash

set -e

# Get the actual script location (follow symlinks)
SCRIPT_PATH="$(readlink -f "${BASH_SOURCE[0]}")"
PROJECT_ROOT="$(cd "$(dirname "$SCRIPT_PATH")" && pwd)"
cd "$PROJECT_ROOT"

NORMAL_COMPOSE="docker-compose.yaml"
DEV_COMPOSE="docker-compose.dev.yaml"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

function print_success() {
    echo -e "${GREEN}✓${NC} $1"
}

function print_error() {
    echo -e "${RED}✗${NC} $1"
}

function print_info() {
    echo -e "${YELLOW}ℹ${NC} $1"
}

function show_help() {
    cat << EOF
SWStarter CLI Tool

Usage: sw <command> [options]

Commands:
  setup              Setup the application (copy .env, install deps, build containers)
  dev                Run app in development mode with hot reload
  up                 Run app in production mode
  stop               Stop all running containers
  
  artisan <args>     Run Laravel artisan commands
  composer <args>    Run composer commands
  npm <args>         Run npm commands
  
  sql                Open MySQL CLI
  bash <container>   Open bash in specified container (backend|frontend|mysql)
  
  migrate            Run database migrations
  seed               Run database seeders
  migrate:fresh      Fresh migration with seeders

Examples:
  sw setup
  sw dev
  sw artisan make:controller UserController
  sw composer require package/name
  sw npm install
  sw bash backend
  sw migrate

EOF
}

function setup_app() {
    print_info "Setting up SWStarter application..."
    
    # Copy .env if it doesn't exist
    if [ ! -f backend/.env ]; then
        print_info "Copying .env.example to .env..."
        cp backend/.env.example backend/.env
        print_success ".env file created"
    else
        print_info ".env file already exists, skipping..."
    fi
    
    # Build containers in dev mode
    print_info "Building containers in dev mode..."
    docker compose -f $DEV_COMPOSE build
    print_success "Containers built"
    
    # Start containers
    print_info "Starting containers..."
    docker compose -f $DEV_COMPOSE up -d
    print_success "Containers started"
    
    # Wait for MySQL to be ready
    print_info "Waiting for MySQL to be ready..."
    sleep 5
    
    # Install composer dependencies
    print_info "Installing composer dependencies..."
    docker compose -f $DEV_COMPOSE exec backend composer install
    print_success "Composer dependencies installed"
    
    # Generate app key
    print_info "Generating application key..."
    docker compose -f $DEV_COMPOSE exec backend php artisan key:generate
    print_success "Application key generated"
    
    # Run migrations with seeders
    print_info "Running migrations with seeders..."
    docker compose -f $DEV_COMPOSE exec backend php artisan migrate --seed
    print_success "Database migrated and seeded"

    print_info "Running SWAPI synchronization..."
    docker compose -f $DEV_COMPOSE exec backend php artisan sync-swapi
    print_success "SWAPI synchronization completed"
    
    print_success "Setup complete!"
    print_info "Backend: http://localhost:8000"
    print_info "Frontend: http://localhost:3000"
}

function dev_mode() {
    print_info "Starting development mode with hot reload..."
    
    # Stop normal mode if running
    if docker compose -f $NORMAL_COMPOSE ps 2>/dev/null | grep -qE "Up|running"; then
        print_info "Stopping production mode..."
        docker compose -f $NORMAL_COMPOSE down
    fi
    
    # Start dev mode
    docker compose -f $DEV_COMPOSE up -d
    print_success "Development mode started"
    print_info "Backend: http://localhost:8000"
    print_info "Frontend: http://localhost:3000 (with hot reload)"
}

function up_mode() {
    print_info "Starting production mode..."
    
    # Stop dev mode if running
    if docker compose -f $DEV_COMPOSE ps 2>/dev/null | grep -qE "Up|running"; then
        print_info "Stopping development mode..."
        docker compose -f $DEV_COMPOSE down
    fi
    
    # Start normal mode
    docker compose -f $NORMAL_COMPOSE up -d
    print_success "Production mode started"
    print_info "Backend: http://localhost:8000"
    print_info "Frontend: http://localhost:3000"
}

function stop_all() {
    print_info "Stopping all containers..."
    
    docker compose -f $DEV_COMPOSE down 2>/dev/null || true
    docker compose -f $NORMAL_COMPOSE down 2>/dev/null || true
    
    print_success "All containers stopped"
}

function run_artisan() {
    # Check which compose file is running
    if docker compose -f $DEV_COMPOSE ps backend | grep -qE "Up|running"; then
        docker compose -f $DEV_COMPOSE exec -u www-data backend php artisan "$@"
    elif docker compose -f $NORMAL_COMPOSE ps backend | grep -qE "Up|running"; then
        docker compose -f $NORMAL_COMPOSE exec -u www-data backend php artisan "$@"
    else
        print_error "No backend container running. Start with 'sw dev' or 'sw up'"
        exit 1
    fi
}

function run_composer() {
    # Check which compose file is running
    if docker compose -f $DEV_COMPOSE ps backend | grep -qE "Up|running"; then
        docker compose -f $DEV_COMPOSE exec backend composer "$@"
    elif docker compose -f $NORMAL_COMPOSE ps backend | grep -qE "Up|running"; then
        docker compose -f $NORMAL_COMPOSE exec backend composer "$@"
    else
        print_error "No backend container running. Start with 'sw dev' or 'sw up'"
        exit 1
    fi
}

function run_pint() {
    if docker compose -f $DEV_COMPOSE ps backend | grep -qE "Up|running"; then
        docker compose -f $DEV_COMPOSE exec backend ./vendor/bin/pint
    elif docker compose -f $NORMAL_COMPOSE ps backend | grep -qE "Up|running"; then
        docker compose -f $NORMAL_COMPOSE exec backend ./vendor/bin/pint
    else
        print_error "No backend container running. Start with 'sw dev' or 'sw up'"
        exit 1
    fi
}

function run_npm() {
    # Check which compose file is running
    if docker compose -f $DEV_COMPOSE ps frontend-dev | grep -qE "Up|running"; then
        docker compose -f $DEV_COMPOSE exec frontend-dev npm "$@"
    elif docker compose -f $NORMAL_COMPOSE ps frontend | grep -qE "Up|running"; then
        docker compose -f $NORMAL_COMPOSE exec frontend npm "$@"
    else
        print_error "No frontend container running. Start with 'sw dev' or 'sw up'"
        exit 1
    fi
}

function open_sql() {
    # Get MySQL credentials from .env
    if [ -f backend/.env ]; then
        DB_DATABASE=$(grep DB_DATABASE backend/.env | cut -d '=' -f2)
        DB_USERNAME=$(grep DB_USERNAME backend/.env | cut -d '=' -f2)
        DB_PASSWORD=$(grep DB_PASSWORD backend/.env | cut -d '=' -f2)
    else
        DB_DATABASE="laravel"
        DB_USERNAME="laravel"
        DB_PASSWORD="password"
    fi
    
    # Check which compose file is running
    if docker compose -f $DEV_COMPOSE ps mysql 2>/dev/null | grep -qE "Up|running"; then
        docker compose -f $DEV_COMPOSE exec mysql mysql -u"$DB_USERNAME" -p"$DB_PASSWORD" "$DB_DATABASE"
    elif docker compose -f $NORMAL_COMPOSE ps mysql 2>/dev/null | grep -qE "Up|running"; then
        docker compose -f $NORMAL_COMPOSE exec mysql mysql -u"$DB_USERNAME" -p"$DB_PASSWORD" "$DB_DATABASE"
    else
        print_error "No MySQL container running. Start with 'sw dev' or 'sw up'"
        exit 1
    fi
}

function open_bash() {
    local container=$1
    
    if [ -z "$container" ]; then
        print_error "Please specify a container: backend, frontend, or mysql"
        exit 1
    fi
    
    # Map container names
    case $container in
        backend)
            service="backend"
            ;;
        frontend)
            # Check if dev or normal
            if docker compose -f $DEV_COMPOSE ps frontend-dev 2>/dev/null | grep -qE "Up|running"; then
                service="frontend-dev"
                compose_file=$DEV_COMPOSE
            else
                service="frontend"
                compose_file=$NORMAL_COMPOSE
            fi
            ;;
        mysql)
            service="mysql"
            ;;
        *)
            print_error "Unknown container: $container. Use: backend, frontend, or mysql"
            exit 1
            ;;
    esac
    
    # Determine which compose file to use if not already set
    if [ -z "$compose_file" ]; then
        if docker compose -f $DEV_COMPOSE ps $service 2>/dev/null | grep -qE "Up|running"; then
            compose_file=$DEV_COMPOSE
        elif docker compose -f $NORMAL_COMPOSE ps $service 2>/dev/null | grep -qE "Up|running"; then
            compose_file=$NORMAL_COMPOSE
        else
            print_error "Container $container is not running. Start with 'sw dev' or 'sw up'"
            exit 1
        fi
    fi
    
    docker compose -f $compose_file exec $service /bin/bash
}

function run_migrate() {
    run_artisan migrate
}

function run_seed() {
    run_artisan db:seed
}

function run_migrate_fresh() {
    run_artisan migrate:fresh --seed
}

# Main command router
case "${1:-}" in
    setup)
        setup_app
        ;;
    dev)
        dev_mode
        ;;
    up)
        up_mode
        ;;
    stop)
        stop_all
        ;;
    artisan)
        shift
        run_artisan "$@"
        ;;
    composer)
        shift
        run_composer "$@"
        ;;
    pint)
        shift
        run_pint
        ;;
    npm)
        shift
        run_npm "$@"
        ;;
    sql)
        open_sql
        ;;
    bash)
        shift
        open_bash "$@"
        ;;
    migrate)
        run_migrate
        ;;
    seed)
        run_seed
        ;;
    migrate:fresh)
        run_migrate_fresh
        ;;
    help|--help|-h|"")
        show_help
        ;;
    *)
        print_error "Unknown command: $1"
        echo ""
        show_help
        exit 1
        ;;
esac
