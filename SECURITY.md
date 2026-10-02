# SECURITY — Management Kost

Tenant data, WhatsApp numbers and financial information are sensitive operational data.

## Secrets
Never commit .env, API keys, passwords, tokens, private keys or WhatsApp sessions.
Use environment variables.

## Authentication
Use secure Laravel/Filament authentication for admins.
Use dedicated API authentication for the bot.

## Authorization
Check permissions for tenant, payment, expense, pricing, admin and settings operations.

## Validation
Validate all HTTP, API, WhatsApp, query and file inputs.

## File Uploads
For payment proofs:
- validate MIME/type
- limit size
- safe generated paths
- do not trust filenames
- restrict access

## WhatsApp
Verify provider webhook signatures/tokens when available.
Do not trust sender information without validation.

## API
Use HTTPS.
Use rate limits.
Do not expose MySQL publicly.
Bot never connects directly to MySQL.

## SQL
Use Eloquent/query builder/parameterized queries.
Never build raw SQL from untrusted input.

## XSS/CSRF
Use Laravel escaping and standard CSRF protection where applicable.

## Logging
Never log passwords, tokens, API keys, session credentials or payment credentials.

## Audit
Audit price changes, payment verification, checkout, settings and admin changes.

## Backup
Automate MySQL backups, store separately and test restoration.

## Production
HTTPS, APP_DEBUG=false, private database, least privilege, firewall, backup and monitoring.
