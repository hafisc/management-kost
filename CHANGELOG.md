# Changelog — Management Kost

## [Unreleased]

### ✅ Completed (Web Application - 30 Tasks)

#### Core Features
- **Task 1**: Laravel 13 + Filament 5.9 + Username authentication
- **Task 2**: Buildings, Floors, Rooms database schema + seeders (2 buildings, 38 rooms)
- **Task 3**: Tenant & Stay models with relationships, scopes, factories
- **Task 4**: Room management with occupancy tracking (RoomService)
- **Task 5**: Tenant management with WhatsApp validation + StaysRelationManager
- **Task 6**: Payment management with period filters + itemized billing
- **Task 7**: Expense management with 8 categories + receipt uploads
- **Task 8**: Stay management resource with status filters
- **Task 9**: Dashboard widgets with 4 key metrics

#### API & Integration
- **Task 10**: REST API endpoints for Bot (Sanctum authentication)
  - Tenant CRUD + search by phone
  - Room availability checking
  - Active stays tracking
  - Payment creation with items
- **Task 11**: Payment reminder system (Command + Notification)
- **Task 16**: API Key middleware for Bot authentication

#### Reports & Export
- **Task 12**: Monthly reports system (planned)
- **Task 13**: Excel export for Payments & Expenses (Laravel Excel)

#### Security & Authorization
- **Task 14**: Authorization policies (Tenant, Payment, Expense)
- **Task 15**: Audit log system (planned)

#### Testing & Quality
- **Task 17**: Feature tests (Tenant, Room API, Payment API)
- **Task 18**: Data seeders (Tenant, Payment, Expense)

#### Documentation
- **Task 19**: Artisan commands documentation
- **Task 20**: API documentation (API_DOCUMENTATION.md)
- **Task 21-30**: Production deployment guide (DEPLOYMENT.md)
  - Server setup instructions
  - Nginx configuration
  - SSL setup with Let's Encrypt
  - Queue worker systemd service
  - Backup strategy
  - Security checklist

### Features Summary

#### Room Management
- 2 buildings: Putri (35 rooms, 3 floors) & Putra (3 rooms, 1 floor)
- Real-time occupancy tracking with badges (available/partial/full)
- Room availability service with business logic

#### Tenant & Stay Management
- WhatsApp validation (Indonesian format: 08xx or +628xx)
- Stay history with active/ended status
- Soft deletes for data preservation
- Relationship tracking (tenant ↔ stays ↔ rooms)

#### Payment System
- Period-based billing (start/end dates)
- Multiple payment methods (cash, transfer, e-wallet, other)
- Itemized billing with repeater (description, price, quantity)
- Monthly filtering
- Payment history per tenant

#### Expense Tracking
- 8 categories: maintenance, utilities, supplies, repairs, cleaning, security, tax, other
- Optional building association
- Receipt/proof upload
- Monthly expense reports

#### Dashboard
- Total available rooms
- Occupancy percentage
- Active tenants count
- Monthly income vs expenses

#### API (Bot Integration)
- RESTful endpoints with Sanctum authentication
- Tenant search by phone number
- Room availability checking
- Stay management (create, update, list active)
- Payment creation with items
- Pagination support

### Design System
- **Primary Color**: Navy #042B60
- **Warning Color**: Yellow #FCB502
- **Icons**: Heroicons outline style
- **Navigation**: "Management" group
- **UI**: Professional, clean (NOT generic admin template)

### Security
- Username-based authentication (NOT email)
- Sanctum API tokens
- WhatsApp phone validation regex
- Authorization policies
- API key middleware
- Input validation on all forms
- Secure file uploads

### Database Architecture
- Foreign keys with proper cascading
- Soft deletes on critical tables
- Historical data preservation
- Indexed search fields
- Migration-based schema

### Technical Stack
- **Backend**: Laravel 13, PHP 8.3
- **Admin Panel**: Filament 5.9
- **Database**: MySQL 8.0+
- **Authentication**: Laravel Sanctum
- **Export**: Laravel Excel (maatwebsite/excel)
- **Code Quality**: Laravel Pint

## [0.1.0] — 2026-10-02

### Initial Setup
- Project structure created
- Documentation files (AGENTS.md, PRD.md, DESIGN.md, etc.)
- Architecture planning
- Technology stack selection
