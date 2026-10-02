# DATABASE — Management Kost

## Database
MySQL. Laravel is the only direct database client.

## Main Tables
users
buildings
floors
rooms
tenants
stays
additional_charges
tenant_additional_charges
payments
payment_items
payment_proofs
expense_categories
expenses
reminders
notifications
settings
audit_logs

## Users
id, name, email, password, role/permissions, is_active, timestamps.

## Buildings
id, name, code, description, is_active, timestamps.

## Floors
id, building_id, floor_number, name, timestamps.

## Rooms
id, building_id, floor_id, room_number, capacity, notes, is_active, timestamps.
Recommended unique: building_id + room_number.

## Tenants
id, full_name, whatsapp_number, notes, status, timestamps.
Do not use current room as the primary historical source.

## Stays
id, tenant_id, room_id, start_at, end_at, duration_type, duration_value, occupancy_type, status, notes, timestamps.
One tenant can have many stays.

## Additional Charges
id, name, description, amount, billing_type, is_active, timestamps.

## Tenant Additional Charges
id, tenant_id, additional_charge_id, quantity, start_at, end_at, amount_snapshot, status, notes, timestamps.

## Payments
id, tenant_id, stay_id, invoice_number, total_amount, status, payment_method, paid_at, verified_by, notes, timestamps.
Invoice number must be unique.

## Payment Items
id, payment_id, name, item_type, quantity, unit_price, subtotal, reference_type, reference_id, timestamps.
unit_price is the historical snapshot.

## Payment Proofs
id, payment_id, file_path, original_name, mime_type, file_size, uploaded_at, verified_at, verified_by, status, timestamps.

## Expenses
expense_categories: id, name, description, is_active, timestamps.
expenses: id, expense_category_id, amount, expense_date, description, proof_path, created_by, timestamps.

## Reminders
id, tenant_id, stay_id, type, scheduled_at, sent_at, status, event_key, error_message, timestamps.
event_key supports idempotency.

## Notifications
id, recipient, channel, type, payload, status, scheduled_at, sent_at, error_message, timestamps.

## Settings
Business name, admin WhatsApp, bot number, QRIS, bank information and reminder configuration.
Do not store high-risk secrets as ordinary settings.

## Audit Logs
id, user_id, action, entity_type, entity_id, old_values, new_values, ip_address, user_agent, created_at.
Never store secrets.

## Indexes
Consider indexes on WhatsApp number, tenant/room/status/end date, payment status/paid date, expense date, reminder schedule/event key.

## Integrity
Use foreign keys, constraints, transactions and migrations.
Never let current pricing rewrite historical payment data.

## Transactions
Use DB transactions for invoice creation, payment confirmation/renewal and checkout operations that require atomic updates.
