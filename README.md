# GreenTech Boost

Professional Bangla PHP + MySQL marketing website with a secure admin panel.

## Stack
- PHP 8+
- MySQL
- Tailwind CSS (CDN + local structure)
- Vanilla JavaScript

## Structure
- `index.php`
- `access/css/styles.css`
- `access/css/tailwind.css`
- `access/javascript/main.js`
- `access/img/hero-illustration.svg`, `access/img/portfolio-placeholder.svg`
- `admin/*`
- `database/schema.sql`

## Setup
1. Import `database/schema.sql` into MySQL.
2. Update DB values in `includes/config.php` or set environment variables.
3. Run local server:
   ```bash
   php -S 0.0.0.0:8000
   ```
4. Open `http://localhost:8000`.
5. Admin login: `admin@example.com` / `Admin@123`.

> Note: Image assets are SVG placeholders (text-based) to keep the repository binary-free.
