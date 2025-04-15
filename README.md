# Multi-Branch Attendance & Dynamic Salary Management System

A Laravel 10-based web application designed for managing attendance and dynamic salary calculations for employees across multiple branches. The system provides filtering, export, reporting, and performance insights.

---

## 📌 Key Features

- 🔐 Role-based user authentication
- 🏢 Multi-branch employee management
- 📆 Monthly attendance tracking
- 📊 Attendance performance analytics
- 🧾 Export reports in PDF / CSV / Excel formats
- 📉 Automatic average & percentage calculation
- 🏅 Highlight employees with >90% attendance
- 📄 Paginated employee stats with filters
- 📤 AJAX-based dynamic employee dropdown (based on branch selection)

---

## 🚀 Technology Stack

- **Backend:** Laravel 10
- **Frontend:** Blade Templates, Bootstrap 5
- **Database:** MySQL
- **Libraries/Packages:**
  - Laravel Excel (maatwebsite/excel)
  - DomPDF for PDF exports
  - Carbon for date handling

---

## ⚙️ Setup Instructions

```bash
git clone https://github.com/your-username/attendance-system.git
cd attendance-system

composer install
cp .env.example .env
php artisan key:generate

# Setup database
php artisan migrate --seed

# Run the server
php artisan serve
