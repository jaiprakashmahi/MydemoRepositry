# GPS Tracking Timeline — Laravel 10 module

Daily GPS journey history for field employees: total distance (km), travel
time, places visited, trip count, and a visual timeline (map + stop list)
matching the attached UI.

## What's in this package

```
app/Models/Employee.php
app/Models/LocationLog.php
app/Services/JourneyTimelineService.php     <- distance/timeline calculation engine
app/Http/Controllers/TrackingTimelineController.php
database/migrations/2024_01_01_000001_create_employees_table.php
database/migrations/2024_01_01_000002_create_location_logs_table.php
database/seeders/JourneyTimelineSeeder.php  <- Raj Kumar example from the spec
resources/views/tracking/timeline.blade.php <- the UI (Tailwind + Leaflet)
routes/web.php                              <- one route to add
```

## 1. Create the Laravel project (network access is required for this step)

```bash
composer create-project laravel/laravel:^10.10 gps-tracking-timeline
cd gps-tracking-timeline
```

## 2. Copy these files in

Copy each folder from this package into the matching folder of your new
Laravel project (merge, don't overwrite `routes/web.php` — just append the
one `Route::get(...)` line into it).

## 3. Configure the database

In `.env`:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gps_tracking
DB_USERNAME=root
DB_PASSWORD=
```

Create the database:

```sql
CREATE DATABASE gps_tracking;
```

## 4. Register the seeder

In `database/seeders/DatabaseSeeder.php`:

```php
public function run(): void
{
    $this->call(\Database\Seeders\JourneyTimelineSeeder::class);
}
```

## 5. Migrate and seed

```bash
php artisan migrate
php artisan db:seed
```

This creates two employees (Raj Kumar, Deepak Kumar) and seeds a full day
of realistic GPS pings for Raj Kumar following the exact journey from the
spec (Home → Office → Customer A → Customer B → Lunch → Customer C →
Office/End), plus a single ping for Deepak Kumar to reproduce the "0.00 KM"
empty state shown in the screenshot.

## 6. Run it

```bash
php artisan serve
```

Visit `http://127.0.0.1:8000/tracking-timeline`.

## How the numbers are calculated (`JourneyTimelineService`)

Field devices/apps should push raw GPS pings (lat, lng, timestamp) into
`location_logs` every 1–5 minutes throughout the day (typically via a
background job / mobile app / Laravel API endpoint you point your tracking
app at). The service then, per employee per day:

1. **Clusters consecutive pings into "stops"** — any run of pings that stay
   within ~150m of each other for ≥8 minutes is treated as a place visited
   (Office, Customer Location, Lunch, etc.). The first and last cluster of
   the day are always kept as the Start/End point even if brief.
2. **Total Distance** — sums the Haversine (great-circle) distance between
   every consecutive raw ping for the day, converted to km.
3. **Travel Time** — sums the time between *leaving* one stop and
   *arriving* at the next, across all stop-to-stop segments (i.e. time
   actually spent moving, not counting time spent stationary at a stop).
4. **Places Visited** — count of detected stops.
5. **Trips** — count of stop-to-stop movement segments (`stops - 1`).

Tune `STOP_RADIUS_METERS` and `MIN_STOP_MINUTES` in the service to match how
tightly-spaced your customer locations are and how long a "real" stop
should be before it counts as a place.

## Feeding it real data

To ingest live pings from a mobile tracking app, add a simple API endpoint,
e.g.:

```php
Route::post('/api/location-logs', function (Request $request) {
    $request->validate([
        'employee_id' => 'required|exists:employees,id',
        'latitude' => 'required|numeric',
        'longitude' => 'required|numeric',
        'recorded_at' => 'required|date',
    ]);

    \App\Models\LocationLog::create($request->only(
        'employee_id', 'latitude', 'longitude', 'recorded_at'
    ));

    return response()->json(['status' => 'ok']);
});
```

Protect it with Sanctum/API-token auth in production, and have the mobile
app / tracking SDK POST to it every 1–5 minutes while on duty.

## Notes / production suggestions

- For large volumes of pings, consider a nightly job that pre-computes and
  caches each employee's daily summary (distance/time/stops) into a
  `daily_journey_summaries` table instead of recalculating on every page
  load.
- Reverse-geocode `place_name` on the device/ingestion side (e.g. via
  Google Geocoding API) so stops show friendly names like "Customer
  Location A" instead of raw coordinates — the service already prefers
  `place_name` when present and only falls back to generic labels.
- Swap the Leaflet/OpenStreetMap tiles in the Blade view for Google Maps if
  you need Street View / better India POI coverage — just swap the map init
  script, the `route`/`stops` JSON payload stays the same.
