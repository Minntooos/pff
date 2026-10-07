# PFF — Resource Sharing Platform

A Laravel application for uploading, organizing and sharing learning resources: documents, images and PDFs, with categories, tags, commenting, and both public and private sharing.

[![CI](https://github.com/Minntooos/pff/actions/workflows/ci.yml/badge.svg)](https://github.com/Minntooos/pff/actions/workflows/ci.yml)

## Features

- **Resources** — upload files and images, organize with categories, tags and sections, public or private visibility
- **PDF viewing** — in-browser reading with PSPDFKit, plus instant-save
- **Private share links** — generate tokenized links for non-public resources
- **Comments & likes** — threaded comments with edit history
- **Soft delete** — trash with restore instead of hard deletes
- **Notifications** — activity notifications via a scheduled artisan command
- **Admin panel** — approval workflow for submitted resources, reports management
- **Social login** — Google, Facebook, GitHub
- **AI image generation** — FLUX.1-schnell via HuggingFace, wired through `config('services.hf.key')`

## Stack

Laravel 12 · PHP 8.2 · Blade · PostgreSQL · Tailwind CSS · Vite · PSPDFKit

## Run with Docker (recommended)

The only requirement is Docker. The image builds the frontend assets, installs PHP
dependencies, waits for the database, runs migrations and serves the app on
`http://localhost:8080`:

```bash
docker compose up --build
```

Seed demo data (test users and sample content) once the app is up:

```bash
docker compose exec app php artisan db:seed
```

Demo accounts (from the seeder): `test@example.com` / `pass123` and
`admin@example.com` / `admin`.

## Run without Docker

Requirements: PHP 8.2+, Composer, Node.js, PostgreSQL.

```bash
cp .env.example .env        # fill in your DB credentials
composer install
php artisan key:generate
php artisan migrate
npm install && npm run build
php artisan serve
```

## Tests

The suite runs against an in-memory SQLite database and requires built frontend assets:

```bash
composer install
npm ci && npm run build
cp .env.example .env
php artisan test
```

CI runs the same steps (code style via Laravel Pint + PHPUnit) on every push —
see `.github/workflows/ci.yml`.

## Deploy to Render

The repo ships a Render Blueprint (`render.yaml`): one Docker web service plus a
free PostgreSQL database.

1. Push this repo to GitHub.
2. In Render: **New → Blueprint**, select the repo.
3. When prompted, provide `APP_KEY` (generate one with `php artisan key:generate --show`),
   the admin credentials, and optionally `HF_API_KEY`.
4. After the first deploy, set `APP_URL` to the service URL.

Note: the free PostgreSQL instance expires after 90 days — fine for a demo,
not for real data.

## Design notes

`docs/` holds diagrams and feature specs from the design phase.
