# Vaccination Management System (VMS)

Enterprise PHP 8 MVC eProject — MySQL, Tailwind CSS, Vanilla JS.  
Pakistan-localized seed data, strict RBAC (Admin / Parent / Hospital), appointment workflow, inventory orders with delivery ETA.

**Live URL: https://vams.infinityfreeapp.com/**

## Stack

| Layer | Tech |
|-------|------|
| Backend | PHP 8+ (custom MVC, no framework) |
| Database | MySQL 8 / MariaDB |
| Frontend | Tailwind CSS (CDN), Vanilla JS |
| Auth | Session-based, Argon2id/BCrypt passwords, CSRF tokens |

## Demo Logins (all seeded accounts)

### Admin
| Email | Password |
|-------|----------|
| `admin@aku.edu.pk` | `password` |
| `usman.tariq@shifa.edu.pk` | `password` |

### Parent
| Email | Password |
|-------|----------|
| `ali.raza@gmail.com` | `password` |
| `fatima.ahmed@outlook.com` | `password` |
| `hassan.malik@yahoo.com` | `password` |
| `saira.bibi@hotmail.com` | `password` |
| `omar.farooq@gmail.com` | `password` |

### Hospital
| Email | Password |
|-------|----------|
| `info@aku.edu.pk` (Aga Khan) | `hospital123` |
| `info@shifa.edu.pk` (Shifa Intl) | `hospital123` |
| `info@jinnah.edu.pk` (Jinnah Lahore) | `hospital123` |
| `navyshifa@pns.net` (PNS Shifa) | `hospital123` |
| `test@hospital.edu.pk` | `hospital123` |
| `test@hospital.pk` | `hospital123` |
| `nadia.iqbal@aku.edu.pk` | `password` |
| `imran.shah@shifa.edu.pk` | `password` |
| `sana.qureshi@jinnah.edu.pk` | `password` |

> Password recovery: login page → **Forgot password?** → link valid 1 hour (mock email preview page opens on hosts without `mail()`).

## Local Setup (XAMPP / LAMP)

1. **Clone**
   ```bash
   git clone https://github.com/ghaffarsattar00-hub/vms1.git
   cd vms1
   ```

2. **Database**
   ```bash
   mysql -u root -e "CREATE DATABASE vms_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
   mysql -u root vms_db < database/schema_hosting.sql
   mysql -u root vms_db < database/seed_pakistan.sql
   ```
   Existing database? Add reset-password columns only:
   ```bash
   mysql -u root vms_db < database/add_reset_token.sql
   ```

3. **Config** — `config.php` (defaults work with local XAMPP: `root` / empty password):
   ```bash
   cp .env.example .env   # optional
   ```

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

### InfinityFree (LIVE — used for this project)
1. **Live URL: https://vams.infinityfreeapp.com/** (account `if0_42999413`)
2. Panel → File Manager → upload `deploy/vms-full.zip` into `public_html/` → Extract (flat structure: `index.php`, `config.php`, `app/`, `core/`, `database/`)
3. phpMyAdmin → Import `database/add_reset_token.sql` (adds reset-token columns on existing DBs)
4. DB creds live in `config.php` (`sql302.infinityfree.com` / `if0_42999413_vms`), `APP_ENV=production`
5. Note: InfinityFree disables `mail()` — forgot-password shows a **mock email preview page** with the reset link instead

### Railway / Render / Shared cPanel
> Vercel does not run PHP/MySQL. Use Railway, Render, or shared PHP hosting.

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
- **Auth:** login/register, **forgot/reset password** (1-hour token, single-use), role-based redirect  
- Audit logs, CSRF protection, responsive enterprise UI  

## Documentation

- [DOCUMENTATION.md](DOCUMENTATION.md) — algorithms, test cases, schema  
- [eProject-Documentation.md.md](eProject-Documentation.md.md) — full eProject write-up  

## License

For academic / eProject use.
