# WHATSAPP — Bot Management Kost

## Identity
Bot: Bot Management Kost
Business: Kost Sumber Rezeki
Bot number: 6287778395100
Admin number: 6282131964839

Numbers must be configurable, not scattered through code.

## QR Connection
Start -> initialize -> check session -> if unauthenticated show QR -> scan via WhatsApp Linked Devices -> connected.
Persist session securely.

## Message Style
Clear, short, professional and friendly.
Do not use emoji-heavy messages.

## Tenant Identification
Match WhatsApp number against registered tenant data.
If not registered, instruct the person to contact admin.

## Reminder
H-2, H-1 and H.
Reminder ends after renewal or checkout.

## Continue
Tenant selects Lanjut -> Bot calls Laravel -> Laravel calculates invoice -> Bot displays invoice -> tenant pays -> verification -> Laravel extends stay.

## Not Continue
Bot records intent and sends private admin notification:
BOT MANAGEMENT KOST

Penghuni tidak melanjutkan masa kost.

Nama: {name}
WhatsApp: {whatsapp}
Gedung: {building}
Kamar: {room}
Berakhir: {end_date}

Silakan proses checkout melalui Management Kost.

## Payment
Bot displays QRIS, bank details, invoice and instructions from Laravel.

## Payment Proof
Tenant sends proof -> bot uploads to Laravel -> pending verification -> admin notification -> approve/reject.

## Groups
Putra/Putri groups may be used for general announcements.
Never expose private invoices, amounts, proofs or notes to groups.

## Failure
Log disconnects, retry safely and notify admin after persistent failure.
Prevent duplicate messages after reconnect.

## Idempotency
Reminder key should identify tenant + stay + reminder type + date.
