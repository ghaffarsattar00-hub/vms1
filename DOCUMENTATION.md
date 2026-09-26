# Vaccination Management System (VMS) - Academic Documentation

## 1. Problem Definition

### 1.1 Background
In many healthcare systems, infant vaccination scheduling remains a manual, paper-based process. Parents must physically visit hospitals to register their children for vaccinations, often resulting in missed appointments, incomplete immunization records, and preventable disease outbreaks. Manual tracking systems are prone to human error, data loss, and lack real-time visibility into vaccine availability and appointment status.

### 1.2 Problem Statement
The current manual vaccination scheduling process suffers from the following critical issues:

- **Missed Vaccinations:** Without automated reminders and digital tracking, parents frequently miss critical vaccination windows, leaving infants vulnerable to preventable diseases such as polio, measles, and hepatitis.
- **Data Silos:** Paper records are difficult to aggregate, analyze, and share across hospitals, making it impossible to obtain a unified view of vaccination coverage in a region.
- **No Real-Time Visibility:** Hospitals cannot efficiently track which children are scheduled for which vaccines on a given day, leading to resource misallocation.
- **Inventory Mismanagement:** Vaccine availability is not communicated to parents in real-time, leading to wasted trips when a vaccine is out of stock.
- **No Accountability:** Without a digital audit trail, there is no way to track appointment approvals, rejections, or vaccination confirmations.

### 1.3 Impact
These issues contribute to lower immunization rates, increased child mortality from vaccine-preventable diseases, and overburdened healthcare workers who must manage records manually instead of focusing on patient care.

---

## 2. Proposed Solution

### 2.1 Overview
The **Vaccination Management System (VMS)** is a web-based platform designed to digitize the entire infant vaccination lifecycle — from child registration and appointment booking to vaccine administration and record keeping. The system connects three primary stakeholders: **Parents**, **Hospitals**, and **Administrators**.

### 2.2 Key Features
- **Parent Portal:** Secure registration, child profile management, and appointment booking at preferred hospitals with real-time vaccine availability.
- **Hospital Portal:** View assigned appointments, mark children as vaccinated via AJAX, and manage daily vaccination workflows.
- **Admin Dashboard:** Full control over hospitals (CRUD), vaccine inventory management with toggle switches, appointment approval/rejection, and analytics via Chart.js.
- **Password Recovery:** "Forgot password?" flow with single-use 1-hour reset tokens; mock email preview page on hosts without `mail()`.

**Live URL:** https://vams.infinityfreeapp.com/

### 2.3 Technology Stack
| Component | Technology |
|-----------|-----------|
| Backend | PHP 8+ (Strict OOP, Custom MVC) |
| Database | MySQL (PDO Prepared Statements) |
| Frontend | Tailwind CSS (CDN), Vanilla JS |
| Charts | Chart.js |
| Architecture | Front-Controller Pattern, Singleton DB, Security Class |

---

## 3. Customer Requirement Specification (SRS)

### 3.1 Admin Module

| ID | Requirement | Priority | Type |
|----|-------------|----------|------|
| ADM-01 | Admin can view dashboard with total hospitals, vaccines, children, and appointments | High | Functional |
| ADM-02 | Admin can create new hospital records with name, address, and location | High | Functional |
| ADM-03 | Admin can edit existing hospital details via animated modal | High | Functional |
| ADM-04 | Admin can delete hospitals (cascading to remove linked appointments) | High | Functional |
| ADM-05 | Admin can toggle vaccine availability (Available/Unavailable) via AJAX switches | High | Functional |
| ADM-06 | Admin can approve or reject pending vaccination appointments | High | Functional |
| ADM-07 | Admin dashboard displays monthly appointment statistics via bar chart | Medium | Functional |
| ADM-08 | Admin dashboard shows vaccine availability distribution via donut chart | Medium | Functional |
| ADM-09 | All admin actions require CSRF token validation | High | Non-Functional |
| ADM-10 | Admin can filter appointments by status (All, Pending, Approved, Vaccinated, Rejected) | Medium | Functional |

### 3.2 Parent Module

