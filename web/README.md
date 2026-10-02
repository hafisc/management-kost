# Management Kost - Web Application

Sistem manajemen Kost Sumber Rezeki berbasis web dengan Laravel & Filament.

## 🚀 Features

### Core Modules
- ✅ **Building & Room Management** - Kelola gedung, lantai, dan kamar
- ✅ **Tenant Management** - Data penyewa dengan validasi WhatsApp
- ✅ **Stay Tracking** - Histori menginap dengan status aktif/selesai
- ✅ **Payment System** - Pembayaran dengan itemisasi detail
- ✅ **Expense Tracking** - Pencatatan pengeluaran dengan 8 kategori
- ✅ **Dashboard Analytics** - Metrik occupancy, pendapatan, pengeluaran
- ✅ **REST API** - Integrasi Bot WhatsApp

### Key Capabilities
- Real-time room availability checking
- Occupancy percentage tracking
- Period-based payment recording
- Receipt/proof upload for expenses
- Monthly financial reports
- Excel export (payments & expenses)
- Payment reminder system
- Search & filtering on all tables

## 🛠 Tech Stack

- **Framework**: Laravel 13
- **Admin Panel**: Filament 5.9
- **Database**: MySQL 8.0+
- **PHP**: 8.3+
- **Authentication**: Laravel Sanctum
- **Excel Export**: maatwebsite/excel
- **Code Style**: Laravel Pint

## 📋 Prerequisites

- PHP 8.3+
- Composer 2.x
- MySQL 8.0+
- Node.js 20+ & npm

## 🔧 Installation

### 1. Clone Repository
```bash
git clone <repository-url>
cd management-kost/web
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env`:
```env
DB_DATABASE=management_kost
DB_USERNAME=root
DB_PASSWORD=

FILAMENT_COLOR_PRIMARY=#042B60
FILAMENT_COLOR_WARNING=#FCB502
```

### 4. Database Setup
```bash
# Create database
mysql -u root -p
CREATE DATABASE management_kost;
EXIT;

# Run migrations
php artisan migrate

# Seed initial data
php artisan db:seed
```

### 5. Build Assets
```bash
npm run build
```

### 6. Start Development Server
```bash
php artisan serve
```

Access: http://127.0.0.1:8000/admin

**Default Login:**
- Username: `admin`
- Password: `sumberrezeki1515`

## 📁 Project Structure

```
web/
├── app/
│   ├── Console/Commands/        # Artisan commands
│   ├── Filament/                # Filament resources & pages
│   │   ├── Resources/           # CRUD resources
│   │   └── Widgets/             # Dashboard widgets
│   ├── Http/Controllers/Api/    # API controllers
│   ├── Models/                  # Eloquent models
│   ├── Notifications/           # Notification classes
│   ├── Policies/                # Authorization policies
│   └── Services/                # Business logic services
├── database/
│   ├── factories/               # Model factories
│   ├── migrations/              # Database migrations
│   └── seeders/                 # Database seeders
├── routes/
│   ├── api.php                  # API routes
│   └── web.php                  # Web routes
└── tests/                       # Feature & unit tests
```

## 🔌 API Endpoints

### Authentication
All endpoints require Sanctum Bearer token:
```
Authorization: Bearer {token}
```

### Available Endpoints

#### Tenants
- `GET /api/v1/tenants` - List tenants
- `GET /api/v1/tenants/{id}` - Get tenant details
- `GET /api/v1/tenants/phone/{phone}` - Find by phone
- `POST /api/v1/tenants` - Create tenant

#### Rooms
- `GET /api/v1/rooms` - List rooms with availability
- `GET /api/v1/rooms/available` - List available rooms only
- `GET /api/v1/rooms/{id}` - Get room details

#### Stays
- `GET /api/v1/stays` - List stays
- `GET /api/v1/stays/active` - List active stays
- `POST /api/v1/stays` - Create stay
- `PUT /api/v1/stays/{id}` - Update stay

#### Payments
- `GET /api/v1/payments` - List payments
- `POST /api/v1/payments` - Create payment
- `GET /api/v1/payments/{id}` - Get payment details

See [API_DOCUMENTATION.md](./API_DOCUMENTATION.md) for detailed API docs.

## 🎨 Design System

### Colors
- **Primary (Navy)**: `#042B60`
- **Warning (Yellow)**: `#FCB502`

### Icons
- Heroicons outline style
- Semantic icon selection

### Navigation
All resources grouped under "Management"

## 🗄 Database

### Main Tables
- `buildings` - Gedung kost
- `floors` - Lantai per gedung
- `rooms` - Kamar per lantai
- `tenants` - Data penyewa
- `stays` - Histori menginap
- `payments` - Pembayaran
- `payment_items` - Detail item pembayaran
- `expenses` - Pengeluaran

### Key Relationships
- Building → hasMany → Floors → hasMany → Rooms
- Tenant → hasMany → Stays → belongsTo → Room
- Tenant → hasMany → Payments → hasMany → PaymentItems
- Building → hasMany → Expenses

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter=TenantTest

# Run with coverage
php artisan test --coverage
```

## 📦 Artisan Commands

### Payment Reminders
```bash
php artisan reminders:payment
```
Sends payment reminders to tenants with overdue payments.

### Data Seeding
```bash
# Seed all
php artisan db:seed

# Seed specific
php artisan db:seed --class=BuildingSeeder
php artisan db:seed --class=TenantSeeder
```

### Code Formatting
```bash
vendor/bin/pint
```

## 📊 Excel Export

Export payments and expenses to Excel:

1. Navigate to Payments or Expenses table
2. Select records (optional)
3. Click "Export" button
4. Download Excel file

## 🔒 Security

- Username-based authentication
- Input validation on all forms
- WhatsApp number validation (Indonesian format)
- Authorization policies
- API authentication with Sanctum
- Secure file uploads
- CSRF protection

## 🚢 Deployment

See [DEPLOYMENT.md](./DEPLOYMENT.md) for production deployment guide.

### Quick Production Setup
```bash
composer install --optimize-autoloader --no-dev
npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 🤝 Bot Integration

The web app provides REST API for WhatsApp Bot integration:

1. Generate API token for bot user
2. Use token in bot's API calls
3. Bot can:
   - Search tenants by phone
   - Check room availability
   - Create payments
   - View active stays

## 📝 License

Proprietary - Kost Sumber Rezeki

## 👥 Credits

Built for Kost Sumber Rezeki management system.
