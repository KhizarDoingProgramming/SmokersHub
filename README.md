<div align="center">
  <img src="logo.png" alt="Smoker's Hub Logo" width="200"/>
  <h1>🚬 Smoker's Hub</h1>
  <p><strong>A Premium E-Commerce Platform for Smoking Essentials & Accessories</strong></p>
</div>

---

## 🌟 Overview

**Smoker's Hub** is a lightweight, fully functional e-commerce web application built for the modern enthusiast. Whether you're looking for premium cigars, vapes, accessories, or top-tier rolling woods, Smoker's Hub provides a sleek, responsive, and seamless shopping experience. 

Built with a robust **PHP + MySQL** backend and an intuitive front-end, it features secure user authentication, dynamic product browsing, and a dedicated admin interface to manage inventory on the fly.

## ✨ Features

- 🔐 **Secure User Authentication:** Encrypted password storage, session-based logins, and user registration.
- 🛒 **Dynamic Product Catalog:** Browse categories including Cigars, Vapes, Accessories, Woods, and more.
- 👨‍💻 **Admin Dashboard:** Role-based access control allowing administrators to add and manage products directly from the UI.
- ✉️ **Contact System:** Integrated contact forms that securely save customer inquiries directly into the database.
- 🐳 **Docker Ready:** Comes with a pre-configured `docker-compose.yml` for instant zero-configuration deployment.
- 📱 **Fully Responsive:** Sleek, custom-styled UI that looks great on both desktop and mobile devices.

## 🚀 Tech Stack

- **Frontend:** HTML5, CSS3, Vanilla JavaScript
- **Backend:** PHP 8+ (PDO for secure database interactions)
- **Database:** MySQL
- **Infrastructure:** Docker & Docker Compose

## 🛠️ Quick Start (Docker)

The absolute easiest way to get Smoker's Hub running locally is using Docker.

1. **Clone the repository**
   ```bash
   git clone https://github.com/KhizarDoingProgramming/SmokersHub.git
   cd SmokersHub
   ```

2. **Spin up the environment**
   ```bash
   docker-compose up -d
   ```

3. **Access the Application**
   - 🌐 **Storefront:** `http://localhost:8080`
   - 🗄️ **Database Admin (phpMyAdmin):** `http://localhost:8081`

## ⚙️ Manual Setup (LAMP / XAMPP)

If you prefer running it manually via XAMPP/WAMP or a custom LAMP stack:

1. Copy the project files to your web root (e.g., `htdocs/smokers-hub`).
2. Create a MySQL database named `smokers_hub`.
3. Import the initial database schema from `database/init.sql` (or `scratch/setup_db.php`).
4. Update your database credentials in `php/config.php` if necessary:
   ```php
   define('DB_HOST', '127.0.0.1');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   define('DB_NAME', 'smokers_hub');
   ```
5. Navigate to `http://localhost/smokers-hub/index.html` in your browser.

## 📡 API Endpoints

The platform utilizes a custom-built JSON API for smooth, asynchronous operations:

- `POST /php/auth.php` - Handle User Login & Registration
- `GET /php/products.php` - Fetch all products
- `GET /php/products.php?id=1` - Fetch a single product
- `POST /php/products.php` - Add a new product (Admin Only)
- `POST /php/contact.php` - Submit a contact form inquiry

## 👨‍💻 Author

Developed and maintained by **KhizarDoingProgramming**.

---
*Disclaimer: This project is intended for educational purposes and portfolio demonstration.*