| ID | Requirement | Priority | Type |
|----|-------------|----------|------|
| PAR-01 | Parent can register a new account with name, email, and password | High | Functional |
| PAR-02 | Parent can log in securely with email and password | High | Functional |
| PAR-03 | Parent can add child profiles with name, date of birth, and gender | High | Functional |
| PAR-04 | Parent can view list of registered children with age calculation | Medium | Functional |
| PAR-05 | Parent can delete child profiles (cascades to remove linked appointments) | High | Functional |
| PAR-06 | Parent can book vaccination appointments by selecting child, hospital, vaccine, and date | High | Functional |
| PAR-07 | Parent can view all their appointments with current status | High | Functional |
| PAR-08 | Parent dashboard shows total children, appointments, and vaccinated count | Medium | Functional |
| PAR-09 | All parent inputs are sanitized against XSS attacks | High | Non-Functional |
| PAR-10 | Parent can only see available vaccines in the booking dropdown | High | Functional |

### 3.3 Hospital Module

| ID | Requirement | Priority | Type |
|----|-------------|----------|------|
| HOS-01 | Hospital user can log in and view their assigned appointments | High | Functional |
| HOS-02 | Hospital dashboard displays pending, approved, and vaccinated appointment counts | Medium | Functional |
| HOS-03 | Hospital can mark approved appointments as "Vaccinated" via AJAX | High | Functional |
| HOS-04 | Hospital can only update appointments assigned to their facility | High | Non-Functional |
| HOS-05 | Hospital can only vaccinate appointments with "Approved" status | High | Functional |
| HOS-06 | Status update is reflected in real-time without page reload | Medium | Functional |
| HOS-07 | Hospital sees child name, parent name, vaccine, and booking date for each appointment | High | Functional |
| HOS-08 | Hospital profile is automatically linked to user account during registration | High | Functional |

---

## 4. E-R Diagram (Textual Explanation)

### 4.1 Entities and Attributes

**Entity: Users**
| Attribute | Type | Constraints | Description |
|-----------|------|-------------|-------------|
| id | INT | PRIMARY KEY, AUTO_INCREMENT | Unique user identifier |
| name | VARCHAR(100) | NOT NULL | Full name of the user |
| email | VARCHAR(100) | NOT NULL, UNIQUE | Email address for login |
| password | VARCHAR(255) | NOT NULL | Bcrypt hashed password |
| role | ENUM | NOT NULL | 'admin', 'parent', or 'hospital' |
| created_at | TIMESTAMP | DEFAULT CURRENT_TIMESTAMP | Account creation date |
| updated_at | TIMESTAMP | AUTO-UPDATE | Last modification date |

**Entity: Children**
| Attribute | Type | Constraints | Description |
|-----------|------|-------------|-------------|
| id | INT | PRIMARY KEY, AUTO_INCREMENT | Unique child identifier |
| parent_id | INT | FOREIGN KEY → users(id), ON DELETE CASCADE | Parent who registered this child |
| name | VARCHAR(100) | NOT NULL | Child's full name |
| dob | DATE | NOT NULL | Date of birth |
| gender | ENUM | NOT NULL | 'Male', 'Female', or 'Other' |

**Entity: Hospitals**
| Attribute | Type | Constraints | Description |
|-----------|------|-------------|-------------|
| id | INT | PRIMARY KEY, AUTO_INCREMENT | Unique hospital identifier |
| user_id | INT | UNIQUE, FOREIGN KEY → users(id), ON DELETE SET NULL | Linked hospital user account |
| hospital_name | VARCHAR(150) | NOT NULL | Name of the hospital |
| address | VARCHAR(255) | NOT NULL | Physical address |
| location | VARCHAR(100) | NOT NULL | Area or neighborhood |

**Entity: Vaccines**
| Attribute | Type | Constraints | Description |
|-----------|------|-------------|-------------|
| id | INT | PRIMARY KEY, AUTO_INCREMENT | Unique vaccine identifier |
| name | VARCHAR(100) | NOT NULL, UNIQUE | Vaccine name (e.g., BCG, MMR) |
| description | TEXT | NULLABLE | Detailed description of the vaccine |
| status | ENUM | DEFAULT 'Available' | 'Available' or 'Unavailable' |

