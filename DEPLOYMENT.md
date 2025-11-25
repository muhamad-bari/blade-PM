# Deployment Instructions (Shared Hosting)

These instructions guide you through deploying the Laravel Project Management application to a shared hosting environment (e.g., cPanel) without SSH access.

## Prerequisites
- Local environment with Composer and Node.js installed.
- Shared hosting account with PHP >= 8.1 and MySQL.

## Step 1: Prepare Local Application
1. **Install Dependencies**:
   ```bash
   composer install --optimize-autoloader --no-dev
   npm install
   npm run build
   ```

2. **Configure Environment**:
   - Update `.env` to set `APP_ENV=production` and `APP_DEBUG=false`.
   - Ensure `APP_URL` matches your hosting domain.

3. **Export Database**:
   - If you have local data you want to keep, export your local database to a `.sql` file using a tool like phpMyAdmin or TablePlus.
   - If starting fresh, you can skip this, but you'll need to run migrations on the server or import a structure-only SQL file.
   - **Recommended**: Run `php artisan migrate --force` locally on a fresh DB and export that structure if you can't run artisan on server. Or use the provided migration files if your host supports SSH.
   - **No SSH**: Export your local development database structure (and data if needed) to `database.sql`.

## Step 2: Upload Files
1. **File Structure**:
   - Create a folder named `project_management` (or any name) in your hosting root (outside `public_html` if possible for security).
   - Upload all files and folders **EXCEPT**:
     - `node_modules`
     - `.git`
     - `tests`
   - Upload the contents of the `public` folder to your hosting's `public_html` (or a subdirectory if deploying to a subdomain).

2. **Modify `index.php`**:
   - Edit `public_html/index.php` to point to the correct paths.
   - Change:
     ```php
     require __DIR__.'/../vendor/autoload.php';
     $app = require_once __DIR__.'/../bootstrap/app.php';
     ```
   - To (assuming you placed the project in `project_management` folder at the same level as `public_html`):
     ```php
     require __DIR__.'/../project_management/vendor/autoload.php';
     $app = require_once __DIR__.'/../project_management/bootstrap/app.php';
     ```

## Step 3: Database Setup
1. **Create Database**:
   - Go to cPanel > MySQL Databases.
   - Create a new database and a user.
   - Grant all privileges to the user for that database.

2. **Import SQL**:
   - Go to cPanel > phpMyAdmin.
   - Select your new database.
   - Import the `database.sql` file you exported earlier.

3. **Update Configuration**:
   - Edit the `.env` file in your uploaded `project_management` folder.
   - Update DB credentials:
     ```ini
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=your_db_name
     DB_USERNAME=your_db_user
     DB_PASSWORD=your_db_password
     ```

## Step 4: Storage Link
Shared hosting often doesn't allow running `php artisan storage:link`.
1. **Manual Link**:
   - If you have SSH, run `php artisan storage:link`.
   - If not, you can create a PHP script in `public_html` named `symlink.php`:
     ```php
     <?php
     target = '/path/to/project_management/storage/app/public';
     $shortcut = '/path/to/public_html/storage';
     symlink($target, $shortcut);
     echo 'Symlink created';
     ?>
     ```
   - Run it by visiting `yourdomain.com/symlink.php`, then delete it.

## Step 5: Final Checks
- Visit your domain.
- Login with the admin credentials (if you seeded the DB locally and exported it).
- Check if assets (CSS/JS) are loading correctly.
