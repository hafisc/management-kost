# Quick Start Guide - Management Kost

## 🚀 Get Started in 5 Minutes

### 1. Setup Database
```bash
mysql -u root -p
CREATE DATABASE management_kost;
EXIT;
```

### 2. Install & Configure
```bash
cd web
composer install
npm install
cp .env.example .env
php artisan key:generate
```

Edit `.env`:
```env
DB_DATABASE=management_kost
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 3. Migrate & Seed
```bash
php artisan migrate
php artisan db:seed
```

### 4. Build & Run
```bash
npm run build
php artisan serve
```

### 5. Login
- URL: http://127.0.0.1:8000/admin
- Username: `admin`
- Password: `sumberrezeki1515`

## 📱 Bot API Setup

### Generate Token
```bash
php artisan tinker
```
```php
$user = User::first();
$token = $user->createToken('bot-token')->plainTextToken;
// Copy token untuk bot
```

### Test API
```bash
curl -H "Authorization: Bearer YOUR_TOKEN" \
  http://127.0.0.1:8000/api/v1/rooms/available
```

## 📊 Key Features

### Admin Panel
- **Dashboard**: http://localhost:8000/admin
- **Buildings**: Kelola gedung & lantai
- **Rooms**: Kelola kamar + occupancy
- **Tenants**: Data penyewa + WhatsApp
- **Stays**: Histori menginap
- **Payments**: Pembayaran + itemisasi
- **Expenses**: Pengeluaran + kategori

### API Endpoints
- `GET /api/v1/rooms/available` - Kamar tersedia
- `GET /api/v1/tenants/phone/{phone}` - Cari penyewa
- `POST /api/v1/payments` - Catat pembayaran
- `GET /api/v1/stays/active` - Penyewa aktif

## 🔧 Common Commands

```bash
# Development
php artisan serve                    # Start server
npm run dev                          # Watch assets

# Database
php artisan migrate:fresh --seed     # Reset database
php artisan db:seed --class=X        # Seed specific

# Maintenance
php artisan reminders:payment        # Send reminders
vendor/bin/pint                      # Format code
php artisan test                     # Run tests

# Production
composer install --optimize-autoloader --no-dev
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 📚 Documentation

- **README**: `web/README.md` - Complete guide
- **API Docs**: `web/API_DOCUMENTATION.md` - API reference
- **Deployment**: `web/DEPLOYMENT.md` - Production setup
- **Completion Report**: `COMPLETION_REPORT.md` - Full details

## 🐛 Troubleshooting

### Database Connection Error
```bash
# Check MySQL running
sudo systemctl status mysql

# Verify credentials in .env
DB_DATABASE=management_kost
DB_USERNAME=root
DB_PASSWORD=
```

### Filament Assets Missing
```bash
npm install
npm run build
php artisan filament:assets
```

### API 401 Unauthorized
- Generate new token via tinker
- Check `Authorization: Bearer {token}` header
- Verify token in `personal_access_tokens` table

### Permission Errors
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

## 💡 Tips

1. **Use seed data**: BuildingSeeder creates 38 rooms ready to use
2. **API testing**: Use Postman or curl with Bearer token
3. **Excel export**: Select records then click "Export" button
4. **Dashboard refresh**: Stats update on page reload
5. **Room availability**: Automatically calculated from active stays

## 🎯 Next Steps

1. ✅ Web app is ready
2. 📱 Develop Bot Management Kost
3. 🔗 Integrate bot with API
4. 🚀 Deploy to production
5. 📊 Monitor & maintain

## 📞 Support

For issues or questions, refer to:
- Documentation files in `/web`
- COMPLETION_REPORT.md for detailed implementation
- AGENTS.md for project guidelines

---

**Version**: 1.0.0  
**Status**: Production Ready  
**Last Updated**: October 2, 2026