**Entity: Appointments**
| Attribute | Type | Constraints | Description |
|-----------|------|-------------|-------------|
| id | INT | PRIMARY KEY, AUTO_INCREMENT | Unique appointment identifier |
| child_id | INT | FOREIGN KEY → children(id), ON DELETE CASCADE | Child receiving the vaccine |
| hospital_id | INT | FOREIGN KEY → hospitals(id), ON DELETE CASCADE | Hospital administering the vaccine |
| vaccine_id | INT | FOREIGN KEY → vaccines(id), ON DELETE CASCADE | Vaccine to be administered |
| booking_date | DATE | NOT NULL | Scheduled appointment date |
| status | ENUM | DEFAULT 'pending' | 'pending', 'approved', 'rejected', 'vaccinated', 'not_vaccinated', or 'cancelled' |

### 4.2 Relationships

```
Users (1) ──────── (0..*) Children       [One parent has many children]
Users (1) ──────── (0..1) Hospitals      [One hospital user manages one hospital record]
Children (1) ────── (0..*) Appointments   [One child has many appointments]
Hospitals (1) ───── (0..*) Appointments   [One hospital has many appointments]
Vaccines (1) ────── (0..*) Appointments   [One vaccine is used in many appointments]
```

### 4.3 Normalization (3NF)
- **1NF:** All attributes contain atomic values; no repeating groups.
- **2NF:** All non-key attributes are fully dependent on the primary key (no partial dependencies).
- **3NF:** No transitive dependencies exist. Hospital contact info is stored in the `users` table and linked via `user_id` foreign key rather than duplicating email/name in the hospitals table.

---

## 5. Step-by-Step Algorithms

### 5.1 Algorithm: Appointment Booking (Parent)

```
ALGORITHM BookAppointment

INPUT:  child_id, hospital_id, vaccine_id, booking_date
OUTPUT: Appointment record created with "Pending" status

PROCEDURE:
1.  VALIDATE CSRF token from form submission
2.  SANITIZE all input parameters against XSS
3.  CHECK that child_id is provided and is a positive integer
4.  CHECK that hospital_id is provided and is a positive integer
5.  CHECK that vaccine_id is provided and is a positive integer
6.  CHECK that booking_date is not empty
7.  VERIFY booking_date is today or in the future
    IF booking_date < TODAY THEN
        RETURN error: "Booking date must be today or in the future"
    END IF
8.  VERIFY the child belongs to the currently logged-in parent
    QUERY: SELECT * FROM children WHERE id = child_id AND parent_id = session_user_id
    IF no result THEN
        RETURN error: "Invalid child selected"
    END IF
9.  VERIFY the hospital exists in the database
    QUERY: SELECT * FROM hospitals WHERE id = hospital_id
    IF no result THEN
        RETURN error: "Selected hospital does not exist"
    END IF
10. VERIFY the vaccine exists and has status = 'Available'
    QUERY: SELECT * FROM vaccines WHERE id = vaccine_id AND status = 'Available'
    IF no result THEN
        RETURN error: "Selected vaccine is currently unavailable"
    END IF
11. INSERT appointment record with status = 'pending'
    QUERY: INSERT INTO appointments (child_id, hospital_id, vaccine_id, booking_date, status)
           VALUES (child_id, hospital_id, vaccine_id, booking_date, 'pending')
12. IF insert successful THEN
        SET session success message: "Appointment booked successfully!"
        REDIRECT to parent dashboard
    ELSE
        SET session error message: "Failed to book appointment."
        REDIRECT back to booking form
    END IF

END ALGORITHM
```

### 5.2 Algorithm: Status Update (Hospital → Vaccinated)

