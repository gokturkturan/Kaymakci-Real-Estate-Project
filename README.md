# Kaymakci Real Estate — Booking Platform

Production website and booking system for **Kaymakci Real Estate GmbH**, a provider of furnished short- and mid-term rental apartments in the Rhine-Main area (Frankfurt, Offenbach, Hanau).

🌐 **Live:** [kaymakcirealestate.de](https://kaymakcirealestate.de)

Guests browse and filter apartments, see availability on a calendar and send booking requests in five languages. The owner manages properties, media and bookings through an admin panel, and every booking decision triggers an email to the guest in the guest's own language.

![Laravel](https://img.shields.io/badge/Laravel_12-FF2D20?logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP_8.2-777BB4?logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?logo=mysql&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-06B6D4?logo=tailwindcss&logoColor=white)
![Vite](https://img.shields.io/badge/Vite-646CFF?logo=vite&logoColor=white)

---

## Features

### For guests
- **Property listing** with filters for location, price, bedrooms, bathrooms, area and parking, with paginated results
- **Property detail pages** with an image/video gallery, an interactive map (Leaflet + OpenStreetMap) and SEO-friendly slugs (`/immobilie/{slug}`)
- **Availability calendar** (Flatpickr) that blocks dates already booked
- **Booking requests** with validation, overlap checks and server-side price calculation (`nights × guests × price per person per night`)
- **Five languages**: German, English, Polish, Slovak and Romanian, switchable on every page and stored in the session
- **Contact form**, About page, and fully localized validation messages and emails

### For the owner (admin panel)
- Session-based admin login protected by a custom `admin` middleware
- **Property management** (CRUD) with a rich-text description editor (Quill), multiple image and video uploads, and drag-and-drop ordering of mixed media
- **Automatic geocoding** of the address via the OpenStreetMap Nominatim API, so the map pin is set without manual coordinates
- **Automatic translation** of titles and descriptions from German into the four other languages (see [Design notes](#design-notes))
- **Booking management**: dashboard with status counters, filters by status and date range, and approve, reject (with an optional reason) or delete actions
- **Email notifications**: the owner is notified of each new request (reply-to is set to the guest), and the guest gets an approval or rejection email in the language they booked in

### SEO
- Dynamic `sitemap.xml` generated from the current properties
- `robots.txt`, Open Graph tags and a localized `og:locale`
- **schema.org JSON-LD** structured data for the organization and for each property

## Tech stack

| Area | Technology |
| --- | --- |
| Backend | Laravel 12, PHP 8.2, Eloquent ORM |
| Database | MySQL / MariaDB |
| Frontend | Blade templates, Tailwind CSS 4, Vite |
| UI libraries | Leaflet (maps), Flatpickr (date picker), Quill (rich-text editor) |
| External APIs | OpenStreetMap Nominatim (geocoding), MyMemory (machine translation) |
| Email | Laravel Mail over SMTP |

## Design notes

**Booking integrity.** A booking request is rejected if its date range overlaps any *pending* or *approved* booking for the same property. Pending requests count as well, so two guests cannot request the same nights while the owner is still deciding. The public availability calendar shows only *approved* bookings. The total price is calculated on the server and stored together with the per-night rate at the moment of booking, so later price changes never alter existing bookings.

**Translation pipeline.** The owner writes content in German only. When a property is saved, `PropertyTranslator` translates the title and description into EN, PL, SK and RO and stores the results in dedicated columns. Visitors therefore never trigger an API call, and pages render from the database. The pipeline:
- re-translates only fields whose German source actually changed,
- splits long texts into chunks under the API's ~500-byte limit, at paragraph boundaries first, then at sentence and word boundaries,
- detects quota warnings that the API returns inside an HTTP 200 response,
- discards the whole translation if any chunk fails, so no half-translated text is ever saved.

Rich-text formatting is kept only in the German source. Inline tags were not translated reliably, so the other languages are stored as plain text: always correct, never broken markup.

**XSS-safe rich text.** Descriptions from the editor are filtered against an allow-list of tags, and every attribute is removed except a validated `font-size` style on `<span>`. Event handlers such as `onclick` and arbitrary CSS therefore cannot reach the public pages.

**Resilient side effects.** Emails, geocoding and translation all fail softly: errors are logged, and the main action (saving a booking or a property) still succeeds.

## Routes overview

| Method | Path | Description |
| --- | --- | --- |
| GET | `/` | Property listing with filters |
| GET | `/immobilie/{slug}` | Property detail page |
| GET | `/immobilie/{slug}/buchungen` | Booked dates as JSON, for the calendar |
| POST | `/immobilie/{slug}/buchen` | Submit a booking request |
| GET | `/ueber-uns` · `/kontakt` | About and contact pages |
| POST | `/kontakt` | Send the contact form |
| GET | `/sprache/{locale}` | Switch language (`de`, `en`, `pl`, `sk`, `ro`) |
| GET | `/sitemap.xml` | XML sitemap |
| — | `/admin/*` | Admin panel: dashboard, properties, bookings |

## Getting started

**Prerequisites:** PHP 8.2+, Composer, Node.js 20+, MySQL or MariaDB

```bash
# 1. Install dependencies, create .env, generate the app key, run migrations and build assets
composer run setup

# 2. Configure the database and SMTP settings in .env, then migrate again if needed
php artisan migrate

# 3. (Optional) Seed sample properties and an admin user
php artisan db:seed

# 4. Make uploaded media publicly accessible
php artisan storage:link

# 5. Start the app, queue worker, log viewer and Vite together
composer run dev
```

The site runs at http://localhost:8000 and the admin panel at http://localhost:8000/admin.

> **Note:** The admin booking list uses MySQL's `FIELD()` function for status sorting, so use MySQL or MariaDB rather than the SQLite default in `.env.example`.

## Project structure

```
app/
├── Http/Controllers/          # public pages, properties, bookings, locale, sitemap
│   └── Admin/                 # admin auth, dashboard, property and booking management
├── Http/Middleware/           # AdminMiddleware, SetLocale
├── Models/                    # Property, PropertyImage, PropertyVideo, Booking, User
└── Support/
    ├── BookingMailer.php      # owner and guest notification emails, localized
    └── PropertyTranslator.php # automatic DE → EN/PL/SK/RO translation
database/migrations/           # schema history
lang/{de,en,pl,sk,ro}/         # UI translations
resources/views/               # Blade templates (public and admin)
routes/web.php
```

## Author

Designed and developed by **Göktürk Turan** · [gokturkturan.com](https://gokturkturan.com) · [LinkedIn](https://www.linkedin.com/in/gokturkturan/)
