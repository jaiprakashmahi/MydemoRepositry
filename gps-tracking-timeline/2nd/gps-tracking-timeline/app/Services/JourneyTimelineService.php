<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\LocationLog;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * Turns a day's worth of raw GPS pings for an employee into:
 *   - total distance travelled (km)
 *   - total travel time (minutes actually spent moving)
 *   - a list of "stops" (Home, Office, Customer Location A, Lunch, etc.)
 *   - number of trips (movement segments between stops)
 *   - the raw polyline of points, for drawing the route on the map
 */
class JourneyTimelineService
{
    /**
     * A GPS ping is considered part of the same "stop" if it stays within
     * this radius (in meters) of the stop's centroid.
     */
    protected const STOP_RADIUS_METERS = 150;

    /**
     * A cluster of pings only counts as a genuine "stop" (place visited) if
     * the employee stayed there for at least this many minutes. Short
     * clusters (e.g. stopped at a traffic light) are treated as part of
     * the trip in between two real stops.
     */
    protected const MIN_STOP_MINUTES = 8;

    public function build(Employee $employee, string $date): array
    {
        $day = Carbon::parse($date);

        /** @var Collection<int, LocationLog> $points */
        $points = LocationLog::query()
            ->where('employee_id', $employee->id)
            ->whereBetween('recorded_at', [$day->copy()->startOfDay(), $day->copy()->endOfDay()])
            ->orderBy('recorded_at')
            ->get();

        if ($points->isEmpty()) {
            return $this->emptyResult();
        }

        $stops = $this->detectStops($points);
        $totalDistanceKm = $this->totalDistanceKm($points);
        $travelTimeMinutes = $this->travelTimeMinutes($stops);
        $tripsCount = max(count($stops) - 1, 0);

        return [
            'total_distance_km' => round($totalDistanceKm, 2),
            'travel_time_minutes' => (int) round($travelTimeMinutes),
            'places_visited' => count($stops),
            'trips' => $tripsCount,
            'stops' => $stops,
            'route' => $points->map(fn (LocationLog $p) => [
                'lat' => $p->latitude,
                'lng' => $p->longitude,
                'recorded_at' => $p->recorded_at->format('h:i A'),
            ])->values()->all(),
        ];
    }

    protected function emptyResult(): array
    {
        return [
            'total_distance_km' => 0,
            'travel_time_minutes' => 0,
            'places_visited' => 0,
            'trips' => 0,
            'stops' => [],
            'route' => [],
        ];
    }

    /**
     * Cluster consecutive GPS pings into "stops": Home, Office, Customer A, etc.
     *
     * @param Collection<int, LocationLog> $points
     * @return array<int, array{name:string, lat:float, lng:float, arrival:Carbon, departure:Carbon, duration_minutes:int}>
     */
    protected function detectStops(Collection $points): array
    {
        $stops = [];
        $n = $points->count();
        $i = 0;

        while ($i < $n) {
            $clusterStart = $i;
            $clusterPoints = collect([$points[$i]]);
            $centroidLat = $points[$i]->latitude;
            $centroidLng = $points[$i]->longitude;

            $j = $i + 1;
            while ($j < $n) {
                $distance = $this->haversineMeters(
                    $centroidLat,
                    $centroidLng,
                    $points[$j]->latitude,
                    $points[$j]->longitude
                );

                if ($distance > self::STOP_RADIUS_METERS) {
                    break;
                }

                $clusterPoints->push($points[$j]);
                $centroidLat = $clusterPoints->avg('latitude');
                $centroidLng = $clusterPoints->avg('longitude');
                $j++;
            }

            /** @var LocationLog $first */
            $first = $clusterPoints->first();
            /** @var LocationLog $last */
            $last = $clusterPoints->last();

            $durationMinutes = $first->recorded_at->diffInMinutes($last->recorded_at);
            $isFirstOrLastPingOfDay = $clusterStart === 0 || $j === $n;

            // Always keep the very first and very last cluster (start / end
            // point of the day), even if brief. Otherwise only keep clusters
            // where the employee genuinely dwelled for a while.
            if ($durationMinutes >= self::MIN_STOP_MINUTES || $isFirstOrLastPingOfDay) {
                $stops[] = [
                    'name' => $first->place_name
                        ?? $last->place_name
                        ?? $this->fallbackStopName(count($stops), $clusterStart === 0, $j === $n),
                    'lat' => round($centroidLat, 7),
                    'lng' => round($centroidLng, 7),
                    'arrival' => $first->recorded_at,
                    'departure' => $last->recorded_at,
                    'duration_minutes' => (int) $durationMinutes,
                ];
            }

            $i = $j;
        }

        return $stops;
    }

    protected function fallbackStopName(int $index, bool $isFirst, bool $isLast): string
    {
        if ($isFirst) {
            return 'Home / Starting Point';
        }

        if ($isLast) {
            return 'Office / End Point';
        }

        return 'Location';
    }

    /**
     * Time actually spent travelling = time between leaving one stop and
     * arriving at the next, summed across all stop-to-stop segments.
     *
     * @param array<int, array{arrival:Carbon, departure:Carbon}> $stops
     */
    protected function travelTimeMinutes(array $stops): float
    {
        $minutes = 0;

        for ($i = 0; $i < count($stops) - 1; $i++) {
            $minutes += $stops[$i]['departure']->diffInMinutes($stops[$i + 1]['arrival']);
        }

        return $minutes;
    }

    /**
     * Sum of the great-circle distance between every consecutive raw GPS
     * ping for the day, in kilometers.
     *
     * @param Collection<int, LocationLog> $points
     */
    protected function totalDistanceKm(Collection $points): float
    {
        $totalMeters = 0;
        $previous = null;

        foreach ($points as $point) {
            if ($previous !== null) {
                $totalMeters += $this->haversineMeters(
                    $previous->latitude,
                    $previous->longitude,
                    $point->latitude,
                    $point->longitude
                );
            }
            $previous = $point;
        }

        return $totalMeters / 1000;
    }

    /**
     * Great-circle distance between two lat/lng points, in meters.
     */
    protected function haversineMeters(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadiusMeters = 6371000;

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadiusMeters * $c;
    }
}
