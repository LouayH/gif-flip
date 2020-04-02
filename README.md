# GIF Browser Lumen API and Vue SPA

This web application uses GIPHY Public API to search within all GIFs they made available.

## Before we start

**All commands below should be executed in cli mode, inside application directory.**

**You should have `PHP >= 7.2.4`, `mysql`, `composer` and `npm` installed on your system, so you can execute below commands.**

**You can use `php artisan` commands with this Lumen project.**

**You should have empty mysql database ready to use with application.**

## Instructions

1. Run `composer install` to install required composer packages.
2. Rename `.env.example` to `.env` and set some required values like:
- Your mysql database information `DB_DATABASE`, `DB_USERNAME` and `DB_PASSWORD`, `DB_HOST` and `DB_PORT` if required.
- `JWT_SECRET` random string required for `jwt-auth` Package.
- `RECAPTCHA_SECRET_KEY` and `MIX_RECPATCHA_SITE_KEY` to enable `Google reCaptcha v3`.
- `GIPHY_API_KEY` to enable GIPHY API.
3. Run `php artisan migrate` to migrate all database migrations files.
4. Run `npm install` to install required npm dependencies.
5. Run `npm run dev` to compile Vue assets into public folder.
6. Run `php artisan serve` to start the application, it should be working on `http://localhost:8000` and that's it.

## Application Features

- Authentication System (Register with reCaptcha, Login, Logout).
- Search GIFs using GIPHY API, (Authentication not required).
- Results displayed in a Grid of Thumbnails Layout
- Fully animated GIF can be viewed in a LightBox Slideshow with navigation controls, Favorite Button and Copy Shorten-URL Button.
- Authenticated Users can List their Search History.
- Authenticated Users can Favorite their Beloved GIFs, and List All of Them.

## API Functions

`\app\Http\Controllers\AuthController.php`

**register**: store a new user (username, email and password), after reCaptcha test against bot scripting.

**login**: Login user and return JWT token.

**me**: Get authenticated user object.

**logout**: Logout user

**refresh**: Refresh JWT token for another 1 hour.

**respondWithToken**: Return JWT token with its expiry in seconds.

`\app\Http\Controllers\GifController.php`

**search**: Search for GIFs via GIPHY API (query_string and offset)
, save URLs of every GIF in database and encoded them, save a row in search history if there is an authenticated user.

**decodeURL**: Decode Shorten URL, get basic URL information from database and redirect user to it.

**history**: Return Search History for authenticated user.

**favorite**: Favorite GIF by authenticated user.

**unfavorite**: Unfavorite GIF by authenticated user.

`\app\ShortURL.php`

**encode**: Encode URL ID via base62 algorithm, and return encoded string.

## Shorten URL Algorithm (base62)

- URL goes into dedicated database table.
- Its unique id (integer, auto-increment start from 1000) encoded and sent with search results to SPA.
- All shortend URLs pass specific route `/g/{url}` where `url` is string to decode.
- The system will fetch URL information from database, and redirect user to it.
