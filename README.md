# PHP site — Mailer & SMTP setup

This folder contains the PHP port of the site and a small mailer helper.

Required environment variables
- `CONTACT_EMAIL` — address to receive contact form messages (default: `info@houseandhomesintoronto.com`)
- `SMTP_HOST` — SMTP server host (optional; if set, PHPMailer SMTP will be used)
- `SMTP_PORT` — SMTP port (default: `587`)
- `SMTP_USER` — SMTP username
- `SMTP_PASS` — SMTP password
- `SMTP_SECURE` — `tls` or `ssl` (optional)
- `SMTP_AUTH` — `true`/`false` (defaults to `true`)

Composer / PHPMailer (optional but recommended)
1. From the repository root run:

```bash
composer require phpmailer/phpmailer
```

2. The helper `php/lib/mailer.php` will attempt to load `vendor/autoload.php` and use PHPMailer if available; otherwise it falls back to PHP's `mail()`.

Local testing
- You can use PHP's built-in server to serve the `php/` folder for quick local testing:

```powershell
php -S localhost:8000 -t php/
```

- Then test the contact endpoint with `curl` (or the contact form):

```bash
curl -X POST -F "name=Test User" -F "email=test@example.com" -F "message=hello" http://localhost:8000/api/contact.php
```

Deployment notes
- Ensure environment variables are configured on your host (Hostinger/other) so SMTP or `CONTACT_EMAIL` are available to PHP.
- If you prefer using a transactional email provider for reliability, configure SMTP with Mailgun/SendGrid/SMTP relay and set the SMTP env vars.

If you want, I can add a minimal Composer `composer.json` under `php/` and run `composer require` for you (requires Composer installed). Next I will begin planning the MLS/DDF port.

DDF (MLS) quick test
--------------------
If you have DDF credentials you can quickly verify token and a small sample of listings with the debug script:

Environment variables required for DDF testing:
- `DDF_CLIENT_ID`
- `DDF_CLIENT_SECRET`
- `DDF_BASE_URL` (optional)
- `DDF_AUTH_URL` (optional)

Run the debug script directly:

```powershell
php php/ddf-debug.php
```

Or serve the `php/` folder and curl the endpoint:

```powershell
# from repo root
php -S localhost:8000 -t php/
# then in another shell
curl http://localhost:8000/ddf-debug.php
```

The script returns JSON with a token preview and up to 3 sample listings (or an error message if credentials are missing).