```
ALGORITHM UpdateToVaccinated

INPUT:  appointment_id, csrf_token (via AJAX JSON payload)
OUTPUT: Appointment status changed from "Approved" to "Vaccinated"

PROCEDURE:
1.  VERIFY HTTP method is POST
    IF method != POST THEN
        RETURN JSON { success: false, message: "Invalid request method" }
    END IF
2.  PARSE JSON body from php://input
3.  VALIDATE CSRF token against session token
    IF token invalid THEN
        RETURN JSON { success: false, message: "Invalid security token" }
    END IF
4.  VALIDATE appointment_id is a positive integer
    IF appointment_id <= 0 THEN
        RETURN JSON { success: false, message: "Invalid appointment ID" }
    END IF
5.  QUERY appointment record
    QUERY: SELECT * FROM appointments WHERE id = appointment_id
    IF no result THEN
        RETURN JSON { success: false, message: "Appointment not found" }
    END IF
6.  VERIFY the hospital user owns this appointment
    QUERY: SELECT * FROM hospitals WHERE user_id = current_user_id
    IF hospital.id != appointment.hospital_id THEN
        RETURN JSON { success: false, message: "Access denied" }
    END IF
7.  VERIFY appointment status is "Approved"
    IF appointment.status != "Approved" THEN
        RETURN JSON { success: false, message: "Only approved appointments can be vaccinated" }
    END IF
8.  UPDATE appointment status to "Vaccinated"
    QUERY: UPDATE appointments SET status = 'vaccinated' WHERE id = appointment_id
9.  IF update successful THEN
        RETURN JSON { success: true, message: "Appointment marked as vaccinated!" }
    ELSE
        RETURN JSON { success: false, message: "Failed to update status" }
    END IF

END ALGORITHM
```

---

## 6. Unit Testing Checklist

| # | Test Case | Module | Preconditions | Input | Expected Output | Priority |
|---|-----------|--------|---------------|-------|-----------------|----------|
| 1 | User login with valid credentials | Auth | User exists in DB | email: parent@vms.com, password: password | Redirect to parent dashboard, session created | High |
| 2 | User login with invalid password | Auth | User exists in DB | email: parent@vms.com, password: wrongpass | Error message "Invalid email or password", stay on login page | High |
| 3 | Parent registration with matching passwords | Auth | No existing account | name: Test, email: test@vms.com, password: pass123, confirm: pass123 | Account created, redirect to parent dashboard | High |
| 4 | Parent registration with mismatched passwords | Auth | No existing account | password: pass123, confirm: pass456 | Error "Passwords do not match", redirect to register | High |
| 5 | Add child profile | Parent | Parent logged in | name: Emma, dob: 2026-03-01, gender: Female | Child created, visible on dashboard | High |
| 6 | Book appointment with valid data | Parent | Child exists, hospital exists, vaccine available | child_id: 1, hospital_id: 1, vaccine_id: 1, date: 2026-10-15 | Appointment created with "Pending" status | High |
| 7 | Book appointment with past date | Parent | Child exists | booking_date: 2025-01-01 | Error "Booking date must be today or in the future" | Medium |
| 8 | Admin approve pending appointment | Admin | Appointment exists with "Pending" status | appointment_id: 3 | Status changes to "Approved" | High |
| 9 | Admin reject pending appointment | Admin | Appointment exists with "Pending" status | appointment_id: 3 | Status changes to "Rejected" | High |
| 10 | Hospital mark as vaccinated | Hospital | Appointment exists with "Approved" status | appointment_id: 2 (AJAX POST) | Status changes to "Vaccinated", JSON success response | High |
| 11 | Hospital attempt to vaccinate non-approved appointment | Hospital | Appointment exists with "Pending" status | appointment_id: 3 | Error "Only approved appointments can be vaccinated" | Medium |
| 12 | Toggle vaccine availability | Admin | Vaccine exists in inventory | vaccine_id: 1, new_status: "Unavailable" | Vaccine status updated to "Unavailable" | High |
| 13 | Admin create hospital | Admin | Admin logged in | name: New Hospital, address: 123 St, location: North | Hospital created and visible in list | High |
| 14 | Admin delete hospital | Admin | Hospital exists, no active appointments | hospital_id: 2 | Hospital deleted, success message shown | Medium |
| 15 | CSRF token validation | Security | Any POST request | Missing or invalid csrf_token | Request rejected with security error | High |
| 16 | XSS sanitization of user input | Security | Any form submission | Input: `<script>alert('xss')</script>` | Input sanitized, script tags converted to HTML entities | High |
| 17 | Unauthorized access to admin routes | Auth | User logged in as parent | Navigate to /admin/dashboard | Redirected to parent dashboard with access denied message | High |
| 18 | Session timeout handling | Security | Session expired | Navigate to any protected page | Redirect to login with "Authentication required" message | Medium |

---

## 7. Project File Structure

