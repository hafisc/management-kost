# Management Kost

Digital management system for Kost Sumber Rezeki.

## Applications
web/ = Laravel 13 + Filament + MySQL
bot/ = Bot Management Kost + WhatsApp

## Architecture
WhatsApp -> Bot Management Kost -> Laravel API -> MySQL

There is only one application database. The bot never connects directly to MySQL.

## Documentation Order
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
11. CHANGELOG.md

## Goals
Tenant management, room management, stay tracking, payments, additional charges, reminders, WhatsApp communication, expenses, financial reports, Excel exports and audit history.

## Principle
Keep it simple, secure, maintainable and production-ready.
