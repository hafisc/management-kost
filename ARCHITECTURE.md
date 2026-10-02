# ARCHITECTURE — Management Kost

## Overview

WhatsApp
  ↓
Bot Management Kost
  ↓ authenticated REST API
Laravel 13 + Filament
  ↓
MySQL

There is only one application database.
The bot never connects directly to MySQL.

## Web Responsibilities
Laravel owns:
- business logic
- database
- authentication
- authorization
- Filament
- REST API
- scheduler
- queues
- reports
- Excel exports

## Bot Responsibilities
Bot owns:
- WhatsApp connection
- QR authentication
- receiving/sending messages
- calling Laravel API
- conversation flow
- operational retry/connection handling

Bot does not own core business rules.

## Laravel Organization
Use normal Laravel structure. When justified:
app/
  Actions/
  Enums/
  Filament/
  Http/
    Controllers/
    Requests/
    Resources/
  Jobs/
  Models/
  Notifications/
  Policies/
  Services/
  Support/

Do not create folders only for appearance.

## Business Services
Possible:
TenantService
RoomService
StayService
BillingService
PaymentService
ReminderService
ExpenseService
ReportService

Create only when logic is reusable/complex.

## API
Version: /api/v1
Bot-specific endpoints: /api/v1/bot/*
Bot gets only required permissions.

## Scheduler
Laravel Scheduler detects expiring stays and creates/dispatches reminder work.
Queues handle retryable/long-running work.

## Idempotency
Reminder and payment operations must prevent duplicate processing.

## External Integrations
WhatsApp provider/library should be isolated behind a service/adapter where useful.

## Reports
Use dedicated report/query logic rather than duplicating calculations across UI components.

## Future WiFi
Network device -> monitoring adapter -> Laravel integration -> dashboard/WhatsApp alert.
Do not couple it to tenant/payment services.
