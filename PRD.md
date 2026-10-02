# PRD — Management Kost

## Product
Management Kost is a digital system for Kost Sumber Rezeki.

Components:
- Laravel 13 web application
- Filament admin panel
- MySQL
- REST API
- Bot Management Kost
- WhatsApp reminders
- Payment management
- Expense management
- Reports
- Excel exports

## Property
Putri: approximately 35 rooms, 3 floors.
Putra: 3 rooms in a separate building beside the putri building.
Exact room configuration must remain editable.

## Current Pricing
- Single: Rp600.000
- Sharing: Rp350.000 per person

These values MUST NOT be hardcoded. Admin must be able to change them.

Supported stay periods:
- daily
- several days
- weekly
- several weeks
- monthly
- custom period where needed

## Tenant
Main fields:
- full name
- WhatsApp number
- building
- floor
- room
- occupancy arrangement
- check-in
- stay end
- status
- notes
- timestamps

The main tenant record must have notes. Important structured information must not exist only inside notes.

## Rooms
Each room:
- building
- floor
- room number
- capacity
- notes
- active/inactive

Shared rooms are supported.

## Stay
A tenant can have many historical stays.
A stay has:
- tenant
- room
- start
- end
- duration type/value
- occupancy type
- status
- notes

Never overwrite historical stays.

## Additional Charges
Admin CRUD:
- Magicom
- Catokan
- Kulkas
- other electricity/equipment charges

Charges have name, description, amount, billing type and active state.
Historical invoices keep their charged price.

## Payments
Track:
- invoice
- tenant
- stay
- payment items
- total
- status
- method
- paid date
- proof
- verifier
- notes

Statuses may include Pending, Paid, Failed, Expired, Cancelled and future Refunded.

## Payment Information
Admin can configure QRIS, bank account and payment instructions.
QRIS image must be replaceable without code changes.

## Reminder
Default:
- H-2
- H-1
- H

Reminder stops after renewal or checkout.
Reminder processing must be idempotent.

## WhatsApp Flow
Stay nearing end -> Laravel scheduler -> reminder -> Bot -> tenant chooses Continue/Not Continue.

Continue:
Bot requests Laravel calculation -> Laravel creates invoice -> Bot sends payment information -> payment verification -> Laravel extends stay.

Not Continue:
Bot records intent -> sends private admin notification -> admin processes checkout.

## Numbers
Bot: 6287778395100
Admin: 6282131964839

Store these in environment/configuration, not throughout source code.

## Expenses
Admin can record:
- electricity
- WiFi
- water
- maintenance
- cleaning
- supplies
- other operational expenses

Fields:
category, amount, date, description, proof, created by.

## Financial Report
Monthly:
Income
Expenses
Net Income

Net income = valid paid income - expenses.

Filters may include month, year, building, category and payment status.

## Excel
Export:
- tenants
- rooms/occupancy
- stays
- payments
- income
- expenses
- financial summary
- checkout records

## Dashboard
Show operationally useful information:
- rooms
- occupancy
- active tenants
- expiring stays
- pending payments
- income
- expenses
- net income
- recent activity

Avoid excessive KPI cards.

## Future WiFi
Possible future integration:
- internet status
- router status
- AP status
- connected clients
- bandwidth
- outage alerts

Keep it separate from tenant/payment domain logic.

## Roles
Initial:
- Super Admin
- Admin

## Audit
Audit important price, payment, checkout, settings and admin changes.
