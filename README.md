# 🚀 CogniShift

> AI-powered multi-tenant HR management platform built with Laravel, Vue 3, Inertia.js, and Tailwind CSS.

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11.x-red?style=for-the-badge&logo=laravel" />
  <img src="https://img.shields.io/badge/Vue-3.x-42b883?style=for-the-badge&logo=vue.js" />
  <img src="https://img.shields.io/badge/Inertia.js-Fullstack-purple?style=for-the-badge" />
  <img src="https://img.shields.io/badge/TailwindCSS-Modern_UI-38bdf8?style=for-the-badge&logo=tailwindcss" />
  <img src="https://img.shields.io/badge/PostgreSQL-Database-336791?style=for-the-badge&logo=postgresql" />
  <img src="https://img.shields.io/badge/Redis-Queues-red?style=for-the-badge&logo=redis" />
</p>

---

# ✨ Features

## 🧠 AI-Powered HR Automation

- AI-generated HR insights
- Smart employee analytics
- Automated workflows & recommendations
- AI queue processing with Laravel Horizon
- OpenAI / Anthropic integration support

## 🏢 Multi-Tenant Architecture

- Secure tenant isolation
- Global tenant scoping
- Scalable SaaS-ready structure
- Tenant-aware data handling

## ⚡ Modern Full-Stack Experience

- Laravel backend
- Vue 3 Composition API frontend
- Inertia.js SPA experience without REST APIs
- Tailwind CSS modern UI system
- Vite-powered development workflow

## 🔒 Enterprise Ready

- Queue management with Horizon
- PostgreSQL optimized
- Redis-powered background processing
- Clean modular architecture
- Scalable infrastructure support

---

# 🛠️ Tech Stack

| Layer        | Technology         |
| ------------ | ------------------ |
| Backend      | Laravel 11         |
| Frontend     | Vue 3              |
| SPA Bridge   | Inertia.js         |
| Styling      | Tailwind CSS       |
| Database     | PostgreSQL         |
| Queue System | Redis + Horizon    |
| Build Tool   | Vite               |
| AI Providers | OpenAI / Anthropic |

---

# 🚀 Getting Started

## 📋 Prerequisites

Before you begin, ensure you have the following installed:

- PHP 8.2+
- Composer
- Node.js 18+
- NPM 18+
- PostgreSQL
- Redis
- OpenAI or Anthropic API Key

---

# 📦 Installation

## 1️⃣ Clone the Repository

```bash
git clone https://github.com/Hammad-arshad18/CogniShift.git
cd CogniShift
```

---

## 2️⃣ Install Dependencies

### Backend Dependencies

```bash
composer install
```

### Frontend Dependencies

```bash
npm install
```

---

## 3️⃣ Environment Configuration

Copy the example environment file:

```bash
cp .env.example .env
```

Generate the Laravel application key:

```bash
php artisan key:generate
```

---

## 4️⃣ Configure Environment Variables

Update your `.env` file:

```env
APP_NAME="Nucleus HR"

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=employee_management
DB_USERNAME=root
DB_PASSWORD=

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

OPENAI_API_KEY=your_openai_key

# OR

ANTHROPIC_API_KEY=your_anthropic_key
```

---

## 5️⃣ Run Database Migrations & Seeders

```bash
php artisan migrate --seed
```

---

# ▶️ Running the Application

## Terminal 1 — Laravel Backend

```bash
php artisan serve
```

---

## Terminal 2 — Vite Frontend

```bash
npm run dev
```

---

## Terminal 3 — Queue Workers (Horizon)

```bash
php artisan horizon
```

---

# 🏗️ Project Architecture

## Frontend Architecture

The frontend is built using:

- Vue 3 Composition API
- Inertia.js
- Tailwind CSS
- Vite

Inertia.js allows a seamless SPA-like experience without maintaining a separate REST API.

---

## Multi-Tenancy

Nucleus HR uses a tenant-based architecture.

### Important Notes

- All tenant models use a global `TenantScope`
- Always assign `tenant_id` during model creation
- Ensure tenant isolation in queries and jobs

Example:

```php
Employee::create([
    'tenant_id' => tenant()->id,
    'name' => 'John Doe',
]);
```

---

# 📁 Recommended Project Structure

```bash
app/
├── Actions/
├── AI/
├── Domains/
├── Http/
├── Models/
├── Policies/
├── Services/
└── Tenancy/

resources/
├── js/
│   ├── Components/
│   ├── Layouts/
│   ├── Pages/
│   └── Composables/
└── css/
```

---

# 🧪 Development Commands

## Run Tests

```bash
php artisan test
```

## Format Code

```bash
./vendor/bin/pint
```

## Build Production Assets

```bash
npm run build
```

---

# 🤝 Contributing

Contributions are welcome and greatly appreciated.

## Contribution Workflow

1. Fork the repository
2. Create your feature branch

```bash
git checkout -b feature/amazing-feature
```

3. Commit your changes

```bash
git commit -m "Add amazing feature"
```

4. Push to GitHub

```bash
git push origin feature/amazing-feature
```

5. Open a Pull Request

---

# 🐛 Issues & Feature Requests

If you discover a bug or want to request a feature, please open an issue in the repository.

---

# 👨‍💻 Author

## Hammad Arshad

Senior Web Developer  
AI & SaaS Systems Architect

---

# 📄 License

Distributed under the MIT License.

See the `LICENSE` file for more information.

---

# ⭐ Support the Project

If you find this project useful:

- Star the repository
- Share it with others
- Contribute improvements
- Report bugs & suggestions

---

<p align="center">
  Built with ❤️ using Laravel, Vue & AI
</p>
