# DEPLOYMENT — Management Kost

## Components
Web: Laravel 13 + Filament.
Database: MySQL.
Bot: Node.js on VPS.
Domain: kostkampunginggris.com
Possible admin subdomain: admin.kostkampunginggris.com

## Web
Run Laravel behind HTTPS. Never expose .env, source or database.

## Bot
Run on VPS. Pterodactyl may manage the process.
Bot needs persistent session storage, environment variables, logs and API access.

## Environment
Laravel:
APP_ENV=production
APP_DEBUG=false
APP_URL=
DB_CONNECTION=mysql
DB_HOST=
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

Bot:
NODE_ENV=production
API_BASE_URL=
BOT_WHATSAPP_NUMBER=6287778395100
ADMIN_WHATSAPP_NUMBER=6282131964839
BOT_API_TOKEN=

Never commit real values.

## Scheduler/Queue
Run Laravel scheduler through cron.
Run queue workers continuously when queues are used.

## Health
Provide a safe health check without exposing secrets.

## Logs
Rotate logs.

## Backup
Automate MySQL backup and store separately. Test restoration.

## Deployment
Pull -> install dependencies -> tests -> build -> migrations -> cache/config as appropriate -> restart workers -> health check.

## Rollback
Document rollback and handle destructive migrations carefully.
