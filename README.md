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

# Install dependencies (twig)
docker compose exec web composer install

# Setup database (create tables + seed)
docker compose exec web php bootstrap.php
```

### 3. Access the Application

Open your browser and navigate to: **http://localhost:8080**

**Login credentials:**
- Username: `admin`
- Password: `test`

**Versions on my local:**
- Docker version 28.2.2
- docker-compose version 1.8.1
