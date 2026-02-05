# UI42 Candidate Assignment – Slovak Cities (Nitra Region)

Simple Laravel web app that imports Slovak municipalities from e-obce.sk (Nitra region only), stores them in DB, geocodes them with Google Geocoding API, and provides a basic frontend with autocomplete search.

## Artisan Commands

- `php artisan data:import`
- `php artisan data:geocode`


## Tech Stack

- Laravel (PHP 7.1+ compatible; tested with newer PHP versions)
- Bootstrap (via npm)
- Google Geocoding API

## Setup

### 1) Install dependencies

```bash
composer install
npm install
```

### 2) Environment

Copy env and generate key:

```bash
cp .env.example .env
php artisan key:generate
```

Set database credentials in `.env` and add:

```
GOOGLE_GEOCODING_KEY=YOUR_GOOGLE_KEY
```

### 3) Migrate database

```bash
php artisan migrate
```

### 4) Import data

Imports all districts of Nitra region by default:

```bash
php artisan data:import
```

### 5) Geocode

Geocode only cities missing coordinates:

```bash
php artisan data:geocode 
```
### 6) Run the app

```bash
php artisan serve
npm run dev
```

Open:

```
http://127.0.0.1:8000
```

## Notes

- Autocomplete endpoint: `GET /search?term=...` returns top 10 matches.
- Coat of arms images are stored in `public/coats`.

---
