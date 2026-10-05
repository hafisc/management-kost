# AGENTS.md — Management Kost

## Project
Management Kost is the management system for Kost Sumber Rezeki.
Bot: Bot Management Kost.

## Stack
Web: Laravel 13, Vue.js, Tailwind CSS, PHP, MySQL.
Bot: Node.js, JavaScript/TypeScript, WhatsApp Web integration.
Infrastructure: web hosting for Laravel and VPS for the bot. Pterodactyl may manage the bot process.

Do NOT introduce Next.js, SQLite, Prisma, or another primary backend unless explicitly requested.

## Architecture
Laravel is the single source of truth.

WhatsApp -> Bot Management Kost -> Laravel REST API -> MySQL

The bot MUST NOT connect directly to MySQL. Core business rules belong to Laravel.

## Before Coding
Read:
1. AGENTS.md
2. ROLE_AI.md
3. PRD.md
4. DESIGN.md
5. ARCHITECTURE.md
6. DATABASE.md
7. API.md
8. SECURITY.md
9. WHATSAPP.md
10. DEPLOYMENT.md

Inspect the existing repository before changing code.

## Engineering
Priorities:
1. Correctness
2. Security
3. Data integrity
4. Maintainability
5. Reliability
6. UX
7. Performance
8. Scalability

Prefer simple, explicit, Laravel-conventional solutions. Do not overengineer.

## Laravel
Use appropriate separation:
- Models: data and relationships
- Requests: validation
- Policies: authorization
- Services/Actions: reusable business logic
- Controllers: thin orchestration
- API Resources: API responses
- Vue Components/Pages: admin UI
- Jobs: asynchronous work
- Commands: CLI
- Notifications: notifications

Do not put large business logic in controllers or Vue components.

## Database
Use MySQL with foreign keys, indexes, unique constraints, transactions and migrations.
Preserve financial and stay history.
Historical invoice prices must be snapshots.
Room availability should be derived from active stays, not duplicated manual state.

## WhatsApp
The bot authenticates by QR scan, stores its session securely, calls Laravel API, and must not duplicate billing or occupancy logic.
Prevent duplicate reminders with idempotency.

## UI
The interface must NOT look AI-generated or like a generic admin template.
Do not use decorative emojis, emoji-heavy UI, excessive gradients, excessive glassmorphism, random icons, excessive cards, or unnecessary animation.
Follow DESIGN.md.

## Security
Never commit .env, passwords, API keys, tokens, private keys or WhatsApp sessions.
Validate all external input. Enforce authentication and authorization. Verify webhooks when supported. Secure file uploads. Do not expose internal exceptions.

## Testing
For substantial work run formatter, tests and relevant checks. Test important edge cases, authorization and data integrity.

## Git
Use meaningful commits, e.g.:
feat: add tenant management
fix: prevent duplicate reminder
refactor: extract billing service
security: validate whatsapp webhook

## Completion Report
Report:
Implemented:
Files changed:
Database:
API:
Security:
Testing:
Notes:

Never claim a mocked or incomplete feature is finished.
