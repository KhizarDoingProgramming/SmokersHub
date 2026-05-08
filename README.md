# Smoker's Hub

## Overview
Smoker's Hub is a lightweight PHP‑MySQL web application that lets users register, log in, browse products, and submit contact messages. It is designed to run in a Docker environment for easy local development and can be deployed to any LAMP stack.

## Features
- **User authentication** (register, login, logout, session handling)
- **Product catalog** with a simple REST API (GET all, GET by ID, add product – admin only)
- **Contact form** storing messages in the database
- **Docker Compose** setup that provides:
  - PHP + Apache container
  - MySQL database container
  - phpMyAdmin for database inspection
- **Responsive front‑end** using plain HTML/CSS (no JavaScript framework needed)

## Prerequisites
- Docker & Docker‑Compose installed
- (Optional) Git for version control

## Quick Start with Docker
```bash
# Clone the repository (once it is pushed to GitHub)
git clone https://github.com/<YOUR_USERNAME>/smokers-hub.git
cd smokers-hub

# Build and start containers
docker-compose up -d
```
The application will be available at `http://localhost:8080` and phpMyAdmin at `http://localhost:8081`.

## Local Development (without Docker)
1. Install a LAMP stack (Apache, PHP 8+, MySQL).
2. Copy the `php/` directory into your web root.
3. Create a MySQL database named `smokers_hub` and import the schema from `database/schema.sql` (if present).
4. Adjust the credentials in `php/config.php`.
5. Access the site via your local web server.

## Testing the API
- **Register**: `POST /php/auth.php` with JSON `{"action":"register","name":"John","email":"john@example.com","password":"secret"}`
- **Login**: `POST /php/auth.php` with JSON `{"action":"login","email":"john@example.com","password":"secret"}`
- **Get products**: `GET /php/products.php`
- **Get single product**: `GET /php/products.php?id=3`
- **Add product (admin)**: `POST /php/products.php` with JSON product data and an admin session.

## Removing Comments
A helper script `strip_comments.sh` is provided to automatically strip all `//` and `/* */` comments from the PHP source files:
```bash
chmod +x strip_comments.sh
./strip_comments.sh
```
The script rewrites the files in‑place, leaving the functional code untouched.

## Git Workflow
```bash
git init
git add .
git commit -m "Initial clean commit"
# Add remote repository
git remote add origin https://github.com/<YOUR_USERNAME>/smokers-hub.git
# Push to GitHub (main branch)
git push -u origin main
```
The `.gitignore` file (created separately) ensures that large media assets, Docker environment files, and other non‑essential files are not uploaded.

## License
This project is licensed under the MIT License.
