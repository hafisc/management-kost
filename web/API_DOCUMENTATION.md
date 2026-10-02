# API Documentation - Management Kost

## Authentication
All API endpoints require authentication using Laravel Sanctum.

### Headers
```
Authorization: Bearer {token}
Content-Type: application/json
```

## Endpoints

### Tenants

#### GET /api/v1/tenants
List all tenants with pagination.

**Query Parameters:**
- `search` - Search by name or phone

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "John Doe",
      "phone": "08123456789",
      "id_card_number": "1234567890123456"
    }
  ]
}
```

#### GET /api/v1/tenants/{id}
Get tenant details with stays.

#### GET /api/v1/tenants/phone/{phone}
Find tenant by phone number.

#### POST /api/v1/tenants
Create new tenant.

**Request:**
```json
{
  "name": "John Doe",
  "phone": "08123456789",
  "id_card_number": "1234567890123456"
}
```

### Rooms

#### GET /api/v1/rooms
List all active rooms with availability status.

#### GET /api/v1/rooms/available
List available rooms only.

#### GET /api/v1/rooms/{id}
Get room details with current tenants.

### Stays

#### GET /api/v1/stays
List stays with filters.

**Query Parameters:**
- `status` - Filter by status (active/ended)
- `room_id` - Filter by room

#### GET /api/v1/stays/active
Get all active stays.

#### POST /api/v1/stays
Create new stay.

**Request:**
```json
{
  "tenant_id": 1,
  "room_id": 1,
  "start_date": "2024-01-01",
  "end_date": null
}
```

#### PUT /api/v1/stays/{id}
Update stay (mainly to set end_date).

### Payments

#### GET /api/v1/payments
List payments with filters.

**Query Parameters:**
- `tenant_id` - Filter by tenant
- `month` - Filter by month (1-12)
- `year` - Filter by year

#### POST /api/v1/payments
Create new payment.

**Request:**
```json
{
  "tenant_id": 1,
  "period_start": "2024-01-01",
  "period_end": "2024-01-31",
  "payment_date": "2024-01-05",
  "amount": 1500000,
  "payment_method": "cash",
  "notes": "Pembayaran bulan Januari",
  "items": [
    {
      "description": "Sewa kamar",
      "price": 1000000,
      "quantity": 1
    },
    {
      "description": "Listrik",
      "price": 500000,
      "quantity": 1
    }
  ]
}
```

#### GET /api/v1/payments/{id}
Get payment details with items.

## Error Responses

```json
{
  "message": "Error message",
  "errors": {
    "field": ["Validation error"]
  }
}
```

## Rate Limiting
- 60 requests per minute per IP address
