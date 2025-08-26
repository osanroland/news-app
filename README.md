# News App

### 1. Clone the Repository

```bash
git clone <repository-url>
cd News
```

### 2. Start the Application

```bash
# Build and start containers
docker compose up --build -d

# Install PHP dependencies
docker compose exec web composer install --no-dev --optimize-autoloader

# Setup database (create tables + sample data)
docker compose exec web php bootstrap.php
```

### 3. Access the Application

Open your browser and navigate to: **http://localhost:8080**

**Login credentials:**
- Username: `admin`
- Password: `test`
