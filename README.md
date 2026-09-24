# Vaccination Management System (VMS)

Enterprise PHP 8 MVC eProject — MySQL, Tailwind CSS, Vanilla JS.  
Pakistan-localized seed data, strict RBAC (Admin / Parent / Hospital), appointment workflow, inventory orders with delivery ETA.

## Stack

| Layer | Tech |
|-------|------|
| Backend | PHP 8+ (custom MVC, no framework) |
| Database | MySQL 8 / MariaDB |
| Frontend | Tailwind CSS (CDN), Vanilla JS |
| Auth | Session-based, Argon2id passwords |

## Roles & Demo Logins

| Role | Email | Password |
|------|-------|----------|
| Admin | `admin@aku.edu.pk` | `password` |
| Parent | `ali.raza@gmail.com` | `password` |
| Hospital (PNS Shifa) | `navyshifa@pns.net` | `hospital123` |
| Hospital (Aga Khan) | `info@aku.edu.pk` | `hospital123` |
| Hospital (Jinnah) | `info@jinnah.edu.pk` | `hospital123` |

## Local Setup (XAMPP / LAMP)

1. **Clone**
   ```bash
   git clone https://github.com/USERNAME/vms.git
   cd vms
   ```

2. **Database**
   ```bash
   mysql -u root -e "CREATE DATABASE vms_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
   mysql -u root vms_db < database/schema.sql
   mysql -u root vms_db < database/seed_pakistan.sql
   ```

3. **Config** — copy example if needed, or edit `config.php`:
   ```bash
   cp .env.example .env   # optional
   ```
   Defaults work with local XAMPP (`root` / empty password).

4. **Run**
   ```bash
   php -S 127.0.0.1:8080 -t public
   ```
   Open http://127.0.0.1:8080

## Environment Variables

| Variable | Default | Description |
|----------|---------|-------------|
| `APP_ENV` | `development` | `production` hides errors |
| `DB_HOST` | `127.0.0.1:3306` | MySQL host:port |
| `DB_NAME` | `vms_db` | Database name |
| `DB_USER` | `root` | DB user |
| `DB_PASS` | `` | DB password |
| `BASE_URL` | `/` | App base path |

## Deploy

> **Note:** Vercel does not run PHP/MySQL. Use Railway, Render, or shared PHP hosting.

### Railway
1. Push this repo to GitHub
2. [railway.app](https://railway.app) → **New Project** → deploy from GitHub
3. Add **MySQL** plugin → copy connection env vars into service
4. Set `APP_ENV=production`
5. Import SQL via Railway MySQL console or Workbench

### Render
1. Push to GitHub
2. [render.com](https://render.com) → **New Web Service** → connect repo
3. Runtime: PHP — `render.yaml` is included
4. Add PostgreSQL/MySQL → set env vars → import schema + seed

### Shared PHP Hosting (cPanel)
1. Upload project, point document root to `public/`
2. phpMyAdmin → import `database/schema.sql` then `database/seed_pakistan.sql`
3. Set DB credentials in `config.php`
4. Set `APP_ENV=production`

## Project Structure

```
├── app/
│   ├── Controllers/     # Auth, Admin, Parent, Hospital
│   ├── Models/          # Appointment, Child, Hospital, Order, ...
│   └── Views/           # PHP templates (Tailwind)
├── core/                # Router, Database, Security, Controller
├── database/            # schema.sql + seed_pakistan.sql
├── public/              # Web root (index.php front controller)
├── config.php           # Env-aware configuration
├── Procfile             # Render/Railway start command
└── render.yaml          # Render blueprint
```

## Features

- **Admin:** hospitals, vaccines, inventory, orders (approve/deliver), view-only appointments  
- **Parent:** children profiles, book appointments, track status  
- **Hospital:** approve/reject appointments, mark vaccinated, inventory, place orders  
- Audit logs, CSRF protection, role-based access, responsive enterprise UI  

## Documentation

- [DOCUMENTATION.md](DOCUMENTATION.md) — algorithms, test cases, schema  
- [eProject-Documentation.md.md](eProject-Documentation.md.md) — full eProject write-up  

## License

For academic / eProject use.
