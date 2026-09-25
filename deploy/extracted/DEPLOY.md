# VMS — Free Shared Hosting Deploy (InfinityFree / GoogieHost)

## Target FTP Structure

```
/home/ACCOUNT/
├── config.php          ← DB credentials yahan
├── app/
├── core/
├── database/
│   ├── schema_hosting.sql
│   └── seed_pakistan.sql
└── public_html/        ← WEB ROOT (sirf yeh public hai)
    ├── .htaccess
    ├── index.php
    ├── vms-logo.svg
    └── vms-icon.png
```

> Sensitive files (`config.php`, `app/`, `core/`) **public_html ke BAHAR** hain — web se direct access nahi.

---

## Step-by-Step

### 1. Account banao (InfinityFree recommended)
1. https://www.infinityfree.com → **Sign Up**
2. Email verify → control panel (VistaPanel) khulega
3. **Create Account** → free subdomain choose karo (e.g. `vms1.epizy.com`)
4. Wait 5–10 min activation

**Ya GoogieHost:** https://googiehost.com → free hosting → DirectAdmin panel

### 2. MySQL Database banao
Panel mein:
- **MySQL Databases** → Create DB
- Note karo: **DB Name**, **DB User**, **DB Password**, **DB Host**
  - InfinityFree host kuch aisa: `sqlXXX.byetcluster.com`
  - GoogieHost host panel mein dikhega

### 3. Files upload (FTP ya File Manager)
**File Manager (easy):**
1. Panel → **File Manager** → `/` (home, NOT public_html)
2. Upload karo:
   - `config.php` → home root (deploy/config.php wala — edit karke DB values daalo)
   - `app/`, `core/`, `database/` folders → home root
3. Andar `public_html/` mein:
   - `.htaccess`
   - `index.php`
   - `vms-logo.svg`, `vms-icon.png`

**Ya FTP (FileZilla):**
- Host: `ftp.infinityfree.com` (ya panel mein FTP host)
- User/Pass: panel se
- Drag-drop same structure

### 4. Database Import
1. Panel → **phpMyAdmin**
2. Apni DB select karo
3. **Import** → `database/schema_hosting.sql` → Go
4. Phir **Import** → `database/seed_pakistan.sql` → Go

### 5. config.php edit karo
```php
define('DB_HOST', 'sqlXXX.byetcluster.com'); // panel host
define('DB_NAME', 'if0_123_vms');            // exact DB name
define('DB_USER', 'if0_123');                // exact user
define('DB_PASS', 'xxxxx');                  // exact password
```

### 6. Open karo
`https://vms1.epizy.com/` (apna domain)

**Demo login:**
| Role | Email | Password |
|------|-------|----------|
| Admin | admin@aku.edu.pk | password |
| Parent | ali.raza@gmail.com | password |
| Hospital | navyshifa@pns.net | hospital123 |

---

## Common Errors

| Error | Fix |
|-------|-----|
| Database connection failed | config.php values ↔ panel match? |
| 404 on /login | `.htaccess` public_html mein hai? mod_rewrite on? |
| Blank page | Panel → PHP version 8.x select |
| Forbidden | `.htaccess` syntax / Options lacking — host support se |

## InfinityFree Notes
- Free: 5GB, PHP 8.3, 400 MySQL DBs, SSL auto
- No credit card
- Fair usage: ~50k hits/day (demo ke liye enough)
- Email sending disabled (app mein mail nahi chalta — fine for eProject)

## GoogieHost Notes
- DirectAdmin panel
- PHP version select karo (8.x)
- 1 MySQL DB free mein
