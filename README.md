# Star Wars Project

A fullstack project consisting of a Laravel API and a Vue.js application that consumes data from the Star Wars API (SWAPI).

## Project Structure

```
.
├── star-wars-api/          # Backend - Laravel API
├── star-wars-app-vue/      # Frontend - Vue.js Application
├── docker-compose.yml      # Docker orchestration
└── DOCKER.md               # Detailed Docker documentation
```

## Technologies Used

### Backend (star-wars-api)
- PHP 8.x
- Laravel
- MySQL 8.0
- Redis (cache and queues)

### Frontend (star-wars-app-vue)
- Vue.js 3
- TypeScript
- Vite

## Requirements

### With Docker (Recommended)
- Docker
- Docker Compose

### Without Docker
- PHP 8.x
- Composer
- Node.js 18+
- npm
- MySQL 8.0
- Redis

---

## Running with Docker (Recommended)

### 1. Start the Services

```bash
# Build and start all containers
docker-compose up -d --build

# View logs (optional)
docker-compose logs -f
```

### 2. Access the Applications

| Service | URL |
|---------|-----|
| Frontend Vue.js | http://localhost:5173 |
| Laravel API | http://localhost:8000 |
| MySQL | localhost:3380 |
| Redis | localhost:6379 |

### Useful Docker Commands

```bash
# Stop containers
docker-compose stop

# Restart containers
docker-compose restart

# Stop and remove containers
docker-compose down

# Stop and remove containers + volumes (deletes database data)
docker-compose down -v

# View logs for a specific service
docker-compose logs -f api
docker-compose logs -f app
```

---

## Running without Docker

### Backend (star-wars-api)

```bash
cd star-wars-api

# Install dependencies
composer install

# Configure environment
cp .env.example .env

# Edit .env with your database settings:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=starwars
# DB_USERNAME=your_username
# DB_PASSWORD=your_password

# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate

# Start development server
php artisan serve
```

The API will be available at `http://localhost:8000`

### Frontend (star-wars-app-vue)

```bash
cd star-wars-app-vue

# Install dependencies
npm install

# Start development server
npm run dev
```

The application will be available at `http://localhost:5173`

---

## API Endpoints

| Method | Endpoint | Description                   |
|--------|----------|-------------------------------|
| GET | `/` | Home page                     |
| GET | `/people` | List all characters           |
| GET | `/people/{id}` | Get a specific character      |
| GET | `/films` | List all films                |
| GET | `/films/{id}` | Get a specific film           |
| GET | `/aggregated-stats` | Get aggregated statistics     |
| GET | `/processStats` | Process statistics (for testing purposes only) |

---

## Development

### Hot Reload

Both applications support hot reload during development:

- **Vue.js**: Vite automatically reloads changes
- **Laravel**: Changes to PHP files are reflected automatically

## Troubleshooting

### Ports in Use

If a port is already in use, edit `docker-compose.yml` or use different ports:

```yaml
ports:
  - "8001:80"  # API on port 8001
  - "5174:5173"  # Frontend on port 5174
```

### Laravel Permissions

```bash
docker-compose exec api bash
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

### Rebuild Containers

```bash
docker-compose down
docker-compose up -d --build --force-recreate
```

---

## Additional Documentation

For more detailed information about Docker configuration, see the [DOCKER.md](./DOCKER.md) file.
