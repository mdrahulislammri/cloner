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

## Quick Preview (No DB needed)
> Homepage preview দেখার জন্য MySQL import বাধ্যতামূলক না। DB না থাকলেও fallback content দিয়ে `index.php` render হবে।

```bash
php -S 127.0.0.1:8000
```

তারপর browser এ open করুন:
- `http://127.0.0.1:8000/index.php`

## Full Setup (With Admin + Dynamic Data)
1. `database/schema.sql` MySQL এ import করুন।
2. `includes/config.php` এ DB credential update করুন (বা environment variable set করুন)।
3. Server run করুন:
   ```bash
   php -S 127.0.0.1:8000
   ```
4. Open `http://127.0.0.1:8000`.
5. Admin login: `admin@example.com` / `Admin@123`.

## Troubleshooting (Preview না দেখালে)
- **Port busy**: `php -S 127.0.0.1:8080` দিয়ে run করে `http://127.0.0.1:8080/index.php` open করুন।
- **Wrong path**: command অবশ্যই project root (`/workspace/cloner`) থেকে run করবেন।
- **PHP missing**: `php -v` কাজ করছে কিনা check করুন।
- **XAMPP/WAMP ব্যবহার করলে**: project htdocs/www এ রেখে `http://localhost/cloner/index.php` open করুন।

> Note: Image assets are SVG placeholders (text-based) to keep the repository binary-free.
