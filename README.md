# The FPA Event App

todo: add a description

## Feature List

- Homepage
- Event List
    - List all upcoming events
- Event Archive
    - List all past events, grouped by year
- Event Detail
    - Logged-in Users can select "Attending"
    - Information about the event (Date, Location, Count of users attending)
    - Tab for Event Description
    - Tab for Event Schedule
- Event Configuration
    - Event admins can create a schedule for the event
    - Event creator can create a magic link to invite others to organize the event
-

## Documentation

## Dev Setup

Install dependencies,
```shell
composer install
npm install
```
Create env file, generate key and run migrations with seeders
```shell
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
```

### While developing
Run the local development server and the frontend build process
```shell
php artisan serve
npm run dev
```
Or use the solo package
```shell
php artisan solo
```

Refresh the database and run seeders again
```shell
php artisan migrate:fresh --seed
```
