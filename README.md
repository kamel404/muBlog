# MU Blog - Maaref University Blog

## Project Description

MU Blog is a Laravel-based blog platform designed for Maaref University students to share their thoughts, ideas, and questions. It allows users to create and interact with posts through comments and likes, while admins manage content, users, and categories.

## Features

-   User authentication (Login, Register, Password Reset)
-   Role-based access control (Admin & User)
-   CRUD operations for posts
-   Commenting and liking system
-   Profile management with image upload
-   Admin dashboard for managing users and categories

## Installation & Setup

### 1. Clone the Repository

```bash
git clone https://github.com/your-username/mu-blog.git
cd mu-blog
```

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Configure Environment Variables

Rename `.env.example` to `.env` and update the following values:

```env
APP_NAME=MU_Blog
APP_URL=http://localhost
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mu_blog
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Run Migrations & Seeders

```bash
php artisan migrate --seed
```

This will create the database tables and populate initial data like admin and user roles.

### 6. Start the Development Server

```bash
php artisan serve
```

## Admin Credentials (Default Seeded User)

```
Email: admin@mu.edu.lb
Password: password
```

## Useful Commands

-   **Run Seeders Again:** `php artisan db:seed`
-   **Refresh Database (Drops & Reruns Migrations):** `php artisan migrate:fresh --seed`
-   **Cache Configuration:** `php artisan config:cache`
-   **Clear Cache:** `php artisan cache:clear`

## Technologies Used

-   **Laravel** (Backend Framework)
-   **MySQL** (Database)
-   **Blade Templates** (Frontend Rendering)
-   **Eloquent ORM** (Database Management)
-   **Middleware & Policies** (Access Control)

## Entity Relationship Diagram

Below is the ER Diagram representing the database structure:

![ER Diagram](docs/ER%20Diagram.png)

## Home Page

This is the homepage of the MU Blog project:

![Home Page](docs/Home%20Page.jpg)

## Future Enhancements

-   Real-time notifications for comments and likes
-   AI-based content moderation
-   Improved UI/UX design

## License

This project is open-source and available for educational purposes.

---

For any issues or contributions, feel free to open a pull request or contact the developers!
