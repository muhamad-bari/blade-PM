# Laravel Project Management

A streamlined project management application built with Laravel. This system is designed with a **single-admin control** architecture, meaning only the administrator has the authority to manage the todo lists, projects, and employees. Other users (guests) are restricted to read-only access.

**[Live Preview](http://www.pm-app.fwh.is)**

## Features

-   **Single Admin Authority**:
    -   Full access to create, edit, and delete projects and employees.
    -   Exclusive rights to export data.
-   **Guest Read-Only Access**:
    -   Guests can view dashboards, projects, and employee lists but cannot modify any data.
-   **Project Management**:
    -   Create and track projects.
    -   Pin important projects to the dashboard.
    -   Assign leaders and members to projects.
-   **Employee Management**:
    -   Maintain a database of employees (who are distinct from system users).
-   **Excel Export**:
    -   Admin can export project data for reporting.

## Tech Stack

-   **Framework**: [Laravel](https://laravel.com)
-   **Frontend**: Blade Templates, [Tailwind CSS](https://tailwindcss.com)
-   **Bundler**: [Vite](https://vitejs.dev)
-   **Database**: MySQL

## Installation

1.  **Clone the repository**
    ```bash
    git clone <repository-url>
    cd <repository-directory>
    ```

2.  **Install PHP dependencies**
    ```bash
    composer install
    ```

3.  **Install Node.js dependencies**
    ```bash
    npm install
    npm run build
    ```

4.  **Environment Configuration**
    -   Copy `.env.example` to `.env`
    ```bash
    cp .env.example .env
    ```
    -   Update database credentials in `.env`

5.  **Generate Application Key**
    ```bash
    php artisan key:generate
    ```

6.  **Run Migrations and Seeders**
    -   This will set up the database and create the default Admin and Guest accounts.
    ```bash
    php artisan migrate --seed
    ```

7.  **Run the Application**
    ```bash
    php artisan serve
    ```

## Default Credentials

The database seeder creates the following accounts for testing:

### Admin (Full Access)
-   **Email**: `admin@example.com`
-   **Password**: `password8899`

### Guest (Read-Only)
-   **Email**: `guest@user.com`
-   **Password**: `user123`

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
