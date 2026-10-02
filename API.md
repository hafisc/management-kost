# API — Management Kost

## Base
/api/v1

## Rules
Private endpoint:
Authentication -> Authorization -> Validation -> Business Logic -> Response.

## Admin Examples
GET/POST/PATCH /api/v1/tenants
GET /api/v1/buildings
GET /api/v1/floors
GET /api/v1/rooms
GET/POST /api/v1/stays
POST /api/v1/stays/{stay}/checkout
POST /api/v1/stays/{stay}/renew
GET/POST /api/v1/payments
GET/POST /api/v1/expenses
GET /api/v1/reports

Implement only when needed.

## Bot Examples
/api/v1/bot/profile
/api/v1/bot/stay
/api/v1/bot/reminders
/api/v1/bot/reminders/{reminder}/respond
/api/v1/bot/invoices/{invoice}
/api/v1/bot/payments/{payment}/proof

Bot endpoints must be limited in scope.

## Authentication
Bot uses a dedicated credential. Never use an admin password/session or database credential.

## Response
Success:
{
  "success": true,
  "data": {},
  "message": "Success"
}

Error:
{
  "success": false,
  "message": "Unable to process request",
  "errors": {}
}

Never expose stack traces.

## Validation
Use Laravel validation/Form Requests.

## Rate Limiting
Apply where appropriate, especially authentication, public endpoints, bot actions and file uploads.

## Versioning
Breaking API changes require a new version.
