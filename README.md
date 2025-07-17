# Real-Estate-Management-System
A powerful and easy-to-use business management system designed to handle investors, funding, building layout, property sales, expenses, and financial reporting — all in one platform.

Built using HTML, CSS, bootstrap, JavaScript, PHP, and phpMyAdmin with the SB Admin 2 template and custom frontend design.

---

## ✨ Features

### 🔐 Authentication
- Secure user login system
- Forgot password with OTP-based reset via email
- Email integration using PHPMailer and `.env` config

### 👥 User Roles
- **Super Admin**
  - Cannot be deleted
  - Can create/manage all other users
- **Admin**
  - Cannot manage users
  - Same permissions as Super Admin for all other modules
- All users can manage their own profile

### 📊 Dashboard
- Total access count
- Monthly/annual payments
- Daily/monthly/annual expenses
- Annual sales and funding
- Graphs and charts for:
  - Asset Overview
  - Asset Sources (Payments vs Funding)
  - Sales Overview
  - Payment Overview

### 💼 Investor & Funding
- Add/manage investors
- Add/manage funding records linked to investors

### 🏢 Building Management
- Add buildings with floor and flat structure
- Assign flats to floors
- Manage buildings and layout dynamically

### 🛒 Sales & Payments
- Create sales by selecting building → floor → flat
- Add customer info and sale type (EMI / Full)
- Manage customer details and payment history
- Record EMI payments and view full payments

### 📉 Expense Management
- Add/manage expenses:
  - Purpose, amount (৳), notes, upload receipt
- Track all recorded expenses

### 📄 Reports
- Generate reports between any two dates
- Includes funding, payments, expenses
- Printable format

---

## 🧰 Tech Stack

| Area       | Technology           |
|------------|----------------------|
| Frontend   | HTML, CSS, Bootstrap, JS, SB Admin 2, Custom UI |
| Backend    | PHP                  |
| Database   | MySQL (phpMyAdmin)   |
| Email      | PHPMailer (via Composer) |
| Config     | `.env` file for secrets |

---

## ⚙️ Installation & Setup

### ✅ Requirements

- PHP 7.4+
- MySQL or phpMyAdmin
- Apache/Nginx server or XAMPP
- Composer (for PHPMailer)
- `mod_rewrite` enabled (if using pretty URLs)

### 📥 1. Clone the Project

```bash
git clone https://github.com/your-username/business-management-system.git
cd business-management-system
```
### ⚙️ 3. Set Up `.env` File

Create a `.env` file in the root of your project with the following variables:

```ini
# Database Configuration
DB_HOST=localhost
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

# Email Configuration (PHPMailer)
MAIL_MAILER=smtp
MAIL_HOST=smtp.yourhost.com
MAIL_PORT=587
MAIL_USERNAME=your-email@example.com
MAIL_PASSWORD=your-email-password
MAIL_FROM=your-email@example.com
MAIL_FROM_NAME=Your App Name
```
### 🗝️ User login
- Super Admin:
  - Email: syed.shuvon@gmail.com
  - Password: syed.shuvon@gmail.com
- Admin:
  - Email: syed.shuvon2@gmail.com
  - Password: syed.shuvon2@gmail.com
