# DESIGN SYSTEM — Management Kost

## Goal
The UI must feel custom-built, professional, calm, modern and operational.
It must not look like an AI-generated dashboard or copied admin template.

Avoid:
- decorative emojis
- emoji-heavy UI
- excessive gradients
- excessive glassmorphism
- random icons
- excessive cards
- unnecessary animation

## Brand
Business: Kost Sumber Rezeki
Application: Management Kost
Bot: Bot Management Kost

Use the provided Kost Sumber Rezeki logo as the brand reference.

## Colors
Primary Navy: #042B60
Accent Yellow: #FCB502
Background: #F8FAFC
White: #FFFFFF
Text: #0F172A
Muted: #64748B
Border: #E2E8F0
Success: #16A34A
Warning: #D97706
Danger: #DC2626
Info: #2563EB

Navy is dominant. Yellow is an accent, not a page-wide background.

## Typography
Use one modern sans-serif family with clear hierarchy.
Prioritize readability over decoration.

## Layout
Desktop: sidebar, header, main content, comfortable spacing.
Mobile: responsive navigation, usable tables/forms, accessible actions.

## Navigation
Dashboard
Penghuni
Kamar
Masa Tinggal
Pembayaran
Pengeluaran
Laporan
Pengaturan
  Harga Kost
  Biaya Tambahan
  Pembayaran
  WhatsApp
  Admin

## Dashboard
Hierarchy:
1. page header
2. compact key metrics
3. urgent items
4. expiring tenants
5. payment activity
6. expense activity
7. operational activity

## Tables
Search, filters, sorting, pagination and clear actions.
Avoid unnecessary columns.

## Forms
Clear labels, logical sections, validation and meaningful errors.
Use confirmation for destructive actions.

## Status
Always show text:
ACTIVE
EXPIRING
PENDING
PAID
CHECKED OUT
FAILED

Color supports the text; it must not be the only indicator.

## Icons
Use one consistent icon family.
Do not use emoji as interface icons.

## WhatsApp
Keep messages concise and professional.

Example:
BOT MANAGEMENT KOST
Kost Sumber Rezeki

Halo, {name}.

Masa kost Anda akan berakhir pada
30 September 2026.

DETAIL
Kamar       : P-12
Periode     : 01 - 30 September 2026

Apakah Anda ingin melanjutkan?

[ Lanjut Kost ]
[ Tidak Lanjut ]

## Payment Message
BOT MANAGEMENT KOST
Kost Sumber Rezeki

Perpanjangan Masa Kost

Kamar: P-12
Periode: 01 - 31 Oktober 2026

RINCIAN
Kost          Rp600.000
Magicom        Rp25.000
Catokan        Rp15.000
-------------------------
TOTAL         Rp640.000

[ QRIS ]
[ Transfer Bank ]

Actual values always come from Laravel.

## Terminal
Clean and informative, without excessive ASCII art:
BOT MANAGEMENT KOST
Kost Sumber Rezeki

Environment : production
API         : connected
WhatsApp    : connected
Scheduler   : running

Bot Number  : 6287778395100

Bot siap digunakan.
