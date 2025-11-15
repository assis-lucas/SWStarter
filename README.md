# SWStarter

A full-stack starter template with Laravel backend and React frontend, fully containerized with Docker.

## 🚀 Quick Start

### Prerequisites
- [Docker](https://www.docker.com/get-started) and Docker Compose
- Git

### Setup

1. **Configure Laravel environment:**
   ```bash
   cp backend/.env.example backend/.env
   ```

2. **Generate Laravel application key:**
   ```bash
   docker-compose up backend -d
   docker exec -it $(docker-compose ps -q backend) php artisan key:generate
   docker-compose down
   ```

3. **Start the application:**
   ```bash
   # Production mode
   docker-compose up -d
   
   # OR Development mode (with hot reload)
   docker-compose -f docker-compose.dev.yaml up -d
   ```

4. **Run database migrations:**
   ```bash
   docker exec -it $(docker-compose ps -q backend) php artisan migrate
   ```

### Access the Application

- **Frontend (React)**: http://localhost:3000
- **Backend (Laravel API)**: http://localhost:8000
- **Database (MySQL)**: localhost:3306

## 🛠️ Development

### Development Mode
```bash
# Start with hot reload for frontend
docker-compose -f docker-compose.dev.yaml up -d

# View logs
docker-compose logs -f frontend-dev
docker-compose logs -f backend
```

### Useful Commands

**Laravel (Backend):**
```bash
# Run migrations
docker exec -it $(docker-compose ps -q backend) php artisan migrate

# Create new migration
docker exec -it $(docker-compose ps -q backend) php artisan make:migration create_example_table

# Laravel Tinker (REPL)
docker exec -it $(docker-compose ps -q backend) php artisan tinker

# Install PHP packages
docker exec -it $(docker-compose ps -q backend) composer install
```

**React (Frontend):**
```bash
# Install npm packages (development mode)
docker exec -it $(docker-compose ps -q frontend-dev) npm install

# Run tests
docker exec -it $(docker-compose ps -q frontend-dev) npm test
```

**Database:**
```bash
# Access MySQL
docker exec -it $(docker-compose ps -q mysql) mysql -u laravel -p laravel

# Reset database
docker-compose down
docker volume rm swstarter_mysql_data
docker-compose up -d
```

## 🆘 Troubleshooting

### Common Issues

**Port already in use:**
```bash
# Check what's using the port
netstat -tulpn | grep :3000
# Kill the process or change port in docker-compose.yaml
```

**Database connection issues:**
```bash
# Ensure MySQL is healthy
docker-compose ps
# Check backend logs
docker-compose logs backend
```

**Frontend not updating:**
```bash
# Use development compose file
docker-compose -f docker-compose.dev.yaml up -d
```

**Permission issues:**
```bash
# Fix file permissions
sudo chown -R $USER:$USER .
```

For more detailed Docker setup information, see the individual application README files in `frontend/` and `backend/` directories.