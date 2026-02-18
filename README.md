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
4. Browser এ open করুন `http://127.0.0.1:8000/install/index.php` এবং installer complete করুন।
   - Installer auto-detect করে আপনার current IP whitelist এ add করবে।
   - চাইলে setup এর সময় extra IP/CIDR add করতে পারবেন।
5. তারপর Admin login করুন: `admin@example.com` / `Admin@123`.


## Installer Features
- Full installation wizard at `install/index.php`
- Server requirement checks (PHP/PDO/PDO-MySQL/.env writable/upload dir writable)
- DB configuration form + automatic `.env` generation
- Automatic schema import (`database/schema.sql`)
- Admin account setup from installer form
- Navbar/Favicon control from Admin Settings (path or upload)
- Auto-detected admin IP whitelist + manual IP/CIDR add
- Global install lock: until installation completes, all pages redirect to installer

## Troubleshooting (Preview না দেখালে)
- **Port busy**: `php -S 127.0.0.1:8080` দিয়ে run করে `http://127.0.0.1:8080/index.php` open করুন।
- **Wrong path**: command অবশ্যই project root (`/workspace/cloner`) থেকে run করবেন।
- **PHP missing**: `php -v` কাজ করছে কিনা check করুন।
- **XAMPP/WAMP ব্যবহার করলে**: project htdocs/www এ রেখে `http://localhost/cloner/index.php` open করুন।

> Note: Image assets are SVG placeholders (text-based) to keep the repository binary-free.

## Custom Error Pages (cPanel/Apache)
- Included: `403.php`, `404.php`, `500.php`
- Apache mapping is configured in `.htaccess` with `ErrorDocument` directives.
- This works out of the box on most cPanel Apache hosting.


## Admin IP Whitelist (Security)
- Installer run করার সময় detected IP auto-whitelist হয়।
- অতিরিক্ত IP/CIDR Admin Panel → **Manage Settings** থেকে update করা যায় (`admin_ip_whitelist`)।
- Whitelist এ না থাকলে admin route/login access করলে custom **403** error page দেখাবে।

Optional fallback env (যদি DB setting empty থাকে):

```bash
ADMIN_IP_WHITELIST=127.0.0.1,::1,103.25.44.10,103.25.44.0/24
```
