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
git clone https://github.com/SyedRafid/Real-Estate-Management-System.git
cd Real-Estate-Management-System
```

### 📂 2. Importing the Database using phpMyAdmin

This project uses a MySQL database named **`building`**. To set it up locally, follow these steps:

1. **Create the Database:**

   - Open **phpMyAdmin** in your browser (e.g., http://localhost/phpmyadmin).
   - Click on the **Databases** tab.
   - In the "Create database" field, enter the name:  
     ```
     building
     ```
   - Choose the collation (e.g., `utf8mb4_general_ci`) and click **Create**.

2. **Import the SQL File:**

   - Click on the newly created `building` database in phpMyAdmin.
   - Go to the **Import** tab.
   - Click **Choose File** and browse to the project folder's `database` directory.
   - Select the SQL file (e.g., `building.sql`).
   - Click **Go** at the bottom to start the import.
   - Wait for the success message confirming the import.
     

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
---

## 🙏 Thank You!

Thank you for checking out this project!  
If you find it helpful, please consider giving it a ⭐️ on GitHub.

Feel free to open issues or submit pull requests — contributions and feedback are always welcome!  

Happy coding and best of luck managing your real estate business efficiently! 🏢✨
