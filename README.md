# A simple Kanban board application

A simple Kanban board application with Symfony 7.2, PostgreSQL, Turbo Drive, Stimulus controller, Bootstrap 5

---

## ✅ Requirements

Before starting, make sure you have the following installed:

* PHP 8.2+
* [Composer](https://getcomposer.org/)
* [Symfony CLI (optional)](https://symfony.com/download)
* PostgreSQL 13+
* Node.js + NPM (e.g., Node 18)

---

## 🚀 Setup Instructions

### 1. Clone the Repository

```bash
git clone https://github.com/thaiht/simple_kanban_board_symfony_app.git
cd simple_kanban_board_symfony_app
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install JavaScript Dependencies

```bash
npm install
```

---

### 4. Configure Environment

Edit `.env` and update your database credentials:

```dotenv
DATABASE_URL="postgresql://your_user:your_password@127.0.0.1:5432/your_db_name"
```

Create the PostgreSQL database if it doesn't exist:

```bash
php bin/console doctrine:database:create
```

---

### 5. Create Database Schema & Load Fixtures

Create database schema:

```bash
php bin/console doctrine:schema:create
```

[REQUIRED]Load sample data with Doctrine Fixtures:

```bash
php bin/console doctrine:fixtures:load
```

---

## 🧪 Run the App

Start the local server:

```bash
symfony server:start
```

Then visit: [http://127.0.0.1:8000](http://127.0.0.1:8000), you should see a Login page. Please, use demo Users below to start test the app.

---

## Test the app

After created users from UserFixtures, there are 5 demo users
```
user1@example.com / password1
user2@example.com / password2
user3@example.com / password3
user4@example.com / password4
user5@example.com / password5
```

## 📄 License

MIT License.