```
Vaccination-Management-System/
├── config.php                  # Database credentials & app settings
├── DOCUMENTATION.md            # This academic documentation file
├── database/
│   └── schema.sql              # Complete database schema with seed data
├── core/
│   ├── Controller.php          # Base controller with view rendering
│   ├── Database.php            # Singleton PDO wrapper
│   ├── Router.php              # Custom routing engine
│   └── Security.php            # CSRF & XSS protection utilities
├── app/
│   ├── Controllers/
│   │   ├── AdminController.php     # Admin module controller
│   │   ├── AuthController.php      # Authentication controller
│   │   ├── HospitalController.php  # Hospital module controller
│   │   └── ParentController.php    # Parent module controller
│   ├── Models/
│   │   ├── Appointment.php     # Appointment CRUD model
│   │   ├── Child.php           # Child profile model
│   │   ├── Hospital.php        # Hospital CRUD model
│   │   ├── User.php            # User authentication model
│   │   └── Vaccine.php         # Vaccine inventory model
│   └── Views/
│       ├── auth/
│       │   ├── login.php       # Login form (standalone layout)
│       │   └── register.php    # Registration form (standalone layout)
│       ├── admin/
│       │   ├── dashboard.php   # Admin dashboard with Chart.js
│       │   ├── hospitals.php   # Hospital CRUD with animated modals
│       │   ├── inventory.php   # Vaccine toggle switches
│       │   └── appointments.php # Appointment management table
│       ├── parent/
│       │   ├── dashboard.php   # Parent dashboard with child cards
│       │   └── book.php        # Appointment booking form
│       ├── hospital/
│       │   └── dashboard.php   # Hospital dashboard with AJAX vaccinate
│       └── layout/
│           ├── header.php      # Global sidebar + header layout
│           └── footer.php      # Global footer + scripts
└── public/
    └── index.php               # Front controller & route definitions
```

---

## 8. Security Measures Implemented

1. **CSRF Protection:** Every form includes a hidden CSRF token validated on every POST request.
2. **XSS Prevention:** All user inputs are sanitized via `htmlspecialchars()` before output.
3. **SQL Injection Prevention:** All database queries use PDO prepared statements with bound parameters.
4. **Password Hashing:** Passwords are stored using `password_hash()` with bcrypt and verified with `password_verify()`.
5. **Session Security:** Session IDs are regenerated on login to prevent session fixation attacks.
6. **Role-Based Access Control:** Each controller enforces role checks before executing any action.
7. **Input Validation:** Server-side validation for email format, password length, date ranges, and foreign key existence.

---

## 9. Installation & Setup Instructions

1. **Import Database:** Run `database/schema_hosting.sql` then `database/seed_pakistan.sql` in MySQL (`vms_db`). Upgrading an older database? Run `database/add_reset_token.sql` (adds reset-token columns).
2. **Configure Credentials:** `config.php` — defaults work with local XAMPP (`root` / empty password); environment variables override them.
3. **Start PHP Server:** Run `php -S 127.0.0.1:8080 -t public` from the project root.
4. **Access Application:** Open `http://127.0.0.1:8080` (live: **https://vams.infinityfreeapp.com/**).
5. **Login:** Use seed credentials:

   | Role | Email | Password |
   |------|-------|----------|
   | Admin | admin@aku.edu.pk | password |
   | Admin | usman.tariq@shifa.edu.pk | password |
   | Parent | ali.raza@gmail.com | password |
   | Parent | fatima.ahmed@outlook.com | password |
   | Parent | hassan.malik@yahoo.com | password |
   | Parent | saira.bibi@hotmail.com | password |
   | Parent | omar.farooq@gmail.com | password |
   | Hospital | info@aku.edu.pk | hospital123 |
   | Hospital | info@shifa.edu.pk | hospital123 |
   | Hospital | info@jinnah.edu.pk | hospital123 |
   | Hospital | navyshifa@pns.net | hospital123 |
   | Hospital | test@hospital.edu.pk | hospital123 |
   | Hospital | test@hospital.pk | hospital123 |
   | Hospital | nadia.iqbal@aku.edu.pk | password |
   | Hospital | imran.shah@shifa.edu.pk | password |
   | Hospital | sana.qureshi@jinnah.edu.pk | password |

---

*Document prepared for the Vaccination Management System eProject.*
*Technology: PHP 8+, MySQL, Tailwind CSS, Chart.js*
