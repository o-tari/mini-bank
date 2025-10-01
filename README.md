# Mini-Bank Application

## Project Overview

This is a comprehensive Mini-Bank application designed to simulate core banking functionalities. It provides a secure and efficient platform for managing user accounts, processing transactions, handling loan applications, and generating financial reports. The application is built with a focus on modularity, scalability, and a rich user experience, leveraging modern web technologies.

## Features

### User Management
*   **User Registration & Authentication:** Secure user signup, login, and password management.
*   **Role-Based Access Control (RBAC):** Differentiated access for various user types (e.g., Admin, Manager, Regular User).
*   **User Profiles:** View and manage personal account information.

### Account & Transaction Management
*   **Account Dashboard:** Personalized dashboard for users to view their account summary, recent transactions, and loan status.
*   **Deposit & Withdrawal:** Simulate basic banking operations for adding and removing funds.
*   **Fund Transfers:** Securely transfer funds between accounts.
*   **Transaction History:** Detailed log of all incoming and outgoing transactions.
*   **Audit Logs:** Comprehensive logging of critical system activities for security and compliance.

### Loan Management
*   **Loan Application:** Users can apply for various types of loans through a dedicated form.
*   **Loan Approval Workflow:** Admins/Managers can review, approve, or reject loan applications.
*   **Loan Repayment Tracking:** Monitor loan schedules and repayment status.
*   **Loan Status Notifications:** Users receive updates on their loan application and repayment status.

### Notifications & Alerts
*   **Real-time Notifications:** In-app notifications for transactions, loan updates, and system alerts.
*   **Low Balance Alerts:** Automated alerts to users when their account balance falls below a predefined threshold.
*   **Loan Due Soon Reminders:** Notifications for upcoming loan repayment dates.

### Reporting & Analytics
*   **Financial Reports:** Generate reports on transactions, loans, and user activity (Admin/Manager).
*   **Dashboard Analytics:** Visual summaries of key banking metrics.

### Role-Based Dashboards And Functionality
**User:**
<img width="991" height="665" alt="Screenshot 2025-10-01 at 5 02 16 PM" src="https://github.com/user-attachments/assets/1ec37297-6243-49bc-92ee-b6b63106ebf1" />
<img width="993" height="503" alt="Screenshot 2025-10-01 at 5 02 31 PM" src="https://github.com/user-attachments/assets/014c1b82-e52e-4308-a429-4874794ead0c" />

**Manager:**
<img width="1257" height="653" alt="Screenshot 2025-10-01 at 5 12 40 PM" src="https://github.com/user-attachments/assets/c5416426-e28f-465c-87bb-72069b2a9ce7" />
<img width="1258" height="388" alt="Screenshot 2025-10-01 at 5 12 48 PM" src="https://github.com/user-attachments/assets/cadce8ec-108b-4597-a7b8-ad33abfe40d8" />

**Admin:**
<img width="1205" height="631" alt="Screenshot 2025-10-01 at 5 13 30 PM" src="https://github.com/user-attachments/assets/b8b77c11-99d7-41c0-83a9-be1a17b0286a" />
<img width="1205" height="282" alt="Screenshot 2025-10-01 at 5 13 38 PM" src="https://github.com/user-attachments/assets/1af22ae9-d8ba-4fcb-b767-dfecc57c0be7" />


## Technologies Used

*   **Backend:** PHP 8.x, Laravel Framework
*   **Frontend:** Livewire, Alpine.js, Blade Templates, Tailwind CSS
*   **Database:** MySQL (or other compatible relational database)
*   **Package Management:** Composer (PHP), npm/Yarn (JavaScript)
*   **Authentication/Authorization:** Laravel Breeze, Spatie Laravel Permission
*   **Testing:** Pest PHP, PHPUnit
*   **Version Control:** Git

## Installation Guide

To set up the project locally, follow these steps:

1.  **Clone the repository:**
    ```bash
    git clone <repository_url>
    cd mini-bank
    ```

2.  **Install PHP Dependencies:**
    ```bash
    composer install
    ```

3.  **Install JavaScript Dependencies:**
    ```bash
    npm install
    # OR
    yarn install
    ```

4.  **Environment Configuration:**
    *   Copy the example environment file:
        ```bash
        cp .env.example .env
        ```
    *   Generate an application key:
        ```bash
        php artisan key:generate
        ```
    *   Configure your database connection in the `.env` file.

5.  **Run Database Migrations & Seeders:**
    ```bash
    php artisan migrate --seed
    ```
    This will set up the database schema and populate it with initial data (users, roles, etc.).

6.  **Build Frontend Assets:**
    ```bash
    npm run dev
    # OR for production
    npm run build
    ```

7.  **Start the Local Server:**
    ```bash
    php artisan serve
    ```

    The application will be accessible at `http://127.0.0.1:8000` (or a similar address).

## Usage

*   **Admin Access:** Log in with an admin account (seeded during installation) to manage users, approve loans, and view reports.
*   **Manager Access:** Log in with a manager account to oversee operations and manage specific banking tasks.
*   **User Access:** Register a new user account or log in with a seeded user account to access personal banking features.

## Contributing

Contributions are welcome! Please fork the repository and submit pull requests. For major changes, please open an issue first to discuss what you would like to change.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
